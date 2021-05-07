import Vue from 'vue'
import Vuex from 'vuex'

import User from './modules/UserModule'
import Navigation from './modules/NavigationModule'
import Board from './modules/BoardModule'
import Modals from './modules/ModalsModule'
import Task from './modules/TaskModule'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        User,
        Navigation,
        Board,
        Modals,
        Task,
    },
    strict: true,
    devtools: debug,
})
