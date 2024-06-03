<template>
  <div>
    <br><br><br>
    <br><br><br>
    <div class="row">
      <div class="col-12">
        <div class="shadow p-3 mb-5 bg-body rounded">
          <div id="chart" class="mb-4">
            <h3 style="text-align: center; color: #1C406B;">Nombre d'auto écoles</h3>
            <br>
            <h4 style="text-align: center; color: #1C406B;">{{ numberOfAutoEcoles }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 ">
        <div class="shadow p-3 mb-5 bg-body rounded h-100">
          <div id="chart" class="mb-4">
            <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
            <h4 style="text-align: center; color: #1C406B;">Nombre d'auto écoles</h4>
          </div>
        </div>
      </div>
    </div>
    <footer-component></footer-component>
  </div>
</template>

<script>
export default {
  data() {
    return {
      numberOfAutoEcoles: 0,
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
            format: 'MMM yyyy'
          },
        },
      },
      series: [{
        name: 'Auto école',
        data: [] 
      }],
      series_pie_chart: [44, 55, 13, 43],
      chartOptions_pie_chart: {
        chart: {
          width: 380,
          type: 'pie',
        },
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
    let isAdmin = JSON.parse(localStorage.getItem('users'))[0].role === "admin";
    if (isAdmin){
      window.location.href = '/dashbord';
    }
    console.log("Component mounted.");
    this.fetchNumberOfAutoEcoles();
    this.fetchNumberOfAutoEcolesByMonth();
  },
  methods: {
    fetchNumberOfAutoEcolesByMonth() {
      axios.get("/autoEcole/countAutoEcolesByMonth")
        .then(response => {
          const data = response.data;
          const autoEcoleCounts = data.map(item => item.count);
          this.series = [{
            name: 'Auto école',
            data: autoEcoleCounts
          }];
          // Mettre à jour le nombre total d'auto-écoles
          this.numberOfAutoEcoles = autoEcoleCounts.reduce((total, count) => total + count, 0);
        })
        .catch(error => {
          console.error('Erreur lors de la récupération du nombre d\'auto-écoles:', error);
        });
    },
  
    fetchNumberOfAutoEcoles() {
      axios.get("/autoEcole/countAutoEcoles")
        .then(response => {
          this.numberOfAutoEcoles = response.data.count;
        })
        .catch(error => {
          console.error('Error fetching number of auto-ecoles:', error);
        });
    },
  },
};
</script>
