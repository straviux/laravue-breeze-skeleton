import {createRouter, createWebHistory} from 'vue-router';

import Container from '../components/Container.vue'
import { clusteredPrecinctStore } from "../store/clustered_precinct_result";

const routes = [
    // {path:'/test',component:()=>import('../components/Test.vue')},
    {
        path:'/',
        component: Container,
        redirect: 'home',
        children:[
            {path:'',name:'Home',component:()=>import('../components/Home.vue'),
                children:[
                    {path:'results',name:'ElectionResults',component:()=>import('../components/Results.vue')}
                ]
            }
        ]
    },
    {
        path:'/verify-access',
        name:'verify-access',
        component: ()=>import('../components/VerifyAccess.vue')
    },
    // {
    //     path:'/register',
    //     component: ()=>import('../components/Register.vue')
    // },

    {
        path:'/control001',
        component: ()=>import('../components/ControlPanel/Container.vue'),
        redirect: 'control-panel-home',
        children:[
            {path:'login',name:'control-panel-login',component:()=>import('../components/ControlPanel/Login.vue')},
            {path:'',name:'control-panel-home',component:()=>import('../components/ControlPanel/Index.vue')}
        ]
    },

    {
        path:'/barangay',
        component: ()=>import('../components/ControlPanel/Container.vue'),
        redirect: 'barangay-result-home',
        children:[
            {path:'login',name:'barangay-result-login',component:()=>import('../components/ControlPanel/Login.vue')},
            {path:'',name:'barangay-result-home',component:()=>import('../components/BarangayElection/Container.vue')}
        ]
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = ['/verify-access','/control001/login'];
    const authRequired = !publicPages.includes(to.path);
    const clusteredPrecinct = clusteredPrecinctStore();
//verify_access

    // console.log(clusteredPrecinct.verify_access);
    if (authRequired && !clusteredPrecinct.verify_access) {
        // auth.returnUrl = to.fullPath;
        if(to.name=='control-panel-home') {
            return '/control001/login'
        } else if(to.name=='barangay-result-home') {
            return '/barangay-result-home'
        }else {
           return '/verify-access';
        }

    }
});

export default router;
