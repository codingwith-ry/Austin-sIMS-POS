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
                backgroundColor: "rgba(200, 200, 200, 0.7)",
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

        // Update total income only
        const totalIncome = data.income.reduce((a, b) => a + b, 0);

        document.querySelector(
          "#totalValues"
        ).innerText = `Total Income: ₱${totalIncome.toLocaleString()}`;
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

document.addEventListener("DOMContentLoaded", function () {
  function formatPeso(value) {
    return (
      "₱" +
      parseFloat(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })
    );
  }

  $("#productSalesTable").DataTable({
    ajax: {
      url: "scripts/fetchOrderDetails.php",
      method: "POST", // important: must POST
      dataSrc: function (json) {
        if (json.success) {
          return json.data;
        } else {
          alert("Failed to fetch product sales");
          return [];
        }
      },
    },
    columns: [
      {
        data: "productName",
        render: function (data, type, row) {
          return `<img src="${row.productImage}" alt="${data}" width="50"> ${data}`;
        },
      },
      {
        data: "productPrice",
        render: function (data) {
          return formatPeso(data);
        },
      },
      {
        data: "total_quantity",
      },
      {
        data: "total_quantity",
        render: function (data, type, row) {
          return data; // same as quantity
        },
      },
      {
        data: "total_revenue",
        render: function (data) {
          return formatPeso(data);
        },
      },
    ],
    responsive: true,
    pageLength: 5,
    language: {
      emptyTable: "No product sales available",
    },
  });
});
