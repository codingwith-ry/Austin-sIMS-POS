/****************Number Of Employees Chart Initialization****************/
const employeeGraph = document.getElementById("employeeChart");

const employeeData = {
  labals: ["Total Employees", "Available Employees"],
  datasets: [
    {
      data: [60, 100],
      backgroundColor: ["#686D76"],
    },
  ],
};

new Chart(employeeGraph, {
  type: "doughnut",
  data: employeeData,
  options: {
    layout: {
      padding: 40,
    },
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

/****************Number Of POS Employees Chart Initialization****************/

const posEmployeesGraph = document.getElementById("No_POS_Employees_Chart");

const posEmployeeData = {
  labels: ["Total Employees", "POS Employees"],
  datasets: [
    {
      data: [20, 100],
      backgroundColor: ["#686D76"],
    },
  ],
};

new Chart(posEmployeesGraph, {
  type: "doughnut",
  data: posEmployeeData,
  options: {
    layout: {
      padding: 40,
    },
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

/****************Number Of IMS Employees Chart Initialization****************/

const imsEmployeeGraph = document.getElementById("No_IMS_Employees_Chart");

const imsEmployeeData = {
  labals: ["Total Employees", "IMS Employees"],
  datasets: [
    {
      data: [50, 100],
      backgroundColor: ["#686D76"],
    },
  ],
};

new Chart(imsEmployeeGraph, {
  type: "doughnut",
  data: imsEmployeeData,
  options: {
    layout: {
      padding: 40,
    },
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

/****************Number Of Administrator Chart Initialization****************/

const adminGraph = document.getElementById("Admin_Chart");
const adminData = {
  labels: ["Total Employees", "Admin"],
  datasets: [
    {
      data: [2, 100],
      backgroundColor: ["#686D76"],
    },
  ],
};

new Chart(adminGraph, {
  type: "doughnut",
  data: adminData,
  options: {
    layout: {
      padding: 40,
    },
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

/****************Sales Data Chart Initialization****************/
const saleGraph = document.getElementById("salesDataChart");

const salesData = {
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
      data: [12, 19, 3, 5, 2, 3, 10],
      fill: false,
      borderColor: "rgb(75, 192, 192)",
      tension: 0.1,
    },
  ],
};

new Chart(saleGraph, {
  type: "line",
  data: salesData,
  options: {
    plugins: {
      legend: {
        display: false,
      },
    },
    scales: {
      x: {
        grid: {
          drawOnChartArea: false,
          drawBorder: false,
        },
      },
      y: {
        display: false,
        grid: {
          drawOnChartArea: false,
          drawBorder: false,
        },
        ticks: {
          display: false,
        },
      },
    },
    responsive: true,
    maintainAspectRatio: false,
  },
});

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
