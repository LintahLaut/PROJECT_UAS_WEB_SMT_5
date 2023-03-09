import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'welcome',
        component: () => import(/* webpackChunkName: "home" */ '@/views/WelcomePage.vue')
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import(/* webpackChunkName: "login" */ '@/views/LoginPage.vue')
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import(/* webpackChunkName: "login" */ '@/views/RegisterPage.vue')
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import(/* webpackChunkName: "home" */ '@/components/NavBarDashboard.vue'),
        children: [
            {
                path: '/reservasi',
                name: 'reservasi',
                component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Reservasi/ReservasiPage.vue')
            },
            {
                path: '/layanan',
                name: 'layanan',
                component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Layanan/LayananPage.vue')
            },    
            {
                path: '/produk',
                name: 'produk',
                component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Produk/ProdukPage.vue')
            },
        ]
    },
]

const router = createRouter({  
    history: createWebHistory(),
    routes
})

export default router;
