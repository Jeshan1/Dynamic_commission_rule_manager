import { createRouter, createWebHistory } from 'vue-router'
import { store } from './store/authStore'

import Login from './auth/Login.vue'
import AdminLayout from './admin/AdminLayout.vue'
import Commission from './admin/components/comissions/Index.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false }
    },
    {
        path: '/admin',
        name: 'Admin',
        component: AdminLayout,
        meta: { requiresAuth: true, roles: ['admin'] },
        children: [
            {
                path: '/commission-management',
                name: 'Commission',
                component: Commission,
                meta: { requiresAuth: true, roles: ['admin'] }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const token = store.state.token
    const user = store.state.user

    if (to.meta.requiresAuth && !token) {
        return next('/login')
    }

    if (to.path === '/login' && token) {
        return next('/admin')
    }

    if (to.meta.roles?.includes('admin')) {
        const userRole = user?.role
        
        if (userRole !== 'admin') {
            return next('/')
        }
    }

    next()
})

export default router