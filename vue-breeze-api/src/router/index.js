import {createRouter, createWebHistory} from 'vue-router';
import DefaultLayout from '../components/DefaultLayout.vue'

const routes = [
    {
        path:'/',
        component: DefaultLayout,
        redirect: 'home',
        children:[
            {path:'home',name:'Home',component:()=>import('../components/Home.vue')}
        ]
    },
    {
        path:'/login',
        component: ()=>import('../components/Login.vue')
    },
    {
        path:'/register',
        component: ()=>import('../components/Register.vue')
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
