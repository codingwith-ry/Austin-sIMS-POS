/****************Number Of Employees Chart Initialization****************/

document.addEventListener("DOMContentLoaded", function () {
  fetch("scripts/adminEmployee.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const {
          totalEmployees,
          regularEmployees,
          posEmployees,
          imsEmployees,
          adminEmployees,
        } = data;

        // Update DOM counts
        document.getElementById("regularEmployeesCount").innerText =
          regularEmployees;
        document.getElementById("posEmployeesCount").innerText = posEmployees;
        document.getElementById("imsEmployeesCount").innerText = imsEmployees;
        document.getElementById("adminEmployeesCount").innerText =
          adminEmployees;

        // Create Charts
        createChart(
          "Regular_Chart",
          regularEmployees,
          totalEmployees,
          "#17a2b8"
        );
        createChart(
          "No_POS_Employees_Chart",
          posEmployees,
          totalEmployees,
          "#28a745"
        );
        createChart(
          "No_IMS_Employees_Chart",
          imsEmployees,
          totalEmployees,
          "#ffc107"
        );
        createChart("Admin_Chart", adminEmployees, totalEmployees, "#dc3545");
      } else {
        console.error("Failed to load employee data:", data.message);
      }
    })
    .catch((err) => {
      console.error("Error fetching employee data:", err);
    });

  function createChart(canvasId, roleCount, totalCount, roleColor) {
    const ctx = document.getElementById(canvasId).getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Role Count", "Total Count"],
        datasets: [
          {
            data: [roleCount, totalCount],
            backgroundColor: [roleColor, "#d6d6d6"],
          },
        ],
      },
      options: {
        responsive: false,
        cutout: "70%",
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (context) => `${context.label}: ${context.parsed}`,
            },
          },
        },
      },
    });
  }
});

/****************Sales Data Chart Initialization****************/
// Send a POST request to fetch the weekly sales data
fetch("scripts/adminSalesChartData.php", {
  method: "POST",
})
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      const labels = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ];
      const sales = data.sales; // Array of total sales for each day

      // Create the chart data (no revenue data)
      const chartData = {
        labels: labels,
        datasets: [
          {
            label: "Daily Sales",
            data: sales,
            fill: false,
            borderColor: "rgb(75, 192, 192)",
            tension: 0.1,
          },
        ],
      };

      // Chart.js configuration
      const config = {
        type: "line",
        data: chartData,
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: "Sales for the Week",
            },
            legend: {
              position: "top",
            },
          },
          scales: {
            x: {
              title: {
                display: true,
                text: "Days of the Week",
              },
            },
            y: {
              title: {
                display: true,
                text: "Quantity Sold",
              },
              ticks: {
                callback: function (value) {
                  return value.toLocaleString(); // Format sales number
                },
              },
            },
          },
        },
      };

      // Initialize and render the chart
      const ctx = document.getElementById("salesDataChart").getContext("2d");
      new Chart(ctx, config);
    } else {
      console.error("Error fetching data:", data.message);
    }
  })
  .catch((error) => console.error("Error:", error));

/****************Top Orders Chart Initialization****************/

const topOrdersGraph = document.getElementById("totalOrderChart");

const topOrderLabel = {
  id: "topOrderLabel",
  beforeDatasetsDraw: function (chart, args, options) {
    const { ctx, data } = chart;
    ctx.save();
    const xCoor = chart.getDatasetMeta(0).data[0].x;
    const yCoor = chart.getDatasetMeta(0).data[0].y;
    ctx.font = "bold 20px sans-serif";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText("₱ 10,000", xCoor, yCoor);
  },
};
const orderData = {
  data: {
    labels: ["Food", "Drinks", "Others"],
    datasets: [
      {
        data: [300, 50, 100],
        backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
        hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
      },
    ],
    hoverOffset: 4,
  },
};

new Chart(topOrdersGraph, {
  type: "doughnut",
  data: orderData.data,
  plugins: [topOrderLabel],
  options: {
    plugins: {
      legend: {
        display: true,
        position: "bottom",
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

/****************Stastics Bar Chart Initialization****************/
const statisticsGraph = document.getElementById("statisticsBarChart");

const statisticsData = {
  data: {
    labels: [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ],
    datasets: [
      {
        label: "Orders",
        data: [1000, 2000, 3000, 4000, 6000, 7500, 3500],
        backgroundColor: "rgb(255, 99, 132)",
        borderRadius: 10,
        borderSkipped: false,
        barThickness: 40,
      },
    ],
  },
};

const chartOptions = {
  scales: {
    x: {
      grid: {
        display: false,
      },
    },
    y: {
      beginAtZero: true,
      min: 0,
      max: 9000,
      ticks: {
        stepSize: 1000,
      },
      grid: {
        display: false,
      },
    },
  },
  responsive: true,
  maintainAspectRatio: false,
};

new Chart(statisticsGraph, {
  type: "bar",
  data: statisticsData.data,
  options: chartOptions,
});
