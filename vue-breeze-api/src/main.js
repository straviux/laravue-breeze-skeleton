import { createApp, markRaw } from 'vue'
import {createPinia} from 'pinia'
import piniaPluginPersistedState from "pinia-plugin-persistedstate"
import router from './router'
import './axios'
import './style.css'
import App from './App.vue'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'


const pinia = createPinia();

pinia.use(({store})=>{
    store.router = markRaw(router);
});
pinia.use(piniaPluginPersistedState);

const app = createApp(App);
app.use(pinia);
app.use(router);
app.use(mdiVue, {
  icons: mdijs
});
app.mount('#app');
// window.onbeforeunload = function() {
//    localStorage.clear();
// };
