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

let totalSalesAmount = 0; // Declare a variable to store totalSales for the plugin

// Fetch the category data from the backend
fetch("scripts/adminTotalOrders.php")
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      const categoryData = data.categoryData;
      totalSalesAmount = data.totalSales; // Save totalSales globally

      const orderData = {
        labels: ["Food", "Drinks", "Others"],
        datasets: [
          {
            data: [categoryData.Food, categoryData.Drinks, categoryData.Others],
            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
            hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
          },
        ],
      };

      // Create the chart with fetched data
      new Chart(topOrdersGraph, {
        type: "doughnut",
        data: orderData,
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
    } else {
      console.error("Failed to fetch data:", data.message);
    }
  })
  .catch((error) => {
    console.error("Error fetching data:", error);
  });

// Plugin to draw total sales in the middle of the doughnut
const topOrderLabel = {
  id: "topOrderLabel",
  beforeDatasetsDraw(chart, args, options) {
    const { ctx } = chart;
    ctx.save();
    const meta = chart.getDatasetMeta(0);

    if (meta.data.length === 0) return;

    const xCoor = meta.data[0].x;
    const yCoor = meta.data[0].y;

    const formattedTotalSales = totalSalesAmount.toLocaleString("en-PH", {
      style: "currency",
      currency: "PHP",
    });

    ctx.font = "bold 20px sans-serif";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText(formattedTotalSales, xCoor, yCoor);
  },
};

/****************Stastics Bar Chart Initialization****************/
const statisticsGraph = document.getElementById("statisticsBarChart");

const statisticsData = {
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
      data: [], // will be filled from backend
      backgroundColor: "rgb(255, 99, 132)",
      borderRadius: 10,
      borderSkipped: false,
      barThickness: 40,
    },
  ],
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
      ticks: {
        stepSize: 100,
      },
      grid: {
        display: false,
      },
    },
  },
  responsive: true,
  maintainAspectRatio: false,
};

// Initialize chart with empty data
const statisticsChart = new Chart(statisticsGraph, {
  type: "bar",
  data: {
    labels: statisticsData.labels,
    datasets: statisticsData.datasets,
  },
  options: chartOptions,
});

// Fetch actual data from PHP backend
fetch("scripts/adminOrders.php", {
  method: "POST",
})
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      statisticsChart.data.datasets[0].data = data.ordersPerDay;
      statisticsChart.update();
    } else {
      console.error("Failed to fetch data:", data.message);
    }
  })
  .catch((error) => {
    console.error("Fetch error:", error);
  });

document.addEventListener("DOMContentLoaded", () => {
  fetch("scripts/adminTopSelling.php") // Update to actual PHP path
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        const list = document.getElementById("topSellingList");
        list.innerHTML = "";

        data.products.forEach((product) => {
          const badgeClass =
            product.categoryID == 1 ? "text-bg-primary" : "text-bg-warning";

          const listItem = `
                          <li class="d-flex justify-content-between align-items-center">
                              <div class="d-flex align-items-center gap-2">
                                  <!-- Display Category Icon as text -->
                                  <span class="${product.categoryIcon}" style="font-size: 30px"></span>
                                  <div class="ms-2 me-auto">
                                      <div>
                                          <!-- Display Category Name as badge -->
                                          <span class="badge ${badgeClass} rounded-pill">
                                              ${product.categoryName}
                                          </span>
                                      </div>
                                      <span style="font-size: 20px; font-weight:bold">${product.productName}</span>
                                  </div>
                              </div>
                              <span style="font-size: 30px; font-weight:bold">${product.totalOrders}</span>
                          </li>
                          <hr />
                      `;
          list.innerHTML += listItem;
        });
      } else {
        console.error("Failed to load products:", data.message);
      }
    })
    .catch((err) => console.error("Fetch error:", err));
});
