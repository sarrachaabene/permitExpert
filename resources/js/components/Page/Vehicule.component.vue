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
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher..." />
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
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(veh, key) in vehicule" :key="veh.id">
                          <th scope="row">{{ key + 1 }}</th>
                          <td>{{ veh.immatricule}}</td>
                          <td>{{ veh.marque}}</td>
                          <td>{{ veh.typeV }}</td>
                          <td>{{ veh.kilometrage }}</td>
                          <td>
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
            Ajouter véhicule
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form action="" method="post">
            <div class="mb-3">
              <label class="form-label" for="exampleFormControlInput1">Immatriculation:</label>
              <input name="Immatriculation" class="form-control" id="exampleFormControlInput1" type="text"
                placeholder="Immatriculation" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Kilométrage:</label>
              <input name="Kilométrage" class="form-control" id="exampleTextarea" placeholder="Kilométrage" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Marque:</label>
              <input name="Marque" class="form-control" id="exampleTextarea" placeholder="Marque" />
            </div>
            <div class="mb-3">
  <label class="form-label" for="type">Marque:</label>
  <select name="type" class="form-select" id="type">
    <option value="option1">Moto</option>
    <option value="option2">Voiture</option>
    <option value="option3">Cammion</option>
    <option value="option3">Bus</option>
    <!-- Ajoutez d'autres options selon vos besoins -->
  </select>
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
const VEHICULE_API_BASE_URL = "http://localhost:8000/api/vehicule";

export default {
  data() {
    return {
      vehicule: [],
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
      console.log("Data fetched successfully:", this.vehicule);
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
