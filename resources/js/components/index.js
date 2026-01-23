import appLayout from './Layout/index.vue'
import toast from './Generic/Toast.vue'
import reportCard from './Generic/ReportCard.vue'
import programEditionIndex from './ProgramEdition/index.vue'
import companyIndex from './Company/index.vue'
import studentIndex from './Student/index.vue'
import userIndex from './User/index.vue'
import DatetimePickerPlugin from '../plugins/datetime-picker'

export function registerComponents(app) {
  app.component('app-layout', appLayout)
  app.component('toast', toast)
  app.component('report-card', reportCard)
  app.component('program-edition-index', programEditionIndex)
  app.component('company-index', companyIndex)
  app.component('student-index', studentIndex)
  app.component('user-index', userIndex)

  // Register datetime picker plugin
  app.use(DatetimePickerPlugin)
}
