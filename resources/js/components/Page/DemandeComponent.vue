<template>
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
                      <table class="table table-borded">
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
                              <!-- Ajout d'un événement click pour ouvrir le modal -->
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

  <!-- Modal pour afficher les détails de la demande -->
  <div class="modal" tabindex="-1" role="dialog" id="demandeModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ selectedDemande.nomEcole }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Affichage des détails de la demande -->
          <p>Adresse: {{ selectedDemande.adresseEcole }}</p>
          <p>Description: {{ selectedDemande.DescriptionEcole }}</p>
          <p>Email: {{ selectedDemande.emailA }}</p>
          <p>CIN: {{ selectedDemande.cin }}</p>
          <p>Numéro de téléphone: {{ selectedDemande.numTel }}</p>
          <p>Date de Naissance: {{ selectedDemande.DateNaissance }}</p>
          <p>Admin: {{ selectedDemande.user_nameA }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
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
      // Ajoutez une propriété pour stocker les détails de la demande sélectionnée
      selectedDemande: {}
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
    },
    // Méthode pour charger les détails de la demande et ouvrir le modal
    async loadDemandeDetails(id) {
      try {
        const response = await axios.get(`${Demande_API_BASE_URL}/show/${id}`);
        // Stockez les détails de la demande sélectionnée
        this.selectedDemande = response.data;
        // Ouvrez le modal
        $('#demandeModal').modal('show');
      } catch (error) {
        console.error("Erreur lors du chargement des détails de la demande:", error);
      }
    }
  },
  computed: {
    filteredDemandes() {
      return this.demande.filter((demande) => {
        return demande.nomEcole.toLowerCase().includes(this.searchQuery.toLowerCase());
      });
    },
  },
};
</script>
