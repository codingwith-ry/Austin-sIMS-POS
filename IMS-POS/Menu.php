<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'pos staff management') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Menu</title>
    <?php require_once('links.php'); ?>
</head>

<body>
<?php include 'verticalNav.php'?>
<div class="offcanvas offcanvas-end show" id="nav2" data-bs-scroll="true" data-bs-backdrop="false" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header" style="padding-bottom: 0px;">
            <div class="row" style="width: 100%; margin-left: 1px;">
                <div class="col-6" style="padding-left: 0px;">
                    <p style="margin-bottom: 0px;">Order No.:
                        <br />
                        <span id="orderNum">1001</span>
                    </p>
                </div>
                <div class="col-6" style="justify-content: right; display: flex; padding: 20px; padding-right: 0px; padding-top: 0px;">
                    <button type="button" class="btn btn-success"><i class="fi fi-rr-rotate-right"></i></button>
                </div>
            </div>
        </div>
        <br>
        <hr />
        <div class="offcanvas-body overflow-y-auto" style="padding-top: 0px;">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group" style="width: 100%;">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" style="width: 100%;" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Dine In</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" style="width: 100%;" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Take Out</label>
            </div>
            
            <div class="card mb-3 mt-3 flex-shrink-0">
                <div class="row g-0">
                <div class="col-6 flex-shrink-0" id="foodCol" style="justify-content: center; display: flex;">
                    <img src="resources/nachos.jpg" id = "imgFood"class="img-fluid rounded-start rounded-end ">
                    </div>
                    <div class="col-6" id="foodCol" style="padding-left: 5px; ">
                        <div class="row">
                            <div class="col-8" style="padding-right: 0px;">
                                <span id="foodName">
                                    Nachos
                                </span>
                            </div>
                            <div class="col-4" style="justify-content: right; padding-left: 0px;">
                                <button type="button" class="btn btn-link" id="btnEdit"><i class="fi fi-rr-pencil"></i></button>
                            </div>
                        </div>
                        <span id="foodQuantity">
                            x2
                        </span>
                        <br>
                        <span id="foodAddon">
                            +Cheese
                            <br>
                            +Meat
                        </span>
                    
                        <br>
                        <span id="foodPrice">
                            ₱240.00
                        </span>
                    </div>
                </div>
            </div>

            <div class="card mb-3 mt-3 flex-shrink-0">
                <div class="row g-0">
                <div class="col-6" id="foodCol" style="justify-content: center; display: flex;">
                    <img src="resources/chickenalfredo.jpg" id = "imgFood"class="img-fluid rounded-start rounded-end">
                    </div>
                    <div class="col-6" id="foodCol" style="padding-left: 5px;">
                        <div class="row">
                            <div class="col-8" style="padding-right: 0px;">
                                <span id="foodName">
                                    Chicken Alfredo
                                </span>
                            </div>
                            <div class="col-4" style="justify-content: right; padding-left: 0px;">
                                <button type="button" class="btn btn-link" id="btnEdit"><i class="fi fi-rr-pencil"></i></button>
                            </div>
                        </div>
                        <span id="foodQuantity">
                            x1
                        </span>
                        <br>
                        <span id="foodAddon">
                            +Cheese
                        </span>
                    
                        <br>
                        <span id="foodPrice">
                            ₱320.00
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="form-check mb-3 flex-shrink-0">
                <label class="form-check-label mt-1 ms-1" for="flexCheckDefault">
                    Senior Citizen/PWD
                </label>
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">  
            </div>
            <div class="mb-3 flex-shrink-0">
                <input class="form-control form-control" type="text" placeholder="Voucher" id="flexCheckDefault">  
            </div>

            <div class="card" id="orderTotal1 flex-shrink-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span id="totalDetails1">
                                Sub Total:
                            </span>
                            <span id="totalDetails2">
                                Discount(20%):
                            </span>
                            <span id="totalDetails3">
                                VAT(12%):
                            </span>
                        </div>
                        <div class="col" style="text-align: right;">
                            <span id="totalValue1">
                                ₱560.00
                            </span>
                            <br>    
                            <span id="totalValue2">
                                ₱112.00
                            </span>
                            <br>
                            <span id="totalValue3">
                                ₱53.76
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <span style="font-weight: bold; font-size: 16px;">Total Amount</span>
                        </div>
                        <div class="col" style="text-align: right;">
                            ₱501.76
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="btnProceed" style="width: 100%; margin-top: 10px;">Proceed to Payment</button>
        </div>
    </div>

<div id="mainPOS">
    <div class="container mt-3"> 
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
    <div class="container">
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
        </ul>

        <br />
        <div class="tab-content" id="pills-tabContent">
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
        
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Add to Order</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-4">
                                    <img src="resources/nachos.jpg" class="card-img-top img-fluid rounded-start rounded-end mb-2" id="productImage" alt="...">
                                </div>
                                <div class="col-8">
                                    <h4 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Nachos</h4>
                                    <span class="text-success" style="font-size: 12px;"><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Starters</span>
                                    <br />
                                    <span id="foodPrice">₱280.00</span>
                                    <br />
                                    <br />
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination align-items-center">
                                            <li class="page-item">
                                                <button type="button" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;" >
                                                    −
                                                </button>
                                            </li>
                                            <li class="page-item mx-2">
                                                <span class="fw-bold">1</span>
                                            </li>
                                            <li class="page-item">
                                                <button type="button" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;">
                                                    +
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>  
                            </div>
                            <hr />
                            <span style="font-weight: bold; font-size: 18px;">Addons</span>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <input class="form-check-input" type="checkbox" value="" id="item3">
                                    <label class="form-check-label mt-1" for="item3">
                                        Cheese
                                        <br />
                                        <span id="foodPrice">+ ₱50.00</span>
                                    </label>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <input class="form-check-input" type="checkbox" value="" id="item3">
                                    <label class="form-check-label mt-1" for="item3">
                                        Sauce
                                        <br />
                                        <span id="foodPrice">+ ₱50.00</span>
                                    </label>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <input class="form-check-input" type="checkbox" value="" id="item3">
                                    <label class="form-check-label mt-1" for="item3">
                                        Meat
                                        <br />
                                        <span id="foodPrice">+ ₱50.00</span>
                                    </label>
                                </div>
                                </label>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <span style="font-weight: bold; font-size: 16px;">Total Amount</span>
                                </div>
                                <div class="col" style="text-align: right;">
                                    ₱280.00
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
    </div>
</div>
</body>
<script type="text/javascript" src="scripts/POS.js"></script>
<?php include 'footer.php' ?>
</html>