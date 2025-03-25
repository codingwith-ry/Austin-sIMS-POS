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
            <li class="list-group-item border-0 py-3 d-flex align-items-center" data-link="adminDashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="ms-2">Dashboard</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center" data-link="/Austin-sIMS-POS/Admin/adminEmployees.php">
                <span class="material-symbols-outlined">groups</span>
                <span class="ms-2">Employees</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted" data-link="/Austin-sIMS-POS/Admin/adminSales.php">
                <span class="material-symbols-outlined">analytics</span>
                <span class="ms-2">Sales</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted" data-link="/Austin-sIMS-POS/Admin/adminProducts.php">
                <i class="fi fi-rr-utensils" style="font-size: large;"></i>
                <span class="ms-2">Products</span>
            </li>
        </ul>
    </div>
    <div class="offcanvas-footer border-top p-3">
        <a href="/Austin-sIMS-POS/Login/logout.php" class="d-flex align-items-center text-danger text-decoration-none">
            <i class="bi bi-box-arrow-left"></i>
            <span class="ms-2">Logout</span>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all list items
        const navItems = document.querySelectorAll('.list-group-item');

        // Highlight the current page
        const currentPage = window.location.pathname;
        navItems.forEach(item => {
            const link = item.getAttribute('data-link');
            if (currentPage.includes(link)) {
                item.classList.add('active');
                item.classList.remove('text-muted');
            } else {
                item.classList.remove('active');
                item.classList.add('text-muted');
            }

            // Add click event to redirect
            item.addEventListener('click', function () {
                window.location.href = link;
            });
        });
    });
</script>