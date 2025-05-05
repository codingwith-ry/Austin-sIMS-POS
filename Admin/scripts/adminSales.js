const ctx = document.getElementById('transactionTypes').getContext('2d');
  const myDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Cash', 'GCash', 'PayMaya'],
      datasets: [{
        label: 'Payments',
        data: [300, 50, 100],
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
      labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
      datasets: [
        {
          label: 'Expected Cash Amount',
          data: [1000, 1500, 1200, 10000, 1600],
          borderColor: 'rgba(54, 162, 235, 1)',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          tension: 0.4,
        },
        {
          label: 'Actual Cash Amount',
          data: [950, 0, 0, 1700, 1650],
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
      labels: ['May 1', 'May 2', 'May 3', 'May 4', 'May 5'], // Dates or time periods
      datasets: [
        {
          label: 'Coffee Menu Sales',
          data: [1200, 1350, 1100, 1500, 1400],
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          tension: 0.4
        },
        {
          label: 'Gastro Pub Menu Sales',
          data: [2000, 2100, 1900, 2200, 2050],
          borderColor: 'rgba(255, 159, 64, 1)',
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          tension: 0.4
        },
        {
          label: 'Party Tray Menu Sales',
          data: [3000, 2800, 3200, 3100, 3300],
          borderColor: 'rgba(153, 102, 255, 1)',
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Sales Comparison by Menu Category'
        },
        legend: {
          position: 'top'
        }
      },
      interaction: {
        mode: 'index',
        intersect: false
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Sales (₱)'
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
    $('#productSalesTable').DataTable({
        ajax: 'scripts/fetchProductSales.php', // Replace with your server-side script URL
        columns: [
            { data: 'product' },       // Product Name
            { data: 'price' },         // Price
            { data: 'quantity' },      // Quantity
            { data: 'total_sales' },   // Total Sales
            { data: 'total_revenue' }  // Total Revenue
        ],
        responsive: true, // Makes the table responsive
        paging: true,     // Enables pagination
        searching: true,  // Enables search functionality
        ordering: true,   // Enables column sorting
        lengthChange: true, // Allows changing the number of rows displayed
        pageLength: 10,   // Default number of rows per page
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
});