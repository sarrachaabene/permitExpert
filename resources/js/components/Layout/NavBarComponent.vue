<template>
  <nav class="navbar navbar-expand-lg navbar-light">
      <ul class="navbar-nav">
          <li class="nav-item d-block d-xl-none">
              <a
                  class="nav-link sidebartoggler nav-icon-hover"
                  id="headerCollapse"
                  href="javascript:void(0)"
              >
                  <i class="ti ti-menu-2"></i>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                  <i class="ti ti-bell-ringing"></i>
                  <div class="notification bg-primary rounded-circle"></div>
              </a>
          </li>
      </ul>
      <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
          <ul
              class="navbar-nav flex-row ms-auto align-items-center justify-content-end"
          >
              <li class="nav-item dropdown">
                <div  class="d-flex align-items-center">
                  <span style="margin-right: 5px;">{{ userRole }}</span> ,
                  <span>{{ user.user_name }}</span> 
                  <img style="margin-right: 55px;" class="rounded-circle mt-2 mb-2 ml-2 image-left image-right" width="50px" :src="getImageUrl">
                </div>
              </li>
          </ul>
      </div>
  </nav>
</template>

<style>
.image-left {
  margin-left: 50px; 
}
.image-right {
  margin-left: 5px; 
}
</style>

<script>
import axios from "axios";
const showProfile_API_BASE_URL = "http://localhost:8000/api/showProfile";

export default {
  data() {
    return {
      user: {
        user_name: '',
        user_image: ''
      },
      userRole: '' // Add user role property
    };
  },
  mounted() {
    this.fetchData();
  },
  methods:{
    fetchData() {
      axios.post(`${showProfile_API_BASE_URL}`)
        .then(response => {
          this.handleSuccess(response.data);
        })
        .catch(error => {
          this.handleError(error);
        });
    },
    handleSuccess(data) {
      this.user = data; // Set the fetched user data
      this.userRole = data.role; // Set the user role
    },
    handleError(error) {
      console.error("Error fetching user data:", error);
    }
  },
  computed: {
    getImageUrl() {
      const baseUrl = 'http://localhost:8000/storage/images/';
      return baseUrl + this.user.user_image;
    }
  }
};
</script>
