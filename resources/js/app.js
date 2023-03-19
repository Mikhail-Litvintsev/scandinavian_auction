import './bootstrap';

import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
import globals from "./composeables/globals";

const app = createApp(App).use(router)
app.config.globalProperties.$globals = globals;
app.mount("#app")
