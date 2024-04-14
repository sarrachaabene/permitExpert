import { createRouter, createWebHistory } from 'vue-router';
import DashbordComponent from './components/Page/Dashbord.component.vue';
import AutoEcoleComponent from './components/Page/AutoEcole.component.vue';
import LoginComponent from './components//Page/Logincomponent.vue';
import RegisterComponent from './components//Page/Registercomponent.vue';
import CalendarComponent from './components//Page/CalendarComponent.vue';
import DemandeComponent from './components//Page/DemandeComponent.vue';
import PageNotFound from './components/Page/PageNotFound.component.vue';
const routes = [
  { path: '/', component: LoginComponent },
  { path: '/autoEcole', component: AutoEcoleComponent },
  { path: '/login', component: LoginComponent },
  { path: '/Register', component: RegisterComponent },
  { path: '/Calendar', component: CalendarComponent },
  { path: '/demande', component: DemandeComponent },
  { path: '/:pathMatch(.*)*', component: PageNotFound },

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  // Remove the 'selected' class from all sidebar items
  const sidebarItems = document.querySelectorAll('.sidebar-item');
  sidebarItems.forEach(item => {
    item.classList.remove('selected');
  });

  // Add the 'selected' class to the sidebar item corresponding to the current route
  const currentPath = to.path;
  const currentSidebarItem = document.querySelector(`.sidebar-item [to="${currentPath}"]`);
  if (currentSidebarItem) {
    currentSidebarItem.closest('.sidebar-item').classList.add('selected');
  }

  next();
});

export default router;