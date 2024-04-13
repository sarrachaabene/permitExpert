<template >
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div style="background-color:white"
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div  style="border: 0.01px solid wheat; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);"  class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../../public/assets/images/logos/permit.png" width="150" alt="">
                </a>
                <h5 style="text-align: center; font-weight: bold;" >Connection</h5 >
                  <h6 style="text-align: center;">Bienvenue sur PermitExpert</h6>
                  <br> <br>
                <form>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe">
                  </div>
                
                  <div class="text-center">
    <a href="" style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px;" class="btn btn-danger">Se connecter</a>
</div>
                    <br>
                    <div class="d-flex align-items-center justify-content-center">
                    <h1 class="fs-4 mb-0 fw-bold">Vous n'avez pas de compte?</h1>
                    <a style="color: #FA7F35; "  href="/register">S'inscrire</a>
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