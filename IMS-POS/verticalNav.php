<?php

$menuActive = "";
$queueActive = "";
$stockActive = "";
$inventoryActive = "";

if($active == "menu"){
    $menuActive = "active";
    $queueActive = "";
    $stockActive = "";
    $inventoryActive = "";
}
else if($active == "orderQueue_History"){
    $queueActive = "active";
    $menuActive = "";
    $stockActive = "";
    $inventoryActive = "";
}
else if($active == "stockPage"){
    $stockActive = "active";
    $menuActive = "";
    $queueActive = "";
    $inventoryActive = "";
}
else if($active == "Inventory_Item-Records"){
    $inventoryActive = "active";
    $menuActive = "";
    $stockActive = "";
    $queueActive = "";
}
else{
    $menuActive = "";
    $queueActive = "";
    $stockActive = "";
    $inventoryActive = "";
}
    echo'
<div class="offcanvas offcanvas-start show" id="nav" tabindex="1" data-bs-scroll="true" data-bs-backdrop="false">
        <div class="p-3">
            <div>
            <div class="row">
                <div class="col-md-8 col-xl-4 col-lg-12 p-0">
                    <img src="resources/logo.png" alt="Logo" style="width: 65px; height: 65px; object-fit: cover;" class="rounded-circle">
                </div>
                <div class="col-md-8 col-xl-8 col-lg-12 p-1">
                    <span id="offcanvasScrollingLabel" class="flex-grow-1 w-75" style="font-size: 16px;"><b>Austin\'s Cafe & Gastro Pub IMS-POS</b></span>
                </div>
            </div>
            </div>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item '.$menuActive.' border-0 py-3 d-flex align-items-center">
                    <i class="fi fi-tr-utensils" style="font-size: large;"></i>
                    <a href="menu.php" class="ms-2 text-decoration-none text-dark w-100">Menu</a>
                </li>
                <li class="list-group-item '.$queueActive.' border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-journal-text"></i>
                    <a href="orderQueue_History.php" class="ms-2 text-decoration-none text-dark w-100">Orders</a>
                </li>
                <li class="list-group-item '.$stockActive.' border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-box"></i>
                    <a href="stockPage.php" class="ms-2 text-decoration-none text-dark w-100">Stocks</a>
                </li>
                <li class="list-group-item '.$inventoryActive.' border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-clipboard"></i>
                    <a href="Inventory_Item-Records.php" class="ms-2 text-decoration-none text-dark w-100">Inventory</a>
                </li>
            </ul>
        </div>
        <div class="offcanvas-footer border-top p-3">
            <a href="#" class="d-flex align-items-center text-danger text-decoration-none" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-left"></i>
                <span class="ms-2">Logout</span>
            </a>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="/Austin-sIMS-POS/Login/logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
    ';
?>
