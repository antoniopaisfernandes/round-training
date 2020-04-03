import appLayout from './Layout'
import toast from './Toast'
import programEditionIndex from './ProgramEdition/index.vue'
import companyIndex from './Company/index.vue'

Vue.component('app-layout', appLayout)
Vue.component('toast', toast)
Vue.component('program-edition-index', programEditionIndex)
Vue.component('company-index', companyIndex)


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
