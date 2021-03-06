import Vue from 'vue'
import appLayout from './Layout'
import toast from './Generic/Toast'
import reportCard from './Generic/ReportCard.vue'
import programEditionIndex from './ProgramEdition/index.vue'
import companyIndex from './Company/index.vue'
import studentIndex from './Student/index.vue'
import userIndex from './User/index.vue'

Vue.component('app-layout', appLayout)
Vue.component('toast', toast)
Vue.component('report-card', reportCard)
Vue.component('program-edition-index', programEditionIndex)
Vue.component('company-index', companyIndex)
Vue.component('student-index', studentIndex)
Vue.component('user-index', userIndex)
