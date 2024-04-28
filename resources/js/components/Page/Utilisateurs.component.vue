<template>
  <div class="container text-center"> <!-- Ajout de la classe text-center -->
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
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Rechercher..." />
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

                    <table class="table table-borded">
                      <thead>
                        <tr>
<th scope="col" style="font-size: 14px;">Rôle</th>
<th scope="col" style="font-size: 14px;">Nom</th>
<th scope="col" style="font-size: 14px;">Numéro de téléphone</th>
<th scope="col" style="font-size: 14px;">Email</th>
<th scope="col" style="font-size: 14px;">CIN</th>

                          <!-- Affichage conditionnel des colonnes -->
                          <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">Catégorie permis</th>
                          <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">Résultat</th>
                          <!-- Fin affichage conditionnel -->
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(use) in user" :key="use.id">
                          <td>{{ use.role }}</td>
                          <td>{{ use.user_name }}</td>
                          <td>{{ use.numTel }}</td>
                          <td>{{ use.email }}</td>
                          <td>{{ use.cin }}</td>
                          <!-- Affichage conditionnel des colonnes -->
                          <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">{{ use.cat_permis }}</td>
                          <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'"><a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Modifier</a></td>

                          <!-- Fin affichage conditionnel -->
                          <td class="d-flex justify-content-between">
                            <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Modifier</a>
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
</template>

<script>
import axios from "axios";
const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {
      user: [],
      searchQuery: '',
      selectedRole: ''
    };
  },
  mounted() {
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
      console.log("Data fetched successfully:", this.user);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    filterUsers() {
      if (this.selectedRole === "") {
        this.fetchData();
      } else {
        const filteredUsers = this.user.filter(user => user.role === this.selectedRole);
        this.user = filteredUsers;
      }
    }
  }
};
</script>
