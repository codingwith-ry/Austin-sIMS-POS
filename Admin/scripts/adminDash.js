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
        label: "Sales",
        data: [55, 60, 40, 25, 79, 80, 90],
        backgroundColor: "rgb(75, 192, 192)",
        borderRadius: Number.MAX_VALUE,
        borderSkipped: false,
      },
      {
        label: "Orders",
        data: [80, 40, 30, 50, 60, 85, 95],
        backgroundColor: "rgb(255, 99, 132)",
        borderRadius: Number.MAX_VALUE,
        borderSkipped: false,
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
      max: 100,
      ticks: {
        stepSize: 25,
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
