import Model from './Model'
import Role from './Role'

export default class User extends Model {

  resource() {
    return 'admin/users'
  }

  roles() {
    return this.hasMany(Role)
  }

}
