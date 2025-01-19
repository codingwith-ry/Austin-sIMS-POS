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

    <div class="container data-summary" style="margin-bottom: 20px;">
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

    <div class="container charts" style="margin-bottom: 20px;">
        <div class="row rows=cols-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Stock Analytics</h5>
                        <div id="stock-bar-Container">
                            <canvas id="stockAnalyticsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="doughnutContainer">
                            <div class="chartDetails">
                                <ul>
                                    <li>
                                        <div style="display: flex; flex-direction:column;">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Remaining Stock Budget</span>
                                            <span style="font-size: 40px; font-weight:bold">20%</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div style="display: flex; flex-direction:column">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Total Expenses</span>
                                            <span style="font-size: 40px; font-weight:bold">70%</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div style="display: flex; flex-direction:column">
                                            <span class="stockLabel" style="font-size: 15px; font-weight:bold">Total Items In Stock</span>
                                            <span style="font-size: 40px; font-weight:bold">100%</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div id="doughnut-chart-Container">
                                <canvas id="stockdoughnutChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container stockTable">
        <div class="card" style="padding: 5px;">
            <table id="stockTable" class="display">
                <thead>
                    <tr>
                        <th>Stock Items</th>
                        <th>Date Purchased</th>
                        <th>Employee Assigned</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</main>

<?php include 'footer.php' ?>

<script>
    /*************Date Picker Set Up Start****************/
    document.addEventListener("DOMContentLoaded", function(){
        flatpickr(".flatpickr input", {
            enableTime: false,
            dateFormat: "Y-M-D",
        });
    });
    /*************Date Picker Set Up End****************/

    
    const stockChart = document.getElementById('stockAnalyticsChart')
    const stockDoughnutChart = document.getElementById('stockdoughnutChart')

    
    /*************Bar Chart Set Up Start****************/
    new Chart( stockChart,
        {
            type: 'bar',
            data:{
                labels: [
                    'Mon', 
                    'Tue', 
                    'Wed', 
                    'Thu', 
                    'Fri', 
                    'Sat', 
                    'Sun'
                ],
                datasets: [{
                    label: 'Daily Expenses',
                    data: [1000, 3000, 5000, 7000, 10000, 500, 600],
                    borderWidth: 1
                }]
            }
        }
    )
    /*************Bart Chart Set Up End****************/

    
    /*************Doughnut Chart Set Up Start****************/
    const doughnutChartData = {
        labels: [
            'Remaining Stock Budget',
            'Total Expenses',
            'Total Items in Stock'
        ],
        data: [
            20,
            70,
            100
        ],
    }

    new Chart(stockDoughnutChart, {
    type: 'doughnut',
    data: {
        labels: doughnutChartData.labels,
        datasets: [
            {
                data: doughnutChartData.data
            }
        ]
    },
    options: {
        borderRadius: 2,
        hoverBorderWidth: 0,
        plugins:{
            legend:{
                display: false
            }
        },
        spacing: 5, 
        weight: 1,
        cutout: '80%'
    }
});
    /*************Doughnut Chart Set Up End****************/

    
    /*************Stock Table Set up Start****************/
    let table = new DataTable('#stockTable', {
        responsive: true
    })
</script>