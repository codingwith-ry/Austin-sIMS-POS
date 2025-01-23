<?php include 'verticalNav.php' ?>
<main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
    <div class="title">
        <div class="row">
            <div>
                <h1>Inventory</h1>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 12px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#Records-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                        Records
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Items-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                        Items
                    </button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="Records-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <table id="itemRecords" class="display nowrap">
                            <thead>
                                <tr>
                                    <th>Inventory Name</th>
                                    <th>Purchase Date</th>
                                    <th>Employee Assigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!----Test niyo kung gagana yung pag export pati print ng data lagay kayo mga data !------>
                                <tr>
                                    <td>Inventory Gagu</td>
                                    <td>Ba malay ko Kailan to na purchase</td>
                                    <td>Wala employee</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="Items-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <div class="card">
                    <div class="card-body">
                        <table id="inventoryItems" class="display nowrap">
                            <thead>
                                <tr>
                                    <th>Inventory Name</th>
                                    <th>Item Quantity</th>
                                    <th>Purchase Date</th>
                                    <th>Employee Assigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Condense</td>
                                    <td>20</td>
                                    <td>5/22/2024</td>
                                    <td>Dominic Adino</td>
                                </tr>
                                <tr>
                                    <td>Egg</td>
                                    <td>25</td>
                                    <td>11/12/2024</td>
                                    <td>Owen Trinidad</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include 'footer.php' ?>

<script>
    /***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
    new DataTable('#itemRecords', {
        responsive: true,
        layout: {
            topEnd: {
                search: {
                    placeholder: 'Search Item'
                }
            },
            topStart:{
                buttons:[
                    
                    'excel',
                    'print',
                    {
                        text: 'Add Item',
                        action: function(e, dt, node, config, cb) {
                            var addItemHTML =
                                `
                            <div class="modal" id="addItemForm">
                                <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Item</h5>
                                        </div>
                                    <div class="modal-body">
                                        <form id="myForm">
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Item Name</span>
                                                <div class="col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Category</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select Category</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Units</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select unit of measurement</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Unit Sales Price</span>
                                                <div class="col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Purchase Date</span>
                                                <div class="flatpickr col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Select Date" data-input class="dateInputField">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Expiration Date</span>
                                                <div class="flatpickr col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Select Date" data-input class="dateInputField">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Employee Assigned</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select Employee</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            `;
                            $('body').append(addItemHTML);

                            flatpickr(".flatpickr", {
                                enableTime: false,
                                dateFormat: "Y-M-D",
                            });

                            var myModal = new bootstrap.Modal(document.getElementById('addItemForm'));
                            myModal.show();
                        }
                    }
                ]
            },
        },
    });
    /***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
    
    /***************************INITIALIZATION OF INVENTORY ITEMS TABLE********************************/
    new DataTable('#inventoryItems', {
        responsive: true,
        layout:{
            topStart: null,
        }
    });
</script>