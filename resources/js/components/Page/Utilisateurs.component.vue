<template>
  <div class="container text-center">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="font-weight: bold">
              Liste des utilisateurs
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 order-lg-last">
                        <input type="text" class="form-control" v-model="searchQuery" @input="filterUsersByName"
                          placeholder="Rechercher par nom..." />
                      </div>
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter utilisateur 
                        </button>
                      </div>
                      <div class="col-lg-4 text-right">
                        <select v-model="selectedRole" @change="filterUsers" class="form-select">
                          <option value="">Tous les rôles</option>
                          <option value="secretaire">Secrétaire</option>
                          <option value="moniteur">Moniteur</option>
                          <option value="candidat">Candidat</option>
                        </select>
                      </div>
                    </div>
                    <br />
                    <div class="col">
                      <div class="table-responsive">
                        <table class="table table-borded">
                          <thead>
                            <tr>
                              <th scope="col" style="font-size: 14px;">Rôle</th>
                              <th scope="col" style="font-size: 14px;">Nom</th>
                              <th scope="col" style="font-size: 14px;">Numéro de téléphone</th>
                              <th scope="col" style="font-size: 14px;">Email</th>
                              <th scope="col" style="font-size: 14px;">CIN</th>
                              <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'"
                                style="font-size: 14px;">Catégorie permis</th>
                              <th scope="col" style="font-size: 14px;">Historique de paiement</th>
                              <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'"
                                style="font-size: 14px;">Résultat</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(user, index) in user" :key="index">
                              <td>{{ user.role }}</td>
                              <td>{{ user.user_name }}</td>
                              <td>{{ user.numTel }}</td>
                              <td>{{ user.email }}</td>
                              <td>{{ user.cin }}</td>
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">{{ user.cat_permis }}</td>
                              <td>
                                <button @click="showTransactionDetails(user.id)" style="background-color: #9dcd5a; border-color: #9dcd5a;"  class="btn btn-success">Consulter</button>
                              </td>
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">
                                <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Consulter</a>
                              </td>
                              <td class="d-flex justify-content-between">
                                <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Modifier</a>
                                <a href="" @click.prevent="showDeleteConfirmationModal(user.id)" style="background-color: orangered; border-color: orangered; margin-left: 5px;" class="btn btn-danger">Supprimer</a>
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

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de suppression</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Êtes-vous sûr de vouloir supprimer cet utilisateur ?
          </div>
          <div class="modal-footer">
            <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" @click="confirmDeleteUser">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal des détails de la transaction -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <h5 style="text-align: center;">Historique de paiement</h5>
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
    <tr v-for="(transaction, index) in transactionDetails" :key="index">
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
  </div>
</template>

<script>
import axios from "axios";

const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {
      originalUserList: [],
      user: [],
      searchQuery: '',
      selectedRole: '',
      userIdToDelete: null,
      transactionDetails: {},
    
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
        const response = await axios.get(`${USER_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.user = data;
      this.originalUserList = data;
      console.log("Data fetched successfully:", this.user);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    showDeleteConfirmationModal(userId) {
      this.userIdToDelete = userId;
      $('#deleteConfirmationModal').modal('show');
    },
    async confirmDeleteUser() {
      try {
        await axios.delete(`http://localhost:8000/api/user/delete/${this.userIdToDelete}`);
        this.fetchData();
        $('#deleteConfirmationModal').modal('hide');
      } catch (error) {
        console.error("Erreur lors de la suppression de l'utilisateur:", error);
      }
    },
    async showTransactionDetails(userId) {
  $('#transactionDetailsModal').modal('show'); // Affichez le modal avant même de recevoir la réponse

  try {
    const response = await axios.get(`http://localhost:8000/api/transaction/ShowTransactionByuserId/${userId}`);
    console.log(response.data);
    this.transactionDetails = response.data; // Mettez à jour pour stocker directement les détails de la transaction renvoyés par l'API
  } catch (error) {
    console.error("Erreur lors de la récupération des détails de la transaction:", error);
    // En cas d'erreur, vous pouvez initialiser transactionDetails à un tableau vide
    this.transactionDetails = [];
  }
},



    filterUsersByName() {
      if (!this.searchQuery) {
        this.user = this.originalUserList;
        return;
      }
      this.user = this.originalUserList.filter(user => user.user_name.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },
    filterUsers() {
      if (this.selectedRole === "") {
        this.user = this.originalUserList;
      } else {
        const filteredUsers = this.originalUserList.filter(user => user.role === this.selectedRole);
        this.user = filteredUsers;
      }
    },
  }
}
</script>
