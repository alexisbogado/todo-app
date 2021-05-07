export default [
    {
        path: '/',
        name: 'login',
        component: () => import(/* webpackChunkName: 'pages.authentication.login' */'../../vue/pages/authentication/Login.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import(/* webpackChunkName: 'pages.authentication.register' */'../../vue/pages/authentication/Register.vue'),
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/logout',
        name: 'logout',
        component: () => import(/* webpackChunkName: 'pages.authentication.logout' */'../../vue/pages/authentication/Logout.vue'),
        meta: {
            requiresAuth: true
        }
    },
]
