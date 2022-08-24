import {createApp} from 'vue'
import App from './App.vue'
import router from './router/index.js'
import 'bootstrap/dist/js/bootstrap.js'
import 'bootstrap/dist/css/bootstrap.css'
import './assets/styles.css' 
import { BootstrapIconsPlugin } from 'bootstrap-icons-vue'

const app = createApp(App);
app.use(BootstrapIconsPlugin);
app.use(router).mount('#app');

