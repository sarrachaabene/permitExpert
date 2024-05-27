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
                        <button  data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
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
                          <option v-if="userRole" value="secretaire">Secrétaire</option>
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
                              <th scope="col" style="font-size: 14px;">Status</th>
                              <th scope="col" v-if="selectedRole !== 'secretaire' && selectedRole !== 'candidat'" style="font-size: 14px;">Autorité de délivrance</th>
                              <th scope="col" style="font-size: 14px;">Historique de paiement</th>

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
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'moniteur'">{{ user.cat_permis
                                }}</td>
                              <td>
                                <span v-if="user.deleted_at == null"
                                  style="font-weight: bold;  color: green;">Authorisé</span>
                                <span v-if="user.deleted_at != null"
                                  style="font-weight: bold;  color: red;">non Authorisé</span>
                              </td>
                              <td v-if="selectedRole !== 'secretaire' && selectedRole !== 'candidat'">
  <button 
    @click="viewPdf(user)"
    style="color: white; background-color: #1F4069; border-color: #1F4069;" 
    class="btn btn-success">
    Consulter
  </button>
</td>
                              <td>
                                <button @click="showTransactionDetails(user.id)"
                                  style="background-color: #9dcd5a; border-color: #9dcd5a;"
                                  class="btn btn-success">Consulter</button>
                              </td>

                              <td class="d-flex justify-content-between">
                                <a href="#" @click="openEditModal(user)" 
                                    style="
                                    background-color: #9dcd5a;
                                    border-color: #9dcd5a;
                                    margin-right: 5px;" 
                                    class="btn btn-success">Modifier
                                </a> 
                                <a v-if="user.deleted_at==null" @click.prevent="showDeleteConfirmationModal(user.id)"
                                  style="background-color: orangered; border-color: orangered; margin-left: 5px;"
                                  class="btn btn-danger">Bloquer</a>
                                  <a v-if="user.deleted_at!=null" @click.prevent="showDeleteConfirmationModal(user.id)"
                                  style="
                                    background-color: #9dcd5a;
                                    border-color: #9dcd5a;
                                    margin-right: 5px;" 
                                    class="btn btn-success">Récuperer</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="AddSuccessMessage" class="alert alert-success" role="alert">
                {{ AddSuccessMessage }}
              </div>
              <div v-if="deleteSuccessMessage" class="alert alert-success" role="alert">
                {{ deleteSuccessMessage }}
              </div>

              <div v-if="updateSuccessMessage" class="alert alert-success" role="alert">
                {{ updateSuccessMessage }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de modification du status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div  class="modal-body">
            Êtes-vous sûr de modifier le status ?
          </div>
          <div class="modal-footer">
            <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary"
              data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger"
              style="background-color: #9dcd5a; border-color: #9dcd5a; margin-right: 5px;"
              @click="confirmDeleteUser">Modifier</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Ajouter utilisateur -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
              Ajouter utilisateur
            </h5>
            <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
              <span class="fas fa-times fs--1"></span>
            </button>
          </div>
          <br />
          <div class="modal-body">
            <form @submit.prevent="addUser" method="post">
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Role:</strong></h6>
                <select v-model="newUser.role" class="form-select" id="role" placeholder="Role">
                  <option value="candidat">Candidat</option>
                  <option value="moniteur">Moniteur</option>
                  <option v-if="userRole" value="secretaire">Secretaire</option>
                </select>
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Nom d'utilisateur:</strong></h6>
                <input v-model="newUser.user_name" class="form-control" id="user_name" type="text"
                  placeholder="Nom d'utilisateur" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Email:</strong></h6>
                <input v-model="newUser.email" class="form-control" id="email" type="email" placeholder="Email" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>CIN:</strong></h6>
                <input v-model="newUser.cin" class="form-control" id="cin" type="text" placeholder="cin" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Numéro de téléohone:</strong></h6>
                <input v-model="newUser.numTel" class="form-control" id="numTel" type="tel" placeholder="numTel" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Date de naissance:</strong></h6>
                <input v-model="newUser.dateNaissance" class="form-control" id="dateNaissance" type="date"
                  placeholder="date de naissance" />
              </div>
              <div class="mb-3" v-if="newUser.role === 'candidat'">
                <h6 style="text-align: left; "><strong>Catégorie Permis:</strong></h6>
                <select v-model="newUser.cat_permis" class="form-select" id="EditCatPermis">
                  <option value="Permis_A1">Permis_A1</option>
                  <option value="Permis_A">Permis_A</option>
                  <option value="Permis_B">Permis_B</option>
                  <option value="Permis_B_E">Permis_B_E</option>
                  <option value="Permis_C">Permis_C</option>
                  <option value="Permis_C_E">Permis_C_E</option>
                  <option value="Permis_D">Permis_D</option>
                  <option value="Permis_D_E">Permis_D_E</option>
                  <option value="Permis_D1">Permis_D1</option>
                  <option value="Permis_H">Permis_H</option>
                </select>
              </div>
              <div class="mb-3" v-if="newUser.role === 'moniteur'">
  <h6 style="text-align: left;"><strong>Autorité de délivrance:</strong></h6>
  <input @change="handleFileUpload" class="form-control" id="autorite_de_delivrance" type="file" accept=".pdf" />
</div>

              <div v-if="AddErrorMessage" class="alert alert-danger" role="alert">
                {{ AddErrorMessage }}
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                  Ajouter
                </button>
                <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary"
                  data-bs-dismiss="modal">Annuler</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Modifier user -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto" id="editModalLabel" style="font-weight: bold; margin-top: 30px">
              Modifier utilisateur
            </h5>
            <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
              <span class="fas fa-times fs--1"></span>
            </button>
          </div>
          <br>
          <div class="modal-body t">

            <form @submit.prevent="updateUser" method="post">
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Nom d'utilisateur:</strong></h6>
                <input v-model="editedUser.user_name" class="form-control" id="EditNom" type="text" placeholder="Nom" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Numéro de téléphone:</strong></h6>
                <input v-model="editedUser.numTel" class="form-control" id="EditNumTel" type="text"
                  placeholder="Num tel" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>Email:</strong></h6>
                <input v-model="editedUser.email" class="form-control" id="EditEmail" type="text" placeholder="Email" />
              </div>
              <div class="mb-3">
                <h6 style="text-align: left; "><strong>CIN:</strong></h6>
                <input v-model="editedUser.cin" class="form-control" id="EditCin" type="text" placeholder="Email" />
              </div>
              <div class="mb-3" v-if="editedUser.role === 'candidat'">
                <h6 style="text-align: left; "><strong>Catégorie Permis:</strong></h6>
                <select v-model="editedUser.cat_permis" class="form-select" id="EditCatPermis">
                  <option value="Permis_A1">Permis_A1</option>
                  <option value="Permis_A">Permis_A</option>
                  <option value="Permis_B">Permis_B</option>
                  <option value="Permis_B_E">Permis_B_E</option>
                  <option value="Permis_C">Permis_C</option>
                  <option value="Permis_C_E">Permis_C_E</option>
                  <option value="Permis_D">Permis_D</option>
                  <option value="Permis_D_E">Permis_D_E</option>
                  <option value="Permis_D1">Permis_D1</option>
                  <option value="Permis_H">Permis_H</option>
                </select>
              </div>
<!--               <div class="mb-3" v-if="editedUser.role === 'moniteur'">
            <h6 style="text-align: left;"><strong>Autorité de délivrance:</strong></h6>
            <input @change="handleEditFileUpload" class="form-control" id="EditAutoriteDeDelivrance" type="file" accept=".pdf" />
          </div> -->
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                  Modifier
                </button>
                <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary"
                  data-bs-dismiss="modal">Annuler</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal des détails de la transaction -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel"
      aria-hidden="true">
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
    editFile: null,
    AddSuccessMessage: '',
    deleteSuccessMessage: '',
    updateSuccessMessage: '',
    AddErrorMessage: '',
    originalUserList: [],
    user: [],
    searchQuery: '',
    selectedRole: '',
    userIdToDelete: null,
    transactionDetails: {},
    editedUser: {},
    userRole: localStorage.getItem('users') && JSON.parse(localStorage.getItem('users'))[0].role === "admin",
    newUser: { user_name: '', email: '', role: '', cin: '', numTel: '', dateNaissance: '', cat_permis: '', autorite_de_delivrance: '' },
  };
},
  mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin) {
      window.location.href = '/dashbord_Super_Admin';
    }
    console.log("Component mounted.");
    this.fetchData();
  },
  methods: {
    handleEditFileUpload(event) {
      this.editFile = event.target.files[0];
    },
    handleFileUpload(event) {
      this.newUser.autorite_de_delivrance = event.target.files[0];
    },
    async fetchData() {
      try {
        const response = await axios.get(`${USER_API_BASE_URL}/index`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    async addUser() {
      if (!this.newUser.user_name || !this.newUser.email || !this.newUser.role || !this.newUser.cin || !this.newUser.numTel || !this.newUser.dateNaissance) {
        this.AddErrorMessage = 'Veuillez remplir tous les champs obligatoires.';
        setTimeout(() => {
          this.AddErrorMessage = '';
        }, 3000);
        return;
      }

      const formData = new FormData();
      formData.append('user_name', this.newUser.user_name);
      formData.append('email', this.newUser.email);
      formData.append('role', this.newUser.role);
      formData.append('cin', this.newUser.cin);
      formData.append('numTel', this.newUser.numTel);
      formData.append('dateNaissance', this.newUser.dateNaissance);
      formData.append('cat_permis', this.newUser.cat_permis);
      if (this.newUser.role === 'moniteur' && this.newUser.autorite_de_delivrance) {
        formData.append('autorite_de_delivrance', this.newUser.autorite_de_delivrance);
      }

      try {
        const response = await axios.post(`${USER_API_BASE_URL}/store`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        console.log("User added successfully:", response.data);
        this.fetchData();
        $('#exampleModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        this.AddSuccessMessage = 'Utilisateur ajouté avec succès.';
        setTimeout(() => {
          this.AddSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Error adding user:", error);
        this.AddErrorMessage = 'Erreur lors de l\'ajout de l\'utilisateur';
        setTimeout(() => {
          this.AddErrorMessage = '';
        }, 3000);
      }
    },
    viewPdf(user) {
      if (user.autorite_de_delivrance) {
        const pdfUrl = `http://localhost:8000/storage/${user.autorite_de_delivrance}`;
        window.open(pdfUrl, '_blank');
      } else {
        alert("Aucun fichier PDF disponible pour cet utilisateur.");
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
        this.deleteSuccessMessage = 'Status modifié.';
        setTimeout(() => {
          this.deleteSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Erreur lors de la suppression de l'utilisateur:", error);
      }
    },
    openEditModal(user) {
      this.editedUser = JSON.parse(JSON.stringify(user));
      $('#editModal').modal('show');
    },

    async updateUser() {
      try {
        const response = await axios.put(`${USER_API_BASE_URL}/update/${this.editedUser.id}`, this.editedUser);
        console.log("User updated successfully:", response.data);
        this.fetchData();
        $('#editModal').modal('hide');
        this.updateSuccessMessage = 'Utilisateur a été mise à jour avec succès.';
        setTimeout(() => {
          this.updateSuccessMessage = '';
        }, 3000);
      } catch (error) {
        console.error("Error updating User:", error);
      }
    },
    async showTransactionDetails(userId) {
      $('#transactionDetailsModal').modal('show');

      try {
        const response = await axios.get(`http://localhost:8000/api/transaction/ShowTransactionByuserId/${userId}`);
        console.log(response.data);
        this.transactionDetails = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des détails de la transaction:", error);
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