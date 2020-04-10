import { Model as BaseModel } from 'vue-api-query'
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

export default class Model extends BaseModel {
  // We need to add this since there's a conditional
  // in the parent:   if (attributes.length === 0)
  // that we should handle properly
  constructor(attributes) {
    if (attributes) {
      super(attributes)
    } else {
      super()
    }
  }

  baseURL() {
    return ''
  }

  request(config) {
    return this.$http.request({
      // Fix circular reference bug in _builder prop
      transformRequest: [(data) => {
        return isObject(data)
          ? JSON.parse(JSON.stringify(data, circularReplacer()))
          : data
      }, ...this.$http.defaults.transformRequest],
      ...config
    })
  }

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

  clone() {
    const obj = cloneDeep(this)
    return obj
  }

}
