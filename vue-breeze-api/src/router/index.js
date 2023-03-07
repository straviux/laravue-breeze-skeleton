import {createRouter, createWebHistory} from 'vue-router';
import { useAuthStore } from '../store/auth';
import Container from '../components/Container.vue'

const routes = [
    {path:'/test',component:()=>import('../components/Test.vue')},
    {
        path:'/',
        component: Container,
        redirect: 'home',
        children:[
            {path:'home',name:'Home',component:()=>import('../components/Home.vue'),
                children:[
                    {path:'er',name:'ElectionResults',component:()=>import('../components/Results.vue')}
                ]
            }
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

// router.beforeEach(async (to) => {
//     // redirect to login page if not logged in and trying to access a restricted page
//     const publicPages = ['/login'];
//     const authRequired = !publicPages.includes(to.path);
//     const auth = useAuthStore();


//     if (authRequired && !auth.user) {
//         auth.returnUrl = to.fullPath;
//         return '/login';
//     }
// });

export default router;
