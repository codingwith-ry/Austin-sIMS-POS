<?php
// Default to "queue"
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'queue';
?>
<?php
$page = basename($_SERVER['PHP_SELF']);
if ($page == "orderQueue_History.php") {
    echo '<link rel="stylesheet" href="styles/orderQH.css">';
}
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
                <?php
                  require_once('../Login/database.php');
                  $queue_orders = $conn->query("
                    SELECT 
                    tbl_orders.orderID,
                    tbl_orders.orderNumber, 
                    tbl_orders.salesOrderNumber, 
                    tbl_orders.orderTime, 
                    tbl_orders.orderClass, 
                    tbl_orders.orderStatus,
                    tbl_orders.additionalNotes, 
                    COALESCE(SUM(tbl_orderItems.productQuantity), 0) AS productQuantity
                    FROM tbl_orders
                    LEFT JOIN tbl_orderItems ON tbl_orders.orderNumber = tbl_orderItems.orderNumber
                    GROUP BY 
                    tbl_orders.orderNumber, 
                    tbl_orders.salesOrderNumber, 
                    tbl_orders.orderTime, 
                    tbl_orders.orderClass, 
                    tbl_orders.orderStatus
                    HAVING tbl_orders.orderStatus = 'IN PROCESS';
                  ");
                  while ($order = $queue_orders->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='order-row' data-toggle='collapse' data-target='#orderDetails{$order['orderID']}' aria-expanded='false' aria-controls='orderDetails{$order['orderID']}'>
                            <td>{$order['orderID']}</td>
                            <td>{$order['orderNumber']}</td>
                            <td>{$order['salesOrderNumber']}</td>
                            <td>{$order['productQuantity']}</td>
                            <td>{$order['orderTime']}</td>
                            <td>IN PROCESS</td>
                            <td class='p-3'>
                              <button class='btn btn-success'><i class='fas fa-check'></i> Done</button>
                              <button class='btn btn-danger'><i class='fas fa-times'></i> Cancel</button>
                            </td>
                          </tr>";
                    $orderCollapse = $conn->query("
                      SELECT
                      tbl_orderitems.orderItemID,
                      tbl_orderitems.orderNumber,
                      tbl_menuclass.menuName,
                      tbl_categories.categoryName,
                      tbl_variations.variationName,
                      tbl_menu.productName,
                      tbl_orderitems.productQuantity
                      FROM tbl_orderitems
                      LEFT JOIN tbl_variations ON tbl_orderitems.variationID = tbl_variations.variationID
                      LEFT JOIN tbl_menu ON tbl_orderitems.productID = tbl_menu.productID
                      LEFT JOIN tbl_menuClass ON tbl_menu.menuID = tbl_menuClass.menuID
                      LEFT JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID
                      WHERE tbl_orderitems.orderNumber = '{$order['orderNumber']}';
                    ");
                    // Collapsible Order Details
                    // This is a placeholder for the order details. You can replace it with actual data.
                    $rowCount = $orderCollapse->rowCount();

                    echo "
                    <tr>
                      <td colspan='8' class='p-0'>
                        <div class='collapse order-collapse' id='orderDetails{$order['orderID']}'>
                          <div class='order-details'>
                              <!-- Table for Order Details -->
                              <table id='collapsible' class='border-bottom'>
                                  <tr>
                                    <thead>
                                      <th>Order Type</th>
                                      <th>Order Items</th>
                                      <th>Add-Ons</th>
                                      <th colspan='3'>Notes/Remarks</th>
                                    </thead>
                                  </tr>

                                  ";
                                    $counter = 0;
                                      while($orderDetails = $orderCollapse->fetch(PDO::FETCH_ASSOC)) {
                                      $variation = isset($orderDetails['variationName']) ? '('.$orderDetails['variationName'].')' :"";
                                      
                                      if($counter == 0) {
                                        echo"
                                        <tr>
                                          <!-- Order Type -->
                                          <td id='orderType' rowspan='{$rowCount}'>
                                          <span>{$order['orderClass']}</span>
                                          </td>
                                          
                                            <td class='' colspan='1'>
                                                <span class='fw-bold'>{$orderDetails['menuName']}({$orderDetails['categoryName']})</span>
                                                <br />
                                                <span>{$orderDetails['productQuantity']} x {$orderDetails['productName']}{$variation}</span>
                                            </td>

                                            <!-- Add-Ons -->
                                            <td colspan='2'>
                                              <br />
                                              <ul>
                                        ";
                                        $orderAdd = $conn->query("
                                          SELECT
                                          tbl_addons.addonName
                                          FROM tbl_orderaddons
                                          LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
                                          WHERE tbl_orderaddons.orderItemID = '{$orderDetails['orderItemID']}';
                                        ");

                                        if($orderAdd->rowCount() > 0) {
                                          while ($addon = $orderAdd->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<li>+{$addon['addonName']}</li>";
                                          }
                                        } else {
                                          echo "<li>No Add-Ons</li>";
                                        }

                                      

                                        echo "
                                              </ul>
                                            </td>
                                            <!-- Notes/Remarks -->
                                            <td id='remarks' class='text-break' colspan='2' rowspan='{$rowCount}'>
                                                <span>{$order['additionalNotes']}</span>
                                            </td>
                                        </tr>
                                      ";
                                      } else {
                                          echo "
                                          <tr>
                                            <td class='' colspan='1'>
                                                <span class='fw-bold'>{$orderDetails['menuName']}({$orderDetails['categoryName']})</span>
                                                <br />
                                                <span>{$orderDetails['productQuantity']} x {$orderDetails['productName']}{$variation}</span>
                                            </td>

                                            <!-- Add-Ons -->
                                            <td colspan='2'>
                                              <br />
                                              <ul>
                                        ";
                                        $orderAdd = $conn->query("
                                          SELECT
                                          tbl_addons.addonName
                                          FROM tbl_orderaddons
                                          LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
                                          WHERE tbl_orderaddons.orderItemID = '{$orderDetails['orderItemID']}';
                                        ");

                                        if($orderAdd->rowCount() > 0) {
                                          while ($addon = $orderAdd->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<li>+{$addon['addonName']}</li>";
                                          }
                                        } else {
                                          echo "<li>No Add-Ons</li>";
                                        }
                                        echo"
                                              </ul>
                                            </td>
                                          </tr>
                                          ";
                                      }
                                      $counter++;
                                      }
                                      echo"
                              </table>
                        </div>
                      </div>
                    </td>
                  </tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
  
        <div class="tab-pane fade <?php echo ($activeTab=='pickup') ? 'show active' : ''; ?>" id="pickup" role="tabpanel" aria-labelledby="pickup-tab">
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
                <?php
                  $pickup_orders = $conn->query("
                    SELECT 
                    tbl_orders.orderID,
                    tbl_orders.orderNumber, 
                    tbl_orders.salesOrderNumber, 
                    tbl_orders.orderTime, 
                    tbl_orders.orderClass, 
                    tbl_orders.orderStatus,
                    tbl_orders.additionalNotes, 
                    COALESCE(SUM(tbl_orderItems.productQuantity), 0) AS productQuantity
                    FROM tbl_orders
                    LEFT JOIN tbl_orderItems ON tbl_orders.orderNumber = tbl_orderItems.orderNumber
                    WHERE tbl_orders.orderStatus = 'pickup'
                    GROUP BY 
                    tbl_orders.orderNumber, 
                    tbl_orders.salesOrderNumber, 
                    tbl_orders.orderTime, 
                    tbl_orders.orderClass, 
                    tbl_orders.orderStatus
                    HAVING tbl_orders.orderStatus = 'PICKUP';
                  ");
                  while ($order = $pickup_orders->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='order-row' data-toggle='collapse' data-target='#pickupDetails{$order['orderID']}' aria-expanded='false' aria-controls='pickupDetails{$order['orderID']}'>
                            <td>{$order['orderID']}</td>
                            <td>{$order['orderNumber']}</td>
                            <td>{$order['salesOrderNumber']}</td>
                            <td>{$order['productQuantity']}</td>
                            <td>{$order['orderTime']}</td>
                            <td>IN PROCESS</td>
                            <td class='p-3'>
                              <button class='btn btn-success'><i class='fas fa-check'></i> Done</button>
                              <button class='btn btn-danger'><i class='fas fa-times'></i> Cancel</button>
                            </td>
                          </tr>";
                    $pickupCollapse = $conn->query("
                      SELECT
                      tbl_orderitems.orderItemID,
                      tbl_orderitems.orderNumber,
                      tbl_menuclass.menuName,
                      tbl_categories.categoryName,
                      tbl_variations.variationName,
                      tbl_menu.productName,
                      tbl_orderitems.productQuantity
                      FROM tbl_orderitems
                      LEFT JOIN tbl_variations ON tbl_orderitems.variationID = tbl_variations.variationID
                      LEFT JOIN tbl_menu ON tbl_orderitems.productID = tbl_menu.productID
                      LEFT JOIN tbl_menuClass ON tbl_menu.menuID = tbl_menuClass.menuID
                      LEFT JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID
                      WHERE tbl_orderitems.orderNumber = '{$order['orderNumber']}';
                    ");
                    $rowCount = $pickupCollapse->rowCount();

                    echo "
                    <tr>
                      <td colspan='8' class='p-0'>
                        <div class='collapse order-collapse' id='pickupDetails{$order['orderID']}'>
                          <div class='order-details'>
                              <table id='collapsible' class='border-bottom'>
                                  <tr>
                                    <thead>
                                      <th>Order Type</th>
                                      <th>Order Items</th>
                                      <th>Add-Ons</th>
                                      <th colspan='3'>Notes/Remarks</th>
                                    </thead>
                                  </tr>
                                  ";
                                    $counter = 0;
                                    while($orderDetails = $pickupCollapse->fetch(PDO::FETCH_ASSOC)) {
                                      $variation = isset($orderDetails['variationName']) ? '('.$orderDetails['variationName'].')' : "";
                                      if($counter == 0) {
                                        echo "
                                        <tr>
                                          <td id='orderType' rowspan='{$rowCount}'>
                                            <span>{$order['orderClass']}</span>
                                          </td>
                                          <td colspan='1'>
                                            <span class='fw-bold'>{$orderDetails['menuName']}({$orderDetails['categoryName']})</span>
                                            <br />
                                            <span>{$orderDetails['productQuantity']} x {$orderDetails['productName']}{$variation}</span>
                                          </td>
                                          <td colspan='2'>
                                            <br />
                                            <ul>
                                        ";
                                        $orderAdd = $conn->query("
                                          SELECT
                                          tbl_addons.addonName
                                          FROM tbl_orderaddons
                                          LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
                                          WHERE tbl_orderaddons.orderItemID = '{$orderDetails['orderItemID']}';
                                        ");
                                        if($orderAdd->rowCount() > 0) {
                                          while ($addon = $orderAdd->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<li>+{$addon['addonName']}</li>";
                                          }
                                        } else {
                                          echo "<li>No Add-Ons</li>";
                                        }
                                        echo "
                                            </ul>
                                          </td>
                                          <td id='remarks' class='text-break' colspan='2' rowspan='{$rowCount}'>
                                            <span>{$order['additionalNotes']}</span>
                                          </td>
                                        </tr>
                                        ";
                                      } else {
                                        echo "
                                        <tr>
                                          <td colspan='1'>
                                            <span class='fw-bold'>{$orderDetails['menuName']}({$orderDetails['categoryName']})</span>
                                            <br />
                                            <span>{$orderDetails['productQuantity']} x {$orderDetails['productName']}{$variation}</span>
                                          </td>
                                          <td colspan='2'>
                                            <br />
                                            <ul>
                                        ";
                                        $orderAdd = $conn->query("
                                          SELECT
                                          tbl_addons.addonName
                                          FROM tbl_orderaddons
                                          LEFT JOIN tbl_addons ON tbl_orderaddons.addonID = tbl_addons.addonID
                                          WHERE tbl_orderaddons.orderItemID = '{$orderDetails['orderItemID']}';
                                        ");
                                        if($orderAdd->rowCount() > 0) {
                                          while ($addon = $orderAdd->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<li>+{$addon['addonName']}</li>";
                                          }
                                        } else {
                                          echo "<li>No Add-Ons</li>";
                                        }
                                        echo "
                                            </ul>
                                          </td>
                                        </tr>
                                        ";
                                      }
                                      $counter++;
                                    }
                                    echo "
                              </table>
                          </div>
                        </div>
                      </td>
                    </tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- HISTORY TAB -->
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
                  $history_orders = $conn->query("SELECT * FROM tbl_orders WHERE orderStatus IN ('DONE', 'CANCELLED') ORDER BY orderDate DESC");
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
      function refreshTable() {
          $.ajax({
              url: 'scripts/fetchOrders.php',
              type: 'GET',
              dataType: 'json',
              data: {
                orderStatus: 'IN PROCESS',
              },
              success: function (data) {
                  // Clear the existing table body
                  const tableBody = $('#queue tbody');
                  tableBody.empty();

                  // Populate the table with the updated data
                  data.forEach(order => {
                      const row = `
                          <tr class="order-row" data-toggle="collapse" data-target="#orderDetails${order.orderID}" aria-expanded="false" aria-controls="orderDetails${order.orderID}">
                              <td>${order.orderID}</td>
                              <td>${order.orderNumber}</td>
                              <td>${order.salesOrderNumber}</td>
                              <td>${order.productQuantity}</td>
                              <td>${order.orderTime}</td>
                              <td>${order.orderStatus}</td>
                              <td class="p-3">
                                  ${order.orderStatus === 'IN PROCESS' ? `
                                      <button class="btn btn-success"><i class="fas fa-check"></i> Done</button>
                                      <button class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
                                  ` : ''}
                              </td>
                          </tr>
                      `;
                      tableBody.append(row);
                  });
              },
              error: function () {
                  console.error('Failed to fetch orders.');
              }
          });
      }

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
          const orderNum = orderRow.find('td:eq(1)').text(); // Get the Order ID from the first column

          // SweetAlert confirmation dialog
          Swal.fire({
              title: isDone ? 'Mark as Done?' : 'Cancel Order?',
              text: isDone
              ? `Are you sure you want to mark Order #${orderNum} to be for pickup?`
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
                          orderNum: orderNum,
                          status: 'PICKUP',
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

                              // Optionally, remove the row or update the status in the table
                              // Refresh the table to reflect the changes
                              refreshTable();
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
              }else {
                  // User clicked "No" or closed the modal
                
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