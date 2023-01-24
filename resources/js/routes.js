// import Auth from './Auth.js';
// import VueRouter from 'vue-router';
import Auth from './Auth.js';
import * as VueRouter from 'vue-router';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Dashboard from './components/Dashboard.vue';
import Profile from './components/Profile.vue';

const routes = [
    {
        path: '/login',
        component: Login,
        name: "Login"
    },
    {
        path: '/',
        component: Login,
        name: "HomeLogin"
    },
    {
        path: '/register',
        component: Register,
        name: "Register"
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: "Dashboard",
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profile-settings',
        component: Profile,
        name: "Profile Settings",
        meta: {
            requiresAuth: true
        }
    }
];

const router = VueRouter.createRouter({
    history: VueRouter.createWebHashHistory(),
    routes,
    linkActiveClass: 'active-link',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth) ) {
        if (Auth.check()) {
            next();
            return;
        } else {
            router.push('/login');
        }
    } else {
        next();
    }
});

export default router;