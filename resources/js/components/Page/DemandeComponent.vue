<template>
  <div>
    <br><br><br>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h4 style="text-align: center; font-weight: bold">
                Liste des demandes
              </h4>
              <div class="content">
                <div class="pb-5">
                  <div class="row g-5">
                    <div class="col">
                      <div class="row mt-4">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4">
                          <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher..." />
                        </div>
                      </div>
                      <br />
                      <div class="table-responsive">
                        <table class="table table">
                          <thead>
                            <tr>
                              <th scope="col">id</th>
                              <th scope="col">auto école</th>
                              <th scope="col">Admin</th>
                              <th scope="col">status</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(demande, index) in filteredDemandes" :key="index">
                              <th scope="row">{{ index + 1 }}</th>
                              <td>{{ demande.nomEcole }}</td>
                              <td>{{ demande.user_nameA }}</td>
                              <td>{{ demande.status }}</td>
                              <td>
                                <a href="#" @click="loadDemandeDetails(demande.id)" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-success">Consulter</a>
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

    <div class="modal" tabindex="-1" role="dialog" id="demandeModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title mx-auto" style="font-weight: bold; margin-top: 30px">{{ selectedDemande.nomEcole }}</h4>
          </div>
          <br>
          <div class="modal-body">
            <h5 style="font-weight: bold; text-decoration: underline;">Information d'auto école</h5>
            <p><strong>Nom d'auto école :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ selectedDemande.nomEcole }}</p>
            <p><strong>Adresse :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ selectedDemande.aderesseEcole }}</p>
            <p><strong>Description :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ selectedDemande.descriptionEcole }}</p>
            <h5 style="font-weight: bold; text-decoration: underline;">Information sur l'administrateur</h5>
            <p><strong>Nom  :</strong> {{ selectedDemande.user_nameA }} </p>
            <p><strong>Email :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ selectedDemande.emailA }}</p>
            <p><strong>CIN:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ selectedDemande.cin }}</p>
            <p><strong>Numéro de téléphone:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{ selectedDemande.numTel }}</p>
            <p><strong>Date de Naissance:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ selectedDemande.dateNaissance }}</p>
          </div>
          
          <div class="modal-footer justify-content-between"> 
            <div> 
              <button style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-outline" @click="accepterDemande">Accepter</button> 
              <button style="background-color: #FA7F35; border-color: #FA7F35; margin-right: 5px;" class="btn btn-outline" data-bs-dismiss="modal" @click="refuserDemande">Refuser</button> 
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="successMessage" class="alert alert-success" role="alert" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 999;">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-danger" role="alert" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 999;">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
import axios from "axios";
const Demande_API_BASE_URL = "http://localhost:8000/api/demandeInscript";

export default {
  data() {
    return {
      demande: [],
      searchQuery: "",
      selectedDemande: {},
      successMessage: "",
      errorMessage: ""
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${Demande_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.demande = data;
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
      this.errorMessage = "Erreur lors de la récupération des données.";
      setTimeout(() => {
        this.errorMessage = "";
      }, 2000); 
    },
    async loadDemandeDetails(id) {
      try {
        const response = await axios.get(`${Demande_API_BASE_URL}/show/${id}`);
        this.selectedDemande = response.data;
        $('#demandeModal').modal('show');
      } catch (error) {
        console.error("Erreur lors du chargement des détails de la demande:", error);
      }
    },
    async accepterDemande() {
  try {
    const idDemande = this.selectedDemande.id;
    const response = await axios.post(`${Demande_API_BASE_URL}/accepter/${idDemande}`);
    console.log("Demande acceptée avec succès:", response.data);
    this.successMessage = "La demande a été acceptée avec succès.";
    setTimeout(() => {
      this.successMessage = "";
    }, 2000); 
    $('#demandeModal').modal('hide');
    this.fetchData(); 
  } catch (error) {
    console.error("Erreur lors de l'acceptation de la demande:", error);
    this.errorMessage = "Erreur lors de l'acceptation de la demande.";
    setTimeout(() => {
      this.errorMessage = "";
    }, 2000);
  }
},
async refuserDemande() {
      try {
        const idDemande = this.selectedDemande.id;
        const response = await axios.post(`${Demande_API_BASE_URL}/refuseDemande/${idDemande}`);
        console.log("Demande refusée avec succès:", response.data);
        this.successMessage = "La demande a été refusée avec succès.";
        setTimeout(() => {
          this.successMessage = "";
        }, 2000); 
        $('#demandeModal').modal('hide'); 
        this.fetchData(); 
      } catch (error) {
        console.error("Erreur lors du refus de la demande:", error);
        this.errorMessage = "Erreur lors du refus de la demande.";

        setTimeout(() => {
          this.errorMessage = "";
        }, 2000); 
      }
    }


  },
  computed: {
    filteredDemandes() {
      return this.demande.filter((demande) => {
        return demande.nomEcole.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
    }
  }
};
</script>
