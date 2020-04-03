import { Model as BaseModel } from 'vue-api-query'
import cloneDeep from 'lodash-es/cloneDeep'
import isObject from 'lodash-es/isObject'

const getCircularReplacer = () => {
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

  baseURL() {
    return ''
  }

  request(config) {
    return this.$http.request({
      // Fix circular reference bug in _builder prop
      transformRequest: [(data) => {
        return isObject(data)
          ? JSON.parse(JSON.stringify(data, getCircularReplacer()))
          : data
      }, ...this.$http.defaults.transformRequest],
      ...config
    })
  }

  clone() {
    const obj = cloneDeep(this)
    return obj
  }

}
