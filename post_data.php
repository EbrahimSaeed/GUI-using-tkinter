
<?php
    include_once('database.php');
    
  
 $readings_count = 150;
    $last_reading = getLastReadings();
    $freq = $last_reading["frequency"];
    $voltage = $last_reading["voltage"];
    $power = $last_reading["power"];
    $current = $last_reading["current"];
    $flow = $last_reading["flow"];
    $tflow = $last_reading["tflow"];
    $pressure = $last_reading["pressure"];
    $time = $last_reading["time"];


    
    $last_reading_time = $last_reading["time"];



    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SCADA System Monitoring Dashboard</title>
<style>
  body, html {
    height: 100%;
    margin: 0;
    font-family: 'Consolas', 'Arial', sans-serif;
    background: #263238;
    color: #cfd8dc;
  }

  .header {
    background: #37474f;

    color: white;
    text-align: center;
    padding: 20px 0;
    font-size: 24px;
    font-weight: bold;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.1);
  }

  .container, .table-container {
    display: flex;
    justify-content: center;
    padding: 20px;
    flex-wrap: wrap;
  }

  .gauge-container {
    text-align: center;
    padding: 20px;
    margin: 20px;
    background: #455a64;
    border-radius: 10px;
    box-shadow: 0 2px 10px 0 rgba(0,0,0,0.2);
    width: 300px; /* Increased width */
  }

  .gauge-title {
    color: #29b6f6;
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: 600;
  }

  .gauge {
    width: 250px; /* Increased gauge width */
    height: 200px; /* Increased gauge height */
    margin: 15px auto;
  }

  .gauge-value {
    font-size: 18px;
    color: #ffd740;
    font-weight: 600;
  }

  .chart-container {
    width: 90%;
    max-width: 900px;
    height: 400px;
    margin: 20px auto;
    background: #37474f;
    border-radius: 10px;
    box-shadow: 0 2px 10px 0 rgba(0,0,0,0.2);
    padding: 20px;
  }

  canvas {
    width: 100% !important;
    height: 100% !important;
  }

  /* Table Styles */
  table {
    width: 90%;
    max-width: 900px;
    border-collapse: collapse;
    margin: 20px auto;
    background: #37474f;
    box-shadow: 0 2px 10px 0 rgba(0,0,0,0.2);
    border-radius: 10px;
  }

  th, td {
    text-align: left;
    padding: 12px;
    color: #cfd8dc;
    border-bottom: 1px solid #455a64;
  }

  th {
    background: #455a64;
    color: #fff;
  }

  tr:hover {
    background-color: #455a64;
  }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.4.0/justgage.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="header">
  <h1>SCADA System Monitoring Dashboard</h1>
</div>

<div class="container">
  <!-- Power Gauge Container -->
  <div class="gauge-container">
    <div class="gauge-title">Power (kW)</div>
    <div class="gauge" id="powerGauge"></div>
    <div class="gauge-value" id="powerValue">Loading...</div>
  </div>
  <!-- Voltage Gauge Container -->
  <div class="gauge-container">
    <div class="gauge-title">Voltage (V)</div>
    <div class="gauge" id="voltageGauge"></div>
    <div class="gauge-value" id="voltageValue">Loading...</div>
  </div>
  <!-- Frequency Gauge Container -->
  <div class="gauge-container">
    <div class="gauge-title">Frequency (Hz)</div>
    <div class="gauge" id="freqGauge"></div>
    <div class="gauge-value" id="freqValue">Loading...</div>
  </div>
  <!-- Flow Gauge Container -->
  <div class="gauge-container">
    <div class="gauge-title">Flow (L/min)</div>
    <div class="gauge" id="flowGauge"></div>
    <div class="gauge-value" id="flowValue">Loading...</div>
  </div>
  <!-- Pressure Gauge Container -->
  <div class="gauge-container">
    <div class="gauge-title">Pressure (kPa)</div>
    <div class="gauge" id="pressureGauge"></div>
    <div class="gauge-value" id="pressureValue">Loading...</div>
  </div>
</div>

<div class="chart-container">
  <canvas id="lineChart"></canvas>
</div>

<div class="table-container">
  <table>
    <thead>
      <tr>
        <th>Time</th>
        <th>Power (kW)</th>
        <th>Voltage (V)</th>
        <th>Frequency (Hz)</th>
        <th>Flow (L/min)</th>
        <th>Pressure (kPa)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>09:00</td>
        <td>65</td>
        <td>220</td>
        <td>60</td>
        <td>30</td>
        <td>100</td>
      </tr>
      <!-- Repeat <tr> for more data entries -->
    </tbody>
  </table>
</div>

<script>
 
  document.addEventListener("DOMContentLoaded", function(event) {
    if (typeof JustGage === 'undefined' || typeof Raphael === 'undefined') {
      console.error('JustGage or Raphael not loaded');
      return;
    }
    function fetchData() {
  fetch('dd.php')  // This should point to your PHP file.
    .then(response => response.json())
    .then(data => {
      updateGauges(data);
    })
    .catch(error => console.error('Error fetching data:', error));
}
function updateGauges(data) {
  // Assuming 'data' is an object with the new readings.
  // Update your gauges using the new data:
  powerGauge.refresh(data.power);
  freqGauge.refresh(data.frequency);
  voltageGauge.refresh(data.voltage);
  flowGauge.refresh(data.flow);
  pressureGauge.refresh(data.pressure);

  // Update the gauge value text display:
  document.getElementById('powerValue').textContent = data.power + ' kW';
  document.getElementById('freqValue').textContent = data.frequency + ' Hz';
  document.getElementById('voltageValue').textContent = data.voltage + ' V';
  document.getElementById('flowValue').textContent = data.flow + ' m3/3';
  document.getElementById('pressureValue').textContent = data.pressure + ' bar';

}
setInterval(fetchData, 2000);
    try {
  
      // Power Gauge Initialization
      var powerGauge = new JustGage({
        id: "powerGauge",
        value: <?php echo $power; ?>, // Replace with real data
        min: 0,
        max: 100,
        title: "Power",
        label: "kW"
      });
  var freqGauge = new JustGage({
        id: "freqGauge",
        value: <?php echo $freq; ?>, // Replace with real data
        min: 0,
        max: 100,
        title: "Frequency",
        label: "Hz"
      });
  var voltageGauge = new JustGage({
        id: "voltageGauge",
        value:  <?php echo $voltage; ?>, // Replace with real data
        min: 0,
        max: 450,
        title: "voltage",
        label: "V"
      });
  var flowGauge = new JustGage({
        id: "flowGauge",
        value:  <?php echo $flow; ?>, // Replace with real data
        min: 0,
        max: 50,
        title: "flow",
        label: "m3/h"
      });
  var pressureGauge = new JustGage({
        id: "pressureGauge",
        value:  <?php echo $pressure; ?>, // Replace with real data
        min: 0,
        max: 10,
        title: "pessure",
        label: "bar"
      });
      /*
      document.getElementById('powerValue').textContent = '<?php echo $power; ?>' + ' kW';
       document.getElementById('freqValue').textContent = '<?php echo $freq; ?>' + ' Hz';
       document.getElementById('voltageValue').textContent = '<?php echo $voltage; ?>' + ' voltage';
       document.getElementById('flowValue').textContent = '<?php echo $flow; ?>' + ' m3/h';
       document.getElementById('pressureValue').textContent = '<?php echo $pressure; ?>' + ' bar';
      */
      // Initialize other gauges (Voltage, Frequency, etc.) similarly...

    } catch (error) {
      console.error('Error initializing gauges:', error);
    }

    // Initialize the Chart.js Multi-Line Chart
    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['1:00', '3:00', '5:00', '7:00', '9:00', '11::00', '13:00'],
        datasets: [{
          label: 'Power (kW)',
          backgroundColor: 'rgba(0, 181, 204, 0.2)',
          borderColor: 'rgba(0, 181, 204, 1)',
          data: [0, 0, 0, 22, 43, 26, 0],
          fill: false,
        }, {
          label: 'Voltage (V)',
          fill: false,
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: [0, 0, 0, 19, 46, 19, 0],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: 'System Parameters Over Time'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  });  

</script>
</body>
</html>
