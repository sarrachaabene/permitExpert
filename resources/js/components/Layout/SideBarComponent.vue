<template>
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
    
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="../../../../public/assets/images/logos/permit.png" width="180" height="120"  alt="" />
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
            <li class="sidebar-item">
              <router-link class="sidebar-link" to="/" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Tableau de bord</span>
              </router-link>

            </li>
          <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Les auto écoles</span>
            </li>--> 
            <li class="sidebar-item">
              <router-link class="sidebar-link" to="/autoEcole" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Auto écoles</span>
              </router-link>
            </li>
            <li class="sidebar-item">
              <router-link class="sidebar-link" to="/inscription" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-circle"></i>
                </span>
                <span class="hide-menu">Demandes inscriptions</span>
              </router-link>
            </li>
            <li class="sidebar-item">
              <router-link class="sidebar-link" to="/profile" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Profil</span>
              </router-link>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-forms.html" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Paramètres</span>
              </a>
            </li>
          <!--   <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-typography.html" aria-expanded="false">
                <span>
                  <i class="ti ti-typography"></i>
                </span>
                <span class="hide-menu">Typography</span>
              </a>
            </li> -->
          <!--   <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">AUTH</span>
            </li> -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-login.html" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                <span class="hide-menu">Se déconnecter</span>
              </a>
            </li>
          <!--   <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-register.html" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Register</span>
              </a>
            </li> -->
          <!--   <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">EXTRA</span>
            </li> -->
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="./icon-tabler.html" aria-expanded="false">
                <span>
                  <i class="ti ti-mood-happy"></i>
                </span>
                <span class="hide-menu">Icons</span>
              </a>
            </li> -->
          <!--   <li class="sidebar-item">
              <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Sample Page</span>
              </a>
            </li> -->
          </ul>
        <!--   <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
                <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
              </div>
              <div class="unlimited-access-img">
                <img src="C:\laragon\www\PermitExpert\resources\assets\images\backgrounds\rocket.png" alt="" class="img-fluid">
              </div>
            </div>
          </div> -->
        </nav>
        
      </div>
  
    </aside>    
    <!--  Sidebar End -->
  </template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>