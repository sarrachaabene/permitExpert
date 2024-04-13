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
              <form>
                <div class="mb-3">
                  <label for="exampleInputName" class="form-label">Nom d'utilisateur</label>
                  <input type="text" class="form-control" id="exampleInputName" placeholder="Nom d'utilisateur">
                </div>
                <div class="mb-4">
                  <label for="exampleInputTel" class="form-label">Numéro du téléphone</label>
                  <input type="tel" class="form-control" id="exampleInputTel" placeholder="Numéro du téléphone">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword" class="form-label">Mot de passe</label>
                  <input type="password" class="form-control" id="exampleInputPassword" placeholder="Mot de passe">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPasswordConf" class="form-label">Confirmer votre mot de passe</label>
                  <input type="password" class="form-control" id="exampleInputPasswordConf" placeholder="Confirmer mot de passe" >
                </div>
                <div class="text-center">
                     <a href="" style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px; font-size: 16px;" class="btn btn-danger">S'inscrire  </a>
               </div>
              </form>
            </div>
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
};
</script>