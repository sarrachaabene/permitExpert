<template>
  <div class="container">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="text-align: center; font-weight: bold">
              Liste des administrateurs
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
                          Ajouter un administrateur
                        </button>
                      </div>
                    </div>
                    <br />
                    <div class="table-responsive">
                    <table class="table table-borded">
                      <thead>
                        <tr>
                          <th scope="col">nom</th>
                          <th scope="col">Numéro de téléphone</th>
                          <th scope="col">Email</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(use) in user" :key="use.id">
                          <td>{{ use.user_name }}</td>
                          <td>{{ use.numTel }}</td>
                          <td>{{ use.email }}</td>
                          <td class="d-flex justify-content-center">
  <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;" class="btn btn-success">Modifier</a>
  <a href="" style="background-color: orangered; border-color: orangered; margin-left: 5px;" class="btn btn-danger">Supprimer</a>
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
  <!-- modal Ajouter auto ecole -->
  <button type="button"></button>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
            Ajouter administrateur
          </h5>
          <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span class="fas fa-times fs--1"></span>
          </button>
        </div>
        <br />
        <div class="modal-body">
          <form action="" method="post">
            <div class="mb-3">
              <label class="form-label" for="exampleFormControlInput1">Nom d'admin:</label>
              <input name="nom auto ecole" class="form-control" id="exampleFormControlInput1" type="text"
                placeholder="Nom d'auto école" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Numéro de telephone:</label>
              <input name="adresse" class="form-control" id="exampleTextarea" placeholder="Adresse" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="exampleTextarea">Email:</label>
              <input name="nom admin" class="form-control" id="exampleTextarea" placeholder="Nom d'admin" />
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
const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {
      user: [],
    };
  },
  mounted() {
    let isAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "admin";
    if (isAdmin){
      window.location.href = '/dashbord';
    }
    console.log("Component mounted.");
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${USER_API_BASE_URL}/indexForSuper`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.user = data;
      console.log("Data fetched successfully:", this.use);

    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
  },
};
</script>
