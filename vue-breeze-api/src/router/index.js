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
        path:'/controlpanel',
        component: ()=>import('../components/ControlPanel/Index.vue')
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = ['/verify-access'];
    const authRequired = !publicPages.includes(to.path);
    const clusteredPrecinct = clusteredPrecinctStore();
//verify_access

    // console.log(clusteredPrecinct.verify_access);
    if (authRequired && !clusteredPrecinct.verify_access) {
        // auth.returnUrl = to.fullPath;
        return '/verify-access';
    }
});

export default router;
