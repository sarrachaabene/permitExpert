
<template >
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div style="background-color:white"
    class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);"  class="card mb-0">
            <div class="card-body">
              <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="../../../../public/assets/images/logos/permit.png" width="170" alt="">
                <h5 style="text-align: center; font-weight: bold;" >Inscription</h5 >  </a>     
                  <h6 style="text-align: center;">Commençons avec votre adresse e-mail</h6>         
                  <br><br>
                  
      <form action="#">
        <div class="input-field">
          <input type="number" />
          <input type="number"  />
          <input type="number"  />
          <input type="number"  />
        </div>
      </form>
    </div>
    <br><br>
    <br>
                <div class="text-center">
                  <a href="/registerf" style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 10px; font-size: 17px; padding: 12px 24px; color: white; text-decoration: none; border-radius: 8px;">Continuer</a>
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
export default {
  mounted() {
    console.log("Component mounted.");
  },
  
  mounted() {
    console.log("Component mounted.");
    const inputs = document.querySelectorAll("input[type='number']");

    inputs.forEach((input, index) => {
      input.addEventListener("input", (e) => {
        // Limiter la longueur de la saisie à un seul chiffre
        if (input.value.length > 1) {
          input.value = input.value.slice(0, 1);
        }

        // Déplacer automatiquement le focus vers le prochain champ d'entrée s'il existe
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
          inputs[index + 1].select(); // Sélectionner tout le texte dans le champ
        }
      });
    });
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
</style>
