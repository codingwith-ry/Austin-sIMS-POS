const ctxDoughnut = document
  .getElementById("stockdoughnutChart")
  .getContext("2d");

// Initialize Doughnut Chart
let doughnutChart = new Chart(ctxDoughnut, {
  type: "doughnut",
  data: {
    labels: [], // No labels will be displayed
    datasets: [
      {
        label: "Stock Budget vs Expenses",
        data: [60, 40], // Example data, will be dynamically updated
        backgroundColor: ["#36A2EB", "#FF6384"],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false, // Disable the legend
      },
      tooltip: {
        callbacks: {
          label: function (tooltipItem) {
            return tooltipItem.raw + "%"; // Customize tooltip
          },
        },
      },
    },
    cutoutPercentage: 70, // Adjust to make it look like a doughnut
  },
});

// Function to update Doughnut Chart with dynamic data based on date and radio button selection
async function updateDoughnutChart(period) {
  try {
    // Fetch dynamic data from PHP backend (stockBudgetGraph.php)
    const response = await fetch(`stockBudgetGraph.php?period=${period}`);
    const result = await response.json();

    if (result.success !== false) {
      let remainingBudget = result.remaining_budget; // Remaining Budget Percentage
      let totalExpenses = result.expenses; // Total Expenses Percentage
      let itemsInStock = result.items_in_stock; // Items in Stock Percentage

      // Update Doughnut chart data
      doughnutChart.data.datasets[0].data = [remainingBudget, totalExpenses];
      document.getElementById("remainingBudget").innerText =
        remainingBudget + "%";
      document.getElementById("totalExpenses").innerText = totalExpenses + "%";
      document.getElementById("itemsInStock").innerText = itemsInStock;

      // Re-render the chart with new data
      doughnutChart.update();
    } else {
      alert("Failed to load data: " + result.message);
    }
  } catch (error) {
    console.error("Error updating doughnut chart:", error);
  }
}

// Event listeners for radio buttons
document.getElementById("btnradio1").addEventListener("change", () => {
  updateDoughnutChart("weekly");
});

document.getElementById("btnradio2").addEventListener("change", () => {
  updateDoughnutChart("monthly");
});

document.getElementById("btnradio3").addEventListener("change", () => {
  updateDoughnutChart("yearly");
});

// Call the function to set initial data when the page loads
updateDoughnutChart("weekly"); // Default to weekly
