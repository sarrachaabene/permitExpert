<template>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <side-bar-component></side-bar-component>
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <header class="app-header">
        <nav-bar-component></nav-bar-component>
      </header>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import FooterComponent from '../components/Layout/FooterComponent.vue';
import SideBarComponent from '../components/Layout/SideBarComponent.vue';
import NavBarComponent from '../components/Layout/NavBarComponent.vue';
import DashbordComponent from './Page/Dashbord.component.vue';
export default {
  props: ['user'],
  name: 'ExempleComponent',
  components: { // Register the child component
    FooterComponent,
    SideBarComponent,
    NavBarComponent,
    DashbordComponent
  },
  mounted() {
    if (!localStorage.getItem('token')) window.location.href = '/login';
  }
}
</script>
