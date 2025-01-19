<?php include 'verticalNav.php' ?>

<main>
    <div class="container title">
        <div class="row">
            <div class="col-md-10"><h1>Stock List Overview</h1></div>
            <div class="col-md-2">
                <div class="flatpickr">
                    <div class="flatpickr">
                        <input type="text" placeholder="Date" data-input class="dateInputField" style="font-weight: 600; font-size: 15px"> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container date-selector" style="margin-bottom: 20px;">
        <ul class="nav nav-underline">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page">Weekly</a>
            </li>
            <li class="nav-item">
                <a class="nav-link">Monthly</a>
            </li>
            <li class="nav-item">
                <a class="nav-link">Yearly</a>
            </li>
        </ul>
    </div>
    <div class="container data-summary">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; gap:20px">
                            <div style="margin-top: 10px;">
                                <i class="fa-solid fa-chart-line fa-3x"></i>
                            </div>
                            <div> 
                                <h6 class="card-title" style="font-size: 20px;">Remaining Stock Budget</h6>
                                <h5 class="card-subtitle" style="font-size: x-large; font-weight:bold">Number</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; gap:20px">
                            <div style="margin-top: 10px;">
                                <i class="fa-solid fa-peso-sign fa-3x"></i>
                            </div>
                            <div>
                                <h6 class="card-title" style="font-size: 20px;">Total Expenses</h6>
                                <h5 class="card-subtitle" style="font-size: x-large; font-weight:bold">Number</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; gap:20px">
                            <div style="margin-top: 10px;">
                                <i class="fa-solid fa-box fa-3x"></i>
                            </div>
                            <div>
                                <h6 class="card-title" style="font-size: 20px;">Total Items In Stock</h6>
                                <h6 class="card-subtitle" style="font-size: x-large; font-weight:bold">Number</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php' ?>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        flatpickr(".flatpickr input", {
            enableTime: false,
            dateFormat: "Y-m-d",
        });
    });
</script>