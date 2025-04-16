var ctx1 = document.getElementById("salesChart").getContext("2d");
var salesChart = new Chart(ctx1, {
  type: "bar",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
    datasets: [
      {
        label: "Income",
        data: [80, 90, 65, 95, 55, 40, 85, 70],
        backgroundColor: "rgba(200, 200, 200, 0.5)",
        borderWidth: 0,
      },
      {
        label: "Profit",
        data: [60, 60, 50, 80, 50, 30, 70, 55],
        backgroundColor: "black",
        borderWidth: 0,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { beginAtZero: true },
    },
  },
});

var ctx2 = document.getElementById("dailySalesChart").getContext("2d");
var dailySalesChart = new Chart(ctx2, {
  type: "line",
  data: {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [
      {
        label: "Sales",
        data: [20, 35, 25, 40, 50, 45, 55],
        borderColor: "black",
        borderWidth: 2,
        fill: false,
      },
      {
        label: "Revenue",
        data: [15, 30, 20, 35, 45, 40, 50],
        borderColor: "blue",
        borderWidth: 2,
        fill: false,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { beginAtZero: true },
    },
  },
});
