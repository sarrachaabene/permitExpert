<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des auto-écoles
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 order-lg-last">
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher..." />
                      </div>
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter auto-école
                        </button>
                      </div>
                    </div>
                    <br />
                    <div class="table-responsive scrollable-content">
                      <table class="table table-borded">
                        <thead>
                          <tr>
                            <th scope="col">id</th>
                            <th scope="col">auto école</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">nom d'admin</th>
                            <th scope="col">Numéro de téléphone</th>
                            <th scope="col">Email</th>
                            <th scope="col" style="font-size: 14px;">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(auto, index) in filteredAutoEcole" :key="index">
                            <th scope="row">#</th>
                            <td>{{ auto.nom }}</td>
                            <td>{{ auto.adresse }}</td>
                            <td>{{ auto.admin.user_name }}</td>
                            <td>{{ auto.admin.numTel }}</td>
                            <td>{{ auto.admin.email }}</td>
                            <td>
                                <span v-if="auto.deleted_at == null"
                                  style="font-weight: bold;  color: green;">Disponible</span>
                                <span v-if="auto.deleted_at != null"
                                  style="font-weight: bold;  color: red;">Non disponible</span>
                              </td>
                            <td style="display: flex; justify-content: space-between;">
                              <a href="#" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-success" @click="openEditModal(auto.id)">Modifier</a>
                              <a href="#" @click="confirmDelete(auto.id)" style="background-color: orangered; border-color: orangered; margin-left: 5px;" class="btn btn-danger">Supprimer</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="deleteSuccessMessage" class="alert alert-success" role="alert">
              {{ deleteSuccessMessage }}
            </div>
            <div v-if="AddSuccessMessage" class="alert alert-success" role="alert">
              {{ AddSuccessMessage }}
            </div>
            <div v-if="updateSuccessMessage" class="alert alert-success" role="alert">
              {{ updateSuccessMessage }}
            </div>
            <div v-if="deleteerrorsMessage" class="alert alert-danger" role="alert">
              {{ deleteerrorsMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal Ajouter auto ecole -->
  <button type="button"></button>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
            Ajouter auto-école
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form @submit.prevent="addAutoEcole">
            <div class="mb-3">
              <label class="form-label" for="nomAdmin">Nom d'admin:</label>
              <select v-model="nomAdmin" name="nomAdmin" class="form-control" id="nomAdmin" placeholder="Nom d'admin" >
                <option v-for="user in users" :key="user.id" :value="user.user_name">
                  {{ user.user_name }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="nomAutoEcole">Nom d'auto école:</label>
              <input v-model="nomAutoEcole" name="nomAutoEcole" class="form-control" id="nomAutoEcole" type="text" placeholder="Nom d'auto école" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="adresse">Adresse:</label>
              <input v-model="adresse" name="adresse" class="form-control" id="adresse" placeholder="Adresse" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="description">Description:</label>
              <input v-model="description" name="description" class="form-control" id="description" placeholder="Description" />
            </div>  
            <div v-if="AddErrorMessage" class="alert alert-danger" role="alert">
        {{ AddErrorMessage }}
      </div>   
            <div v-if="!isFormValid() && formSubmitted" class="alert alert-danger" role="alert">
              Veuillez remplir tous les champs correctement.
            </div> 
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Ajouter
              </button>
              <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Annuler</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de confirmation de suppression -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer cette auto-école ?
          <br>
          <br>
          Lors de la suppression, tous les utilisateurs, véhicules, séances, examens et transactions de cette auto-école seront supprimés.        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Annuler</button>
          <button type="button" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-danger" @click="deleteAutoEcole">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de modification d'auto-école -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Modifier l'auto-école</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="editAutoEcole">
            <div class="mb-3">
              <label class="form-label" for="editNomAdmin">Nom d'admin:</label>
              <select v-model="editNomAdmin" name="editNomAdmin" class="form-control" id="editNomAdmin" placeholder="Nom d'admin" >
                <option v-for="user in users" :key="user.id" :value="user.user_name">
                  {{ user.user_name }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="editNomAutoEcole">Nom d'auto école:</label>
              <input v-model="editNomAutoEcole" name="editNomAutoEcole" class="form-control" id="editNomAutoEcole" type="text" placeholder="Nom d'auto école" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="editAdresse">Adresse:</label>
              <input v-model="editAdresse" name="editAdresse" class="form-control" id="editAdresse" placeholder="Adresse" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="editDescription">Description:</label>
              <input v-model="editDescription" name="editDescription" class="form-control" id="editDescription" placeholder="Description" />
            </div>      
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Enregistrer
              </button>
              <button type="button" class="btn btn-secondary" style="background-color: #fa7f35; border-color: #fa7f35" data-bs-dismiss="modal">Annuler</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const AUTOECOLE_API_BASE_URL = "http://localhost:8000/api/autoEcole";
const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {
      formSubmitted: false,
      autoEcole: [],
      users: [],
      deleteSuccessMessage: '',
      AddSuccessMessage: '',
      updateSuccessMessage: '',
      AddErrorMessage: '',
      deleteerrorsMessage: '', // Définir la propriété ici
      searchQuery: '', 
      autoEcoleToDeleteId: null,
      nomAdmin: '',
      nomAutoEcole: '',
      adresse: '',
      description: '',
      editNomAdmin: '',
      editNomAutoEcole: '',
      editAdresse: '',
      editDescription: '',
      editAutoEcoleId: null,
    };
  },
  computed: {
    filteredAutoEcole() {
      return this.autoEcole.filter(auto => {
        return auto.nom.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
    }
  },
  mounted() {
    let isAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "admin";
    if (isAdmin) {
      window.location.href = '/dashbord';
    }
    console.log("Component mounted.");
    this.fetchData();
    this.fetchUsers();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${AUTOECOLE_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.autoEcole = data;
      console.log("Data fetched successfully:", this.autoEcole);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    confirmDelete(autoEcoleId) {
      this.autoEcoleToDeleteId = autoEcoleId;
      $('#deleteModal').modal('show'); 
    },
    async fetchUsers() {
      try {
        const response = await axios.get(`${USER_API_BASE_URL}/indexForSuper`);
        this.users = response.data;
        console.log("Fetched users:", this.users);
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    async deleteAutoEcole() {
      try {
        const response = await axios.delete(`${AUTOECOLE_API_BASE_URL}/delete/${this.autoEcoleToDeleteId}`);
        console.log("Auto-école supprimée avec succès:", response.data);
        this.fetchData();
        $('#deleteModal').modal('hide'); 
        this.deleteSuccessMessage = 'Auto-école été supprimée avec succès.';
        setTimeout(() => {
          this.deleteSuccessMessage = ''; 
        }, 3000);
      } catch (error) {
        console.error("Erreur lors de la suppression de l'auto-école:", error.response.data);
        this.deleteerrorsMessage = 'une erreur lors de la suppression ';
        setTimeout(() => {
          this.deleteerrorsMessage = ''; 
        }, 3000);
      }
    },
    async editAutoEcole() {
      try {
        const formData = {
          user_name: this.editNomAdmin,
          nom: this.editNomAutoEcole,
          adresse: this.editAdresse,
          description: this.editDescription
        };
        const response = await axios.put(`${AUTOECOLE_API_BASE_URL}/update/${this.editAutoEcoleId}`, formData);
        console.log("Auto-école modifiée avec succès:", response.data);
        this.fetchData();
        $('#editModal').modal('hide');
        this.updateSuccessMessage = 'Auto-école modifiée avec succès.';
        setTimeout(() => {
          this.updateSuccessMessage = ''; 
        }, 3000);
      } catch (error) {
        console.error("Erreur lors de la modification de l'auto-école:", error.response.data);
        this.errorMessage = "Une erreur s'est produite lors de la modification de l'auto-école.";
      }
    },
    openEditModal(autoEcoleId) {
      const autoEcole = this.autoEcole.find(auto => auto.id === autoEcoleId);
      if (autoEcole) {
        this.editNomAdmin = autoEcole.admin ? autoEcole.admin.user_name : '';
        this.editNomAutoEcole = autoEcole.nom;
        this.editAdresse = autoEcole.adresse;
        this.editDescription = autoEcole.description;
        this.editAutoEcoleId = autoEcoleId;
        $('#editModal').modal('show');
      } else {
        console.error("Auto-école non trouvée avec l'id:", autoEcoleId);
      }
    },
    async addAutoEcole() {
      this.formSubmitted = true;
      if (this.isFormValid()) {
        try {
          const formData = {
            user_name: this.nomAdmin,
            nom: this.nomAutoEcole,
            adresse: this.adresse,
            description: this.description
          };
          const response = await axios.post(`${AUTOECOLE_API_BASE_URL}/store`, formData);
          console.log("Auto-école ajoutée avec succès:", response.data);
          this.fetchData();
          $('#exampleModal').modal('hide'); 
          $('body').removeClass('modal-open'); 
          $('.modal-backdrop').remove();  
          this.AddSuccessMessage = 'Auto-école ajoutée avec succès.';
          setTimeout(() => {
            this.AddSuccessMessage = ''; 
          }, 3000);  
        } catch (error) {      
          console.error("Erreur lors de l'ajout de l'auto-école:", error.response.data);
          if (error.response && error.response.data && error.response.data.message) {
            this.AddErrorMessage = error.response.data.message;
          } else {
            this.AddErrorMessage = "Une erreur s'est produite lors de l'ajout de l'auto-école,L'administrateur a déjà une auto-école .";
          }
          setTimeout(() => {
            this.AddErrorMessage = ''; 
          }, 5000);
        }
      }
    },
    isFormValid() {
      return (
        this.nomAdmin.trim() !== '' &&
        this.nomAutoEcole.trim() !== '' &&
        this.adresse.trim() !== '' &&
        this.description.trim() !== ''
      );
    },
  }
};
</script>
<style scoped>
.scrollable-content {
  max-height: 600px; 
  overflow-y: auto; 
}

</style>
