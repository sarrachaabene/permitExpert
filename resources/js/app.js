import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import 'vuetify/styles' ;


const app = createApp({});

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

app.use(router).use(vuetify).mount('#app');
