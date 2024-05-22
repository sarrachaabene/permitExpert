<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des transactions
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
  <div class="col-lg-3 order-lg-last">
    <input type="text" class="form-control" v-model="searchQuery" @input="filterTransactions" placeholder="Recherche par description..." />
  </div>
  <div class="col-lg-3 text-right">
    <button v-if="userRole" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
        color: white;
        background-color: #1F4069;
        border-color: #1F4069;
      " class="btn btn-primary m-1">
      Ajouter transaction
    </button>
  </div>
  <div class="col-lg-3 text-right">
    <select v-model="selectedTransactionType" @change="filterTransactions" class="form-select">
      <option value="">Tous transactions</option>
      <option value="flux entrant">Flux entrant</option>
      <option value="flux sortant">Flux sortant</option>
    </select>
  </div>
  <div class="col-lg-3 text-right">
    <select v-model="selectedConcernant" @change="filterTransactions" class="form-select">
      <option value="">Tous concernant</option>
      <option value="utilisateur">Utilisateur</option>
      <option value="vehicule">Véhicule</option>
      <option value="general">Général</option>
    </select>
  </div>
</div>

                    <br />
                    <div class="table-responsive">
                      <table class="table table-borded">
                        <thead>
                          <tr>
                            <th scope="col">Type de transaction</th>
                            <th scope="col">Concernant</th>
                            <th scope="col">Description</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Date</th>
                            <th scope="col" v-if="selectedConcernant !=='vehicule' && selectedConcernant !=='general'" >Nom d'utilisateur</th>
                            <th scope="col" v-if="selectedConcernant !=='utilisateur' && selectedConcernant !=='general'">véhicule</th>
                            <th scope="col" v-if="userRole">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(tran) in filteredTransactions" :key="tran.id">
                            <td>{{ tran.Type_Transaction }}</td>
                            <td>{{ tran.Type_T }}</td>
                            <td>{{ tran.description }}</td>
                            <td>{{ tran.montantT }} DT</td>
                            <td>{{ tran.dateT }}</td>
                            <td v-if="selectedConcernant !=='vehicule' && selectedConcernant !=='general'">{{ tran.user_name }}  </td>
                            <td v-if="selectedConcernant !=='utilisateur' && selectedConcernant !=='general'">{{ tran.immatricule }}</td>
                            <td  v-if="userRole" style="display: flex; justify-content: space-between;">
                              <a href="#" style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                                " class="btn btn-success">Modifier
                              </a>
                              <a href="#" @click="showDeleteConfirmation(tran.id)" style="
                                background-color: orangered;
                                border-color: orangered;
                                margin-left: 5px;
                                "  class="btn btn-danger">Supprimer
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



  <!-- Modal Confirmation de suppression -->
  <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de suppression</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer cette transaction ?
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
  <!-- Modal Ajouter d'une transaction -->
  <button type="button"></button>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
            Ajouter Transaction
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form action="" method="post">
            <div class="mb-3" >
            <h6 style="text-align: left; "><strong>Concernant</strong></h6>
            <select class="form-select" id="EditTypeCat" >
              <option value="utilisateur">Utilisateur</option>
              <option value="vehicule">Véhicule</option>
              <option value="general">Genéral</option>
            </select>
          </div>
          <div class="mb-3" >
            <h6 style="text-align: left; "><strong>Type transaction</strong></h6>
            <select class="form-select" id="EditTypeCateg" >
              <option value="flux entrant">Flux entrant</option>
              <option value="flux sortant">Flux sortant</option>
            </select>
          </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Adresse:</label>
              <input name="adresse" class="form-control" id="exampleTextarea" placeholder="Adresse" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Nom d'admin:</label>
              <input name="nom admin" class="form-control" id="exampleTextarea" placeholder="Nom d'admin" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="telephone">Numéro de téléphone:</label>
              <input name="telephone" type="tel" class="form-control" id="telephone"
                placeholder="Numéro de téléphone" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="email">E-mail:</label>
              <input name="email" type="email" class="form-control" id="email" placeholder="E-mail" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="button" style="background-color: #9dcd5a; border-color: #9dcd5a">
            Ajouter
          </button>
          <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const TRANSACTION_API_BASE_URL = "http://localhost:8000/api/transaction";

export default {
  data() {
    return {
      originalTransactionList: [], // Ajouter cette variable pour stocker la liste originale des transactions
      transaction: [],
      searchQuery: '',
      selectedTransactionType: '',
      selectedConcernant: '',
      selectedTransactionId: null,
      userRole: localStorage.getItem('users') && JSON.parse(localStorage.getItem('users'))[0].role === "admin"

    };
  },
  computed: {
    filteredTransactions() {
      return this.transaction.filter(tran => {
        const matchesSearch = tran.description.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesType = !this.selectedTransactionType || tran.Type_Transaction === this.selectedTransactionType;
        const matchesConcernant = !this.selectedConcernant || tran.Type_T === this.selectedConcernant;
        return matchesSearch && matchesType && matchesConcernant;
      });
    }
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
        const response = await axios.get(`${TRANSACTION_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    async showDeleteConfirmation(id) {
      this.selectedTransactionId = id;
      $('#deleteConfirmationModal').modal('show');
    },
    async confirmDeleteUser() {
      try {
        const response = await axios.delete(`${TRANSACTION_API_BASE_URL}/delete/${this.selectedTransactionId}`);
        console.log(response.data); 
        this.fetchData(); 
        $('#deleteConfirmationModal').modal('hide');
      } catch (error) {
        console.error("Error deleting user:", error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.transaction = data;
      this.originalTransactionList = data; // Sauvegarder la liste originale des transactions
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    filterTransactions() {
      if (this.selectedTransactionType === "") {
        this.transaction = this.originalTransactionList; // Rétablir la liste originale des transactions
      } else {
        const filteredTransactions = this.originalTransactionList.filter(tran => tran.Type_Transaction === this.selectedTransactionType);
        this.transaction = filteredTransactions;
      }
    }
  },
};
</script>
