import { saveAs } from 'file-saver'
import Model from './Model'
import Student from './Student'

export default class ProgramEdition extends Model {

  resource() {
    return 'program-editions'
  }

  students() {
    return this.hasMany(Student)
  }

  export(options) {
    const params = new URLSearchParams(options).toString()
    const endpoint = `/${this.resource()}/${this.id}/export?${params}`
    const filename = `${this.id}.${options.format}`
    saveAs(endpoint, filename)
  }
}
