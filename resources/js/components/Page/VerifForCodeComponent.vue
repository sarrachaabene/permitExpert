<template>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div style="background-color:white"
    class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center background-image">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); border-radius: 5%;" class="card mb-0">
              <div class="card-body">
                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../../public/assets/images/logos/permit.png" width="170" alt="">
                  <h5 style="text-align: center; font-weight: bold;">Inscription</h5>
                </a>
                <h6 style="text-align: center;">Commençons avec votre adresse e-mail</h6>
                <form @submit.prevent="continueRegistration">
                  <br><br>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Entrer votre Email</label>
                    <input v-model="email" type="email" class="form-control" id="exampleInputEmail1"
                      aria-describedby="emailHelp" placeholder="Email">
                    <div v-if="errorMessage" class="text-danger">{{ errorMessage }}</div> 
                    <div v-if="successMessage" class="text-success">{{ successMessage }}</div> 
                  </div>
                  <br><br>
                  <div class="text-center">
                    <button type="submit" style="background-color: #FA7F35; border-color: #FA7F35; 
                    margin-left: 5px; font-size: 16px;" class="btn btn-danger">Continuer</button>
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

export default {
  data() {
    return {
      email: '', 
      errorMessage: '', 
      successMessage: '', 
    };
  },
  methods: {
    async continueRegistration() {
      if (!this.email) {
        this.errorMessage = 'Veuillez entrer votre adresse e-mail.';
        this.successMessage = ''; 
        return;
      }
      if (!this.email.includes('@')) {
        this.errorMessage = 'Veuillez entrer une adresse e-mail valide.';
        this.successMessage = ''; 
        return;
      }
      try {
        const response = await axios.get(`/checkEmailForPassword/${this.email}`);
        if (response.data.error) {
          this.errorMessage = response.data.error;
          this.successMessage = '';
        } else {
          this.successMessage = response.data.success; 
          this.errorMessage = '';
          // Affichage de l'e-mail dans la console
          console.log('E-mail validé:', this.email);
          // Stockage de l'e-mail dans le stockage local
          localStorage.setItem('userEmail', this.email);
          // Redirection vers la page /code
          window.location.href = '/codeforpassword';
        }
      } catch (error) {
        if (error.response.status === 404) {
          this.errorMessage = 'Cet email n\'existe pas.';
          this.successMessage = ''; 
        } else {
          console.error('Error:', error);
        }
      }
    },
  },
};
</script>
<style scoped>
.background-image {
    background-image: url('C:\laragon\www\PermitExpert\public\assets\images\bg login (1).jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
