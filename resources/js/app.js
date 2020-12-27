import Vue from 'vue'
import { Model } from 'vue-api-query'

require('./bootstrap')
require('./plugins/event-bus')
require('./components/')

import vuetify from './plugins/vuetify'

Model.$http = window.axios

const app = new Vue({
    vuetify,
    el: '#app',
    data() {
        return {
            token: document.head.querySelector('meta[name="csrf-token"]')?.content
        }
    }
})
