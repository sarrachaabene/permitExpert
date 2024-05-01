<template>
  <div class="container">
    <br /><br />
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
                      <div class="row">
  <div class="col-lg-8"> 
  </div>
  <div class="col-lg-4"> 
    <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher..." />
  </div>
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
                        <tr v-for="(demande, key) in demande" :key="demande.id">
                          <th scope="row">{{ key + 1 }}</th>
                          <td>{{ demande.nomEcole }}</td>
                          <td>{{ demande.nomA + " " + demande.prenomA }}</td>
                          <td>{{ demande.status }}</td>
                          <td>
                            <a href="" style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                              " class="btn btn-success">Consulter
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
</template>
<script>
import axios from "axios";
const Demande_API_BASE_URL = "http://localhost:8000/api/demandeInscript";

export default {
  data() {
    return {
      demande: [],
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
      console.log("Data fetched successfully:", this.demande);
      // Do something with the data, like assigning it to a variable
      // this.messages = data;
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
      // Handle error, show error message to user, etc.
    },
  },
};
</script>
