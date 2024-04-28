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
                      <div class="col-lg-4 order-lg-last">
                        <input type="text" class="form-control" v-model="searchQuery" @input="filterTransactions" placeholder="Recherche par description..." />
                      </div>
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Ajouter transaction
                        </button>
                      </div>
                      <div class="col-lg-4 text-right">
<select v-model="selectedTransactionType" @change="fetchData" class="form-select">
  <option value="">Tous transactions</option>
  <option value="secretaire">Flux entrant</option>
  <option value="moniteur">Flux sortant</option>
</select>


                      </div>
                    </div>
                    <br />

                    <table class="table table-borded">
                      <thead>
                        <tr>
                          <th scope="col">Type de transaction</th>
                          <th scope="col">Concernant</th>
                          <th scope="col">Description</th>
                          <th scope="col">Montant</th>
                          <th scope="col">Date</th>
                          <th scope="col">Nom d'utilisateur</th>
                          <th scope="col">véhicule</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(tran) in filteredTransactions" :key="tran.id">
                          <td>{{ tran.Type_Transaction }}</td>
                          <td>{{ tran.Type_T }}</td>
                          <td>{{ tran.description }}</td>
                          <td>{{ tran.montantT }}</td>
                          <td>{{ tran.dateT }}</td>
                          <td>{{ tran.user_id }}  </td>
                          <td>{{ tran.vehicule_id }}</td>
                          <td style="display: flex; justify-content: space-between;">
    <a href="" style="
        background-color: #9dcd5a;
        border-color: #9dcd5a;
        margin-right: 5px;
        " class="btn btn-success">Modifier
    </a>
    <a href="" style="
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
  <!-- modal Ajouter auto ecole -->
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
            <div class="mb-3">
              <label class="form-label" for="exampleFormControlInput1">Nom d'auto école:</label>
              <input name="nom auto ecole" class="form-control" id="exampleFormControlInput1" type="text"
                placeholder="Nom d'auto école" />
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
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal"
            style="background-color: #fa7f35; border-color: #fa7f35">
            Annuler
          </button>
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
    transaction: [],
    searchQuery: '',
    selectedTransactionType: '',
  };
},
  computed: {
    filteredTransactions() {
    return this.transaction.filter(tran => {
      const matchesSearch = tran.description.toLowerCase().includes(this.searchQuery.toLowerCase());
      const matchesType = !this.selectedTransactionType || tran.Type_Transaction === this.selectedTransactionType;
      return matchesSearch && matchesType;
    });
  }

},
  mounted() {
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
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.transaction = data;
      console.log("Data fetched successfully:", this.transaction);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    filterTransaction() {
      if (this.selectedTransaction === "") {
        this.fetchData();
      } else {
        const filteredTransaction = this.transaction.filter(transaction => transaction.Type_Transaction === this.selectedTransaction);
        this.transaction = filteredTransaction;
      }
    }
  },
};
</script>
