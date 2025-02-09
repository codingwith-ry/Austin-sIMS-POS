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
                <!-- History content -->
            </div>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

        $(document).on('change', '.item-checkbox', function() {
            if(this.checked) {
                $(this).parent().addClass('completed');
            } else {
                $(this).parent().removeClass('completed');
            }
        });
    });
</script>
