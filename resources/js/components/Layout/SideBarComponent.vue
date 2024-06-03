<template>
  <aside class="left-sidebar">
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="" class="text-nowrap logo-img">
          <img src="../../../../public/assets/images/logos/permit.png" width="180" height="120" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <br> <br>
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <!--<li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Accueil</span>
            </li>-->
          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/dashbord" aria-expanded="false">
              <span>
                <i class="ti ti-dashboard"></i>
              </span>
              <span class="hide-menu">Tableau de bord</span>
            </router-link>
          </li>
          
          <li class="sidebar-item"  v-if="userRole">
            <router-link class="sidebar-link" to="/dashbord_Super_Admin" aria-expanded="false">
              <span>
                <i class="ti ti-dashboard"></i>
              </span>
              <span class="hide-menu">Tableau de bord </span>
            </router-link>
          </li>
          <li class="sidebar-item"  v-if="userRole">
            <router-link class="sidebar-link" to="/admin" aria-expanded="false">
              <span>
                <i class="ti ti-user"></i>
              </span>
              <span class="hide-menu">Administrateurs</span>
            </router-link>
          </li>

          <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Les auto écoles</span>
            </li>-->
          <li class="sidebar-item"  v-if="userRole">
            <router-link class="sidebar-link" to="/autoEcole" aria-expanded="false">
              <span>
                <i class="ti ti-home"></i>
              </span>
              <span class="hide-menu">Auto écoles</span>
            </router-link>
          </li>
          <li class="sidebar-item"  v-if="userRole">
            <router-link class="sidebar-link" to="/demande" aria-expanded="false">
              <span>
                <i class="ti ti-article"></i>
              </span>
              <span class="hide-menu">Demandes inscriptions</span>
            </router-link>
          </li>
          <li class="sidebar-item"  v-if="userRole">
            <router-link class="sidebar-link" to="/ressource" aria-expanded="false">
              <span>
                <i class="fas fa-graduation-cap"></i>
              </span>
              <span class="hide-menu">Ressources educatives</span>
            </router-link>
          </li>
          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/utilisateur" aria-expanded="false">
              <span>
                <i class="ti ti-users"></i>
              </span>
              <span class="hide-menu">Utilisateurs</span>
            </router-link>
          </li>
          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/Calendar" aria-expanded="false">
              <span>
                <i class="ti ti-calendar"></i>
              </span>
              <span class="hide-menu">Calendrier</span>
            </router-link>
          </li>

          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/transaction" aria-expanded="false">
              <span>
                <i class="ti ti-credit-card"></i>
              </span>
              <span class="hide-menu">Transactions</span>
            </router-link>
          </li>

          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/vehicule" aria-expanded="false">
              <span>
                <i class="ti ti-car"></i>
              </span>
              <span class="hide-menu">véhicules</span>
            </router-link>
          </li>
          <li class="sidebar-item" v-if="!userRole">
            <router-link class="sidebar-link" to="/message" aria-expanded="false">
              <span>
                <i class="fa fa-envelope"></i>
              </span>
              <span class="hide-menu">Contact</span>
            </router-link>
          </li>


          <li class="sidebar-item">
            <router-link class="sidebar-link" to="/profile" aria-expanded="false">
              <span>
                <i class="fas fa-user"></i>
              </span>
              <span class="hide-menu">Profil</span>
            </router-link>
          </li>
  
  <!--         <li class="sidebar-item">
            <a class="sidebar-link" to="/parametre" aria-expanded="false">
              <span>
                <i class="ti ti-settings"></i>
              </span>
              <span class="hide-menu">Paramètres</span>
            </a>
          </li> -->

          <li class="sidebar-item">
            <a class="sidebar-link" v-on:click="logout" aria-expanded="false">
              <span>
                <i class="ti ti-login"></i>
              </span>
              <span class="hide-menu">Se déconnecter</span>
            </a>
          </li>

        </ul>

      </nav>

    </div>

  </aside>
  <!--  Sidebar End -->
</template>
<script>
export default {
  data() {
        return {
          userRole: localStorage.getItem('users') && JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin"
        }
    },
  mounted() {},
  methods: {
    logout() {
      // Remove token from localStorage
      console.log("hello");
      localStorage.removeItem('token');
      localStorage.removeItem('users');
      window.location.href = '/login';
      // Optionally, you can navigate the user to a logout page or another route
      // this.$router.push('/logout'); // Change '/logout' to the desired route
    }
  }
}
</script>