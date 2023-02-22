import {createRouter, createWebHistory} from 'vue-router';
import DefaultLayout from '../components/DefaultLayout.vue'

const routes = [
    {path:'/test',component:()=>import('../components/Test.vue')},
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

    {
        path:'/results',
        component: ()=>import('../components/Results.vue')
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
