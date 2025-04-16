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

document.querySelectorAll('.dineStatus').forEach(radio => {
    radio.addEventListener('change', (event) => {
        const orderType = event.target.id === 'btnradio1' ? 'Dine In' : 'Take Out';
        localStorage.setItem('orderType', orderType);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.form-control[placeholder="Search product here"]');
    const tabsContainer = document.getElementById('pills-tabContent');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase().trim();

        // Get the currently active menu tab
        const activeMenuTab = tabsContainer.querySelector('.tab-pane.active');

        if (!activeMenuTab) return;

        // Get the currently active category tab within the active menu
        const activeCategoryTab = activeMenuTab.querySelector('.tab-pane.active');

        console.log(activeMenuTab);
        console.log(activeCategoryTab);
        
        const searchContainer = activeCategoryTab;

        // Get all product cards in the active menu or category
        const productCards = searchContainer.querySelectorAll('.card-parent');

        productCards.forEach(card => {
            const productName = card.querySelector('#productName').textContent.toLowerCase();

            // Show or hide the card based on the search query
            if (productName.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});


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
        let menuID = event.target.getAttribute('data-menu-id');
        let menuName = event.target.getAttribute('data-menu-name');

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

        document.querySelectorAll(".variation-radio").forEach(radio => {
            radio.checked = false;
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

        fetch("scripts/get_variations.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ productID: productID })
        })
        .then(response => response.json())
        .then(data => {
            let variationContainer = document.getElementById("variationSection");
            variationContainer.innerHTML = "";

            if (data.length > 0) {
                data.forEach(variation => {
                    variationContainer.innerHTML += `
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <input class="form-check-input variation-radio" type="radio" name="variation" value="${variation.variationID}" data-varname ="${variation.variationName}" data-price="${variation.variationPrice}" ${variation.variationID === data[0].variationID ? 'checked' : ''}>
                            <label class="form-check-label mt-1">
                                ${variation.variationName} <br />
                                <span id="variationPrice">₱${variation.variationPrice}</span>
                            </label>
                        </div>
                    `;
                });
                document.querySelectorAll("variation-radio").checked = true;
                basePrice = parseFloat(data[0].variationPrice);

                // Add event listener to update total when variation changes
                document.querySelectorAll(".variation-radio").forEach(radio => {
                    radio.addEventListener("change", updateTotal);
                });
            } else {
                variationContainer.innerHTML = "<p class='text-muted'>No variations available.</p>";
            }
        })
        .catch(error => console.error("Error fetching variations:", error));
        
        
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
            let selectedVariation = document.querySelector(".variation-radio:checked");
            basePrice = selectedVariation ? parseFloat(selectedVariation.getAttribute("data-price")) : basePrice;
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
                menuID: menuID,
                menuName: menuName,
                productPrice: basePrice,
                productName: productName,
                productVariation: document.querySelector(".variation-radio:checked")?.value || null,
                productVariationName: selectedVariation? selectedVariation.getAttribute("data-varname") : null,
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
    if (event.target.matches('#editItemButton')) {
        console.log("Edit button clicked");

        // Remove the existing modal from the DOM

        let index = event.target.getAttribute('data-item-index');
        let orderItemsEdit = JSON.parse(localStorage.getItem('orderItems')) || [];
        let orderItemEdit = orderItemsEdit[index];
        console.log(orderItemEdit);

        // Initialize the edit modal with the selected item
        document.querySelectorAll(".addon-checkbox").forEach(checkbox => {
            checkbox.checked = false;
        });

        document.querySelectorAll(".variation-radio").forEach(radio => {
            radio.checked = false;
        });

        initializeEditModal(orderItemEdit, index);
        if(jQuery('#editItemModal')){
            jQuery('#editItemModal').
            remove();
        }

        
        function initializeEditModal(orderItemEdit, index) {
            // Create a working copy of the item to edit
            let currentItem = JSON.parse(JSON.stringify(orderItemEdit));
            let basePrice = parseFloat(currentItem.productPrice);
            let addons = [...currentItem.productAddons];
        
            // Update modal content
            document.getElementById('prodNameEdit').innerText = currentItem.productName;
            document.getElementById('prodPriceEdit').innerText = "₱" + basePrice.toFixed(2);
            document.getElementById('prodCategoryEdit').innerText = currentItem.productCategory;
            document.getElementById('catIconEdit').className = currentItem.categoryIcon;
            document.getElementById('prodQuantityEdit').innerText = currentItem.productQuantity;
            document.getElementById('totalAmountEdit').textContent = "₱" + currentItem.productTotal.toFixed(2);
        
            // Clear addons and variations sections
            let addonContainer = document.getElementById("addonSectionEdit");
            addonContainer.innerHTML = "";
        
            let variationContainer = document.getElementById("variationSectionEdit");
            variationContainer.innerHTML = "";
        
            // Fetch addons and populate the addon section
            fetch("scripts/get_addons.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ productID: currentItem.productID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(addon => {
                        const isChecked = currentItem.productAddons.some(a => a.addonID == addon.addonID);
                        addonContainer.innerHTML += `
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <input class="form-check-input addon-checkbox2" type="checkbox" 
                                       value="${addon.addonID}" 
                                       data-price="${addon.addonPrice}" 
                                       ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label mt-1">
                                    ${addon.addonName} <br />
                                    <span class="addon-price">+ ₱${(addon.addonPrice * currentItem.productQuantity).toFixed(2)}</span>
                                </label>
                            </div>
                        `;
                    });
        
                    // Properly bind event listeners to checkboxes
                    document.querySelectorAll(".addon-checkbox2").forEach(checkbox => {
                        checkbox.addEventListener("change", function() {
                            const addonID = this.value;
                            const addonPrice = parseFloat(this.getAttribute("data-price"));
                            const addonName = this.nextElementSibling.textContent.trim().split('\n')[0];
                            
                            if (this.checked) {
                                // Add addon if not already present
                                if (!addons.some(a => a.addonID === addonID)) {
                                    addons.push({
                                        addonID: addonID,
                                        addonName: addonName,
                                        addonPrice: (addonPrice * currentItem.productQuantity).toFixed(2)
                                    });
                                }
                            } else {
                                // Remove addon
                                addons = addons.filter(a => a.addonID !== addonID);
                            }
                            
                            // Update price display
                            const priceElement = this.parentElement.querySelector(".addon-price");
                            priceElement.textContent = `+ ₱${(addonPrice * currentItem.productQuantity).toFixed(2)}`;
                            
                            updateTotalEdit();
                        });
                    });
                } else {
                    addonContainer.innerHTML = "<p class='text-muted'>No addons available.</p>";
                }
            })
            .catch(error => console.error("Error fetching addons:", error));
        
            // Fetch variations and populate the variation section
            fetch("scripts/get_variations.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ productID: currentItem.productID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(variation => {
                        variationContainer.innerHTML += `
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <input class="form-check-input variation-radio2" type="radio" 
                                       name="variation" 
                                       value="${variation.variationName}" 
                                       data-price="${variation.variationPrice}" 
                                       ${currentItem.productVariation === variation.variationName ? 'checked' : ''}>
                                <label class="form-check-label mt-1">
                                    ${variation.variationName} <br />
                                    <span class="variation-price">₱${variation.variationPrice}</span>
                                </label>
                            </div>
                        `;
                    });
                    
                    document.querySelectorAll(".variation-radio2").forEach(radio => {
                        radio.addEventListener("change", function() {
                            if (this.checked) {
                                basePrice = parseFloat(this.getAttribute("data-price"));
                                currentItem.productPrice = basePrice;
                                currentItem.productVariation = this.value;
                                updateTotalEdit();
                            }
                        });
                    });
                } else {
                    variationContainer.innerHTML = "<p class='text-muted'>No variations available.</p>";
                }
            })
            .catch(error => console.error("Error fetching variations:", error));
        
            // Quantity buttons
            let btnAddEdit = document.getElementById('addButtonEdit');
            let btnSubtractEdit = document.getElementById('subtractButtonEdit');
        
            btnAddEdit.addEventListener('click', function() {
                currentItem.productQuantity++;
                document.getElementById('prodQuantityEdit').innerText = currentItem.productQuantity;
                
                // Update addon prices based on new quantity
                document.querySelectorAll(".addon-checkbox2").forEach(checkbox => {
                    if (checkbox.checked) {
                        const addonPrice = parseFloat(checkbox.getAttribute("data-price"));
                        const priceElement = checkbox.parentElement.querySelector(".addon-price");
                        priceElement.textContent = `+ ₱${(addonPrice * currentItem.productQuantity).toFixed(2)}`;
                        
                        // Update addon in array
                        const addonID = checkbox.value;
                        const addonIndex = addons.findIndex(a => a.addonID === addonID);
                        if (addonIndex !== -1) {
                            addons[addonIndex].addonPrice = (addonPrice * currentItem.productQuantity).toFixed(2);
                        }
                    }
                });
                
                updateTotalEdit();
            });
        
            btnSubtractEdit.addEventListener('click', function() {
                if (currentItem.productQuantity > 1) {
                    currentItem.productQuantity--;
                    document.getElementById('prodQuantityEdit').innerText = currentItem.productQuantity;
                    
                    // Update addon prices based on new quantity
                    document.querySelectorAll(".addon-checkbox2").forEach(checkbox => {
                        if (checkbox.checked) {
                            const addonPrice = parseFloat(checkbox.getAttribute("data-price"));
                            const priceElement = checkbox.parentElement.querySelector(".addon-price");
                            priceElement.textContent = `+ ₱${(addonPrice * currentItem.productQuantity).toFixed(2)}`;
                            
                            // Update addon in array
                            const addonID = checkbox.value;
                            const addonIndex = addons.findIndex(a => a.addonID === addonID);
                            if (addonIndex !== -1) {
                                addons[addonIndex].addonPrice = (addonPrice * currentItem.productQuantity).toFixed(2);
                            }
                        }
                    });
                    
                    updateTotalEdit();
                }
            });
        
            // Update total function for edit modal
            function updateTotalEdit() {
                let total = basePrice * currentItem.productQuantity;
                
                // Add addon prices
                addons.forEach(addon => {
                    total += parseFloat(addon.addonPrice);
                });
                
                currentItem.productTotal = total;
                currentItem.productAddons = addons;
                
                document.getElementById('totalAmountEdit').textContent = "₱" + total.toFixed(2);
            }
        
            // Save button
            document.getElementById('saveItemOrder').addEventListener('click', function() {
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
                        let orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];
                        
                        // Update all properties
                        orderItems[index] = {
                            ...orderItems[index],
                            productQuantity: currentItem.productQuantity,
                            productPrice: basePrice,
                            productVariation: currentItem.productVariation,
                            productAddons: addons,
                            productTotal: currentItem.productTotal
                        };
                        
                        localStorage.setItem('orderItems', JSON.stringify(orderItems));
                        
                        Swal.fire(
                            'Success!',
                            'Item successfully updated.',
                            'success'
                        );
                        updateOrderBar();
                        document.getElementById('closeEditModal').click();
                    }
                });
            });
        
            // Close button
            document.getElementById('closeEditButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Item will not be updated in your order list.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('closeEditModal').click();
                    }
                });
            });
        
            // Initialize the total
            updateTotalEdit();
        }

    }
});


/*
function updateTotal() {
    let totalAmountElement = document.getElementById("totalAmount");
    let total = orderItem.productPrice * orderItem.productQuantity;

    document.querySelectorAll(".addon-checkbox:checked").forEach(checkbox => {
        let addonPrice = parseFloat(checkbox.getAttribute("data-price"));
        total += addonPrice * orderItem.productQuantity;
    });

    totalAmountElement.textContent = "₱" + total.toFixed(2);
    orderItem.productTotal = total;
}*/


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
                                <span id=""  style="font-size: 14px;">
                                    ${item.productVariationName ? `(${item.productVariationName})` : ''}
                                </span>
                            </div>
                            <div class="col-4" style="justify-content: right; padding-left: 0px;">
                                <button id="editItemButton" type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editItemModal" data-item-index="${index}">
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
