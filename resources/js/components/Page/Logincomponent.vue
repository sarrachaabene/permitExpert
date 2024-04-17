<template>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div style="background-color:white"
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);" class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../../public/assets/images/logos/permit.png" width="150" alt="">
                </a>
                <h5 style="text-align: center; font-weight: bold;">Connection</h5>
                <h6 style="text-align: center;">Bienvenue sur PermitExpert</h6>
                <br><br>
                <!-- Affichage du message de succès ou d'erreur -->
                <div v-if="message" class="alert" :class="{'alert-success': isSuccess, 'alert-danger': !isSuccess}">
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
                  </div>

                  <div class="text-center">
                    <button type="submit"
                      style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px;"
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

export default {
  data() {
    return {
      email: '',
      password: '',
      message: '', // Variable pour stocker le message
      showMessage: false // Variable pour afficher ou masquer le message
    };
  },
  computed: {
    isSuccess() {
      // Détermine si le message est un message de succès
      return this.message && this.message.startsWith('Connexion réussie');
    }
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/api/login', {
          email: this.email,
          password: this.password
        });
        console.log('Login successful:', response.data);
        // Mettre à jour le message de succès
        this.message = 'Connexion réussie !';
        // Afficher le message
        this.showMessage = true;
        // Effacer le message après 5 secondes
        setTimeout(() => {
          this.showMessage = false;
        }, 5000);
        
        // Extraire l'URL de bienvenue de la réponse
        const welcomeUrl = response.data.welcome_url;

        // Rediriger l'utilisateur vers la page de bienvenue
        window.location.href = welcomeUrl;

      } catch (error) {
        console.error('Login failed:', error.response.data);
        // Mettre à jour le message d'erreur
        this.message = 'Échec de la connexion. Veuillez vérifier vos informations d\'identification.';
        // Afficher le message
        this.showMessage = true;
        // Effacer le message après 5 secondes
        setTimeout(() => {
          this.showMessage = false;
        }, 5000);
        // Gérer l'échec de la connexion, afficher le message d'erreur à l'utilisateur
      }
    }
  }
};
</script>
