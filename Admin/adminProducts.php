<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Products</title>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles/adminProducts.css">
</head>
<body style="font-family: Inter, Arial">
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <div id="adminContent">
        <h2>Products</h2>
        <hr />
        <div class="row" style="margin-bottom: 20px;">
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 12px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#productsTab" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                        Products
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#categoriesTab" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                        Categories
                    </button>
                </li>
            </ul>
        </div>

        
        <div class="tab-content" id="myTabContent">
            <div class=""> 
                <div class="row"> 
                    <div class="col"> 
                        <div class="input-group flex-nowrap"> 
                            <span class="input-group-text" id="addon-wrapping">
                            <i class="bi-search"></i></span> <input type="text" class="form-control" placeholder="Search product here" aria-describedby="addon-wrapping"> 
                        </div> 
                    </div> 
                    <div class="col-auto">
                        <button class="btn btn-secondary" type="button" > 
                            <i class="fi fi-rr-settings-sliders" style="vertical-align: middle;"></i>
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
                    <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Item
						<i 
						class="fi fi-rr-add " style="vertical-align: middle; font-size: 18px"></i>
					</button>
                </li>
            </ul>
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Add Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="">
                                            <label for="exampleFormControlInput1" class="form-label"  style="font-weight: bold; font-size: 18px;">Product Name</label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="ex. Nachos">
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-6">
                                        <span style="font-weight: bold; font-size: 18px;">Product Image</span>
                                        <img src="resources/nachos.jpg" class="card-img-top img-fluid rounded-start rounded-end mt-2 mb-2" id="productImage" alt="...">
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control ps-2 pe-2" type="file" id="formFile" placeholder="Upload Image here">
                                    </div>
                                </div>
                                <hr />
                                <span style="font-weight: bold; font-size: 18px;">Addons</span>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="ex. Cheese">
                                        </div>
                                        <button type="button" class="btn btn-primary">Add Another Addon</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <span style="font-weight: bold; font-size: 16px;">Default Amount</span>
                                    </div>
                                    <div class="col ms-auto" style="text-align: right;">
                                        <div class="input-group">
                                            <div class="input-group-text">₱</div>
                                            <input type="text" class="form-control form-control-sm" id="specificSizeInputGroupUsername" placeholder="ex. 200">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Add to Order</button>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div id="productsTab" class="tab-pane fade show active">
                <div class="tab-pane fade show active" id="coffeeMenu" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                    <div class="d-flex flex-row flex-nowrap overflow-x-scroll">
                        <button class="btn btn-primary flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>

                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>

                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>

                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>

                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>

                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold" >All</p> 
                        </button>
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text">Coffees</p>
                        </button>
                        

                        
                    </div>
                    <br />
                    <div class="products">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                    <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                    <div class="card-body" id="productBody">
                                        <div class="row">
                                            <div class="col-8 flex-shrink-0 pe-0">
                                                <span id="productName">Roast Beef with Mashed Potato</span>
                                            </div>
                                            <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Starters</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <span id="foodPrice">₱280.00</span>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                    <img src="resources/chickenalfredo.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                    <div class="card-body" id="productBody">
                                        <div class="row">
                                            <div class="col-8 flex-shrink-0 pe-0">
                                                <span id="productName">Creamy Chicken Alfredo Pasta</span>
                                            </div>
                                            <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Pastas</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <span id="foodPrice">₱280.00</span>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                    <img src="resources/chickenalfredo.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                    <div class="card-body" id="productBody">
                                        <div class="row">
                                            <div class="col-8 flex-shrink-0 pe-0">
                                                <span id="productName">Creamy Chicken Alfredo Pasta</span>
                                            </div>
                                            <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Pastas</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <span id="foodPrice">₱280.00</span>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                    <img src="resources/chickenalfredo.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                    <div class="card-body" id="productBody">
                                        <div class="row">
                                            <div class="col-8 flex-shrink-0 pe-0">
                                                <span id="productName">Creamy Chicken Alfredo Pasta</span>
                                            </div>
                                            <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Pastas</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <span id="foodPrice">₱280.00</span>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div id="categoriesTab" class="tab-pane fade show">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md- mb-3 flex-shrink-0">
                        <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3"  style="width: 12rem; text-align: left; padding:15px;">
                                <span>
                                    <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                </span>
                                <br />
                                <br />
                                <p class="card-text fw-bold " >All</p> 
                        </button>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</body>

</html>


<?php include "cdnScripts.php" ?>