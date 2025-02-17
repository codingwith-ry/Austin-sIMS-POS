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
                        <button class="btn btn-secondary" type="button"> 
                            <i class="fi fi-rr-settings-sliders"></i>
                        </button> 
                    </div> 
                </div>
            </div>
            <br />


            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Coffee Menu</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-primary nav-link" href="#">Gastro Pub Menu</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-primary nav-link" href="#">Party Tray Menu</a>
                </li>
            </ul>
            <br />
            <div id="productsTab" class="tab-pane fade show active">
                <div class="d-flex flex-row flex-nowrap overflow-x-scroll"> 
                    <button class="btn btn-custom-outline flex-shrink-0 align-baseline me-3 active"  style="width: 12rem; text-align: left; padding:15px;">
                            <span>
                                <i class="fi fi-ss-apps" id="categoryIcon"></i>
                            </span>
                            <br />
                            <br />
                            <p class="card-text fw-bold " >All</p> 
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
                            <p class="card-text fw-bold " >All</p> 
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
                            <p class="card-text fw-bold " >All</p> 
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
                            <p class="card-text fw-bold " >All</p> 
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
                            <p class="card-text fw-bold " >All</p> 
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
                            <p class="card-text fw-bold " >All</p> 
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
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Edit Product</button>
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
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Edit Product</button>
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
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Edit Product</button>
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
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Edit Product</button>
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