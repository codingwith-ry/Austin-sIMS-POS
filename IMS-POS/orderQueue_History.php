<?php
$page = basename($_SERVER['PHP_SELF']);
if ($page == "orderQueue_History.php") {
    echo '<link rel="stylesheet" href="styles/orderQH.css">';
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Orders</title>
    <?php require_once('links.php')?>
    <link href="styles/orderCollapse.css" rel="stylesheet">
    <link href="styles/popupSidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .btn-sm {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px; /* Adjust spacing between icon and text */
            text-align: center;
            width: 90px; /* Adjust width as needed */
        }

        .btn-sm i {
            font-size: 16px; /* Adjust icon size */
        }

        .popup-sidebar {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            right: 0;
            width: 300px; /* Adjust width as needed */
            height: 100%;
            background-color: #fff;
            box-shadow: -2px 0 5px rgba(0,0,0,0.5);
            z-index: 1050; /* Ensure it is above other elements */
            overflow-y: auto;
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php include 'verticalNav.php'; ?>
    <!-- Begin main content -->
    <div class="main-content">
        <main role="main" class="content">
            <h2>Orders</h2><hr />
            <ul class="nav nav-pills mb-3" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="queue-tab" data-toggle="pill" href="#queue" role="tab" aria-controls="queue" aria-selected="true">Queue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="pill" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <!-- Queue Tab -->
            <div class="tab-content" id="orderTabsContent">
                <div class="tab-pane fade show active" id="queue" role="tabpanel" aria-labelledby="queue-tab">
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
                                // Database connection
                                    require_once('../Login/database.php');

                                // Fetch orders and their product quantities from the database
                                    $queue_orders = $conn->query("
                                        SELECT tbl_orders.*, tbl_orderitems.productQuantity 
                                        FROM tbl_orders 
                                        LEFT JOIN tbl_orderitems ON tbl_orders.orderID = tbl_orderitems.orderID 
                                        WHERE tbl_orders.orderStatus='IN PROCESS' 
                                        ORDER BY tbl_orders.orderTime ASC
                                        ");

                                    while ($order = $queue_orders->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr class='order-row' data-toggle='collapse' data-target='#orderDetails{$order['orderID']}' aria-expanded='false' aria-controls='orderDetails{$order['orderID']}'>
                                        <td>{$order['orderID']}</td>
                                        <td>{$order['orderNumber']}</td>
                                        <td>{$order['salesOrderNumber']}</td>
                                        <td>{$order['productQuantity']}</td>
                                        <td>{$order['orderTime']}</td>
                                        <td>IN PROCESS</td>
                                        <td>
                                            <button class='btn btn-success btn-sm done'>
                                            <i class='fas fa-check'></i> Done
                                        </button>
                                            <button class='btn btn-danger btn-sm cancel'>
                                            <i class='fas fa-times'></i> Cancel
                                        </button>
                                        </td>
                                            </tr>
                                            <!-- Collapse Content -->
                                            <tr class='no-border'>
                                                <td colspan='7' class='p-0'>
                                                <?php include 'orderCollapse.php'; ?>
                                        </td>
                                        </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <input type="text" id="searchBox" class="form-control mr-2" placeholder="Search...">
                            <i class="fi fi-rr-settings-sliders custom-icon" style="border-radius: 8px; padding: 8px; background-color: #EBEBEB;"></i>
                        </div>
                    </div>
                    <div class="table-container mt-3">
                        <table class="table table-bordered">
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
            // Fetch order history from database
            $history_orders = $conn->query("SELECT * FROM tbl_orders WHERE orderStatus='DONE' OR orderStatus='CANCELLED' ORDER BY orderDate DESC");

            while($order = $history_orders->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr class='history-row'>
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

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center mt-3">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
             </nav>
            <!-- Popup Sidebar Section -->
            <div id="popupSidebar" class="popup-sidebar">
                <?php include 'popupSidebar.php'; ?>
            </div>
        </div>
    </div>
</main>
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
                    <button type="button" class="btn" id="confirmAction">Yes</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Include jQuery, Bootstrap, and SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- Custom JS -->
<script>
$(document).ready(function() {
    let selectedOrder;
    let actionType = '';

    $('.order-row').hover(function() {
        $(this).toggleClass('highlight');
    });

    $('.done, .cancel').click(function(e) {
        e.stopPropagation();
        selectedOrder = $(this).closest('tr');
        actionType = $(this).hasClass('done') ? 'done' : 'cancel';

        Swal.fire({
            title: actionType === 'done' ? "Mark as Done?" : "Cancel Order?",
            text: actionType === 'done' 
                ? "Are you sure you want to mark this order as completed?" 
                : "Are you sure you want to cancel this order? This action cannot be undone.",
            icon: actionType === 'done' ? "success" : "warning",
            showCancelButton: true,
            confirmButtonText: actionType === 'done' ? "Yes, mark as done" : "Yes, cancel order",
            cancelButtonText: "No, keep it",
            confirmButtonColor: actionType === 'done' ? "#28a745" : "#dc3545",
            cancelButtonColor: "#6c757d"
        }).then((result) => {
            if (result.isConfirmed) {
                const orderID = selectedOrder.find('td:first').text();
                const orderStatus = actionType === 'done' ? 'DONE' : 'CANCELLED';

                $.ajax({
                    url: 'updateOrder.php',
                    type: 'POST',
                    data: {
                        orderID: orderID,
                        orderStatus: orderStatus
                    },
                    success: function(response) {
                        console.log("AJAX Response: ", response); 
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                // Remove the order from the queue table
                                selectedOrder.remove();

                                // Add the updated order details to the history table
                                const orderDetails = res.orderDetails;
                                const historyTable = $('#history').find('tbody');
                                const newRow = `<tr class='history-row'>
                                                    <td>${orderDetails.orderNumber}</td>
                                                    <td>${orderDetails.salesOrderNumber}</td>
                                                    <td>${orderDetails.employeeID}</td>
                                                    <td>${orderDetails.orderDate} ${orderDetails.orderTime}</td>
                                                    <td>${orderDetails.orderStatus}</td>
                                                </tr>`;
                                historyTable.append(newRow);

                                Swal.fire({
                                    title: "Success!",
                                    text: `The order has been ${orderStatus.toLowerCase()}.`,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: res.error,
                                    icon: "error",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        } catch (e) {
                            console.error("Parsing error:", e);
                            Swal.fire({
                                title: "Error!",
                                text: "Response parsing failed.",
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "There was an issue with the request.",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    $('.order-row').on('click', function() {
        var collapseElement = $(this).next().find('.collapse');
        collapseElement.collapse('toggle');
    });

    $('.history-row').on('click', function() {
        $('#popupSidebar').show();
    });

    // Close the sidebar when clicking outside of it
    $(document).mouseup(function(e) {
        var container = $("#popupSidebar");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    $(document).on('change', '.item-checkbox', function() {
        if(this.checked) {
            $(this).parent().addClass('completed');
        } else {
            $(this).parent().removeClass('completed');
        }
    });

    $('.order-history-table').DataTable({
        "dom": 'rt',
        "paging": true
    });

    $('#searchBox').on('keyup', function() {
        var table = $('.order-history-table').DataTable();
        table.search(this.value).draw();
    });
});


</script>
</html>
