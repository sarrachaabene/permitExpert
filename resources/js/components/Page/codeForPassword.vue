<template>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div style="background-color:white"
    class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center background-image">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1); border-radius: 5%;" class="card mb-0">
              <div class="card-body">
                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../../public/assets/images/logos/permit.png" width="170" alt="">
                  <h5 style="text-align: center; font-weight: bold;">Code de vérification</h5>
                </a>
                <h6 style="text-align: center;">Entrer votre code</h6>
                <br><br>

                <form action="#">
                  <div class="input-field">
                    <input ref="box1" type="number" maxlength="1" @input="moveToNext($event, 1)" />
                    <input ref="box2" type="number" maxlength="1" @input="moveToNext($event, 2)" />
                    <input ref="box3" type="number" maxlength="1" @input="moveToNext($event, 3)" />
                    <input ref="box4" type="number" maxlength="1" @input="moveToNext($event, 4)" />
                  </div>
                </form>

                <div v-if="message" class="text-center" :class="{ 'text-success': valid, 'text-danger': !valid }">
                  {{ message }}
                </div>
                
                <div v-if="invalidCodeMessage" class="text-center text-danger">
                  {{ invalidCodeMessage }}
                </div>
                
                <div v-if="successMessage" class="text-center text-success">
                  {{ successMessage }}
                </div>

              </div>
              <div class="text-center">
                <div v-if="allFieldsEmpty" class="text-danger">Veuillez remplir tous les champs.</div>
                <br><br>
                <a href="#" @click="onContinueClick"
                  style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 10px; font-size: 17px; padding: 12px 24px; color: white; text-decoration: none; border-radius: 8px;">Continuer</a>
              </div>
              <br><br><br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      message: '',
      valid: false,
      allFieldsEmpty: false,
      invalidCodeMessage: '',
      successMessage: ''
      
    };
  },
  mounted() {
    const userEmail = localStorage.getItem('userEmail');
    console.log('Email récupéré:', userEmail);
  },
  methods: {
    moveToNext(event, boxNumber) {
      const currentBox = this.$refs[`box${boxNumber}`];
      if (event.target.value.length === 1 && boxNumber < 4) {
        const nextBox = this.$refs[`box${boxNumber + 1}`];
        nextBox.focus();
      }
    },
    getCodeFromInputs() {
      let code = '';
      for (let i = 1; i <= 4; i++) {
        code += this.$refs[`box${i}`].value;
      }
      return code;
    },
    checkEmptyFields() {
      const allEmpty = [1, 2, 3, 4].some(boxNumber => {
        const value = this.$refs[`box${boxNumber}`].value;
        return !value;
      });
      this.allFieldsEmpty = allEmpty;
    },
    resetMessages() {
      this.message = '';
      this.invalidCodeMessage = '';
      this.successMessage = '';
    },
    onContinueClick(event) {
      event.preventDefault();
      this.checkEmptyFields();
      if (!this.allFieldsEmpty) {
        const email = localStorage.getItem('userEmail'); // Récupérer l'e-mail depuis le stockage local
        const code = this.getCodeFromInputs();
        
        axios.get(`/verifyCode/${email}/${code}`)
          .then(response => {
            if (response.data.success) {
              localStorage.setItem('userEmail', email);
              
              this.successMessage = 'Le code de vérification est correct.';
              setTimeout(() => {
                window.location.href = '/changepassword';
              }, 5000);
            } else {
              this.invalidCodeMessage = 'Le code de vérification est incorrect.';
              setTimeout(() => {
                this.resetMessages();
              }, 1000);
            }
          })
          .catch(error => {
            console.error('Erreur lors de la vérification du code:', error);
            this.message = 'Une erreur s\'est produite lors de la vérification du code.';
            setTimeout(() => {
              this.resetMessages();
            }, 5000);
          });
      }
    }
  }
};
</script>

<style scoped>
/* Import Google font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

:where(.container, form, .input-field, header) {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

form .input-field {
  flex-direction: row;
  column-gap: 10px;
}

.input-field input {
  height: 45px;
  width: 42px;
  border-radius: 6px;
  outline: none;
  font-size: 1.125rem;
  text-align: center;
  border: 1px solid #ddd;
}
.background-image {
    background-image: url('C:\laragon\www\PermitExpert\public\assets\images\bg login (1).jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
