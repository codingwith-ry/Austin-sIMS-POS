<?php
$page = basename($_SERVER['PHP_SELF']);
if ($page == "orderQueue_History.php") {
    echo '<link rel="stylesheet" href="styles/orderQH.css">';
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap5.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link href="styles/POS.css" rel="stylesheet">
    <link href="styles/IMS.CSS" rel="stylesheet">
    <link href="styles/orderCollapse.css" rel="stylesheet">
    <link href="styles/popupSidebar.css" rel="stylesheet">
    
    <title>Document</title>
</head>
<body style="font-family: Inter, sans-serif;">
    <div class="offcanvas offcanvas-start show" id="nav" tabindex="1" data-bs-scroll="true" data-bs-backdrop="false">
        <div class="p-3">
            <div>
            <div class="row">
                <div class="col-md-8 col-xl-4 col-lg-12 p-0">
                    <img src="resources/logo.png" alt="Logo" style="width: 65px; height: 65px; object-fit: cover;" class="rounded-circle">
                </div>
                <div class="col-md-8 col-xl-8 col-lg-12 p-1">
                    <span id="offcanvasScrollingLabel" class="flex-grow-1 w-75" style="font-size: 16px;"><b>Austin's Cafe & Gastro Pub IMS-POS</b></span>
                </div>
            </div>
            </div>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item border-0 py-3 active d-flex align-items-center">
                    <i class="fi fi-tr-utensils" style="font-size: large;"></i>
                    <span class="ms-2">Menu</span> 
                </li>
                <li class="list-group-item border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-journal-text"></i>
                    <span class="ms-2">Orders</span>
                </li>
                <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted">
                    <i class="bi bi-box"></i>
                    <span class="ms-2">Stocks</span>
                </li>
                <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted">
                    <i class="bi bi-clipboard"></i>
                    <span class="ms-2">Inventory</span>
                </li>
            </ul>
        </div>
        <div class="offcanvas-footer border-top p-3">
            <a href="#" class="d-flex align-items-center text-danger text-decoration-none">
                <i class="bi bi-box-arrow-left"></i>
                <span class="ms-2">Logout</span>
            </a>
        </div>
    </div>