<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Products</title>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles/adminProducts.css">
</head>
<body>
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <div id="adminContent">
        <h2>Products</h2>
        <hr />
        <div class="row" style="margin-bottom: 20px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 12px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#categoriesTab" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                        Products
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#productsTab" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                        Categories
                    </button>
                </li>
            </ul>
        </div>


        <div id="categoriesTab" class="d-flex flex-row flex-nowrap">
            <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding-left:15px;">
                    <span>
                        <i class="fi fi-ss-apps" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text fw-bold " >All</p> 
            </button>
            <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding-left:15px;">
                    <span>
                        <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Coffees</p>
            </button>
        </div>
    </div>
</body>

</html>


<?php include "cdnScripts.php" ?>