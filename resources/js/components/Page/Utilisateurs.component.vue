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
                              <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'" style="font-size: 14px;">Catégorie permis</th>
                              <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'" style="font-size: 14px;">Résultat</th>
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
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">{{ use.cat_permis }}</td>
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">
                                <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Modifier</a>
                              </td>
                              <td class="d-flex justify-content-between">
                                <a href="" style="background-color: #9dcd5a; border-color: #9dcd5a;" class="btn btn-success">Modifier</a>
                                <a href="" @click.prevent="showDeleteConfirmationModal(use.id)" style="background-color: orangered; border-color: orangered; margin-left: 5px;" class="btn btn-danger">Supprimer</a>
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

    <!-- Modal pour ajouter utilisateur -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <!-- Contenu de la modal pour ajouter utilisateur -->
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
            <button type="button" class="btn btn-danger"  style="
                                background-color: #9dcd5a;
                                border-color: #9dcd5a;
                                margin-right: 5px;
                              "  @click="confirmDeleteUser">Supprimer</button>
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
      userIdToDelete: null // Stocke l'ID de l'utilisateur à supprimer
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
      this.originalUserList = data;
      console.log("Data fetched successfully:", this.user);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    showDeleteConfirmationModal(userId) {
      // Stocke l'ID de l'utilisateur à supprimer
      this.userIdToDelete = userId;
      // Affiche la modal de confirmation de suppression
      $('#deleteConfirmationModal').modal('show');
    },
    async confirmDeleteUser() {
      try {
        // Envoie une requête de suppression à l'API avec l'ID de l'utilisateur à supprimer
        await axios.delete(`http://localhost:8000/api/user/delete/${this.userIdToDelete}`);
        // Actualise la liste des utilisateurs après la suppression réussie
        this.fetchData();
        // Cache la modal de confirmation de suppression
        $('#deleteConfirmationModal').modal('hide');
      } catch (error) {
        console.error("Erreur lors de la suppression de l'utilisateur:", error);
      }
    },
    filterUsers() {
      if (this.selectedRole === "") {
        this.user = this.originalUserList;
      } else {
        const filteredUsers = this.originalUserList.filter(user => user.role === this.selectedRole);
        this.user = filteredUsers;
      }
    }
  }
}
</script>
