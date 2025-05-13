const ctx = document.getElementById('transactionTypes').getContext('2d');
  const myDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Cash', 'GCash', 'PayMaya'],
      datasets: [{
        label: 'Payments',
        data: [],
        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Sample Doughnut Chart'
        }
      }
    }
  });


  const dailySales = document.getElementById('cashLineChart').getContext('2d');
    const cashLineChart = new Chart(dailySales, {
        type: 'line',
        data: {
            labels: [], // Placeholder for dates
            datasets: [
                {
                    label: 'Expected Cash Amount',
                    data: [], // Placeholder for expected cash amounts
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4,
                },
                {
                    label: 'Actual Cash Amount',
                    data: [], // Placeholder for actual cash amounts
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Expected vs. Actual Cash Amount Over Time'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
                legend: {
                    position: 'top',
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cash Amount (₱)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });

  const menuSales = document.getElementById('menuSalesChart').getContext('2d');
  const menuSalesChart = new Chart(menuSales, {
    type: 'line',
    data: {
      labels: [], // to be filled by update function
      datasets: [] // to be filled by update function
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Menu Sales (Last 7 Days)'
        },
        legend: {
          display: true,
          position: 'bottom'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Date'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Total Sales'
          },
          beginAtZero: true
        }
      }
    }
  });



  const coffeeMenu = document.getElementById('coffeeMenuChart').getContext('2d');
  const coffeeMenuChart = new Chart(coffeeMenu, {
    type: 'doughnut',
    data: {
      labels: [
        'Hot',
        'Iced',
        'Tea and Refresher',
        'Slushies',
        'Signature Drinks',
        'Non-Coffee',
        'Iced Blended Coffee'
      ],
      datasets: [{
        label: 'Sales by Category',
        data: [120, 150, 90, 60, 80, 70, 100], // Placeholder data
        backgroundColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0',
          '#9966FF',
          '#FF9F40',
          '#7ED6DF'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Coffee Menu Sales by Category'
        }
      }
    }
  });

  const gastroPubSales = document.getElementById('gastroPubChart').getContext('2d');
  const gastroPubChart = new Chart(gastroPubSales, {
    type: 'doughnut',
    data: {
      labels: [
        'Signature Cocktails',
        'Classic Cocktails',
        'Shooters',
        'Beers',
        'Premium Bottles',
        'Drinks',
        'Starter',
        'Chicken',
        'Pork',
        'Seafoods',
        'Rice and Noodles',
        'Beef',
        'Vegetables',
        'Dessert'
      ],
      datasets: [{
        label: 'Sales by Category',
        data: [80, 70, 60, 100, 90, 85, 50, 95, 70, 65, 60, 75, 40, 30], // Replace with real data
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
          '#FF9F40', '#7ED6DF', '#E77F67', '#F8EFBA', '#B8E994',
          '#63cdda', '#778beb', '#e77f67', '#f19066'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Gastro Pub Menu Sales by Category'
        }
      }
    }
  });

  const partyTraySales = document.getElementById('partyTrayChart').getContext('2d');
  const partyTrayChart = new Chart(partyTraySales, {
    type: 'doughnut',
    data: {
      labels: [
        'Beef',
        'Pork',
        'Chicken',
        'Seafoods',
        'Vegetables',
        'Pasta and Noodles'
      ],
      datasets: [{
        label: 'Sales by Category',
        data: [80, 75, 95, 70, 60, 85], // Replace with actual sales data
        backgroundColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0',
          '#9966FF',
          '#FF9F40'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Party Tray Menu Sales by Category'
        }
      }
    }
  });

  $(document).ready(function () {
    // Initialize DataTable
    fetchDailySalesData();


    $('#productSalesTable').DataTable({
        ajax: 'scripts/fetchProductSales.php', // Replace with your server-side script URL
        columns: [
          {
            data: null,
            render: function(data, type, row)
            {
              return `
                <span class="badge text-bg-primary rounded-pill w-72 fs-6 mb-1 p-3">${row.menuName}</span>
                <span class="badge text-bg-success rounded-pill w-72 fs-6 p-3">${row.categoryName}</span>
              `;
            }
          },
          {
            data: 'product'
           },       
          { 
              data: 'price',         // Price
              render: function (data, type, row) {
                  return '₱' + parseFloat(data).toLocaleString(); // Add peso symbol and format as currency
              }
          },
          { data: 'quantity' },      // Quantity
          { 
              data: 'total_sales',   // Total Sales
              render: function (data, type, row) {
                  return '₱' + parseFloat(data).toLocaleString(); // Add peso symbol and format as currency
              }
          }
        ],
        responsive: true, // Makes the table responsive
        paging: true,     // Enables pagination
        searching: true,  // Enables search functionality
        ordering: true,   // Enables column sorting
        lengthChange: true, // Allows changing the number of rows displayed
        pageLength: 5,   // Default number of rows per page
        lengthMenu: [5, 10, 25, 50, 100], // Dropdown options for page length
        language: {
            emptyTable: "No data available in table",
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    function fetchDailySalesData() {
      $.ajax({
          url: 'scripts/fetchDailySales.php', // Path to your PHP script
          method: 'GET',
          dataType: 'json',
          success: function (response) {


              // Update Total Sales
              $('#totalSales').text('₱' + response.totalSales.toLocaleString());

              // Update Discounts
              $('#discounts').text('₱' + response.discounts.toLocaleString());

              // Update Net Sales
              $('#netSales').text('₱' + response.netSales.toLocaleString());

              // Update Refunds
              $('#refunds').text('₱' + response.refunds.toLocaleString());

              // Update Total Transactions
              $('#totalTransactions').text(response.totalTransactions);

              // Update Average Transaction Value
              $('#averageTransactionValue').text('₱' + parseFloat(response.averageTransactionValue).toLocaleString());

              // Update Total Products Sold
              $('#totalProductsSold').text(response.totalProductsSold);

              // Update Actual Cash Amount
              $('#actualCashAmount').text('₱' + parseFloat(response.actualCashAmount).toLocaleString());

              let actualCashAmount = document.getElementById("actualCashAmount").innerText;
              console.log(actualCashAmount);
              let actualInput = document.getElementById("actualInput");
              if(actualCashAmount === "₱0"){
                actualInput.style.removeProperty("display");
              }else{
                console.log("here");
                actualInput.style.display = "none";
              }

              $('#totalPayments').text('₱' + response.totalSales.toLocaleString());

              // Update Cash Last 7 Days (if applicable)
              let cashLast7Days = response.cashlast7days;
              let dates = [];
              let expectedCash = [];
              let actualCash = [];

              cashLast7Days.forEach(day => {
                  dates.push(day.date);
                  expectedCash.push('₱'+day.expectedCashAmount);
                  actualCash.push(day.actualCashAmount ? '₱'+day.actualCashAmount: 0.00);
              });

              // Update the Cash Line Chart
              cashLineChart.data.labels = dates;
              cashLineChart.data.datasets[0].data = expectedCash; // Update Expected Cash Amount
              cashLineChart.data.datasets[1].data = actualCash;   // Update Actual Cash Amount
              cashLineChart.update(); // Redraw the chart
            
              // Update Payment Breakdown
              myDoughnutChart.data.datasets[0].data = [
                parseFloat(response.paymentBreakdown.Cash),
                parseFloat(response.paymentBreakdown.GCash),
                parseFloat(response.paymentBreakdown.PayMaya)
              ];
              myDoughnutChart.update();

              updateMenuSalesChart(response.menuSales7days);
              function updateMenuSalesChart(menuSalesData) {
                const dates = [...new Set(menuSalesData.map(item => item.date))].sort();

                const menuItems = [...new Set(menuSalesData.map(item => item.menu))];

                // Create a dataset for each menu item
                const datasets = menuItems.map(menu => {
                  const data = dates.map(date => {
                    const found = menuSalesData.find(item => item.menu === menu && item.date === date);
                    return found ? parseFloat(found.totalSales) : 0;
                  });

                  return {
                    label: menu,
                    data,
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                  };
                });

                // Update chart
                menuSalesChart.data.labels = dates;
                menuSalesChart.data.datasets = datasets;
                menuSalesChart.update();
              }

          },
          error: function (xhr, status, error) {
              console.error('Error fetching daily sales data:', error);
              alert('Failed to fetch daily sales data. Please try again.');
          }
      });
  }

  // Fetch data on page load
  

  // Optionally, refresh data every 1 minute
  setInterval(fetchDailySalesData, 60000); // 60000ms = 1 minute
  });