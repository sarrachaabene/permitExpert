<template>
  <div>
    <br><br><br><br>
    <br>
    <!-- Conteneur de cartes -->
    <div class="row">
      <div class="col-md-3" v-if="userCounts && userCounts.candidats !== undefined">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-12 text-center">
                <h6 class="card-title mb-9 fw-semibold font-weight-bold" style="color: #203F6A">Nombre des candidats</h6>
                <h5 class="fw-semibold mb-3" style="color:#FA9135;">{{ userCounts.candidats }} Candidats</h5>
                <div class="d-flex align-items-center pb-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3" v-if="userCounts && userCounts.moniteurs !== undefined">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-12 text-center">
                <h6 class="card-title mb-9 fw-semibold font-weight-bold" style="color: #203F6A;">Nombre des Moniteurs</h6>
                <h5 class="fw-semibold mb-3" style="color:#FA9135;">{{ userCounts.moniteurs }} Moniteurs</h5>
                <div class="d-flex align-items-center pb-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3" v-if="userCounts && userCounts.secretaires !== undefined">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-12 text-center">
                <h6 class="card-title mb-9 fw-semibold font-weight-bold" style="color: #203F6A;">Nombre des secrétaires</h6>
                <h5 class="fw-semibold mb-3" style="color:#FA9135;">{{ userCounts.secretaires }} Secrétaires</h5>
                <div class="d-flex align-items-center pb-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3" v-if="vehiculeCount !== null">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-12 text-center">
                <h6 class="card-title mb-9 fw-semibold font-weight-bold" style="color: #203F6A">Nombre des véhicules</h6>
                <h5 class="fw-semibold mb-3" style="color:#FA9135;">{{ vehiculeCount }} véhicules</h5>
                <div class="d-flex align-items-center pb-1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-6 h-100">
        <div class="shadow p-3 mb-5 bg-body rounded h-100">
          <div id="chart" class="mb-4">
            <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
            <h4 style="text-align: center ; color: #1C406B;">Flux entrant et sortant</h4>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 h-100">
        <div class="shadow p-3 mb-5 bg-body rounded h-100 d-flex flex-column align-items-center justify-content-center">
          <div id="chart" class="text-center">
            <br><br><br>
            <apexchart type="pie" class="mt-200" width="380" :options="chartOptions_pie_chart" :series="series_pie_chart" style="max-width: 100%; "></apexchart><br><br>
            <br>
            <h4  style="text-align: center ; color: #1C406B;">Nombre des utilisateurs et véhicules</h4>
            <br>
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
      userCounts: null,
      fluxEntrant: [],
      fluxSortant: [],
      vehiculeCount: null,
      chartOptions: {
        chart: {
          height: 350,
          type: 'area',
          colors: ['#FA7F35', '#9DCD5A']
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
  type: 'category',
  categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
},
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
      },
      series: [{
        name: 'Flux entrant',
        data: []
      }, 
      {
        name: 'Flux sortant',
        data: []
      }],
      series_pie_chart: [],
      chartOptions_pie_chart: {
        chart: {
          width: 380,
          type: 'pie',
        },
        labels: ['Moniteur', 'Candidat', 'Secrétaire', 'Véhicule'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      },
    };
  },
  mounted() {
    let isSuperAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "superAdmin";
    if (isSuperAdmin){
      window.location.href = '/dashbord_Super_Admin';
    }
    console.log("Component mounted.");
    this.fetchUserCounts();
    this.fetchVehiculeCount();
    this.fetch();
    this.fetchFluxSE();
  },
  methods: {
    fetchFluxSE() {
  axios.get("/transaction/indexFluxSE")
    .then(response => {
      const data = response.data;
      if (data && data.fluxEntrant && data.fluxSortant) {
        // Extraction des flux entrants et sortants par mois
        this.fluxEntrant = data.fluxEntrant; // Ajustez ici si la structure de vos données est différente
        this.fluxSortant = data.fluxSortant; // Ajustez ici si la structure de vos données est différente

        // Mise à jour des séries pour le graphique en area
        this.series = [{
          name: 'Flux entrant',
          data: this.fluxEntrant.map(item => parseInt(item)) // Assurez-vous que les valeurs sont des nombres
        }, 
        {
          name: 'Flux sortant',
          data: this.fluxSortant.map(item => parseInt(item)) // Assurez-vous que les valeurs sont des nombres
        }];
      } else {
        console.error('Error fetching flux entrant and sortant: Data is undefined or null.');
      }
    })
    .catch(error => {
      console.error('Error fetching flux entrant and sortant:', error);
    });
},



    fetch() {
      axios.get("/user/count")
        .then(response => {
          const userData = response.data;
          this.series_pie_chart = [userData.moniteurs, userData.candidats, userData.secretaires, this.vehiculeCount];
        })
        .catch(error => {
          console.error('Error fetching user counts:', error);
        });
    },

    fetchUserCounts() {
      axios.get("/user/CountUser")
        .then(response => {
          this.userCounts = response.data;
        })
        .catch(error => {
          console.error('Error fetching user counts:', error);
        });
    },

    fetchVehiculeCount() {
      axios.get("/vehicule/CountVehicule")
        .then(response => {
          this.vehiculeCount = response.data.nombre_de_vehicules; // Accès à la clé "nombre_de_vehicules"
        })
        .catch(error => {
          console.error('Error fetching vehicule count:', error);
        });
    },
  },
};
</script>

<style>
/* Vos styles CSS */
</style>