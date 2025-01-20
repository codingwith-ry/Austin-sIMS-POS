<?php include 'verticalNav.php' ?>
<main id="mainContent">
    <div class="container title">
        <div class="row">
            <div>
                <h1>Inventory</h1>
            </div>
        </div>
        <div class="row container" style="margin-bottom: 20px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
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

            </div>
        </div>
    </div>

</main>

<?php include 'footer.php' ?>

<script>
    new DataTable('#itemRecords', {
        layout: {
            topStart: {
                pageLength: true
            },
            topEnd: {
                search: {
                    placeholder: 'Search Item'
                }
            },
            top2Start: {
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    {
                        text: 'Add Item',
                        action: function(e, dt, node, config, cb) {
                            var addItemHTML =
                                `
                            <div class="modal" id="addItemForm">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Item</h5>
                                        </div>
                                    <div class="modal-body">
                                        <form id="myForm">
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-2 control-label">Item Name</span>
                                                <div class="col-sm-10">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-2 control-label">Category</span>
                                                <div class="col-sm-10">
                                                    <select class="form-select">
                                                        <option selected>Select Category</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-2 control-label">Units(Units of Measurement)</span>
                                                <div class="col-sm-10">
                                                    <select class="form-select">
                                                        <option selected>Select unit</option>
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
                            var myModal = new bootstrap.Modal(document.getElementById('addItemForm'));
                            myModal.show();
                        }
                    }
                ]
            }
        },
    });
</script>