<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des véhicules
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 order-lg-last">
                        <input type="text" class="form-control" v-model="searchQuery" @input="searchVehicles" placeholder="Rechercher par immatriculation..." />
                      </div>
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter véhicule
                        </button>
                      </div>
                    </div>
                    <br />
                    <div class="table-responsive">
                    <table class="table table-borded">
                      <thead>
                        <tr>
                          <th scope="col">Immatriculation</th>
                          <th scope="col">Marque</th>
                          <th scope="col">Type</th>
                          <th scope="col">Kilométrage</th>
                          <th scope="col">Dépense</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(veh) in filteredVehicles" :key="veh.id">
                          <td>{{ veh.immatricule}}</td>
                          <td>{{ veh.marque}}</td>
                          <td>{{ veh.typeV }}</td>
                          <td>{{ veh.kilometrage }}</td>
                          <td>
                            <a href="#" @click="openConsultModal(veh)" style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                              " class="btn btn-success">Consulter
                            </a>
                          </td>
                          <td>
  <div class="btn-group" role="group" aria-label="Actions">
    <a href="#" @click="openEditModal(veh)" style="
      background-color: #9dcd5a;
      border-color: #9dcd5a;
      margin-right: 5px;
      " class="btn btn-success">Modifier
    </a>
    <a href="#" @click="deleteVehicle(veh.id)" style="
      background-color: orangered;
      border-color: orangered;
      margin-left: 5px;
      " class="btn btn-danger">Supprimer
    </a>
  </div>
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

              <div v-if="updateSuccessMessage" class="alert alert-success" role="alert">
                {{ updateSuccessMessage }}
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ajouter véhicule -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
            Ajouter véhicule
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form @submit.prevent="addVehicle" method="post">
            <div class="mb-3">
              <label class="form-label" for="Immatriculation">Immatriculation:</label>
              <input v-model="newVehicle.immatricule" class="form-control" id="Immatriculation" type="text" placeholder="Immatriculation" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="kilometrage">Kilométrage:</label>
              <input v-model="newVehicle.kilometrage" class="form-control" id="kilometrage" type="text" placeholder="Kilométrage" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="marque">Marque:</label>
              <input v-model="newVehicle.marque" class="form-control" id="marque" type="text" placeholder="Marque" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="typeV">Type:</label>
              <select v-model="newVehicle.typeV" class="form-select" id="typeV">
                <option value="moto">moto</option>
                <option value="voiture">voiture</option>
                <option value="camion">camion</option>
                <option value="bus">bus</option>
              </select>
            </div>
            <div v-if="AddErrorMessage" class="alert alert-danger" role="alert">
                {{ AddErrorMessage }}
              </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Ajouter
              </button>
              <button type="button"   style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Consulter -->
  <div class="modal fade" id="consultModal" tabindex="-1" aria-labelledby="consultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <h5 style="text-align: center;">Dépenses du véhicule</h5>
          <br>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Description</th>
                <th scope="col">Montant (DT)</th>
                <th scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in transactions" :key="transaction.id">
                <td>{{ transaction.description }}</td>
                <td>{{ transaction.montantT }}</td>
                <td>{{ transaction.dateT }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modifier véhicule -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="editModalLabel" style="font-weight: bold; margin-top: 30px">
            Modifier véhicule
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form @submit.prevent="updateVehicle" method="post">
            <div class="mb-3">
              <label class="form-label" for="EditImmatriculation">Immatriculation:</label>
              <input v-model="editedVehicle.immatricule" class="form-control" id="EditImmatriculation" type="text" placeholder="Immatriculation" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditKilometrage">Kilométrage:</label>
              <input v-model="editedVehicle.kilometrage" class="form-control" id="EditKilometrage" type="text" placeholder="Kilométrage" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditMarque">Marque:</label>
              <input v-model="editedVehicle.marque" class="form-control" id="EditMarque" type="text" placeholder="Marque" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditType">Type:</label>
              <select v-model="editedVehicle.typeV" class="form-select" id="EditType">
                <option value="moto">Moto</option>
                <option value="voiture">Voiture</option>
                <option value="camion">Camion</option>
                <option value="bus">Bus</option>
              </select>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Modifier
              </button>
              <button type="button"   style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

            </div>
          </form>
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
          Êtes-vous sûr de vouloir supprimer ce véhicule ?
        </div>
        <div class="modal-footer">
          <button type="button"   style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                              "  @click="deleteConfirmed">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const VEHICULE_API_BASE_URL = "http://localhost:8000/api/vehicule";

export default {
  data() {
    return {
      AddSuccessMessage: '',
    deleteSuccessMessage: '',
    updateSuccessMessage: '',
    AddErrorMessage: '',
      vehicule: [],
      searchQuery: '',
      selectedVehicle: {},
      transactions: [],
      newVehicle: { immatricule: '', kilometrage: '', marque: '', typeV: '' },
      selectedVehicleId: null,
      editedVehicle: {}, 
    };
  },
  mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin){
      window.location.href = '/dashbord_Super_Admin';
    }
    console.log("Component mounted.");
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${VEHICULE_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.vehicule = data;
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    searchVehicles() {
      this.filteredVehicles = this.vehicule.filter(veh => veh.immatricule.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },
    openConsultModal(veh) {
      this.selectedVehicle = veh;
      this.fetchTransactionsByVehiculeId(veh.id);
      $('#consultModal').modal('show');
    },
    async fetchTransactionsByVehiculeId(vehiculeId) {
      try {
        const response = await axios.get(`http://localhost:8000/api/transaction/ShowTransactionByvehiculeId/${vehiculeId}`);
        this.transactions = response.data;
      } catch (error) {
        console.error("Error fetching vehicule:", error);
      }
    },
    async addVehicle() {
      try {
        const response = await axios.post(`${VEHICULE_API_BASE_URL}/store`, this.newVehicle);
        console.log("Vehicle added successfully:", response.data);
        this.fetchData();
        $('#exampleModal').modal('hide'); 
          $('body').removeClass('modal-open'); 
          $('.modal-backdrop').remove();
          this.AddSuccessMessage = 'vehicule ajouté avec succès.';
        setTimeout(() => {
          this.AddSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Error adding vehicle:", error);
        this.AddErrorMessage = 'Erreur lors de l\'ajout d\'une vehicule';
        setTimeout(() => {
          this.AddErrorMessage = '';
        }, 3000);
      }
    },
    deleteVehicle(id) {
      this.selectedVehicleId = id;
      $('#deleteConfirmationModal').modal('show');
    },
    async deleteConfirmed() {
      try {
        const response = await axios.delete(`${VEHICULE_API_BASE_URL}/delete/${this.selectedVehicleId}`);
        console.log(response.data);
        this.fetchData(); 
        $('#deleteConfirmationModal').modal('hide');
        this.deleteSuccessMessage = 'Vehicule supprimee';
        setTimeout(() => {
          this.deleteSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Error deleting vehicle:", error);
      }
    },
    openEditModal(veh) {
      this.editedVehicle = JSON.parse(JSON.stringify(veh));
      $('#editModal').modal('show');
    },
    async updateVehicle() {
      try {
        const response = await axios.put(`${VEHICULE_API_BASE_URL}/update/${this.editedVehicle.id}`, this.editedVehicle);
        console.log("Vehicle updated successfully:", response.data);
        this.fetchData();
        $('#editModal').modal('hide');
        this.updateSuccessMessage = 'Vehicule modifié';
        setTimeout(() => {
          this.updateSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Error updating vehicle:", error);
      }
    }
  },
  computed: {
    filteredVehicles() {
      return this.vehicule.filter(veh => veh.immatricule.toLowerCase().includes(this.searchQuery.toLowerCase()));
    }
  }
};
</script>