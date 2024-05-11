<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des ressources éducatives
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 text-right">
                        <br><br><br>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter ressource
                        </button>
                      </div>
                    </div>
                    <br>  
                    <br />
                    <div class="table-responsive">
                      <table class="table table-borded">
                        <thead>
                          <tr>
                            <th scope="col">id</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Description</th>
                            <th scope="col">Lien</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(res, index) in ressource" :key="index">
                            <th scope="row">{{ res.id }}</th>
                            <td>{{ res.titreR }}</td>
                            <td>{{ res.descriptionR }}</td>
                            <td>{{ res.link }} </td>
                            <td style="display: flex; justify-content: space-between;">
                              <button @click="openEditModal(res)" style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                                " class="btn btn-success">Modifier
                              </button>
                              <a href="#" @click="deleteResource(res.id)" style="
                                background-color: orangered;
                                border-color: orangered;
                                margin-left: 5px;
                                " class="btn btn-danger">Supprimer
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Ajouter ressource -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
            Ajouter ressource
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label" for="exampleFormControlInput1">Titre:</label>
              <input v-model="newRessource.titreR" class="form-control" id="exampleFormControlInput1" type="text" placeholder="Titre" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Description:</label>
              <input v-model="newRessource.descriptionR" class="form-control" id="exampleTextarea" placeholder="Description" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Lien:</label>
              <input v-model="newRessource.link" class="form-control" id="exampleTextarea" placeholder="Lien" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button @click="addResource" class="btn btn-primary" type="button" style="background-color: #9dcd5a; border-color: #9dcd5a">
            Ajouter
          </button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal" style="background-color: #fa7f35; border-color: #fa7f35">
            Annuler
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Modification ressource -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="editModalLabel" style="font-weight: bold; margin-top: 30px ;text-align: center;">Modifier la ressource</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label" for="editTitre">Titre:</label>
              <input v-model="updatedRessource.titreR" type="text" class="form-control" id="editTitre" placeholder="Titre">
            </div>
            <div class="mb-3">
              <label class="form-label" for="editDescription">Description:</label>
              <input v-model="updatedRessource.descriptionR" type="text" class="form-control" id="editDescription" placeholder="Description">
            </div>
            <div class="mb-3">
              <label class="form-label" for="editLink">Lien:</label>
              <input v-model="updatedRessource.link" type="text" class="form-control" id="editLink" placeholder="Lien">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button @click="updateResource(selectedResourceId)" type="button" class="btn btn-primary" style="background-color: #9dcd5a; border-color: #9dcd5a">Enregistrer</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #fa7f35; border-color: #fa7f35">Annuler</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Confirmation de suppression de ressource -->
  <div class="modal fade" id="deleteResourceConfirmationModal" tabindex="-1" aria-labelledby="deleteResourceConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteResourceConfirmationModalLabel">Confirmation de suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer cette ressource éducative ?
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button"  style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-danger" @click="deleteResourceConfirmed">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const RESSOURCE_API_BASE_URL = "http://localhost:8000/api/ressourceeducative";

export default {
  data() {
    return {
      ressource: [],
      selectedResourceId: null,
      newRessource: {
        titreR: '',
        descriptionR: '',
        link: ''
      },
      updatedRessource: {
        titreR: '',
        descriptionR: '',
        link: ''
      }
    };
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${RESSOURCE_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.ressource = data;
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    async addResource() {
      try {
        const response = await axios.post(`${RESSOURCE_API_BASE_URL}/store`, this.newRessource);
        console.log(response.data); 
        this.fetchData(); 
        this.newRessource = { titreR: '', descriptionR: '', link: '' };
        $('#exampleModal').modal('hide'); 
        $('body').removeClass('modal-open'); 
        $('.modal-backdrop').remove(); 
      } catch (error) {
        console.error("Error adding resource:", error);
      }
    },
    openEditModal(resource) {
      this.selectedResourceId = resource.id;
      this.updatedRessource = {
        titreR: resource.titreR,
        descriptionR: resource.descriptionR,
        link: resource.link
      };
      $('#editModal').modal('show');
    },
    async updateResource(id) {
      try {
        const response = await axios.put(`${RESSOURCE_API_BASE_URL}/update/${id}`, this.updatedRessource);
        console.log(response.data); 
        this.fetchData(); 
        $('#editModal').modal('hide'); 
        $('body').removeClass('modal-open'); 
        $('.modal-backdrop').remove(); 
      } catch (error) {
        console.error("Error updating resource:", error);
      }
    },
    deleteResource(id) {
      this.selectedResourceId = id;
      $('#deleteResourceConfirmationModal').modal('show');
    },
    async deleteResourceConfirmed() {
      try {
        const response = await axios.delete(`${RESSOURCE_API_BASE_URL}/delete/${this.selectedResourceId}`);
        console.log(response.data); 
        this.fetchData(); 
        $('#deleteResourceConfirmationModal').modal('hide');
      } catch (error) {
        console.error("Error deleting resource:", error);
      }
    },
  },
  mounted() {
    console.log("Component mounted.");
    this.fetchData();
  },
}; 
</script>
