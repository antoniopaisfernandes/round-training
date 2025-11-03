import Vue from 'vue'
import { Model } from 'vue-api-query'

import './bootstrap'
import './plugins/event-bus'
import './components/'

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
