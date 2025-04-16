// Monthly Sales and Profit Chart
document.addEventListener("DOMContentLoaded", function () {
  fetch("scripts/adminProfit.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const ctx1 = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx1, {
          type: "bar",
          data: {
            labels: [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "May",
              "Jun",
              "Jul",
              "Aug",
              "Sep",
              "Oct",
              "Nov",
              "Dec",
            ],
            datasets: [
              {
                label: "Income",
                data: data.income,
                backgroundColor: "rgba(200, 200, 200, 0.5)",
                borderWidth: 0,
              },
              {
                label: "Profit",
                data: data.profit,
                backgroundColor: "black",
                borderWidth: 0,
              },
              {
                label: "Expenses",
                data: data.expenses,
                backgroundColor: "rgba(255, 99, 132, 0.5)",
                borderWidth: 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });

        // Update total income, profit, and expenses
        const totalIncome = data.income.reduce((a, b) => a + b, 0);
        const totalProfit = data.profit.reduce((a, b) => a + b, 0);
        const totalExpenses = data.expenses.reduce((a, b) => a + b, 0);

        document.querySelector(
          "#totalValues"
        ).innerText = `Total Income: ₱${totalIncome.toLocaleString()} | Total Expenses: ₱${totalExpenses.toLocaleString()} | Profit: ₱${totalProfit.toLocaleString()}`;
      } else {
        console.error(data.message);
      }
    })
    .catch((error) => console.error("Fetch error:", error));
});

// Daily Sales Chart
document.addEventListener("DOMContentLoaded", function () {
  fetch("scripts/adminSales.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const ctx2 = document
          .getElementById("dailySalesChart")
          .getContext("2d");
        new Chart(ctx2, {
          type: "line",
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
              {
                label: "Sales",
                data: data.sales,
                borderColor: "black",
                borderWidth: 2,
                fill: false,
              },
              {
                label: "Revenue",
                data: data.revenue,
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
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function (value) {
                    return "₱" + value;
                  },
                },
              },
            },
          },
        });
      } else {
        console.error(data.message);
      }
    })
    .catch((error) => console.error("Fetch error:", error));
});
