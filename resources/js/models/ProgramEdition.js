import Model from './Model'

export default class ProgramEdition extends Model {
  // id = null
  // program_id = null
  // name = null
  // company_id = null
  // cost = null
  // supplier = null
  // teacher_name = null
  // starts_at = null
  // ends_at = null
  // created_by = null

  // company = {}
  // manager = {}
  // schedules = []

  resource() {
    return 'program-editions'
  }

}
