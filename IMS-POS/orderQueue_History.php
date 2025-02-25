<?php include 'verticalNav.php'; ?>
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
        <div class="tab-content" id="orderTabsContent">
            <div class="tab-pane fade show active" id="queue" role="tabpanel" aria-labelledby="queue-tab">
                <div class="table-container mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Queue</th>
                                <th>Order Number</th>
                                <th>Receipt Number</th>
                                <th>Product Quantity</th>
                                <th>Time</th>
                                <th>Order Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Order Row -->
                            <tr class="order-row" data-toggle="collapse" data-target="#orderDetails1" aria-expanded="false" aria-controls="orderDetails1">
                                <td>1</td>
                                <td>003467</td>
                                <td>02022391929102</td>
                                <td>6</td>
                                <td>09:49:53 AM</td>
                                <td>IN PROCESS</td>
                                <td>
                                    <button class="btn btn-success btn-sm done">
                                        <i class="fas fa-check"></i> Done
                                    </button>
                                    <button class="btn btn-danger btn-sm cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </td>
                            </tr>
                            <!-- Collapse Content -->
                            <tr class="no-border">
                                <td colspan="7" class="p-0">
                                    <?php include 'orderCollapse.php'; ?>
                                </td>
                            </tr>
                            <!-- Additional orders as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" id="searchBox" class="form-control mr-2" placeholder="Search...">
                        <i class="fi fi-rr-settings-sliders" style="border-radius: 8px; padding: 8px; background-color: #EBEBEB;"></i>
                    </div>
                </div>
                <div class="table-container mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Receipt Number</th>
                                <th>Employee ID</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- History Row -->
                            <tr class="history-row">
                                <td>1</td>
                                <td>003468</td>
                                <td>EMP001</td>
                                <td>10:00:00 AM</td>
                            </tr>
                            <!-- Additional history rows as needed -->
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
                    if (actionType === 'done') {
                        selectedOrder.find('td:nth-child(6)').text('COMPLETED');
                        selectedOrder.find('.done').prop('disabled', true);
                        selectedOrder.find('.cancel').remove();
                    } else {
                        selectedOrder.remove();
                    }

                    Swal.fire({
                        title: "Success!",
                        text: actionType === 'done' ? "The order has been marked as completed." : "The order has been canceled.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
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
