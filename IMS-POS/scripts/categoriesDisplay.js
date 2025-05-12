document.addEventListener("DOMContentLoaded", function () {
  const chartCanvas = document.getElementById("categoryChart").getContext("2d");
  const startDateInput = document.getElementById("startDate");
  const dateDisplay = document.getElementById("dateDisplay");

  // Fetch data with no period or startDate filter
  function fetchData() {
    const period = document.querySelector(
      'input[name="btnradio"]:checked'
    ).value;
    const startDate =
      startDateInput.value || new Date().toISOString().split("T")[0]; // Default to today if empty

    const url = `scripts/categoriesFetch.php?period=${period}&startDate=${startDate}`;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.categories && data.totals) {
          updateChart(data.categories, data.totals);
          updateDateDisplay(); // optional
        } else {
          console.error("Invalid data format", data);
        }
      })
      .catch((error) => {
        console.error("Error fetching data: ", error);
      });
  }

  function updateChart(categories, totals) {
    if (window.polarChart) {
      window.polarChart.destroy();
    }

    const translucentBlues = [
      "rgba(0, 123, 255, 0.6)",
      "rgba(23, 162, 184, 0.6)",
      "rgba(0, 123, 255, 0.4)",
      "rgba(108, 117, 125, 0.5)",
      "rgba(0, 123, 255, 0.3)",
      "rgba(13, 110, 253, 0.4)",
      "rgba(32, 201, 151, 0.4)",
      "rgba(102, 16, 242, 0.4)",
      "rgba(111, 66, 193, 0.4)",
      "rgba(0, 140, 255, 0.3)",
      "rgba(0, 123, 255, 0.2)",
      "rgba(23, 162, 184, 0.3)",
    ];

    window.polarChart = new Chart(chartCanvas, {
      type: "polarArea",
      data: {
        labels: categories,
        datasets: [
          {
            label: "Purchase Totals by Category",
            data: totals,
            backgroundColor: translucentBlues,
            borderColor: "#fff",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: "top" },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                return `${
                  tooltipItem.label
                }: ₱${tooltipItem.raw.toLocaleString()}`;
              },
            },
          },
        },
      },
    });
  }

  function updateDateDisplay() {
    dateDisplay.textContent = ""; // No date display
  }

  // Bind radio input changes (this can be removed as we don't need the period filter)
  const radioButtons = document.querySelectorAll('input[name="btnradio"]');
  radioButtons.forEach((radio) => {
    radio.addEventListener("change", function () {
      // We don't need to fetch data based on period anymore
      fetchData();
    });
  });

  // Bind date change (this can be removed as we don't need the date filter anymore)
  startDateInput.addEventListener("change", () => {
    fetchData();
  });

  // Initial load (this will fetch all data on page load)
  fetchData();
});
