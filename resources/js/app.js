import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import axios from 'axios';
import ExampleComponent from './components/ExampleComponent.vue';
import LoginComponent from './components/Page/Logincomponent.vue';
import RegisterComponent from './components/Page/Registercomponent.vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';

// Set up axios globally:
window.axios = axios;
// Configure the default headers for axios:
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Define the base URL for all axios requests:
axios.defaults.baseURL = 'http://localhost:8000/api';
// If there's a token in the localStorage, set it as the default Authorization header:
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `${token}`;
}

// Create Vuetify instance
const vuetify = createVuetify({
  components,
  directives
});

const app = createApp({
  router,
});
app.component('example-component', ExampleComponent);
app.component('login-component', LoginComponent);
app.component('register-component', RegisterComponent);

// Handle token expiration or invalid tokens:
axios.interceptors.response.use(
  (response) => response,
  (error) => {
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
