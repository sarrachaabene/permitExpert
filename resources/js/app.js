import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import 'vuetify/styles' ;
const app = createApp({});
import axios from 'axios';
import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);
import LoginComponent from './components/Page/Logincomponent.vue'; // Importez votre composant logincomponent
app.component('login-component', LoginComponent); // Enregistrez votre composant logincomponent
import RegisterComponent from './components/Page/Registercomponent.vue'; // Importez votre composant logincomponent
app.component('register-component', RegisterComponent); // Enregistrez votre composant logincomponent

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
const vuetify = createVuetify({
  components,
  directives
})

import { VCalendar } from 'vuetify/labs/VCalendar'

export default createVuetify({
  components: {
    VCalendar,
  },
})
// Set up axios globally:
window.axios = axios;
// Configure the default headers for axios:
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Define the base URL for all axios requests:
axios.defaults.baseURL = 'http://localhost:8000/api';
// If there's a token in the localStorage, set it as the default Authorization header:
if (localStorage.getItem('token')) {
    axios.defaults.headers.common['Authorization'] = `${localStorage.getItem('token')}`;
    //router.push({ name: 'welcome' });
}
// Handle token expiration or invalid tokens:
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    router.before
      if (error.response?.status === 401) {
          // Remove the token from local storage:
          localStorage.removeItem('token');
          // Reset the axios Authorization header:
          axios.defaults.headers.common['Authorization'] = 'Bearer';
          // Redirect the user to the login page:
          router.push({ name: 'login' });
      }
      return Promise.reject(error);
  }
);
app.use(router).use(vuetify).mount('#app');
