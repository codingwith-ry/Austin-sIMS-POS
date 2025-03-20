
var btnProceed = document.getElementById('btnProceed');
btnProceed.addEventListener('click', function() {
    if(localStorage.getItem('orderItems') === null){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Your order bar is empty!',
        })
    }else{
        window.location.href = 'orderSummary.php';
    }    
});
updateOrderBar();


document.addEventListener('click', function (event) {
    if (event.target.matches('#addtoOrderModal')) {
        let totalAmountElement = document.getElementById("totalAmount");
        let orderItem;
        let addons = [];
        
        document.getElementById('exampleModalLabel').innerText = "Add to Order";
        let addToOrder = document.getElementById('addtoOrder');
        addToOrder.innerText = "Add to Order";

        let closeModal = document.getElementById('closeButton2');
        closeModal.innerText = "Close";

        let productName = event.target.getAttribute('data-product-name') || "No Name";
        let productPrice = event.target.getAttribute('data-product-price') || "No Price";
        let productImage = event.target.getAttribute('data-product-image') || "No Name";
        let productCategory = event.target.getAttribute('data-product-category') || "Unknown Category";
        let categoryIcon = event.target.getAttribute('data-product-icon') || "Unknown Icon";
        let productID = event.target.getAttribute('data-product-id');

        document.getElementById('prodName').innerText = productName;
        document.getElementById('prodPrice').innerText = "₱" + productPrice;
        document.getElementById('prodCategory').innerText = productCategory;
        document.getElementById('prodCategory').innerText = productCategory;
        document.getElementById('catIcon').className = categoryIcon;

        let basePrice = 0; // Default base price, will be updated dynamically
        let quantity = 1; // Default quantity
        
        basePrice = productPrice;
        
        document.querySelectorAll(".addon-checkbox").forEach(checkbox => {
            checkbox.checked = false;
        });

        fetch("scripts/get_addons.php", {
            "method": "POST",
            "headers": { "Content-Type": "application/json" },
            "body": JSON.stringify({ productID: productID })
        })
        .then(response => response.json())
        .then(data => {
            let addonContainer = document.getElementById("addonSection");
            addonContainer.innerHTML = ""; 

            if (data.length > 0) {
                data.forEach(addon => {
                    addonContainer.innerHTML += `
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <input class="form-check-input addon-checkbox" type="checkbox" value="${addon.addonID}" data-price="${addon.addonPrice}">
                            <label class="form-check-label mt-1">
                                ${addon.addonName} <br />
                                <span id="addonPrice">+ ₱${addon.addonPrice}</span>
                            </label>
                        </div>
                    `;
                });
                document.querySelectorAll(".addon-checkbox").forEach(checkbox => {
                    checkbox.addEventListener("change", updateTotal);
                });

                
            } else {
                addonContainer.innerHTML = "<p class='text-muted'>No addons available.</p>";
            }
        })
        .catch(error => console.error("Error fetching addons:", error));

        
        updateTotal();

        document.getElementById('prodQuantity').innerText = quantity;

        let btnAdd = document.getElementById('addButton');
        let btnSubtract = document.getElementById('subtractButton');

        btnAdd.replaceWith(btnAdd.cloneNode(true));
        btnSubtract.replaceWith(btnSubtract.cloneNode(true));

        btnAdd = document.getElementById('addButton');
        btnSubtract = document.getElementById('subtractButton');


        btnAdd.addEventListener('click', function () {
            quantity++;
            document.getElementById('prodQuantity').innerText = quantity;
            updateTotal();
        });

        btnSubtract.addEventListener('click', function () {
            if (quantity > 1) {
                quantity--;
                document.getElementById('prodQuantity').innerText = quantity;
                updateTotal();
            }
        });

        


        function updateTotal() {
            let product = [];
            let total = basePrice * quantity;
        
            document.querySelectorAll(".addon-checkbox:checked").forEach(checkbox => {
                let addonPrice = parseFloat(checkbox.getAttribute("data-price"));
                total += addonPrice * quantity;
            });


            document.querySelectorAll(".addon-checkbox").forEach(checkbox => {
                let addonPrice = parseFloat(checkbox.getAttribute("data-price"));
                let priceLabel = checkbox.parentElement.querySelector("#addonPrice");
                priceLabel.textContent = `+ ₱${(addonPrice * quantity).toFixed(2)}`;

                let addonID = checkbox.value;
                let addonName = checkbox.nextElementSibling.textContent.trim().split('\n')[0];

                if (checkbox.checked) {
                    // Check if the addonID already exists in the addons array
                    let isDuplicate = addons.some(addon => addon.addonID === addonID);
        
                    // If not a duplicate, push the addon into the array
                    if (!isDuplicate) {
                        addons.push({
                            addonID: addonID,
                            addonName: addonName,
                            addonPrice: (addonPrice * quantity).toFixed(2)
                        });
                    }
                } else {
                    // Remove the addon from the array if unchecked
                    addons = addons.filter(addon => addon.addonID !== addonID);
                }
            });
            
        
            
            totalAmountElement.textContent = "₱" + total.toFixed(2);
            product = {
                productID: productID,
                productPrice: basePrice,
                productName: productName,
                productImage: productImage,
                productCategory: productCategory,
                productAddons: addons,
                productQuantity: quantity,
                productTotal: total
            };

            orderItem = product;
            console.log(product);
        }

        addToOrder.addEventListener('click', function() {
            Swal.fire({
                title: 'Add to Order?',
                text: "Item will be added to your order list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    let orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];

                    // Add the new order item to the array
                    localStorage.setItem('orderType', 'Dine In');
                    orderItems.push(orderItem);

                    // Save the updated array back to localStorage
                    localStorage.setItem('orderItems', JSON.stringify(orderItems));

                    Swal.fire(
                        'Success!',
                        'Item successfully added.',
                        'success'
                    )
                    updateOrderBar();
                    document.getElementById('closeAddModal').click();
                }
            })
        });

        closeModal.addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Item will not be added to your order list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                addons = [];
                orderItem = []
                if (result.isConfirmed) {
                }
            })
        });
    }
});

document.addEventListener('click', function (event) {
    if (event.target.matches('#btnEdit')) {
        let index = event.target.getAttribute('data-item-index');
        let orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];
        let orderItem = orderItems[index];

        // Populate the edit modal with the order item details
        document.getElementById('prodName').innerText = orderItem.productName;
        document.getElementById('prodPrice').innerText = "₱" + orderItem.productPrice;
        document.getElementById('prodCategory').innerText = orderItem.productCategory;
        document.getElementById('catIcon').className = orderItem.categoryIcon;
        document.getElementById('prodQuantity').innerText = orderItem.productQuantity;

        // Fetch addons and populate the addon section
        fetch("scripts/get_addons.php", {
            "method": "POST",
            "headers": { "Content-Type": "application/json" },
            "body": JSON.stringify({ productID: orderItem.productID })
        })
        .then(response => response.json())
        .then(data => {
            let addonContainer = document.getElementById("addonSection");
            addonContainer.innerHTML = ""; 

            if (data.length > 0) {
                data.forEach(addon => {
                    addonContainer.innerHTML += `
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <input class="form-check-input addon-checkbox" type="checkbox" value="${addon.addonID}" data-price="${addon.addonPrice}" ${orderItem.productAddons.some(a => a.addonID === addon.addonID) ? 'checked' : ''}>
                            <label class="form-check-label mt-1">
                                ${addon.addonName} <br />
                                <span id="addonPrice">+ ₱${addon.addonPrice}</span>
                            </label>
                        </div>
                    `;
                });
                document.querySelectorAll(".addon-checkbox").forEach(checkbox => {
                    checkbox.addEventListener("change", updateTotal);
                });
            } else {
                addonContainer.innerHTML = "<p class='text-muted'>No addons available.</p>";
            }
        })
        .catch(error => console.error("Error fetching addons:", error));

        // Update the total amount
        updateTotal();

        // Add event listeners to the quantity buttons
        let btnAdd = document.getElementById('addButton');
        let btnSubtract = document.getElementById('subtractButton');

        btnAdd.replaceWith(btnAdd.cloneNode(true));
        btnSubtract.replaceWith(btnSubtract.cloneNode(true));

        btnAdd = document.getElementById('addButton');
        btnSubtract = document.getElementById('subtractButton');

        btnAdd.addEventListener('click', function () {
            orderItem.productQuantity++;
            document.getElementById('prodQuantity').innerText = orderItem.productQuantity;
            updateTotal();
        });

        btnSubtract.addEventListener('click', function () {
            if (orderItem.productQuantity > 1) {
                orderItem.productQuantity--;
                document.getElementById('prodQuantity').innerText = orderItem.productQuantity;
                updateTotal();
            }
        });

        // Update the order item in the local storage when the save button is clicked
        document.getElementById('addtoOrder').addEventListener('click', function() {
            Swal.fire({
                title: 'Update Order?',
                text: "Item will be updated in your order list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    orderItems[index] = orderItem;
                    localStorage.setItem('orderItems', JSON.stringify(orderItems));
                    Swal.fire(
                        'Success!',
                        'Item successfully updated.',
                        'success'
                    )
                    updateOrderBar();
                    document.getElementById('closeAddModal').click();
                }
            })
        });
    }
});

function updateTotal() {
    let totalAmountElement = document.getElementById("totalAmount");
    let total = orderItem.productPrice * orderItem.productQuantity;

    document.querySelectorAll(".addon-checkbox:checked").forEach(checkbox => {
        let addonPrice = parseFloat(checkbox.getAttribute("data-price"));
        total += addonPrice * orderItem.productQuantity;
    });

    totalAmountElement.textContent = "₱" + total.toFixed(2);
    orderItem.productTotal = total;
}


function updateOrderBar(){
let totalAmountElement = document.getElementById('totalAmountElem');
let displayTotalAmount = 0;
// Retrieve orderItems from localStorage
let orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];
console.log(orderItems);
// Get the container element
let orderItemsContainer = document.getElementById('orderItemsContainer');
orderItemsContainer.innerHTML = "";

if (orderItems.length === 0){
    orderItemsContainer.innerHTML = 
    `<br />
    <div class="card dashed" id="orderTotal1 flex-shrink-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col align-items-center">
                                <span class="text-secondary" style="font-weight: bold; font-size: 16px;">Order Bar is empty</span>
                            </div>
                        </div>
                    </div>
                </div>`;
}
// Iterate through the orderItems array
orderItems.forEach((item, index) => {
    // Create the HTML structure for each item
    let itemHTML = `
        <div class="card mb-3 mt-3 flex-shrink-0">
            <div class="row g-0">
                <div class="col-6 flex-shrink-0" id="foodCol" style="justify-content: center; display: flex;">
                    <img src="${item.productImage}" id="imgFood" class="img-fluid rounded-start rounded-end">
                </div>
                <div class="col-6" id="foodCol" style="padding-left: 5px;">
                    <div class="row">
                        <div class="col-8" style="padding-right: 0px;">
                            <span id="foodName">
                                ${item.productName}
                            </span>
                        </div>
                        <div class="col-4" style="justify-content: right; padding-left: 0px;">
                            <button id="editItemModal" type="button" class="btn btn-link" id="btnEdit" data-bs-toggle="modal" data-bs-target="#editItemModal" data-item-index="${index}">
                                <i class="fi fi-rr-pencil"></i>
                            </button>
                        </div>
                    </div>
                    <span id="foodQuantity">
                        x${item.productQuantity}
                    </span>
                    <br>
                    <span id="foodAddon">
                        ${item.productAddons.map(addon => `+${addon.addonName}`).join('<br>')}
                    </span>
                    <br>
                    <span id="foodPrice">
                        ₱${item.productTotal.toFixed(2)}
                    </span>
                </div>
            </div>
        </div>
    `;
    price = parseFloat(item.productTotal.toFixed(2), 10);
    displayTotalAmount += price;


    // Append the generated HTML to the container
    orderItemsContainer.innerHTML += itemHTML;
});

resetButton = document.getElementById("resetOrdersButton");
resetButton.addEventListener('click', () => {
    resetOrderBar();
});
function resetOrderBar(){
    Swal.fire({
        title: 'Are you sure?',
        text: "The order bar will be reset.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes'
    }).then((result) => {
        
        if (result.isConfirmed) {
            Swal.fire(
                'Success!',
                'Order bar has been reset.',
                'success'
            )
            localStorage.removeItem('orderItems');
            updateOrderBar();
        }
        
    })
}

totalAmountElement.innerHTML =  "₱" + displayTotalAmount.toFixed(2);
}






/*
-----------------Gagamitin sa Edit Order modal-----------------
if(x == 1) {
    Swal.fire({
        title: 'Remove Item?',
        text: "This item will be removed from your order list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Success!',
                'Item successfully removed.',
                'success'
            )
        }
    })
}*/



