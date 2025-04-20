<?php
// Default to "queue"
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'queue';
?>
<?php
$page = basename($_SERVER['PHP_SELF']);
if ($page == "orderQueue_History.php") {
    echo '<link rel="stylesheet" href="styles/orderQH.css">';
}

$active = "orderQueue_History";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IMS-POS | Orders</title>
  <?php require_once('links.php'); ?>
  <link href="styles/orderCollapse.css" rel="stylesheet" />
  <link href="styles/sideBar.css" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    .btn-sm {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      text-align: center;
      width: 90px;
    }
    .btn-sm i {
      font-size: 16px;
    }
    #searchBox {
      width: 200px;
    }
    .filter-icon {
      border: 1px solid #ccc;
      border-radius: 0.25rem;
      height: 38px;
      line-height: 38px;
      padding: 0 10px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
    }
    #sortOrder {
      border: 1px solid #ccc;
      border-radius: 0.25rem;
      background-color: #fff;
      padding: 5px;
    }
    .table tbody tr {
      border-bottom: 1px solid #ddd;
    }
  </style>
</head>
<body>
  <?php include 'verticalNav.php'; ?>
  <!-- Begin Main Content -->
  <div class="main-content" id="mainContent">
    <main role="main" class="content">
      <h2>Orders</h2>
      <hr />
      <!-- Nav Tabs -->
      <ul class="nav nav-pills mb-3" id="orderTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeTab=='queue') ? 'active' : ''; ?>" id="queue-tab" data-toggle="pill" href="#queue" role="tab" aria-controls="queue" aria-selected="<?php echo ($activeTab=='queue') ? 'true' : 'false'; ?>">Queue</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeTab=='pickup') ? 'active' : ''; ?>" id="pickup-tab" data-toggle="pill" href="#pickup" role="tab" aria-controls="pickup" aria-selected="<?php echo ($activeTab=='pickup') ? 'true' : 'false'; ?>">Pick Up</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeTab=='history') ? 'active' : ''; ?>" id="history-tab" data-toggle="pill" href="#history" role="tab" aria-controls="history" aria-selected="<?php echo ($activeTab=='history') ? 'true' : 'false'; ?>">History</a>
        </li>
      </ul>
      <div class="tab-content" id="orderTabsContent">
        <!-- QUEUE TAB -->
        <div class="tab-pane fade <?php echo ($activeTab=='queue') ? 'show active' : ''; ?>" id="queue" role="tabpanel" aria-labelledby="queue-tab">
          <div class="table-container mt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Queue</th>
                  <th>Order Number</th>
                  <th>Sales Order Number</th>
                  <th>Product Quantity</th>
                  <th>Time</th>
                  <th>Order Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-pane fade <?php echo ($activeTab=='pickup') ? 'show active' : ''; ?>" id="pickup" role="tabpanel" aria-labelledby="pickup-tab">
          <div class="table-container mt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Order Number</th>
                  <th>Product Quantity</th>
                  <th>Time</th>
                  <th>Order Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
        </div>


        <div class="tab-pane fade <?php echo ($activeTab=='history') ? 'show active' : ''; ?>" id="history" role="tabpanel" aria-labelledby="history-tab">
          <!-- Search and Filter Controls -->
          <div class="d-flex align-items-center mb-3">
              <input type="text" id="searchBox" class="form-control mr-2" placeholder="Search orders..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <i id="filterToggle" class="fas fa-sliders-h filter-icon"></i>
          </div>
          <div id="filterPanel" class="mb-3" style="display: none; padding: 10px;">
              <label for="sortOrder">Sort Order:</label>
              <select id="sortOrder" class="form-control w-auto d-inline-block ml-2">
                  <option value="desc"<?php if (!isset($_GET['sort_order']) || $_GET['sort_order'] === 'desc') echo ' selected'; ?>>Newest first</option>
                  <option value="asc"<?php if (isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc') echo ' selected'; ?>>Oldest first</option>
              </select>
          </div>
          <div class="table-container mt-3">
              <table class="table table-bordered" id="historyTable">
                  <thead>
                      <tr>
                          <th>Order Number</th>
                          <th>Sales Order Number</th>
                          <th>Employee ID</th>
                          <th>Date Purchased</th>
                          <th>Order Status</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      // Load all matching history orders (used for DataTables)
                      require_once('../Login/database.php');
                      $history_orders = $conn->query("SELECT * FROM tbl_orders ORDER BY orderDate AND orderTime DESC");
                      while ($order = $history_orders->fetch(PDO::FETCH_ASSOC)) {
                          echo "<tr class='history-row' data-order-id='{$order['orderID']}' data-order-number='{$order['orderNumber']}' data-order-date='{$order['orderDate']}'>
                                  <td>{$order['orderNumber']}</td>
                                  <td>{$order['salesOrderNumber']}</td>
                                  <td>{$order['employeeID']}</td>
                                  <td>{$order['orderDate']} {$order['orderTime']}</td>
                                  <td>{$order['orderStatus']}</td>
                                </tr>";
                      }
                      ?>
                  </tbody>
              </table>
          </div>
        </div>
      </div>
    </main>
  </div>
  
  <!-- Right Sidebar Overlay for History Tab -->
  <div class="right-sidebar" id="historySidebar" style="display: none;">
    <?php include 'sideBar.php'; ?>
  </div>
  
  <!-- Unified Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="confirmationMessage"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <button type="button" class="btn btn-primary" id="confirmAction">Yes</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Include jQuery, Bootstrap, SweetAlert2, and DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  
  <!-- Custom JS -->
  <script>
    $(document).ready(function() {

      function refreshTable(orderStatus, tableId) {
        $.ajax({
            url: 'scripts/fetchOrders.php',
            type: 'GET',
            dataType: 'json',
            data: {
                orderStatus: orderStatus,
            },
            success: function (data) {
                // Clear the existing table body
                const tableBody = $(`#${tableId} tbody`);
                tableBody.empty();

                // Create an array of promises for nested AJAX calls
                
                const promises = data.map(order => {
                    return new Promise((resolve, reject) => {
                        // Fetch order items for each order
                        $.ajax({
                            url: 'scripts/fetchOrderItems.php',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                salesOrderNumber: order.salesOrderNumber,
                            },
                            success: function (orderItems) {
                                let row = `
                                    <tr class="order-row" data-toggle="collapse" data-target="#orderDetails${order.orderID}" aria-expanded="false" aria-controls="orderDetails${order.orderID}">
                                `;
                                if(tableId === 'pickup') {
                                } else {
                                    row += `<td>${order.queueNumber}</td>`;
                                }
                                row += `
                                        
                                        <td>${order.orderNumber}</td>
                                `;

                                if(tableId === 'pickup') {
                                } else {
                                    row += `<td>${order.salesOrderNumber}</td>`;
                                }

                                row += `
                                        <td>${order.productQuantity}</td>
                                        <td>${order.orderTime}</td>
                                        <td>${order.orderStatus}</td>
                                        <td class="p-3">
                                            ${orderStatus === 'IN PROCESS' || orderStatus === 'PICKUP' ? `
                                                <button class="btn btn-success" data-sales-ordernum=${order.salesOrderNumber}><i class="fas fa-check"></i> Done</button>
                                                <button class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
                                            ` : ''}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="p-0">
                                            <div class="collapse order-collapse" id="orderDetails${order.orderID}">
                                                <div class="order-details">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Order Type</th>
                                                                <th>Order Items</th>
                                                                <th>Add-Ons</th>
                                                                <th colspan='3'>Notes/Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                `;


                                let counter = 0;
                                orderItems.forEach(item => {
                                    const variationName = item.variationName ? `(${item.variationName})` : '';
                                    if (counter == 0) {
                                        row += `
                                            <tr>
                                                <td rowspan=${orderItems.length}>${order.orderClass}</td>
                                                <td style="text-align: left;">
                                                    <span class="fw-bold">${item.menuName} (${item.categoryName})</span><br>
                                                    <span>${item.productQuantity} x ${item.productName} ${variationName}</span>
                                                </td>
                                                <td colspan='2' style="text-align: left;">
                                                    <ul>
                                        `;
                                    } else {
                                        row += `
                                            <tr>
                                                <td style="text-align: left;">
                                                    <span class="fw-bold">${item.menuName} (${item.categoryName})</span><br>
                                                    <span>${item.productQuantity} x ${item.productName} ${variationName}</span>
                                                </td>
                                                <td colspan='2' style="text-align: left;">
                                                    <ul>
                                        `;
                                    }

                                    if (item.addons.length > 0) {
                                        item.addons.forEach(addon => {
                                            row += `<li>${addon}</li>`;
                                        });
                                    } else {
                                        row += `<li>No Add-Ons</li>`;
                                    }
                                    row += `
                                                    </ul>
                                                </td>
                                    `;

                                    counter == 0 ? row += `
                                                <td colspan='2' rowspan=${orderItems.length}>${order.additionalNotes || 'No Notes'}</td>
                                            </tr>
                                    ` : '';

                                    row += `
                                            </tr>
                                    `;

                                    counter++;
                                });

                                row += `
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                resolve(row); // Resolve the promise with the generated row
                            },
                            error: function () {
                                console.error(`Failed to fetch order items for orderNumber: ${order.orderNumber}`);
                                reject(); // Reject the promise on error
                            }
                        });
                    });
                });

                // Wait for all promises to complete
                Promise.all(promises).then(rows => {
                    // Append all rows to the table in the correct order
                    rows.forEach(row => {
                        tableBody.append(row);
                    });
                }).catch(() => {
                    console.error('Failed to fetch some order items.');
                });
            },
            error: function () {
                console.error('Failed to fetch orders.');
            }
        });
      }


      setInterval(() => refreshTable('IN PROCESS', 'QUEUE'), 10000); // Refresh every 10 seconds
      refreshTable('IN PROCESS', 'QUEUE');

      setInterval(() => refreshTable('PICKUP', 'pickup'), 10000); // Refresh every 10 seconds
      refreshTable('PICKUP', 'pickup');

      let selectedOrder;
      let actionType = "";
  
      // Initialization for DataTables in History Tab (e.g. search, ordering, and pagination)
      var historyTable = $("#historyTable").DataTable({
        pageLength: 7,
        lengthChange: false,
        dom: "lrtip",  // Hides the built-in search bar of DataTables
        pagingType: "simple",  
        order: [[3, "desc"]],
        language: {
          paginate: {
            previous: "Previous",
            next: "Next"
          }
        }
      });
  
      // Tie custom search to DataTables API
        $("#searchBox").on("keyup", function() {
          historyTable.search(this.value).draw();
        });
    
        // Toggle filter panel
        $("#filterToggle").on("click", function() {
          $("#filterPanel").toggle();
        });
    
        // Re-order DataTables when sort order changes
        $("#sortOrder").on("change", function() {
          var orderVal = $(this).val();
          historyTable.order([3, orderVal]).draw();
        });
    
        // Manages click event on any history row to load sidebar content dynamically
        $(document).on("click", ".history-row", function() {
          let orderID = $(this).data("order-id");
          if (orderID) {
            $.ajax({
              url: "sideBar.php",
              type: "GET",
              data: { orderID: orderID },
              success: function(response) {
                $("#historySidebar").html(response).show();
              }
            });
          }
        });
  
      // Hide sidebar when clicking outside
      $(document).mouseup(function(e) {
        var container = $("#historySidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
          container.hide();
        }
      });
  
      // Handle Done/Cancel button clicks with confirmation
      // Handle Done/Cancel button clicks with SweetAlert
      $(document).on('click', '.btn-success, .btn-danger', function () {
          event.stopPropagation();

          
          const isDone = $(this).hasClass('btn-success'); // Check if the button is "Done"
          const orderRow = $(this).closest('tr'); // Get the row of the clicked button
          const orderNum = orderRow.find('td:eq(0)').text(); // Get the Order ID from the first column
          const salesOrderNum = $(this).data('sales-ordernum'); // Get the Sales Order Number from the button data attribute 
          const currentTab = $('#orderTabs .nav-link.active').attr('id').replace('-tab', ''); // Get the current tab (queue or pickup)
          
          const status = isDone
              ? (currentTab === 'pickup' ? 'DONE' : 'PICKUP') // "DONE" for pickup, "PICKUP" for queue
              : 'CANCELLED';

          console.log(status);

          // SweetAlert confirmation dialog
          Swal.fire({
              title: isDone ? 'Mark as Done?' : 'Cancel Order?',
              text: isDone
                  ? `Are you sure you want to mark Order #${orderNum} as ${status}?`
                  : `Are you sure you want to cancel Order #${orderNum}?`,
              icon: isDone ? 'success' : 'warning',
              showCancelButton: true,
              confirmButtonColor: isDone ? '#28a745' : '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: isDone ? 'Yes' : 'No',
          }).then((result) => {
              if (result.isConfirmed) {
                  // Send AJAX request to update the order status
                  $.ajax({
                      url: 'scripts/updateOrder.php', // PHP script to handle the update
                      type: 'POST',
                      data: {
                          salesOrderNum: salesOrderNum,
                          status: status,
                      },
                      success: function (response) {
                          response = JSON.parse(response); // Parse the JSON response
                          if (response.success) {
                              // Show success message
                              Swal.fire({
                                  title: 'Success!',
                                  text: response.message,
                                  icon: 'success',
                                  timer: 2000,
                                  showConfirmButton: false,
                              });

                              // Refresh the appropriate table
                              refreshTable('PICKUP', 'pickup');
                              refreshTable('IN PROCESS', 'queue');
                          } else {
                              Swal.fire({
                                  title: 'Error!',
                                  text: response.message,
                                  icon: 'error',
                              });
                          }
                      },
                      error: function () {
                          Swal.fire({
                              title: 'Error!',
                              text: 'An error occurred while updating the order status.',
                              icon: 'error',
                          });
                      },
                  });
              }
          });
      });
      
  
      // Toggle collapse for order rows on click
      $(document).on('click', '.order-row', function (event) {
        // Check if the click target is NOT a button
        if (!$(event.target).closest('.btn-success, .btn-danger').length) {
            var collapseElement = $(this).next().find('.collapse');
            collapseElement.collapse('toggle');
        }
      });
  
      // Hide sidebar when switching to Queue or Pick Up tabs
      $("#queue-tab, #pickup-tab").on("click", function() {
        $("#historySidebar").hide();
      });




    });
  </script>
</body>
</html>