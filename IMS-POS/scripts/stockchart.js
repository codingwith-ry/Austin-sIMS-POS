const ctxElem = document.getElementById("stockAnalyticsChart");
let ctx = null;
if (ctxElem) {
  ctx = ctxElem.getContext("2d");
}

if (ctx != null) {
  let chart = new Chart(ctx, {
    type: "line",
    data: {
      labels: [],
      datasets: [
        {
          label: "Stock Expenses",
          data: [],
          borderColor: "rgba(54, 162, 235, 1)",
          backgroundColor: "rgba(54, 162, 235, 0.1)",
          fill: true,
          tension: 0.4, // adds curve to the line
          pointBackgroundColor: "rgba(54, 162, 235, 1)",
          pointBorderColor: "#fff",
          pointHoverBackgroundColor: "#fff",
          pointHoverBorderColor: "rgba(54, 162, 235, 1)",
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          beginAtZero: true,
          grid: {
            display: false,
          },
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

  const startDateInput = document.getElementById("startDate");
  const today = new Date();
  const todayStr = today.toISOString().split("T")[0];
  startDateInput.value = todayStr;
  startDateInput.max = todayStr;

  // Button group listeners
  document.getElementById("btnradio1").addEventListener("change", () => {
    startDateInput.value = todayStr; // reset to today
    updateChart("weekly", todayStr);
  });

  document
    .getElementById("btnradio2")
    .addEventListener("change", () => updateChart("monthly"));
  document
    .getElementById("btnradio3")
    .addEventListener("change", () => updateChart("yearly"));

  // 🎯 Input field listener
  startDateInput.addEventListener("change", (e) => {
    const selectedDate = e.target.value;
    if (selectedDate) {
      document.getElementById("btnradio1").checked = true;
      updateChart("weekly", selectedDate);
    }
  });

  // 🟩 Load chart with today's date by default
  updateChart("weekly", todayStr);
}
