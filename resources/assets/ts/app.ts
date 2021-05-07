import Vue, { CreateElement } from 'vue'
import Layout from '../vue/Layout.vue'

import router from './routes'
import store from './store'

new Vue({
    router,
    store,
    render: (createElement: CreateElement) => createElement(Layout),
}).$mount('#app')
