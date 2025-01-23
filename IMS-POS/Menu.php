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
            
            <div class="row flex-shrink-0">
                <div class="col" style=" padding-right: 1px">
                    <button type="button" class="btn btn-primary" style="width: 100%;">Dine In</button>
                </div>
                <div class="col" style=" padding-left: 1px">
                    <button type="button" class="btn btn-outline-primary" style="width: 100%;">Take Out</button>
                </div>
            </div>
            <div class="card mb-3 mt-3 flex-shrink-0">
                <div class="row g-0">
                <div class="col-6" id="foodCol" style="justify-content: center; display: flex;">
                    <img src="resources/nachos.jpg" id = "imgFood"class="img-fluid rounded-start rounded-end">
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
                <label class="form-check-label" for="flexCheckDefault">
                    Senior Citizen/PWD
                </label>
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">  
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
            <button type="button" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Proceed to Payment</button>
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
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Coffee Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Gastro Pub Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Party Tray Menu</a>
            </li>
        </ul>
        <br />
        <div class="d-flex flex-row flex-nowrap overflow-x-scroll">
            <div class="card me-3 flex-shrink-0 bg-primary text-white" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-ss-apps" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text fw-bold" >All</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-mug-hot-alt" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Coffees</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-french-fries" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Starters</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-salad" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Chicken</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-bowl-rice" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Pork</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                    <i class="fi fi-rr-fork-spaghetti" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Beef</p> 
                </div> 
            </div>
            
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Seafoods</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Rice and Noodles</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                        <i class="fi fi-rr-cup-straw-swoosh" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Vegetables</p> 
                </div> 
            </div>
            <div class="card me-3 flex-shrink-0" style="width: 12rem;"> 
                <div class="card-body"> 
                    <span>
                    <i class="fi fi-rr-cupcake-alt" id="categoryIcon"></i>
					</span>
                    <br />
                    <br />
                    <p class="card-text">Desserts</p> 
                </div> 
            </div>
        </div>
        <br />
        <div class="products">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-3 flex-shrink-0">
                    <div class="card" style="width: 100%; padding: 15px;">
                        <img src="resources/nachos.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                        <div class="card-body" id="productBody">
                            <div class="row">
                                <div class="col-10">
                                    <span id="productName">Nachos</span>
                                </div>
                                <div class="col-2" style="justify-content: right; display: flex;">
                                    <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Starters</span>
                                </div>
                            </div>
                            
                            <span id="foodPrice">₱120.00</span>
                            <button type="button" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add to Order</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3 flex-shrink-0">
                    <div class="card" style="width: 100%; padding: 15px;">
                        <img src="resources/chickenalfredo.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                        <div class="card-body" id="productBody">
                            <div class="row">
                                <div class="col-10">
                                    <span id="productName">Creamy Chicken Alfredo Pasta</span>
                                </div>
                                <div class="col-2" style="justify-content: right; display: flex;">
                                    <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Pastas</span>
                                </div>
                            </div>
                            
                            <span id="foodPrice">₱280.00</span>
                            <button type="button" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add to Order</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3 flex-shrink-0">
                    <div class="card" style="width: 100%; padding: 15px;">
                        <img src="resources/chickenalfredo.jpg" class="card-img-top rounded-start rounded-end mb-2" id="productImage" alt="...">
                        <div class="card-body" id="productBody">
                            <div class="row">
                                <div class="col-10">
                                    <span id="productName">Creamy Chicken Alfredo Pasta</span>
                                </div>
                                <div class="col-2" style="justify-content: right; display: flex;">
                                    <span class="text-success" style="font-size: 12px; display: flex; justify-content: center; "><i class="fi fi-rr-french-fries" style="margin-top: 1px; padding-right: 3px;"></i> Pastas</span>
                                </div>
                            </div>
                            
                            <span id="foodPrice">₱280.00</span>
                            <button type="button" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Add to Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   
<?php include 'footer.php' ?>