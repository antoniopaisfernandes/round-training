import { createApp } from 'vue'
import { Model } from 'vue-api-query'

require('./bootstrap')
require('./plugins/event-bus')

import vuetify from './plugins/vuetify'

// Import and register components
import appLayout from './components/Layout'
import toast from './components/Generic/Toast'
import reportCard from './components/Generic/ReportCard.vue'
import programEditionIndex from './components/ProgramEdition/index.vue'
import companyIndex from './components/Company/index.vue'
import studentIndex from './components/Student/index.vue'
import userIndex from './components/User/index.vue'
import DatetimePicker from './components/Generic/DatetimePicker'

Model.$http = window.axios

const app = createApp({
    data() {
        return {
            token: document.head.querySelector('meta[name="csrf-token"]')?.content
        }
    }
})

app.use(vuetify)
app.component('app-layout', appLayout)
app.component('toast', toast)
app.component('report-card', reportCard)
app.component('v-datetime-picker', DatetimePicker)
app.component('program-edition-index', programEditionIndex)
app.component('company-index', companyIndex)
app.component('student-index', studentIndex)
app.component('user-index', userIndex)

app.mount('#app')
