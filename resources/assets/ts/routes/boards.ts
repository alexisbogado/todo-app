export default [
    {
        path: '/boards',
        name: 'boards',
        component: () => import(/* webpackChunkName: 'pages.boards.all-boards' */'../../vue/pages/boards/AllBoards.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/boards/:id',
        name: 'board',
        component: () => import(/* webpackChunkName: 'pages.boards.all-boards' */'../../vue/pages/boards/Boards.vue'),
        meta: {
            requiresAuth: true
        }
    }
]