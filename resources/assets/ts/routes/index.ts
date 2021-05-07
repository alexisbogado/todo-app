import Vue from 'vue'
import VueRouter, { NavigationGuardNext, Route } from 'vue-router'
import axios from 'axios'

import auth from './auth'
import boards from './boards'
import store from '../store'
import UserService from '../services/UserService'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        ...auth,
        ...boards,
        {
            path: '*',
            name: 'error',
            component: () => import(/* webpackChunkName: 'pages.error' */'../../vue/pages/Error.vue')
        }
    ]
})

axios.interceptors.request.use(config => {
    const token = store.getters['User/token']
    config.headers.Authorization = `Bearer ${token}`

    return config
})

axios.interceptors.response.use(undefined, async error => {
    if (error.response.status === 401 && error.response.config && !error.response.config.__isRetryRequest) {
        await store.dispatch('User/logout')
        router.push({ name: 'login' })
    }

    return Promise.reject(error)
})

router.afterEach(() => store.dispatch('Navigation/hideLoader'))

router.beforeEach(async (to: Route, from: Route, next: NavigationGuardNext<Vue>): Promise<void> => {
    store.dispatch('Navigation/showLoader')

    let isLoggedIn = store.getters['User/isLoggedIn']

    if (isLoggedIn) {
        await UserService
            .getUser()
            .then(({ data }) => {
                if (data.success) {
                    return
                }

                store.dispatch('User/logout')
            })
            .finally(() => isLoggedIn = store.getters['User/isLoggedIn'])
    }

    if (to.meta.hasOwnProperty('requiresAuth')) {
        const isAuthRequired = to.meta.requiresAuth

        if (isAuthRequired && !isLoggedIn) {
            next({ name: 'login' })
        } else if (!isAuthRequired && isLoggedIn) {
            next({ name: 'boards' })
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router
