import { createApp } from 'vue'
import { Model } from 'vue-api-query'

require('./bootstrap')
require('./plugins/event-bus')

Model.$http = window.axios

const app = createApp()

import EventBus from './plugins/event-bus'
app.use(EventBus)

import DatetimePicker from './plugins/datetime-picker'
app.use(DatetimePicker)

import vuetify from './plugins/vuetify'
app.use(vuetify)

import components from './components'
for (const key in components) {
    app.component(key, components[key])
}

app.mount('#app')
