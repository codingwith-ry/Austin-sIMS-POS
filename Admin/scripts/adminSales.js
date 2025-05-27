
  $(document).ready(function () {
    const datePicker = document.getElementById("datePicker");
    const today = new Date();
    const formatted = today.toISOString().split('T')[0];
    datePicker.value = formatted;
    dailyData(datePicker.value);
    // Initialize DataTable
    function dailyData(date){

      console.log(date)
      fetchDailySalesData(date);

      $('#productSalesTable').DataTable().clear().destroy();
      $('#productSalesTable').DataTable({
          ajax: `scripts/fetchProductSales.php?date=${date}`, // Replace with your server-side script URL
          columns: [
            {
              data: null,
              render: function(data, type, row)
              {
                return `
                    <span class="badge text-bg-primary rounded-pill fs-6 mb-1 p-3" style="width: fit-content;">${row.menuName}</span>
                    <span class="badge text-bg-success rounded-pill fs-6 p-3" style="width: fit-content;">${row.categoryName}</span>
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

      function fetchDailySalesData(date) {
        let myDoughnutChart;
        let cashLineChart;
        let menuSalesChart;

        if (myDoughnutChart) {
          myDoughnutChart.destroy();
        }
        if (cashLineChart) {
          cashLineChart.destroy();
        }
        if (menuSalesChart) {
          menuSalesChart.destroy();
        }

        $.ajax({
            url: `scripts/fetchDailySales.php?date=${date}`, // Path to your PHP script
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
                    expectedCash.push(day.expectedCashAmount);
                    actualCash.push(day.actualCashAmount ? day.actualCashAmount: 0.00);
                });


                let ctx = document.getElementById('transactionTypes').getContext('2d');
                
                myDoughnutChart = new Chart(ctx, {
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

                let dailySales = document.getElementById('cashLineChart').getContext('2d');
                cashLineChart = new Chart(dailySales, {
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

                let menuSales = document.getElementById('menuSalesChart').getContext('2d');
                menuSalesChart = new Chart(menuSales, {
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
      function fetchMenuData(menuID, chartContext, chartInstance) {
          if (window[chartInstance]) {
            window[chartInstance].destroy();
            window[chartInstance] = null;
          }
          
          fetch(`scripts/fetchCategorySales.php?menuID=${menuID}`) // Pass menuID dynamically
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const categories = data.data.map(item => item.categoryName);
                      const totalOrders = data.data.map(item => parseFloat(item.totalOrders || 0)); // Handle null values

                      // If the chart already exists, update it
                      if (chartInstance) {
                          chartInstance.data.labels = categories;
                          chartInstance.data.datasets[0].data = totalOrders;
                          chartInstance.update();
                      } else {
                          // Create the chart if it doesn't exist
                          chartInstance = new Chart(chartContext, {
                              type: 'doughnut',
                              data: {
                                  labels: categories,
                                  datasets: [{
                                      label: 'Sales by Category (₱)',
                                      data: totalOrders,
                                      backgroundColor: [
                                          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                          '#9966FF', '#FF9F40', '#7ED6DF', '#E77F67',
                                          '#F8EFBA', '#B8E994', '#63cdda', '#778beb'
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
                                          text: 'Sales by Category'
                                      }
                                  }
                              }
                          });
                      }
                  } else {
                      console.error('Failed to fetch menu data:', data.message);
                  }
              })
              .catch(error => console.error('Error fetching menu data:', error));
      }

      // Initialize charts for Coffee Menu, Gastro Pub, and Party Tray
      const coffeeMenu = document.getElementById('coffeeMenuChart').getContext('2d');
      let coffeeMenuChart;
      //fetchMenuData(1, coffeeMenu, coffeeMenuChart);

      const gastroPubSales = document.getElementById('gastroPubChart').getContext('2d');
      let gastroPubChart;
      //fetchMenuData(2, gastroPubSales, gastroPubChart);

      const partyTraySales = document.getElementById('partyTrayChart').getContext('2d');
      let partyTrayChart;
      //fetchMenuData(3, partyTraySales, partyTrayChart);
      // Optionally, refresh data every 1 minute
    }

    datePicker.addEventListener("change", function() {
        if (this.value > formatted) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date',
                text: 'You cannot select a date beyond today.',
            });
            this.value = formatted; // Optionally reset to today
            dailyData(this.value);
            return;
        }
        dailyData(this.value);
    });
  });