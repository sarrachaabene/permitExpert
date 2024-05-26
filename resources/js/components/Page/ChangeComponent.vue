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
                  <h5 style="text-align: center; font-weight: bold;">Mot de passe oublié</h5>
                </a>
                <form @submit.prevent="updatePassword">
                  <br><br>
                  <div class="mb-3">
                    <label class="form-label">Entrer votre nouveau mot de passe</label>
                    <input type="password" v-model="newPassword" class="form-control" placeholder="Nouveau mot de passe"
                      autocomplete="new-password">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" v-model="confirmPassword" class="form-control"
                      placeholder="Confirmer le mot de passe" autocomplete="new-password">
                  </div>
                  <div v-if="errorMessage" class="alert alert-danger mt-3" role="alert">
                    {{ errorMessage }}
                  </div>
                  <div v-if="successMessage" class="alert alert-success mt-3" role="alert">
                    {{ successMessage }}
                  </div>
                  <br><br>
                  <div class="text-center">
                    <button type="submit"
                      style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px; font-size: 16px;"
                      class="btn btn-danger">Continuer</button>
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
      errorMessage: '',
      successMessage: '',
      newPassword: '',
      confirmPassword: ''
    };
  },
  mounted() {
    const userEmail = localStorage.getItem('userEmail');
    console.log('Email récupéré:', userEmail);
  },
  methods: {
    updatePassword() {
      if (!this.newPassword || !this.confirmPassword) {
  this.errorMessage = 'Veuillez remplir tous les champs';
  setTimeout(() => {
    this.errorMessage = '';
  }, 3000);
  return;
}

      
      if (this.newPassword.length < 8) {
        this.errorMessage = 'Le mot de passe doit contenir au moins 8 caractères';
        setTimeout(() => {
          this.errorMessage = '';
        }, 3000); 
        return;
      }
      
      if (this.newPassword !== this.confirmPassword) {
        this.errorMessage = 'Les mots de passe ne correspondent pas';
        setTimeout(() => {
          this.errorMessage = '';
        }, 3000); 
        return;
      }

      const userEmail = localStorage.getItem('userEmail');

      axios.put(`/updatePassword/${userEmail}`, {
          password: this.newPassword,
          password_confirmation: this.confirmPassword
        })
        .then(response => {
          this.successMessage = response.data.message;
          setTimeout(() => {
            this.successMessage = '';
            window.location.href = '/';
          }, 3000); 
        })
        .catch(error => {
          if (error.response) {
            this.errorMessage = error.response.data.error;
          } else {
            this.errorMessage = 'Une erreur s\'est produite lors de la communication avec le serveur';
          }
          setTimeout(() => {
            this.errorMessage = '';
          }, 3000); 
        });
    }
  }
}
</script>
<style scoped>
.background-image {
    background-image: url('C:\laragon\www\PermitExpert\public\assets\images\bg login (1).jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>