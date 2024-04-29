<template>
  <br><br>
  <div class="container rounded bg-white mt-5 mb-5 shadow">
    <div class="row">
      <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
          <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
          <span class="font-weight-bold">user name</span>
        </div>
      </div>
      <div class="col-md-5 border-right">
        <div class="p-3 py-5">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-right" style="text-align: center;">Paramètres de profil</h4>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Nom</label>
                <input type="text" class="form-control" placeholder="Nom" value="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Email</label>
                <input type="email" class="form-control" placeholder="Email" value="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Numéro de téléphone</label>
                <input type="tel" class="form-control" placeholder="Numéro de téléphone" value="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <br>
                <label class="labels">Date de naissance</label>
                <input type="date" class="form-control" placeholder="Date de naissance" value="">
              </div>
            </div>
          </div>

          <div class="mt-5 text-center">
            <button class="btn btn-primary profile-button" type="button" style="background-color: #9dcd5a;">Enregistrer</button>
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
};
</script>

<style scoped>
/* Ajoutez ici vos styles CSS spécifiques à ce composant */
</style>
