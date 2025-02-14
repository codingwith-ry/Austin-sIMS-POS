<?php include 'verticalNav.php'; ?>
<!-- Begin main content -->
<div class="main-content">
    <main role="main" class="content">
        <h1>Orders</h1>
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="queue-tab" data-toggle="tab" href="#queue" role="tab" aria-controls="queue" aria-selected="true">Queue</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
            </li>
        </ul>
        <div class="tab-content" id="orderTabsContent">
            <div class="tab-pane fade show active" id="queue" role="tabpanel" aria-labelledby="queue-tab">
                <div class="table-container mt-3">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th class="queue-header">Queue</th>
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
                                <td class="queue-number">1</td>
                                <td>003467</td>
                                <td>02022391929102</td>
                                <td>6</td>
                                <td>09:49:53 AM</td>
                                <td>IN PROCESS</td>
                                <td>
                                    <button class="btn btn-success btn-sm done">
                                        <img src="images/check.png" alt="Done"> Done
                                    </button>
                                    <button class="btn btn-danger btn-sm cancel">
                                        <img src="images/remove.png" alt="Cancel"> Cancel
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
                    <table class="table order-history-table">
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
                                <td class="history-number">1</td>
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

<!-- Include jQuery and Bootstrap JS just before closing body tag -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<!-- Custom JS -->
<script>
    $(document).ready(function() {
        $('.order-row').hover(function() {
            $(this).toggleClass('highlight');
        });

        $('.done, .cancel').click(function(e) {
            e.stopPropagation();
        });

        $('.order-row').on('click', function() {
            var collapseElement = $(this).next().find('.collapse');
            collapseElement.collapse('toggle');
        });

        $('.history-row').on('click', function() {
            $('#popupSidebar').show();
        });

        $(document).on('change', '.item-checkbox', function() {
            if(this.checked) {
                $(this).parent().addClass('completed');
            } else {
                $(this).parent().removeClass('completed');
            }
        });

        // Initialize DataTable without "Show entries" and pagination
        $('.order-history-table').DataTable({
            "dom": 'rt',
            "paging": true
        });

        // Custom Search Functionality
        $('#searchBox').on('keyup', function() {
            var table = $('.order-history-table').DataTable();
            table.search(this.value).draw();
        });
    });
</script>
