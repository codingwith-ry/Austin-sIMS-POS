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
                                    <th></th>
                                    <th>Inventory ID</th>
                                    <th>Purchase Date</th>
                                    <th>Employee Assigned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!----Test niyo kung gagana yung pag export pati print ng data lagay kayo mga data !------>
                                
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
