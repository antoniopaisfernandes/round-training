import Model from './Model'
import Student from './Student'

export default class ProgramEdition extends Model {

  resource() {
    return 'program-editions'
  }

  students() {
    return this.hasMany(Student)
  }

}
