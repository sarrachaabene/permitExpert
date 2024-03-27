import { createRouter, createWebHistory } from 'vue-router';
import DashbordComponent from './components/Page/Dashbord.component.vue';
import AutoEcoleComponent from './components/Page/AutoEcole.component.vue';

const routes = [
  { path: '/', component: DashbordComponent },
  { path: '/autoEcole', component: AutoEcoleComponent }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;