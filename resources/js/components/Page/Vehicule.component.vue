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

                    <table class="table table-borded">
                      <thead>
                        <tr>
                          <th scope="col">id</th>
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
                          <th scope="row">{{ veh.id}}</th>
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
              <input v-model="newVehicle.Immatriculation" class="form-control" id="Immatriculation" type="text" placeholder="Immatriculation" />
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
                <option value="Moto">Moto</option>
                <option value="Voiture">Voiture</option>
                <option value="Camion">Camion</option>
                <option value="Bus">Bus</option>
              </select>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Ajouter
              </button>
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal"  style="background-color: #fa7f35; border-color: #fa7f35"  >
                Annuler
              </button>
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
              <input v-model="selectedVehicle.immatricule" class="form-control" id="EditImmatriculation" type="text" placeholder="Immatriculation" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditKilometrage">Kilométrage:</label>
              <input v-model="selectedVehicle.kilometrage" class="form-control" id="EditKilometrage" type="text" placeholder="Kilométrage" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditMarque">Marque:</label>
              <input v-model="selectedVehicle.marque" class="form-control" id="EditMarque" type="text" placeholder="Marque" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="EditType">Type:</label>
              <select v-model="selectedVehicle.typeV" class="form-select" id="EditType">
                <option value="Moto">Moto</option>
                <option value="Voiture">Voiture</option>
                <option value="Camion">Camion</option>
                <option value="Bus">Bus</option>
              </select>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Modifier
              </button>
              <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal"  style="background-color: #fa7f35; border-color: #fa7f35"  >
                Annuler
              </button>
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
      vehicule: [],
      searchQuery: '',
      selectedVehicle: {},
      transactions: [],
      newVehicle: { Immatriculation: '', kilometrage: '', marque: '', typeV: '' },
      selectedVehicleId: null // Variable pour stocker l'ID du véhicule sélectionné pour la suppression
    };
  },
  mounted() {
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
        console.error("Error fetching transactions:", error);
      }
    },
    async addVehicle() {
      try {
        const response = await axios.post(`${VEHICULE_API_BASE_URL}/store`, this.newVehicle);
        console.log("Vehicle added successfully:", response.data);
        this.fetchData();
        $('#exampleModal').modal('hide');
      } catch (error) {
        console.error("Error adding vehicle:", error);
      }
    },
    deleteVehicle(id) {
      // Stockez l'ID du véhicule à supprimer dans une variable pour référence ultérieure
      this.selectedVehicleId = id;
      // Affichez le modal de confirmation
      $('#deleteConfirmationModal').modal('show');
    },
    async deleteConfirmed() {
      try {
        // Exécutez la suppression du véhicule avec l'ID stocké
        const response = await axios.delete(`${VEHICULE_API_BASE_URL}/delete/${this.selectedVehicleId}`);
        console.log(response.data); // Afficher la réponse de l'API
        this.fetchData(); // Rafraîchir la liste des véhicules après la suppression
        // Cachez le modal de confirmation après la suppression réussie
        $('#deleteConfirmationModal').modal('hide');
      } catch (error) {
        console.error("Error deleting vehicle:", error);
      }
    },
    openEditModal(veh) {
      this.selectedVehicle = veh;
      $('#editModal').modal('show');
    },
    async updateVehicle() {
      try {
        const response = await axios.put(`${VEHICULE_API_BASE_URL}/update/${this.selectedVehicle.id}`, this.selectedVehicle);
        console.log("Vehicle updated successfully:", response.data);
        this.fetchData();
        $('#editModal').modal('hide');
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
