<?php include 'verticalNav.php' ?>
<main>
<div class="container title">
    <div class="row">
        <div><h1>Inventory</h1></div>
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
        top2Start: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
});
</script>