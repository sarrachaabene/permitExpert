<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des administrateurs
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 order-lg-last">
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher par nom d'utilisateur..." />
                      </div>
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter un administrateur
                        </button>
                      </div>
                    </div>
                    <br />
                    <div class="table-responsive">
                      <table class="table table-borded">
                        <thead>
                          <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Numéro de téléphone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(user) in filteredUsers" :key="user.id">
                            <td>{{ user.user_name }}</td>
                            <td>{{ user.numTel }}</td>
                            <td>{{ user.email }}</td>
                            <td class="d-flex justify-content-center">
                              <a href="#" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-success" @click="showEditModal(user)">Modifier</a>
                              <a href="#" @click="showDeleteConfirmation(user.id)" style="background-color: orangered; border-color: orangered; margin-left: 5px;" class="btn btn-danger">Supprimer</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="AddSuccessMessage" class="alert alert-success" role="alert">
  {{ AddSuccessMessage }}
</div>
<div v-if="deleteSuccessMessage" class="alert alert-success" role="alert">
  {{ deleteSuccessMessage }}
</div>
<div v-if="AddErrorMessage" class="alert alert-danger" role="alert">
  {{ AddErrorMessage }}
</div>
<div v-if="updateSuccessMessage" class="alert alert-success" role="alert">
  {{ updateSuccessMessage }}
</div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Ajout d'administrateur -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <br>
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">Ajouter un administrateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="user_name">Nom d'administrateur:</label>
            <input type="text" class="form-control" id="user_name" v-model="newAdmin.user_name">
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" v-model="newAdmin.email">
          </div>
          <div class="form-group">
            <label for="cin">CIN:</label>
            <input type="text" class="form-control" id="cin" v-model="newAdmin.cin">
          </div>
          <div class="form-group">
            <label for="numTel">Numéro de téléphone:</label>
            <input type="text" class="form-control" id="numTel" v-model="newAdmin.numTel">
          </div>
          <div class="form-group">
            <label for="dateNaissance">Date de naissance:</label>
            <input type="date" class="form-control" id="dateNaissance" v-model="newAdmin.dateNaissance">
          </div>
          <div v-if="!isFormValid() && formSubmitted" class="alert alert-danger" role="alert">
            Veuillez remplir tous les champs correctement.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Annuler</button>

          <button type="button" class="btn btn-primary" @click="addAdmin" style="background-color: #9dcd5a; border-color: #9dcd5a">Ajouter</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modification d'administrateur -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <br>
          <h5 class="modal-title mx-auto" id="editModalLabel" style="font-weight: bold; margin-top: 30px ;text-align: center;">Modifier les informations de l'administrateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_user_name">Nom d'utilisateur:</label>
            <input type="text" class="form-control" id="edit_user_name" v-model="editedUser.user_name">
          </div>
          <div class="form-group" v-if="showEmailField">
  <label for="email">Email:</label>
  <input type="email" class="form-control" id="email" v-model="newAdmin.email">
</div>

          <div class="form-group">
            <label for="edit_cin">CIN:</label>
            <input type="text" class="form-control" id="edit_cin" v-model="editedUser.cin">
          </div>
          <div class="form-group">
            <label for="edit_numTel">Numéro de téléphone:</label>
            <input type="text" class="form-control" id="edit_numTel" v-model="editedUser.numTel">
          </div>
          <div class="form-group">
            <label for="edit_dateNaissance">Date de naissance:</label>
            <input type="date" class="form-control" id="edit_dateNaissance" v-model="editedUser.dateNaissance">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Fermer</button>
          <button type="button" style="background-color: #9dcd5a; border-color: #9dcd5a" class="btn btn-primary" @click="updateAdmin">Enregistrer </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Confirmation de suppression -->
  <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer cet administrateur ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" style="
            background-color: #9dcd5a;
            border-color: #9dcd5a;
            margin-right: 5px;
          " @click="confirmDeleteUser">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {

      AddSuccessMessage:'',
      deleteSuccessMessage:'',
      updateSuccessMessage:'',
      AddErrorMessage:'',
      users: [],
      newAdmin: {
        user_name: '',
        numTel: '',
        email: '',
        cin: '',
        dateNaissance: ''
      },
      editedUser: {
        user_name: '',
        numTel: '',
        email: '',
        cin: '',
        dateNaissance: ''
      },
      searchQuery: '',
      selectedUserId: null,
      formSubmitted: false
    };
  },
  mounted() {
    let isAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "admin";
    if (isAdmin){
      window.location.href = '/dashbord';
    }
    console.log("Component mounted.");
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${USER_API_BASE_URL}/indexForSuper`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.users = data;
      console.log("Data fetched successfully:", this.users);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    async addAdmin() {
      this.formSubmitted = true;
      if (this.isFormValid()) {
        try {
          const response = await axios.post(`${USER_API_BASE_URL}/storeForSuperAdmin`, this.newAdmin);
          console.log(response.data); 
          this.fetchData(); 
          this.newAdmin = { user_name: '', numTel: '', email: '', cin: '', dateNaissance: '' };
          $('#exampleModal').modal('hide'); 
          $('body').removeClass('modal-open'); 
          $('.modal-backdrop').remove(); 
          this.AddSuccessMessage = 'Admin a été ajoutée avec succès.';
    setTimeout(() => {
      this.AddSuccessMessage = ''; 
    }, 3000);
        } catch (error) {
          console.error("Error adding admin:", error);
          this.AddErrorMessage = 'Erreur lors ajout d\'admin';
    setTimeout(() => {
      this.AddErrorMessage = ''; 
    }, 3000);
        }
      }
    },
    async showEditModal(user) {
      this.editedUser = { ...user };
      $('#editModal').modal('show');
    },
    async updateAdmin() {
      try {
        const response = await axios.put(`${USER_API_BASE_URL}/updateForSuperAdmin/${this.editedUser.id}`, this.editedUser);
        console.log(response.data);
        this.fetchData();
        $('#editModal').modal('hide');
        this.updateSuccessMessage = 'La transaction a été mise à jour avec succès.';
    setTimeout(() => {
      this.updateSuccessMessage = ''; 
    }, 3000);
      } catch (error) {
        console.error("Error updating admin:", error);
      }
    },
    isFormValid() {
      return (
        this.newAdmin.user_name.trim() !== '' &&
        this.newAdmin.email.trim() !== '' &&
        this.isValidEmail(this.newAdmin.email) &&
        this.newAdmin.cin.trim() !== '' &&
        this.newAdmin.numTel.trim() !== '' &&
        this.newAdmin.dateNaissance.trim() !== ''
      );
    },
    isValidEmail(email) {
      const emailRegex = /\S+@\S+\.\S+/;
      return emailRegex.test(email);
    },
    async showDeleteConfirmation(id) {
      this.selectedUserId = id;
      $('#deleteConfirmationModal').modal('show');
    },
    async confirmDeleteUser() {
      try {
        const response = await axios.delete(`${USER_API_BASE_URL}/deleteForSuperAdmin/${this.selectedUserId}`);
        console.log(response.data); 
        this.fetchData(); 
        $('#deleteConfirmationModal').modal('hide');
        this.deleteSuccessMessage = 'Admin a été supprimée avec succès.';
    setTimeout(() => {
      this.deleteSuccessMessage = ''; 
    }, 3000);
      } catch (error) {
        console.error("Error deleting user:", error);
      }
    }
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => {
        return user.user_name.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
  
    }
  },
  watch: {
    formSubmitted(value) {
      if (value) {
        setTimeout(() => {
          this.formSubmitted = false;
        }, 3000);
      }
    }
  }
};
</script>

