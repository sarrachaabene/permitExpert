<template>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div 
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center background-image">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); border-radius: 5%;" class="card mb-0 " >
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../../public/assets/images/logos/permit.png" width="150" alt="">
                </a>
                <h5 style="text-align: center; font-weight: bold;">Connection</h5>
                <h6 style="text-align: center;">Bienvenue sur PermitExpert</h6>
                <br><br>
                <div v-if="message" class="alert" :class="{ 'alert-success': isSuccess, 'alert-danger': !isSuccess }">
                  {{ message }}
                </div>
                <form @submit.prevent="login">
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input v-model="email" type="email" class="form-control" id="exampleInputEmail1"
                      aria-describedby="emailHelp" placeholder="Email">
                  </div>
                  <div class="mb-4">
  <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
  <input v-model="password" type="password" class="form-control" id="exampleInputPassword1"
    placeholder="Mot de passe">
    <a style="color: green; display: block; margin-top: 5px; text-decoration: underline;" href="/Veriforcode">Mot de passe oublié?</a>
</div>
                  <div class="text-center">
                    <button type="submit" style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px;"
                      class="btn btn-danger">Se connecter</button>
                  </div>
                  <br>
                  <div class="d-flex align-items-center justify-content-center">
                    <h1 class="fs-4 mb-0 fw-bold">Vous n'avez pas de compte?</h1>
                    <a style="color: #FA7F35; " href="/register">S'inscrire</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
const API_BASE_URL = "http://localhost:8000/api";
export default {
  data() {
    return {
      email: '',
      password: '',
      message: '',
      showMessage: false
    };
  },
  mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin){
      window.location.href = '/dashbord_Super_Admin';
    }else {
      window.location.href = '/dashbord';
    }
  },
  computed: {
    isSuccess() {
      return this.message && this.message.startsWith('Connexion réussie');
    }
  },
  methods: {
    async login() {
      try {
        const response = await axios.post(`${API_BASE_URL}/login`, {
          email: this.email,
          password: this.password
        }).then(res => {
          if (res.data.role === "candidat" || res.data.role === "moniteur") {
            this.message = "user  doesn't have access";
            this.showMessage = true;
            setTimeout(() => {
              this.showMessage = false;
            }, 5000);
          } else {
            localStorage.setItem('token', res.data.access_token);
            var user = {
              'email': res.data.email,
              'role': res.data.role
            };
            var users = JSON.parse(localStorage.getItem('users')) || []; 
            users.push(user);
            localStorage.setItem('users', JSON.stringify(users)); 
            axios.defaults.headers.common['Authorization'] = `${res.data.access_token}`;
            this.message = "Connexion réussie !";
            this.showMessage = true;
            setTimeout(() => {
              this.showMessage = false;
              if (res.data.role == "superAdmin") {
                window.location.href = '/dashbord_Super_Admin';

              } else if (res.data.role === "admin" || res.data.role === "secretaire"){
                window.location.href = '/dashbord';
              } else {
                this.message = 'Échec de la connexion. Veuillez vérifier vos informations d\'identification.';
                this.showMessage = true;
              }
            }, 1000);
          }
        });
      } catch (error) {
        console.error('Login failed:', error);
        this.message = 'Échec de la connexion. Veuillez vérifier vos informations d\'identification.';
        this.showMessage = true;
        setTimeout(() => {
          this.showMessage = false;
        }, 5000);
      }
    }
  }
};
</script>
<style scoped>
.background-image {
    background-image: url('C:\Users\Lenovo ThinkPad.DESKTOP-6NTPK62\Desktop\clonage\permitExpert\public\assets\images\bg login (1).jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
