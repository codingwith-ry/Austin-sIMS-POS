<?php
session_start();
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'pos staff management') {
    header("Location: /Austin-sIMS-POS/Login/index.php");
    exit();
}
$employeeID = $_SESSION['employeeID'];

$active = "menu";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS-POS | Menu</title>
    <?php require_once('../Login/database.php'); ?>
    <?php require_once('links.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include_once ('verticalNav.php'); ?>
    <div class="offcanvas offcanvas-end show  overflow-y-auto" id="nav2" data-bs-scroll="true" data-bs-backdrop="false" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <form action="Menu.php" method="POST">
            <div class="offcanvas-header" style="padding-bottom: 0px;">
                <div class="row" style="width: 100%; margin-left: 1px;">
                    <div class="col-10" style="padding-left: 0px;">
                            <span id="" class="fw-bold fs-3">Order Bar</span>
                            <span>Employee: <?php echo $employeeID; ?></span>
                            
                    </div>
                    <div class="col-2" style="justify-content: right; display: flex; padding: 20px; padding-right: 0px; padding-top: 0px;">
                        <button type="button" id="resetOrdersButton" class="btn btn-success"><i class="fi fi-rr-rotate-right"></i></button>
                    </div>
                </div>
            </div>
            <hr />
            <div class="offcanvas-body overflow-y-auto" style="padding-top: 0px;">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group" style="width: 100%;">
                    <input type="radio" class="btn-check dineStatus" name="btnradio" id="btnradio1" autocomplete="off" style="width: 100%;" checked>
                    <label class="btn btn-outline-primary" for="btnradio1">Dine In</label>
                    <input type="radio" class="btn-check dineStatus" name="btnradio" id="btnradio2" style="width: 100%;" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio2">Take Out</label>
                </div>
                
                <div id="orderItemsContainer">
                
                </div>
                
                <hr />

                <div class="card" id="orderTotal1 flex-shrink-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span style="font-weight: bold; font-size: 16px;">Total Amount</span>
                            </div>
                            <div id ="totalAmountElem"class="col" style="text-align: right;">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="btnProceed" style="width: 100%; margin-top: 10px;">Proceed to Payment</button>
            </div>
        </form>
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
        </div>
    </div>

    <br />
    <div class="container">
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
                            <button class="nav-link '.$isTabActive.' me-2 menuPills" id="'.$arrTabs[$index].'Tab" data-bs-toggle="pill" data-bs-target="#'.$arrTabs[$index].'" type="button" role="tab" aria-controls="pills-home" aria-selected="true">'.$row['menuName'].'</button>
                        </li>';
                        $index++;
                }
            }
            ?>
        </ul>

        <br />
        <div class="tab-content menuPanels" id="pills-tabContent">
            <?php
            $index = 0;
            $getMenu = "SELECT * from tbl_menuclass;";
            $result = $conn->prepare("$getMenu");
            $result->execute();
            if($result->rowCount() > 0){
                $categoryArr = [];
                $categoryIDArr = [];
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
                    <div class="tab-pane fade show '.$isActive.' menuPanel" id="'.$arrTabs[$index].'" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
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
                            <button class="nav-link '.$isActiveCat.'  flex-shrink-0 align-baseline me-3 nav-item categoryButtons"  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#all'.$menuCategoryName.'Row" role="tab" aria-selected="true">
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
                        $tabName = strtolower($explodeCategory).$category['categoryID'].$menuCategoryName."Row";
                        $categoryArr[$index][$catIndex]= $category['categoryName'];
                        $categoryIDArr[$index][$catIndex]= $category['categoryID'];
                        echo'
                        <li class="nav-item" role="presentation">
                            <button class="nav-item nav-link flex-shrink-0 align-baseline me-3 categoryButtons"  style="width: 12rem; text-align: left; padding:15px;" data-bs-toggle="pill" data-bs-target="#'.$tabName.'" role="tab" aria-selected="true">
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
                        <hr />
                        <div id="pills-tabContent" class="tab-content products">
                    ';
                    $ifShow = "show active";
                    $x = 0;
                    $allRow = false;
                    if(!$allRow){
                        $ifShow = "show active";
                        $searchProducts = "SELECT tbl_menu.productID, tbl_menu.menuID, tbl_menu.productName, tbl_menu.productImage, tbl_menu.productPrice, tbl_categories.categoryName, tbl_categories.categoryIcon FROM tbl_menu INNER JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID  WHERE tbl_menu.menuID = $currID ORDER BY tbl_menu.productName ASC;";
                        $products = $conn->prepare("$searchProducts");
                        $products->execute();
                        echo'<div class="tab-pane fade '.$ifShow.' categoryPanel" id="all'.$menuCategoryName.'Row">
                             <div class="row">   
                        ';
                        if($products->rowCount()  <= 0){
                            echo'
                            <h2>No products found.</h2>';
                            goto skip;
                        }
                        while($product = $products->fetch()){
                            echo'
                                <div class="card-parent col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0 productCardClass">
                                    <div class="card flex-shrink-0" id="productCard" style="width: 100%; padding: 15px;">
                                        <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                        <div class="card-body" id="productBody">
                                            <div class="row">
                                                <div class="col-xl-8 col-lg-6 flex-shrink-0">
                                                    <span id="productName" class="productName">'.$product['productName'].'</span>
                                                </div>
                                                <div class="col-xl-4 col-lg-6 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                    <span class="text-success displayCategory" style="font-size: 12px; display: flex; justify-content: center; "><i class="'.$product['categoryIcon'].'" style="margin-top: 1px; padding-right: 3px;"></i>'.$product['categoryName'].'</span>
                                                </div>
                                            </div>
                                                                                                
                                            <div class="row">
                                                <div class="col-12">
                                                    <span id="foodPrice">₱'.$product['productPrice'].'</span>
                                                    <button type="button" id="addtoOrderModal" class="btn btn-primary" data-product-id="'.$product['productID'].'" data-menu-id="'.$product['menuID'].'" data-menu-name="'.$row['menuName'].'" data-product-name="'.$product['productName'].'" 
                                                        data-product-price="'.$product['productPrice'].'"  data-product-image="resources/nachos.jpg"  data-product-category="'.$product['categoryName'].'" 
                                                        data-product-icon="'.$product['categoryIcon'].'" data-bs-toggle="modal" data-bs-target="#addItemModal" style="width: 100%; margin-top: 10px;">
                                                        Add to Order
                                                    </button>
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
                        $tabName = strtolower($explodeRow).$categoryIDArr[$index][$x].$menuCategoryName."Row";
                        $searchCategories;
                        $products;
                        $categoryCont = $categoryArr[$index][$x];
                        $ifShow = "";
                        $searchProducts = "SELECT tbl_menu.productID, tbl_menu.menuID, tbl_menu.productName, tbl_menu.productImage, tbl_menu.productPrice, tbl_menu.menuID, tbl_categories.categoryName, tbl_categories.categoryIcon FROM tbl_menu 
                                            INNER JOIN tbl_categories ON tbl_menu.categoryID = tbl_categories.categoryID 
                                            WHERE tbl_menu.menuID = $currID AND tbl_categories.categoryName = '$categoryCont' ORDER BY tbl_menu.productName ASC;";
                            
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
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3 flex-shrink-0 productCardClass">
                                        <div class="card flex-shrink-0 overflow-y-auto" id="productCard" style="width: 100%; padding: 15px;">
                                            <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                                            <div class="card-body" id="productBody">
                                                <div class="row">
                                                    <div class="col-xl-8 col-lg-6 flex-shrink-0">
                                                        <span id="productName" class="productName">'.$product['productName'].'</span>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-6 col-md-6 flex-shrink-0 ps-0" style="justify-content: right; display: flex;">
                                                        <span id="" class="text-success displayCategory" style="font-size: 12px; display: flex; justify-content: center; "><i class="'.$product['categoryIcon'].'" style="margin-top: 1px; padding-right: 3px;"></i>'.$product['categoryName'].'</span>
                                                    </div>
                                                </div>
                                                                                                    
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span id="foodPrice">₱'.$product['productPrice'].'</span>
                                                        <button id="addtoOrderModal" type="button" data-product-id="'.$product['productID'].'" data-menu-id="'.$product['menuID'].'" data-menu-name="'.$row['menuName'].'" data-product-name="'.$product['productName'].'" 
                                                        data-product-price="'.$product['productPrice'].'"  data-product-icon="'.$product['categoryIcon'].'" data-product-image="resources/nachos.jpg"  data-product-category="'.$product['categoryName'].'"
                                                        class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal" style="width: 100%; margin-top: 10px;">Add to Order</button>
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
            ?>
        </div>
        
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <form action="Menu.php" method="POST">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Add to Order</h1>
                        <button id="closeAddModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="resources/nachos.jpg" class="card-img-top img-fluid rounded-start rounded-end mb-2" id="foodImage" alt="...">
                                </div>
                                <div class="col-8">
                                    <h4 id="prodName" class="modal-title fs-5 fw-bold"></h4>
                                    <span class="text-success" style="font-size: 12px;"><i id="catIcon" class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i><span id="prodCategory"></span></span>
                                    <br />
                                    <span id="prodPrice"></span>
                                    <br />
                                    <br />
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination align-items-center">
                                            <li class="page-item">
                                                <button type="button" id="subtractButton" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;" >
                                                    −
                                                </button>
                                            </li>
                                            <li class="page-item mx-2">
                                                <span id="prodQuantity" class="fw-bold"></span>
                                            </li>
                                            <li class="page-item">
                                                <button type="button" id="addButton" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;">
                                                    +
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>  
                            </div>
                            <hr />
                            <span style="font-weight: bold; font-size: 18px;">Variations</span>
                            <div class="row mb-3" id="variationSection">
                                
                            </div>
                            <hr />
                            <span style="font-weight: bold; font-size: 18px;">Addons</span>
                            <div class="row" id="addonSection">
                                                               
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <span style="font-weight: bold; font-size: 16px;">Total Amount</span>
                                </div>
                                <div id="totalAmount" class="col" style="text-align: right;">
                                    ₱280.00
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button id="closeButton2" type="button"  class="btn btn-secondary closeAddModal" data-bs-dismiss="modal">Close</button>
                        <button id ="addtoOrder" type="button" class="btn btn-primary">Add to Order</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Edit Order</h1>
                        <button id="closeEditModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="resources/nachos.jpg" class="card-img-top img-fluid rounded-start rounded-end mb-2" id="foodImage" alt="...">
                                </div>
                                <div class="col-8">
                                    <h4 id="prodNameEdit" class="modal-title fs-5 fw-bold"></h4>
                                    <span class="text-success" style="font-size: 12px;"><i id="catIconEdit" class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i><span id="prodCategoryEdit"></span></span>
                                    <br />
                                    <span id="prodPriceEdit"></span>
                                    <br />
                                    <br />
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination align-items-center">
                                            <li class="page-item">
                                                <button type="button" id="subtractButtonEdit" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;" >
                                                    −
                                                </button>
                                            </li>
                                            <li class="page-item mx-2">
                                                <span id="prodQuantityEdit" class="fw-bold"></span>
                                            </li>
                                            <li class="page-item">
                                                <button type="button" id="addButtonEdit" class="btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; padding: 0;">
                                                    +
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>  
                            </div>
                            <hr />
                            <span style="font-weight: bold; font-size: 18px;">Variations</span>
                            <div class="row mb-3" id="variationSectionEdit">
                                
                            </div>

                            <hr />
                            <span style="font-weight: bold; font-size: 18px;">Addons</span>
                            <div class="row" id="addonSectionEdit">
                                                               
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <span style="font-weight: bold; font-size: 16px;">Total Amount</span>
                                </div>
                                <div id="totalAmountEdit" class="col" style="text-align: right;">
                                    ₱280.00
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button id="closeEditButton" type="button"  class="btn btn-secondary closeEditModal2" data-bs-dismiss="modal">Close</button>
                        <button id ="saveItemOrder" type="button" class="btn btn-primary">Save Order</button>
                    </div>
                </div>
            </div>
        </div>
</div>


</body>
<?php include 'footer.php' ?>
<script type="text/javascript" src="scripts/POS.js"></script>
</html>