<template>
  <br><br>
  <div class="container rounded bg-white mt-5 mb-5 shadow">
    <div class="row">
      <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
          <img class="rounded-circle mt-5" width="150px" :src="getImageUrl">
          <span class="font-weight-bold">{{ user.user_name }}</span>
        </div>
      </div>
      <div class="col-md-5 border-right">
        <div class="p-3 py-5">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-right" style="text-align: center;">Paramètres de profil</h4>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Nom</label>
                <input type="text" class="form-control" placeholder="Nom" v-model="user.user_name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Email</label>
                <input type="email" class="form-control" placeholder="Email" v-model="user.email">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Numéro de téléphone</label>
                <input type="tel" class="form-control" placeholder="Numéro de téléphone" v-model="user.numTel">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Date de naissance</label>
                <input type="date" class="form-control" placeholder="Date de naissance" v-model="user.dateNaissance">
              </div>
            </div>
          </div>
          <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">images</label>
                <input type="file" @change="onFileChange">
              </div>
            </div>
          <div class="mt-5 text-center">
            <button v-on:click="updateProfile()" class="btn btn-primary profile-button" type="button" style="background-color: #9dcd5a;">Enregistrer</button>
          </div>
          <div v-if="errorMessage" class="alert alert-danger mt-3" role="alert">{{ errorMessage }}</div>
          <div v-if="successMessage" class="alert alert-success mt-3" role="alert">{{ successMessage }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const showProfile_API_BASE_URL = "http://localhost:8000/api/showProfile";

export default {
  data() {
    return {
      formData: {
        user_name: '',
        user_image: null,
        email:'',
        numTel:'',
        dateNaissance:'',
      },
      user: {
        user_name: '',
        user_image:'',
        email:'',
        numTel:'',
        dateNaissance:'',
      },
      errorMessage: '', 
      successMessage: '', 
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async updateProfile() {
      try {
        const formData = new FormData();
        formData.append('user_name', this.user.user_name); 
        formData.append('email', this.user.email); 
        formData.append('numTel', this.user.numTel); 
        formData.append('dateNaissance', this.user.dateNaissance); 
        formData.append('user_image', this.formData.user_image || '');
    
        const response = await axios.post('http://localhost:8000/api/updateProfile', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
    
        this.errorMessage = '';
        this.successMessage = 'Profil mis à jour avec succès';
        this.clearMessages();
    
        console.log('Response:', response.data);
      } catch (error) {
        this.successMessage = '';
        this.errorMessage = 'Erreur lors de la mise à jour du profil. Veuillez réessayer plus tard.';
        this.clearMessages();
        console.error('Error:', error);
      }
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
      this.errorMessage = 'Erreur lors de la récupération des données du serveur. Veuillez réessayer plus tard.';
      this.clearMessages();
    },
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
      console.log("Data fetched successfully:", data);
      this.user = data;
      if (this.user.dateNaissance) {
        const birthDate = new Date(this.user.dateNaissance);
                if (!isNaN(birthDate.getTime())) {
          this.user.dateNaissance = birthDate.toISOString().split('T')[0];
        } else {
          console.error("Format de date de naissance invalide:", this.user.dateNaissance);
        }
      } else {
        console.warn("La date de naissance est manquante dans les données.");
      }
    },
    onFileChange(event) {
      this.formData.user_image = event.target.files[0];
    },
    clearMessages() {
      setTimeout(() => {
        this.errorMessage = '';
        this.successMessage = '';
      }, 3000); 
    },
  },
  computed: {
    getImageUrl() {
      const baseUrl = 'http://localhost:8000/storage/images/';
      return baseUrl + this.user.user_image;
    }
  }
};
</script>
