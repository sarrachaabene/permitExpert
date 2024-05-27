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
                              <a href="#" @click="openEditModal(tran)" style="
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
            <div v-if="deleteSuccessMessage" class="alert alert-success" role="alert">
  {{ deleteSuccessMessage }}
</div>
<div v-if="AddSuccessMessage" class="alert alert-success" role="alert">
  {{ AddSuccessMessage }}
</div>
<div v-if="updateSuccessMessage" class="alert alert-success" role="alert">
  {{ updateSuccessMessage }}
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



    <!-- Modal Modifier une transaction -->
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="editTransactionModalLabel" style="font-weight: bold; margin-top: 30px">
            Modifier Transaction
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form @submit.prevent="updateTransaction">
            <div class="mb-3">
              <h6 style="text-align: left;"><strong>Concernant</strong></h6>
              <select class="form-select" v-model="newTransactionConcernant">
                <option value="utilisateur">Utilisateur</option>
                <option value="vehicule">Véhicule</option>
                <option value="general">Général</option>
              </select>
            </div>
            <div class="mb-3">
              <h6 style="text-align: left;"><strong>Type transaction</strong></h6>
              <select class="form-select" v-model="newTransactionType">
                <option value="flux entrant">Flux entrant</option>
                <option value="flux sortant">Flux sortant</option>
              </select>
            </div>
            <div class="mb-3" v-if="newTransactionConcernant === 'utilisateur'">
              <h6 style="text-align: left;"><strong>Nom d'utilisateur</strong></h6>
              <select class="form-control" v-model="newTransactionUserName" placeholder="Nom d'utilisateur">
                <option v-for="user in users" :key="user.id" :value="user.user_name">
                  {{ user.user_name }}
                </option>
              </select>
            </div>
            <div class="mb-3" v-if="newTransactionConcernant === 'vehicule'">
              <h6 style="text-align: left;"><strong>Immatricule</strong></h6>
              <select class="form-control" v-model="newTransactionImmatricule" placeholder="Immatricule">
                <option v-for="vehicule in vehicules" :key="vehicule.id" :value="vehicule.immatricule">
                  {{ vehicule.immatricule }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Montant:</label>
              <input name="Montant" class="form-control" type="number" v-model="newTransactionMontant" placeholder="Montant" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Description:</label>
              <textarea class="form-control" id="exampleTextarea" rows="4" v-model="newTransactionDescription"></textarea>
            </div>
            <div class="modal-footer">
              <button type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px" class="btn btn-success">
                Modifier
              </button>
              <button type="button" style="
                background-color: #fa7f35;
                border-color: #fa7f35;
              " class="btn btn-secondary" data-bs-dismiss="modal">
                Annuler
              </button>
            </div>
          </form>
        </div></div></div></div>

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
          <form @submit.prevent="addTransaction">
            <div class="mb-3">
              <h6 style="text-align: left;"><strong>Concernant</strong></h6>
              <select class="form-select" v-model="newTransactionConcernant">
                <option value="utilisateur">Utilisateur</option>
                <option value="vehicule">Véhicule</option>
                <option value="general">Général</option>
              </select>
            </div>
            <div class="mb-3">
              <h6 style="text-align: left;"><strong>Type transaction</strong></h6>
              <select class="form-select" v-model="newTransactionType">
                <option value="flux entrant">Flux entrant</option>
                <option value="flux sortant">Flux sortant</option>
              </select>
            </div>
            <div class="mb-3" v-if="newTransactionConcernant === 'utilisateur'">
  <h6 style="text-align: left;"><strong>Nom d'utilisateur</strong></h6>
  <select class="form-control" v-model="newTransactionUserName" placeholder="Nom d'utilisateur">
    <option v-for="user in users" :key="user.id" :value="user.user_name">
      {{ user.user_name }}
    </option>
  </select>
</div>
<div class="mb-3" v-if="newTransactionConcernant === 'vehicule'">
  <h6 style="text-align: left;"><strong>Immatricule</strong></h6>
  <select class="form-control" v-model="newTransactionImmatricule" placeholder="Immatricule">
    <option v-for="vehicule in vehicules" :key="vehicule.id" :value="vehicule.immatricule">
      {{ vehicule.immatricule }}
    </option>
  </select>
</div>

            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Montant:</label>
              <input name="Montant" class="form-control" type="number" v-model="newTransactionMontant" placeholder="Montant" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Description:</label>
              <textarea name="Description" class="form-control" v-model="newTransactionDescription" placeholder="Description"></textarea>
            </div>
            <div v-if="AddErrorMessage" class="alert alert-danger" role="alert">
                {{ AddErrorMessage }}
              </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                Ajouter
              </button>
              <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const TRANSACTION_API_BASE_URL = "http://localhost:8000/api/transaction";
const USER_API_BASE_URL = "http://localhost:8000/api/user";
const VEHICULE_API_BASE_URL = "http://localhost:8000/api/vehicule";


export default {
  data() {
    return {
      AddErrorMessage: '',
      deleteSuccessMessage: '', 
      AddSuccessMessage: '', 
      updateSuccessMessage:'',
      originalTransactionList: [],
      originalUserList: [],
      transaction: [],
      users: [],
      vehicules: [],
      searchQuery: '',
      selectedTransactionType: '',
      selectedConcernant: '',
      selectedTransactionId: null,
      newTransactionConcernant: '',
      newTransactionType: '',
      newTransactionUserName: '',
      newTransactionImmatricule: '',
      newTransactionMontant: '',
      newTransactionDescription: '',
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
  this.fetchUsers();
  this.fetchVehicule();
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
    
async fetchUsers() {
  try {
    const response = await axios.get(`${USER_API_BASE_URL}/index`);
    this.users = response.data.filter(user => ['candidat', 'moniteur'].includes(user.role));
    console.log("Fetched users:", this.users);
  } catch (error) {
    console.error("Error fetching users:", error);
  }
},
resetNewTransaction() {
      this.newTransactionConcernant = '';
      this.newTransactionType = '';
      this.newTransactionUserName = '';
      this.newTransactionImmatricule = '';
      this.newTransactionMontant = '';
      this.newTransactionDescription = '';
    },

openEditModal(transaction) {
      this.editingTransaction = true;
      this.newTransactionConcernant = transaction.Type_T;
      this.newTransactionType = transaction.Type_Transaction;
      this.newTransactionUserName = transaction.user_name;
      this.newTransactionImmatricule = transaction.immatricule;
      this.newTransactionMontant = transaction.montantT;
      this.newTransactionDescription = transaction.description;
      this.newTransactionId = transaction.id;
      $('#editTransactionModal').modal('show');
    },


async fetchVehicule() {
  try {
    const response = await axios.get(`${VEHICULE_API_BASE_URL}/index`);
    this.vehicules = response.data
    console.log("Fetched vehicules:", this.vehicules);
  } catch (error) {
    console.error("Error fetching vehicules:", error);
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
        this.deleteSuccessMessage = 'La transaction a été supprimée avec succès.';
    setTimeout(() => {
      this.deleteSuccessMessage = ''; 
    }, 3000);
      } catch (error) {
        console.error("Error deleting user:", error);
      }
    },
    async addTransaction() {
  let newTransaction = {};

  if (this.newTransactionConcernant === 'utilisateur') {
    newTransaction = {
      Type_T: this.newTransactionConcernant,
      Type_Transaction: this.newTransactionType,
      montantT: this.newTransactionMontant,
      description: this.newTransactionDescription,
      user_name: this.newTransactionUserName, 
    };
  } else if (this.newTransactionConcernant === 'vehicule') {
    newTransaction = {
      Type_T: this.newTransactionConcernant,
      Type_Transaction: this.newTransactionType,
      montantT: this.newTransactionMontant,
      description: this.newTransactionDescription,
      immatricule: this.newTransactionImmatricule, 
    };
  } else if (this.newTransactionConcernant === 'general') {
    newTransaction = {
      Type_T: this.newTransactionConcernant,
      Type_Transaction: this.newTransactionType,
      montantT: this.newTransactionMontant,
      description: this.newTransactionDescription,
    };
  }

  try {
    const response = await axios.post(`${TRANSACTION_API_BASE_URL}/store`, newTransaction);
    console.log(response.data);
    this.fetchData();
    $('#exampleModal').modal('hide'); 
    $('body').removeClass('modal-open'); 
    $('.modal-backdrop').remove();
    this.AddSuccessMessage = 'La transaction a été ajoutée avec succès.';
    setTimeout(() => {
      this.AddSuccessMessage = ''; 
    }, 3000);
  } catch (error) {
    console.error("Error adding transaction:", error);
    this.AddErrorMessage = 'Erreur lors de l\'ajout d\'une transaction';
        setTimeout(() => {
          this.AddErrorMessage = '';
        }, 3000);
  }
},


async updateTransaction() {
      let updatedTransaction = {};

      if (this.newTransactionConcernant === 'utilisateur') {
        updatedTransaction = {
          Type_T: this.newTransactionConcernant,
          Type_Transaction: this.newTransactionType,
          montantT: this.newTransactionMontant,
          description: this.newTransactionDescription,
          user_name: this.newTransactionUserName,
        };
      } else if (this.newTransactionConcernant === 'vehicule') {
        updatedTransaction = {
          Type_T: this.newTransactionConcernant,
          Type_Transaction: this.newTransactionType,
          montantT: this.newTransactionMontant,
          description: this.newTransactionDescription,
          immatricule: this.newTransactionImmatricule,
        };
      } else if (this.newTransactionConcernant === 'general') {
        updatedTransaction = {
          Type_T: this.newTransactionConcernant,
          Type_Transaction: this.newTransactionType,
          montantT: this.newTransactionMontant,
          description: this.newTransactionDescription,
        };
      }

      try {
        const response = await axios.put(`${TRANSACTION_API_BASE_URL}/update/${this.newTransactionId}`, updatedTransaction);
        console.log(response.data);
        this.fetchData();
        $('#editTransactionModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        this.updateSuccessMessage = 'La transaction a été mise à jour avec succès.';
        setTimeout(() => {
          this.updateSuccessMessage = '';
        }, 3000);
        this.resetNewTransaction();
      } catch (error) {
        console.error("Error updating transaction:", error);
      }
    },
  

    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.transaction = data;
      this.originalTransactionList = data;
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    filterTransactions() {
      this.transaction = this.originalTransactionList.filter(tran => {
        const matchesSearch = tran.description.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesType = !this.selectedTransactionType || tran.Type_Transaction === this.selectedTransactionType;
        const matchesConcernant = !this.selectedConcernant || tran.Type_T === this.selectedConcernant;
        return matchesSearch && matchesType && matchesConcernant;
      });
    }
  },
};
</script>
