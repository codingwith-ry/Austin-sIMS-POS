<body style="font-family: Inter, sans-serif; padding-top: 56px;">
<!-- Navbar (with toggle on mobile) -->
<nav class="navbar navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#nav" aria-controls="nav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Sidebar -->
<div class="offcanvas offcanvas-start d-md-block" id="nav" tabindex="-1" data-bs-scroll="true" data-bs-backdrop="true">
    <div class="p-3 border-bottom d-flex align-items-center">
        <img src="resources/logo.png" alt="Logo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle">
        <span class="ms-2 sidebar-label" style="font-size: 14px;"><b>Austin's Cafe & Gastro Pub IMS-POS</b></span>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-group">
            <li class="list-group-item border-0 py-3 d-flex align-items-center" data-link="adminDashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="ms-2 sidebar-label">Dashboard</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center" data-link="/Austin-sIMS-POS/Admin/adminEmployees.php">
                <span class="material-symbols-outlined">groups</span>
                <span class="ms-2 sidebar-label">Employees</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted" data-link="/Austin-sIMS-POS/Admin/adminSales.php">
                <span class="material-symbols-outlined">analytics</span>
                <span class="ms-2 sidebar-label">Sales</span>
            </li>
            <li class="list-group-item border-0 py-3 d-flex align-items-center text-muted" data-link="/Austin-sIMS-POS/Admin/adminProducts.php">
                <i class="fi fi-rr-utensils" style="font-size: large;"></i>
                <span class="ms-2 sidebar-label">Products</span>
            </li>
        </ul>
    </div>
    <div class="offcanvas-footer border-top p-3 mt-auto">
        <a href="#" class="d-flex align-items-center text-danger text-decoration-none" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-left"></i>
            <span class="ms-2 sidebar-label">Logout</span>
        </a>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to log out?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="/Austin-sIMS-POS/Login/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Highlight active link -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navItems = document.querySelectorAll('.list-group-item');
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

            item.addEventListener('click', function () {
                window.location.href = link;
            });
        });
    });
</script>

<!-- Sidebar Styles & Transitions -->
<style>
    .offcanvas-start {
        transition: all 0.3s ease-in-out;
    }

    .list-group-item {
        transition: all 0.3s ease-in-out;
    }

    @media (min-width: 992px) {
        .offcanvas-start {
            width: 260px;
            top: 56px;
            height: calc(100vh - 56px);
            z-index: 1020;
            border-right: 1px solid #dee2e6;
            position: fixed;
            visibility: visible !important;
            transform: none !important;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            margin-left: 260px;
            padding-top: 70px;
        }
    }

    @media (max-width: 991.98px) {
        .sidebar-label {
            display: none !important;
        }

        .offcanvas-start {
            width: 80px;
        }

        .main-content {
            margin-left: 80px;
            padding-top: 70px;
        }

        .offcanvas-footer span {
            display: none;
        }
    }

    .list-group-item[title] {
        position: relative;
    }

    .list-group-item[title]:hover::after {
        content: attr(title);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.75);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        white-space: nowrap;
        margin-left: 8px;
        font-size: 12px;
        z-index: 9999;
    }
</style>
</body>
