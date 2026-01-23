import { createApp } from 'vue'
import axios from 'axios'

import './bootstrap'
import Event from './plugins/event-bus'
import { registerComponents } from './components/'

import vuetify from './plugins/vuetify'
import { Model } from './models/Model'

// Set up axios for Model
Model.$http = axios

// Create Vue app
const app = createApp({
    data() {
        return {
            token: document.head.querySelector('meta[name="csrf-token"]')?.content
        }
    }
})

// Use Vuetify
app.use(vuetify)

// Register global components
registerComponents(app)

// Provide Event bus globally
app.provide('eventBus', Event)

// Mount the app
app.mount('#app')
