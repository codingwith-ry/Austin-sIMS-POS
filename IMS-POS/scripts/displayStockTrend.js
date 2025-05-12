const stockTrend = document.getElementById("stockTrendChart").getContext("2d");

let lineChart;

// Fetch and render chart
function fetchAndRenderChart(range) {
  fetch(`scripts/fetchStockTrend.php?range=${range}`)
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) {
        console.error("Failed to load data:", data.message);
        alert(data.message || "Error loading chart data.");
        return;
      }

      // Check if the data contains the necessary arrays
      if (!data.labels || !data.quantities || !data.prices) {
        console.error("Missing expected data: labels, quantities, or prices.");
        alert("Missing expected data.");
        return;
      }

      // ✅ FORMAT LABELS BASED ON RANGE
      const labels = data.labels.map((label) => {
        if (range === "yearly") {
          // Year only, e.g. "2022"
          return label;
        }

        if (range === "monthly") {
          // "2025-01" => "Jan"
          const [year, month] = label.split("-");
          return new Date(`${year}-${month}-01`).toLocaleString("default", {
            month: "short",
          });
        }

        if (range === "weekly") {
          // "2025-05-07" => "Wed, May 7"
          return new Date(label).toLocaleDateString("default", {
            weekday: "short",
            month: "short",
            day: "numeric",
          });
        }

        return label; // fallback
      });

      const quantities = data.quantities.map((quantity) => parseInt(quantity));
      const prices = data.prices.map((price) => parseFloat(price));

      const chartData = {
        labels,
        datasets: [
          {
            label: "Total Quantity",
            data: quantities,
            borderColor: "rgb(75, 192, 192)",
            tension: 0.4,
            fill: false,
            borderWidth: 2,
          },
          {
            label: "Total Price",
            data: prices,
            borderColor: "rgb(255, 99, 132)",
            tension: 0.4,
            fill: false,
            borderWidth: 2,
          },
        ],
      };

      // If a chart exists, update it, else create a new one
      if (lineChart) {
        lineChart.data = chartData;
        lineChart.update();
      } else {
        lineChart = new Chart(stockTrend, {
          type: "line",
          data: chartData,
          options: {
            responsive: true,
            scales: {
              x: {
                type: "category",
                title: {
                  display: true,
                  text: "Date",
                },
                ticks: {
                  autoSkip: true,
                  maxRotation: 45,
                  minRotation: 45,
                },
              },
              y: {
                title: {
                  display: true,
                  text: "Amount",
                },
              },
            },
            plugins: {
              legend: {
                position: "top",
              },
            },
          },
        });
      }
    })
    .catch((err) => {
      console.error("Error fetching chart data:", err);
      alert("Failed to load chart data.");
    });
}

// Initial load
fetchAndRenderChart("weekly");

// Listen to radio changes
document.querySelectorAll('input[name="btnradio"]').forEach((radio) => {
  radio.addEventListener("change", function () {
    fetchAndRenderChart(this.value);
  });
});
