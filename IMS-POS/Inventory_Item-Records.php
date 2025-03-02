<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Inventory</title>
    <?php require_once('links.php'); ?>
</head>

<body>
<?php include 'verticalNav.php' ?>
<main id="mainContent" style="padding-left: 12px; padding-right: 12px ;">
    <div class="title">
        <div class="row">
            <div>
                <h2>Inventory</h2>
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
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" id="select-all" type="checkbox">
                                        </div>
                                    </th>
                                    <th>Inventory ID</th>
                                    <th>Purchase Date</th>
                                    <th>Employee Assigned</th>
                                    <th></th>
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
                <strong style="font-size: 25px">Categories</strong>
                <div class="row">
                    <div class="col">
                        <div class="mt-3">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">
                                            <i class="bi-search"></i></span> <input type="text" class="form-control" placeholder="Search product here" aria-describedby="addon-wrapping">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-secondary" type="button">
                                        <i class="fi fi-rr-settings-sliders"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <br />
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="coffeeMenuTab" data-bs-toggle="pill" data-bs-target="#coffeeMenu" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Coffee Menu</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="gastropubMenu" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Gastro Pub Menu</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="partytrayMenu" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Party Tray Menu</button>
                            </li>
                            <li class="nav-item ms-auto" role="presentation">
                                <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item
						            <i 
						            class="fi fi-rr-add " style="vertical-align: middle; font-size: 18px"></i>
					            </button>
                            </li>
                        </ul>
                        
                        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5 fw-bold" id="addItemLabel">Add Item</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <!-- Category -->
                                            <div class="mb-3">
                                                <label for="itemCategory" class="form-label fw-bold" style="font-size: 18px;">Category</label>
                                                <select class="form-select" id="itemCategory">
                                                    <option selected>Select Category</option>
                                                    <option value="food">Food</option>
                                                    <option value="beverage">Beverage</option>
                                                    <option value="dessert">Dessert</option>
                                                </select>
                                            </div>

                                            <!-- Item Name -->
                                            <div class="mb-3">
                                                <label for="itemNameInput" class="form-label fw-bold" style="font-size: 18px;">Item Name</label>
                                                <input type="text" class="form-control" id="itemNameInput" placeholder="ex. Nachos">
                                            </div>

                                            <!-- Unit of Measurement -->
                                            <div class="mb-3">
                                                <label for="unitMeasurement" class="form-label fw-bold" style="font-size: 18px;">Unit of Measurement</label>
                                                <select class="form-select" id="unitMeasurement">
                                                    <option selected>Select Unit</option>
                                                    <option value="piece">Piece</option>
                                                    <option value="kg">Kilogram</option>
                                                    <option value="liter">Liter</option>
                                                </select>
                                            </div>

                                            <!-- Item Quantity -->
                                            <div class="mb-3">
                                                <label for="itemQuantityInput" class="form-label fw-bold" style="font-size: 18px;">Item Quantity</label>
                                                <input type="number" class="form-control" id="itemQuantityInput" placeholder="Enter quantity">
                                            </div>

                                            <!-- Supplier -->
                                            <div class="mb-3">
                                                <label for="supplierInput" class="form-label fw-bold" style="font-size: 18px;">Supplier</label>
                                                <input type="text" class="form-control" id="supplierInput" placeholder="Enter supplier name">
                                            </div>

                                            <hr />

                                            <!-- Item Picture -->
                                            <div class="row">
                                                <div class="col-6">
                                                    <span class="fw-bold" style="font-size: 18px;">Item Picture</span>
                                                    <img src="resources/nachos.jpg" class="card-img-top img-fluid rounded-start rounded-end mt-2 mb-2" id="itemImagePreview" alt="...">
                                                </div>
                                            <div class="col-12">
                                                <input class="form-control ps-2 pe-2" type="file" id="itemImageUpload">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Add Item</button>
                                </div>
                            </div>
                        </div>
                    </div>


                        <br />
                        <div class="">
                            <div class="d-flex flex-row flex-nowrap overflow-x-scroll custom-scrollbar">
                                <button class="btn btn-primary flex-shrink-0 align-baseline me-3" style="width: 12rem; text-align: left; padding-left:15px;">
                                    <span>
                                        <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                    </span>
                                    <br />
                                    <br />
                                    <p class="card-text fw-bold ">All</p>
                                </button>
                                <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3" style="width: 12rem; text-align: left; padding-left:15px;">
                                    <span>
                                        <i class="fi fi-rr-milk-alt" id="categoryIcon"></i>
                                    </span>
                                    <br />
                                    <br />
                                    <p class="card-text">Dairy Products</p>
                                </button>

                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-drumstick" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Meat & Poultry</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-aubergine" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Vegetable</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-apple-whole" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Fruits</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-bowl-rice" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Grains</p>
                                    </div>
                                </div>

                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Seafoods</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Rice and Noodles</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Vegetables</p>
                                    </div>
                                </div>
                                <div class="card me-3 flex-shrink-0" style="width: 12rem;">
                                    <div class="card-body">
                                        <span>
                                            <i class="fi fi-rr-cupcake-alt" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text">Desserts</p>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <strong style="font-size: 25px">Products</strong>
                            <br />
                            <br />
                            <div class="products">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/milk.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-8 flex-shrink-0 pe-0">
                                                        <span style="font-weight: bold; opacity: 0.5; font-size:15px">Dairy Products</span><br />
                                                        <span style="font-size: 20px; font-weight:bold; padding-bottom:10%;">Magnolia Fresh Milk</span><br />
                                                        <span style="font-weight: bold; font-size:15px">15 cartons</span><br />
                                                        <span style="opacity: 0.5; font-size:15px">1 liter</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/chickenbrest.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-8 flex-shrink-0 pe-0">
                                                        <span style="font-weight: bold; opacity: 0.5; font-size:15px">Meat & Poultry</span><br />
                                                        <span style="font-size: 20px; font-weight:bold; padding-bottom:10%;">Bounty Chicken Breast</span><br />
                                                        <span style="font-weight: bold; font-size:15px">10 kilograms</span><br />
                                                        <span style="opacity: 0.5; font-size:15px">1 kilogram</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/rigate.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-8 flex-shrink-0 pe-0">
                                                        <span style="font-weight: bold; opacity: 0.5; font-size:15px">Grains</span><br />
                                                        <span style="font-size: 20px; font-weight:bold; padding-bottom:10%;">Penne Pasta</span><br />
                                                        <span style="font-weight: bold; font-size:15px">10 kilograms</span><br />
                                                        <span style="opacity: 0.5; font-size:15px">1 kilogram</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/spinach.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-8 flex-shrink-0 pe-0">
                                                        <span style="font-weight: bold; opacity: 0.5; font-size:15px">Vegetables</span><br />
                                                        <span style="font-size: 20px; font-weight:bold; padding-bottom:10%;">Spinach</span><br />
                                                        <span style="font-weight: bold; font-size:15px">7 kilograms</span><br />
                                                        <span style="opacity: 0.5; font-size:15px">1 kilogram</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/strawberry.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-8 flex-shrink-0 pe-0">
                                                        <span style="font-weight: bold; opacity: 0.5; font-size:15px">Fruits</span><br />
                                                        <span style="font-size: 20px; font-weight:bold; padding-bottom:10%;">Strawberry</span><br />
                                                        <span style="font-weight: bold; font-size:15px">3 kilograms</span><br />
                                                        <span style="opacity: 0.5; font-size:15px">1 kilogram</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
<?php include 'footer.php' ?>
</html>

