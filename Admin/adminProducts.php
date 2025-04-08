<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Products</title>
    <?php require_once('../Login/database.php'); ?>    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include 'adminCDN.php'; ?>
    <link rel="stylesheet" type="text/css" href="styles/adminProducts.css">
</head>
<body style="font-family: Inter, Arial">
    <header>
        <?php include 'adminNavBar.php'; ?>
    </header>

    <main id="adminContent">
        <div class="row mb-2">
        <div class="col-md-6 d-flex align-items-center">
        <h1 class="mb-0">Products</h1>
    </div>
    <div class="col-md-6 d-flex justify-content-end align-items-center gap-3">
    <!-- Notification Dropdown -->
    <div class="dropdown me-2">
        <button class="btn btn-outline-secondary position-relative dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
                <span class="visually-hidden">unread notifications</span>
            </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 300px; overflow-y: auto;">
            <li><strong class="dropdown-header">Notifications</strong></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">🛒 New order received</a></li>
            <li><a class="dropdown-item" href="#">📦 Inventory stock low</a></li>
            <li><a class="dropdown-item" href="#">👤 New employee registered</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center text-primary" href="#">View all</a></li>
        </ul>
    </div>

    <!-- Administrator Dropdown -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" id="accountDropdownBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Administrator
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>
</div>
        </div>
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
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <?php 
                        $arrTabs = array();
                        $index = 0;
                        $getMenu = "SELECT * from tbl_menuclass;";
                        $result = $conn->prepare("$getMenu");
                        $result->execute();

                        if($result->rowCount() > 0){
                            while($row = $result->fetch()){
                                $menuName = explode(' ', trim($row['menuName']))[0];
                                $arrTabs[] =  strtolower($menuName)."MenuTab";
                                if($index == 0){
                                    $isTabActive = "active";
                                }else{
                                    $isTabActive = "";
                                }
                                echo '<li class="nav-item" role="presentation">
                                        <button class="nav-link '.$isTabActive.' me-2" id="'.$arrTabs[$index].'Tab" data-bs-toggle="pill" data-bs-target="#'.$arrTabs[$index].'" type="button" role="tab" aria-controls="pills-home" aria-selected="true">'.$row['menuName'].'</button>
                                    </li>';
                                    $index++;
                                }
                        }
                    ?>
                    <li class="nav-item ms-auto" role="presentation">
                        <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Item
                            <i 
                            class="fi fi-rr-add " style="vertical-align: middle; font-size: 18px"></i>
                        </button>
                    </li>
                </ul>
                        
                <br />

                <div class="tab-content" id="pills-tabContent">
                    <?php
                    $index = 0;
                    $getMenu = "SELECT * from tbl_menuclass;";
                    $result = $conn->prepare("$getMenu");
                    $result->execute();
                    
                    if($result->rowCount() > 0){
                        $categoryArr = [];
                        while($row = $result->fetch()){
                            $currID = $row['menuID'];
                            $searchCategories = "SELECT categoryID, categoryName, categoryIcon FROM tbl_categories WHERE menuID = $currID;";
                            $categories = $conn->prepare("$searchCategories");
                            $categories->execute();

                            if($index == 0){
                                $isActive = "active";
                            }else{
                                $isActive = "";
                            }
                            
                            echo'
                            <div class="tab-pane fade show '.$isActive.'" id="'.$arrTabs[$index].'" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                            ';
                                
                            echo'
                                <div class="">
                                <ul class="nav nav-pills d-flex flex-row flex-nowrap overflow-x-scroll" id="pills-tab" role="tablist">
                            ';
                            
                            $catIndex = 0;
                            $menuCategoryName = explode(' ', trim($row['menuName']))[0];
                            $allTab = false;
                            if(!$allTab){
                                $isActiveCat = "fw-bold active";
                                echo'
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link '.$isActiveCat.'  flex-shrink-0 align-baseline me-3 nav-item"  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#all'.$menuCategoryName.'Row" role="tab" aria-selected="true">
                                        <span>
                                            <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text" >All</p> 
                                    </button>
                                </li>
                                ';
                                $allTab = true;
                            }
                            while($category = $categories->fetch()){
                                $isActiveCat = "";
                                $explodeCategory = explode(' ', trim($category['categoryName']))[0];
                                $tabName = strtolower($explodeCategory).$menuCategoryName."Row";
                                $categoryArr[$index][$catIndex]= $category['categoryName'];
                                echo'
                                <li class="nav-item" role="presentation">
                                    <button class="nav-item nav-link flex-shrink-0 align-baseline me-3 "  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#'.$tabName.'" role="tab" aria-selected="true">
                                                <span>
                                                    <i class="'.$category['categoryIcon'].'" id="categoryIcon"></i>  
                                                </span>
                                                <br />
                                                <br />
                                                <p class="card-text" >'.$category['categoryName'].'</p> 
                                    </button>
                                </li>
                                ';
                                $catIndex++;
                            }
                            echo '</ul></div>';
                            

                            echo'
                                <br />
                                <div id="pills-tabContent" class="tab-content products">
                            ';
                            $ifShow = "show active";

                            $x = 0;
                            $allRow = false;
                            if(!$allRow){
                                $ifShow = "show active";
                                $searchProducts = "SELECT tbl_menu.productID, tbl_menu.productName, tbl_menu.productImage, tbl_menu.productPrice, tbl_categories.categoryName, tbl_categories.categoryIcon FROM tbl_menu INNER JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID  WHERE tbl_menu.menuID = $currID;";
                                $products = $conn->prepare("$searchProducts");
                                $products->execute();
                    
                                echo'<div class="tab-pane fade '.$ifShow.'" id="all'.$menuCategoryName.'Row">
                                    <div class="row">   
                                ';
                                
                                if($products->rowCount()  <= 0){
                                    echo'
                                    <h2>No products found.</h2>';
                                    goto skip;
                                }

                                while($product = $products->fetch()){
                                    echo'
                                        <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                            <div class="card flex-shrink-0" id="productCard" style="width: 100%; padding: 15px;">
                                                <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                                <div class="card-body" id="productBody">
                                                    <div class="row">
                                                        <div class="col-8 flex-shrink-0 pe-0">
                                                            <span id="productName">'.$product['productName'].'</span>
                                                        </div>
                                                        <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                            <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="'.$product['categoryIcon'].'" style="margin-top: 1px; padding-right: 3px;"></i>'.$product['categoryName'].'</span>
                                                        </div>
                                                    </div>
                                                                                                        
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span id="foodPrice">₱'.$product['productPrice'].'</span>
                                                            <button type="button" id="addtoOrderModal" class="btn btn-primary"  data-product-name="'.$product['productName'].'" 
                                                                data-product-price="'.$product['productPrice'].'"  data-product-image="resources/nachos.jpg"  data-product-category="'.$product['categoryName'].'" 
                                                                data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }

                                skip:
                                echo'</div>
                                </div>';
                                $allTab = true;
                            }

                            while($x < count($categoryArr[$index])){
                                $explodeRow = explode(' ', trim($categoryArr[$index][$x]))[0];
                                $tabName = strtolower($explodeRow).$menuCategoryName."Row";
                                $searchCategories;
                                $products;
                                $categoryCont = $categoryArr[$index][$x];

                                $ifShow = "";
                                $searchProducts = "SELECT tbl_menu.productID, tbl_menu.productName, tbl_menu.productImage, tbl_menu.productPrice, tbl_menu.menuID, tbl_categories.categoryName FROM tbl_menu 
                                                    INNER JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID 
                                                    WHERE tbl_menu.menuID = $currID AND tbl_categories.categoryName = '$categoryCont';";
                                    
                                $products = $conn->prepare("$searchProducts");
                                $products->execute();
                                
                                echo'<div class="tab-pane fade" id="'.$tabName.'"> 
                                    <div class="row">
                                ';
                                if($products->rowCount()  <= 0){
                                    echo'
                                    <h2>No products found.</h2>';
                                    goto jump;
                                }
                                else{
                                    while($product = $products->fetch()){
                                        echo'
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0">
                                                <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                                    <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                                    <div class="card-body" id="productBody">
                                                        <div class="row">
                                                            <div class="col-8 flex-shrink-0 pe-0">
                                                                <span id="productName">'.$product['productName'].'</span>
                                                            </div>
                                                            <div class="col-4 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                                <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i>'.$product['categoryName'].'</span>
                                                            </div>
                                                        </div>
                                                                                                            
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <span id="foodPrice">₱'.$product['productPrice'].'</span>
                                                                <button id="addtoOrderModal" type="button" data-product-name="'.$product['productName'].'" 
                                                                data-product-price="'.$product['productPrice'].'"  data-product-image="resources/nachos.jpg"  data-product-category="'.$product['categoryName'].'"
                                                                class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                                
                                jump:
                                echo'</div>
                                </div>';
                                $x++;
                            }
                            
                            
                            echo'
                                </div>
                            ';
                            echo '</div>';
                            $index++;
                        }
                    }  
                    $index = 0;
                    $getMenu = "SELECT * from tbl_menuclass;";
                    $result = $conn->prepare("$getMenu");
                    $result->execute();

                    echo'
                        </div>
                    </div>';
                    ?>

                    <div id="categoriesTab" class="tab-pane fade show">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <?php 
                            $arrTabs = array();
                            $index = 0;
                            $getMenu = "SELECT * from tbl_menuclass;";
                            $result = $conn->prepare("$getMenu");
                            $result->execute();

                            if($result->rowCount() > 0){
                                while($row = $result->fetch()){
                                    $menuName = explode(' ', trim($row['menuName']))[0];
                                    $arrTabs[] =  strtolower($menuName)."MenuTab2";
                                    if($index == 0){
                                        $isTabActive = "active";
                                    }else{
                                        $isTabActive = "";
                                    }
                                    echo '<li class="nav-item" role="presentation">
                                            <button class="nav-link '.$isTabActive.' me-2" id="'.$arrTabs[$index].'Tab" data-bs-toggle="pill" data-bs-target="#'.$arrTabs[$index].'" type="button" role="tab" aria-controls="pills-home" aria-selected="true">'.$row['menuName'].'</button>
                                        </li>';
                                        $index++;
                                    }
                            }
                        ?>
                        <li class="nav-item ms-auto" role="presentation">
                            <button class="btn btn-success h-100 pt-2" id="partytrayMenu" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Item
                                <i 
                                class="fi fi-rr-add " style="vertical-align: middle; font-size: 18px"></i>
                            </button>
                        </li>
                    </ul>
                    
                    <br />
                    <?php
                    
                    echo'
                    <div class="tab-content" id="pills-tabContent">
                    ';

                    if($result->rowCount() > 0){
                        $categoryArr = [];
                        while($row = $result->fetch()){
                            $currID = $row['menuID'];
                            $searchCategories = "SELECT categoryID, categoryName, categoryIcon FROM tbl_categories WHERE menuID = $currID;";
                            $categories = $conn->prepare("$searchCategories");
                            $categories->execute();

                            if($index == 0){
                                $isActive = "active";
                            }else{
                                $isActive = "";
                            }
                            
                            echo'
                            <div class="tab-pane fade show '.$isActive.'" id="'.$arrTabs[$index].'" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                            ';
                                
                            echo'
                            <div class="">
                                <ul class="nav nav-pills d-flex flex-row flex-nowrap overflow-x-scroll" id="pills-tab" role="tablist">
                            ';
                            
                            $catIndex = 0;
                            $menuCategoryName = explode(' ', trim($row['menuName']))[0];
                            $allTab = false;
                            if(!$allTab){
                                $isActiveCat = "fw-bold active";
                                echo'
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link '.$isActiveCat.'  flex-shrink-0 align-baseline me-3 nav-item"  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#all'.$menuCategoryName.'Row" role="tab" aria-selected="true">
                                        <span>
                                            <i class="fi fi-ss-apps" id="categoryIcon"></i>
                                        </span>
                                        <br />
                                        <br />
                                        <p class="card-text" >All</p> 
                                    </button>
                                </li>
                                ';
                                $allTab = true;
                            }
                            while($category = $categories->fetch()){
                                $isActiveCat = "";
                                $explodeCategory = explode(' ', trim($category['categoryName']))[0];
                                $tabName = strtolower($explodeCategory).$menuCategoryName."Row";
                                $categoryArr[$index][$catIndex]= $category['categoryName'];
                                echo'
                                <li class="nav-item" role="presentation">
                                    <button class="nav-item nav-link flex-shrink-0 align-baseline me-3 "  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#'.$tabName.'" role="tab" aria-selected="true">
                                                <span>
                                                    <i class="'.$category['categoryIcon'].'" id="categoryIcon"></i>  
                                                </span>
                                                <br />
                                                <br />
                                                <p class="card-text" >'.$category['categoryName'].'</p> 
                                    </button>
                                </li>
                                ';
                                $catIndex++;
                            }
                            echo '</ul>
                            </div>
                            </div>
                            ';
            
                            $index++;
                        }
                    }    
                    ?>

                </div>
            </div>
        </div>
    </div>
</body>

</html>


<?php include "cdnScripts.php" ?>