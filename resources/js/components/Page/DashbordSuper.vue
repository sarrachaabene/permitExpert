<template>
  <br/><br/><br/><br>

  <div class="row">
  <div class="col-12 col-md-6">
    <div id="chart" class="mb-4">
      <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
  </div>
  <div class="col-12 col-md-6">
    <div id="chart">
      <apexchart type="pie" class="mt-200" width="380" :options="chartOptions_pie_chart" :series="series_pie_chart"></apexchart>
    </div>
  </div>
</div>
          <footer-component></footer-component>
        
  </template>
  
  <script>
  export default {
     data: function() {
          return {
  
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
                type: 'datetime',
                categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
              },
              tooltip: {
                x: {
                  format: 'dd/MM/yy HH:mm'
                },
              },
            },
            series: [{
              name: 'series1',
              data: [31, 40, 28, 51, 42, 109, 100]
            }, 
            {
              name: 'series3',
              data: [11, 32, 98, 32, 23, 52, 3]
            }],
            series_pie_chart: [44, 55, 13, 43, 22],
            chartOptions_pie_chart: {
              chart: {
                width: 380,
                type: 'pie',
              },
              labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
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
        console.log("Component mounted.");
        /* 
        axios.get("/api/getnumberOfCandidatByAutoEcole").then(res=>{
          
        })
        */
    },
  };
  </script>