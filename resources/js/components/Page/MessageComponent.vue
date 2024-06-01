<template>
  <div class="container text-center">
    <br /><br />
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 style="font-weight: bold">
              Contact
            </h4>
            <div class="content">
              <div class="pb-5">
                <div class="row g-5">
                  <div class="col">
                    <div class="row mt-4">
                      <div class="col-lg-4 text-right">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" style="
                            color: white;
                            background-color: #1F4069;
                            border-color: #1F4069;
                          " class="btn btn-primary m-1">
                          Envoyer Message
                        </button>
                      </div>
                    </div>
                    <br />
                    <div class="col">
                      <div class="table-responsive">
                        <table class="table table-borded">
                          <thead>
                            <tr>
                              <th scope="col" style="font-size: 14px;">Date</th>
                              <th scope="col" style="font-size: 14px;">L'émetteur</th>
                              <th scope="col" style="font-size: 14px;">Description</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(message, index) in message" :key="index">
                              <td v-html="formatDate(message.created_at)"></td>
                              <td>{{ message.sender_name }}</td>
                              <td>{{ message.message_description }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="successMessage" class="alert alert-success" role="alert" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 999;">
                {{ successMessage }}
              </div>
              <div v-if="errorMessage" class="alert alert-danger" role="alert" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 999;">
                {{ errorMessage }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Ajouter message -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto" id="exampleModalLabel" style="font-weight: bold; margin-top: 30px">
              Ajouter Message
            </h5>
            <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
              <span class="fas fa-times fs--1"></span>
            </button>
          </div>
          <br />
          <div class="modal-body">
            <form @submit.prevent="addMessage" method="post">
              <div class="mb-3">
                <h6 style="text-align: left;"><strong>Destinaire:</strong></h6>
                <select v-model="newMessage.recipient_username" class="form-control">
                  <option v-for="user in users" :key="user.id" :value="user.user_name">
                    {{ user.user_name }}
                  </option>
                </select>
              </div>
              <div class="mb-3">
                <h6 style="text-align: left;"><strong>Entrer votre message:</strong></h6>
                <textarea v-model="newMessage.message_description" class="form-control" id="description" placeholder="Votre message"></textarea>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit" style="background-color: #9dcd5a; border-color: #9dcd5a">
                  Envoyer
                </button>
                <button type="button" style="background-color: #fa7f35; border-color: #fa7f35" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import axios from "axios";

const MESSAGE_API_BASE_URL = "http://localhost:8000/api/Notification/ShowNotificationsByReceptientId";
const USER_API_BASE_URL = "http://localhost:8000/api/user";

export default {
  data() {
    return {
      originalUserList: [],
      message: [],
      users: [],
      searchQuery: '',
      transactionDetails: {},
      newMessage: { recipient_username: '', message_description: '' },
      successMessage: "",
      errorMessage: ""
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.fetchData();
    this.fetchUsers();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`${MESSAGE_API_BASE_URL}`);
        this.handleSuccess(response.data);
      } catch (error) {
        this.handleError(error);
      }
    },
    async fetchUsers() {
      try {
        const response = await axios.get(`${USER_API_BASE_URL}/indexForContactSe`);
        this.users = response.data;
        console.log("Fetched users:", this.users); 
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    async addMessage() {
      try {
        console.log("Adding message:", this.newMessage); 
        const response = await axios.post(`http://localhost:8000/api/message/store`, this.newMessage);
        console.log("Message added successfully:", response.data);
        this.fetchData();
        this.newMessage = { recipient_username: '', message_description: '' }; 
        $('#exampleModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        this.successMessage = "Message envoyé avec succès.";
        setTimeout(() => {
          this.successMessage = "";
        }, 2000); 
      } catch (error) {
        console.error("Error adding message:", error);
        this.errorMessage = "Erreur lors de l'envoi du message.";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000); 
      }
    },
    handleSuccess(data) {
      console.log("Data fetched successfully:", data);
      this.message = data;
      this.originalUserList = data;
      console.log("Data fetched successfully:", this.message);
    },
    handleError(error) {
      console.error("Error fetching data from the backend:", error);
    },
    formatDate(value) {
      if (value) {
        let date = new Date(value);
        return date.toLocaleDateString() + '<span style="white-space: pre;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>' + date.toLocaleTimeString();
      }
      return value;
    },
  }
};

</script> 