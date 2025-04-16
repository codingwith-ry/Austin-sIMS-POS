const ctx = document.getElementById("stockAnalyticsChart").getContext("2d");

let chart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: [],
    datasets: [
      {
        label: "Stock Expenses",
        data: [],
        backgroundColor: "rgba(54, 162, 235, 0.5)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

async function updateChart(type, selectedDate = null) {
  let endpoint = "";
  if (type === "weekly") {
    endpoint = "fetchWeeklyExpenses.php";
    if (selectedDate) {
      endpoint += "?startDate=" + selectedDate;
    }
  } else if (type === "monthly") {
    endpoint = "fetchMonthlyExpenses.php";
  } else if (type === "yearly") {
    endpoint = "fetchYearlyExpenses.php";
  }

  try {
    const response = await fetch(endpoint);
    const result = await response.json();

    if (result.success) {
      const labels = result.labels;
      const data = result.expenses;

      chart.data.labels = labels;
      chart.data.datasets[0].data = data;
      chart.update();
    } else {
      alert("Failed to load data: " + result.message);
    }
  } catch (error) {
    console.error("Fetch error:", error);
    alert("Error fetching data.");
  }
}

// Button group listeners
document.getElementById("btnradio1").addEventListener("change", () => {
  document.getElementById("startDate").value = ""; // reset input
  updateChart("weekly");
});
document
  .getElementById("btnradio2")
  .addEventListener("change", () => updateChart("monthly"));
document
  .getElementById("btnradio3")
  .addEventListener("change", () => updateChart("yearly"));

// 🎯 New: Input field listener
document.getElementById("startDate").addEventListener("change", (e) => {
  const selectedDate = e.target.value;
  if (selectedDate) {
    // Force switch to "weekly" view with selected date
    document.getElementById("btnradio1").checked = true;
    updateChart("weekly", selectedDate);
  }
});

// Load default chart
updateChart("weekly");
