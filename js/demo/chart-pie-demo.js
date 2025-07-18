// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    // Gunakan label dinamis dari PHP
    labels: dynamicLabels,
    datasets: [{
      // Gunakan data dinamis dari PHP
      data: dynamicData,
      // Sediakan palet warna yang cukup untuk jumlah data yang mungkin
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#fd7e14', '#20c9a6'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#c73121', '#60616f', '#4e4f5c', '#e06103', '#1baa8a'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true,
      position: 'bottom', // Menampilkan legenda di bawah chart
    },
    cutoutPercentage: 80,
  },
});