import './bootstrap';
import '../sass/app.scss'
import { createApp } from 'vue';
import App from './App.vue';
import router from './routes';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueGravatar from "vue3-gravatar";

const app = createApp(App);
app.use(router);
app.use(VueGravatar);
app.use(VueSweetalert2);
app.mount('#app');
