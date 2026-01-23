import { saveAs } from 'file-saver'
import cloneDeep from 'lodash-es/cloneDeep'
import isObject from 'lodash-es/isObject'

const circularReplacer = () => {
  const set = new WeakSet()
  return (key, value) => {
    if (isObject(value) && value !== null) {
      if (set.has(value)) {
        return
      }
      set.add(value)
    }
    return value
  }
}

// Simple query builder for API requests
class QueryBuilder {
  constructor(model) {
    this._model = model
    this._includes = []
    this._appends = []
    this._orderBy = []
    this._page = null
    this._limit = null
    this._params = {}
  }

  include(...relations) {
    this._includes = [...this._includes, ...relations.flat()]
    return this
  }

  append(...fields) {
    this._appends = [...this._appends, ...fields.flat()]
    return this
  }

  orderBy(field) {
    if (Array.isArray(field)) {
      this._orderBy = [...this._orderBy, ...field]
    } else {
      this._orderBy.push(field)
    }
    return this
  }

  page(page) {
    this._page = page
    return this
  }

  limit(limit) {
    this._limit = limit
    return this
  }

  where(key, value) {
    this._params[key] = value
    return this
  }

  params(params) {
    this._params = { ...this._params, ...params }
    return this
  }

  _buildQueryParams() {
    const params = { ...this._params }

    if (this._includes.length) {
      params.include = this._includes.join(',')
    }
    if (this._appends.length) {
      params.append = this._appends.join(',')
    }
    if (this._orderBy.length) {
      params.sort = this._orderBy.join(',')
    }
    if (this._page) {
      params.page = this._page
    }
    if (this._limit) {
      params.limit = this._limit
    }

    return params
  }

  async get() {
    const params = this._buildQueryParams()
    const response = await Model.$http.get(this._model._resourceUrl(), { params })
    return this._processResponse(response.data)
  }

  async $get() {
    return this.get()
  }

  _processResponse(data) {
    // Handle Laravel API Resource format with data wrapper
    if (data.data && Array.isArray(data.data)) {
      const items = data.data.map(item => new this._model.constructor(item))
      // Include meta for pagination
      items.meta = data.meta || {}
      items.links = data.links || {}
      return items
    }
    // Handle array response without wrapper
    if (Array.isArray(data)) {
      return data.map(item => new this._model.constructor(item))
    }
    // Return single resource response
    return { data, meta: data.meta || {} }
  }
}

export class Model {
  static $http = null

  constructor(attributes = {}) {
    Object.assign(this, attributes)
    this._builder = null
  }

  // Override in subclass
  resource() {
    throw new Error('resource() method must be defined in subclass')
  }

  baseURL() {
    return ''
  }

  _resourceUrl() {
    return `${this.baseURL()}/${this.resource()}`
  }

  _itemUrl() {
    return `${this._resourceUrl()}/${this.id}`
  }

  // Static methods for querying
  static include(...relations) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.include(...relations)
  }

  static append(...fields) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.append(...fields)
  }

  static orderBy(field) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.orderBy(field)
  }

  static page(page) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.page(page)
  }

  static limit(limit) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.limit(limit)
  }

  static where(key, value) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.where(key, value)
  }

  static params(params) {
    const instance = new this()
    instance._builder = new QueryBuilder(instance)
    return instance._builder.params(params)
  }

  static async get() {
    const instance = new this()
    const builder = new QueryBuilder(instance)
    return builder.get()
  }

  static async $get() {
    return this.get()
  }

  // Instance methods
  include(...relations) {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    this._builder.include(...relations)
    return this
  }

  append(...fields) {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    this._builder.append(...fields)
    return this
  }

  orderBy(field) {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    this._builder.orderBy(field)
    return this
  }

  page(page) {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    this._builder.page(page)
    return this
  }

  limit(limit) {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    this._builder.limit(limit)
    return this
  }

  async get() {
    if (!this._builder) {
      this._builder = new QueryBuilder(this)
    }
    return this._builder.get()
  }

  async $get() {
    return this.get()
  }

  // Save method - creates or updates
  async save() {
    const data = this._getPayload()

    let response
    if (this.id) {
      response = await Model.$http.put(this._itemUrl(), data, {
        transformRequest: this._getTransformRequest()
      })
    } else {
      response = await Model.$http.post(this._resourceUrl(), data, {
        transformRequest: this._getTransformRequest()
      })
    }

    // Update instance with response data
    const responseData = response.data.data || response.data
    Object.assign(this, responseData)
    return this
  }

  // Delete method
  async delete() {
    if (!this.id) {
      throw new Error('Cannot delete a model without an ID')
    }
    await Model.$http.delete(this._itemUrl())
    return this
  }

  // Fetch with options (for data table pagination/sorting)
  async fetch(options) {
    const orderBy = options['sortBy'].map((value, key) => (options['sortDesc'][key] ? '-' : '') + value)
    const page = options['page'] || 1
    const limit = options['itemsPerPage'] || 10

    const results = await this.orderBy(orderBy)
        .page(page)
        .limit(limit)
        .get()

    return results
  }

  // Export functionality
  export(options) {
    const params = new URLSearchParams(options).toString()
    const endpoint = `/${this.resource()}/${this.id}/export?${params}`
    const filename = `${this.id}.${options.format}`
    saveAs(endpoint, filename)
  }

  // Clone the model
  clone() {
    const obj = cloneDeep(this)
    return obj
  }

  // Relationship helpers
  hasMany(RelatedModel) {
    return RelatedModel
  }

  // Get payload for save
  _getPayload() {
    const payload = {}
    for (const [key, value] of Object.entries(this)) {
      // Skip internal properties
      if (key.startsWith('_')) continue
      payload[key] = value
    }
    return payload
  }

  // Transform request to handle circular references
  _getTransformRequest() {
    return [
      (data) => {
        return isObject(data)
          ? JSON.parse(JSON.stringify(data, circularReplacer()))
          : data
      },
      ...Model.$http.defaults.transformRequest
    ]
  }
}

export default Model
