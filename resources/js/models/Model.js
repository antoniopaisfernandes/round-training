import { Model as BaseModel } from 'vue-api-query'
import cloneDeep from 'lodash-es/cloneDeep'

export default class Model extends BaseModel {

  // ---------------------------
  // vue-api-query related
  // ---------------------------
  baseURL() {
    return ''
  }
  request(config) {
    return this.$http.request(config)
  }

  // ---------------------------
  // Other
  // ---------------------------
  className() {
    return this.constructor.name
  }

  clone() {
    const obj = cloneDeep(this)

    return obj
  }
}
