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
<form>
<br><br>
<div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Entrer votre Email</label>
                    <input v-model="email" type="email" class="form-control" id="exampleInputEmail1"
                      aria-describedby="emailHelp" placeholder="Email">
                  </div>
                <br><br>
                <div class="text-center">
                     <a href="/code" style="background-color: #FA7F35; border-color: #FA7F35; margin-left: 5px; font-size: 16px;" class="btn btn-danger">Continuer  </a>
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