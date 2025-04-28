document.addEventListener('DOMContentLoaded', function () {
    //Handle Menu and Category Changes in Add Product Modal

    const menuSelect = document.getElementById('menuSelect');
    const categorySelect = document.getElementById('categorySelect');
    const addonContainer = document.querySelector('.addonSelectContainer');
    const addAddonButton = document.querySelector('.addonSelectAdd');
    let addonRow = ``;

    fetchMenuClasses();

    // Event listener for menuSelect changes
    menuSelect.addEventListener('change', function () {
        const selectedMenuID = menuSelect.value;

        if (selectedMenuID) {
            fetchCategories(selectedMenuID);
            fetchAddons(selectedMenuID); // Fetch addons based on the selected menu
        } else {
            // Clear categorySelect and addonContainer if no menu is selected
            categorySelect.innerHTML = '<option selected>Choose a category</option>';
            addonContainer.innerHTML = ''; // Clear addons
        }
    });

    // Function to fetch menu classes
    function fetchMenuClasses() {
        fetch('scripts/fetchMenuClasses.php')
            .then(response => response.json())
            .then(data => {
                menuSelect.innerHTML = '<option selected>Choose a menu class</option>';
                data.forEach(menu => {
                    menuSelect.innerHTML += `<option value="${menu.menuID}" data-menuname="${menu.menuName}">${menu.menuName}</option>`;
                });
            })
            .catch(error => console.error('Error fetching menu classes:', error));
    }

    // Function to fetch categories based on the selected menu
    function fetchCategories(menuID) {
        fetch(`scripts/fetchCategories.php?menuID=${menuID}`)
            .then(response => response.json())
            .then(data => {
                categorySelect.innerHTML = '<option selected>Choose a category</option>';
                data.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category.categoryID}" data-categoryname="${category.categoryName}">${category.categoryName}</option>`;
                });
            })
            .catch(error => console.error('Error fetching categories:', error));
    }

    // Function to fetch addons based on the selected menu
    function fetchAddons(menuID) {
        fetch(`scripts/fetchAddons.php?menuID=${menuID}`)
            .then(response => response.json())
            .then(data => {
                addonContainer.innerHTML = ''; // Clear existing addons
                addonRow = ``;
                addonRow += `
                        <div class="row mb-3 addonRow">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                    
                `;
                if(data.length === 0) {
                    addonRow += `<span class="text-danger">No add-ons available for this menu.</span>`;
                    document.getElementById('addAddonButton').disabled = true;
                } else {   
                    document.getElementById('addAddonButton').disabled = false;
                    addonRow += `
                                <select class="form-select addon-dropdown" aria-label="Default select example">
                    `;             
                    data.forEach(addon => {
                        addonRow += `
                                    <option value="${addon.addonID}" data-name="${addon.addonName}"
                data-price="${addon.addonPrice}">${addon.addonName} - ₱${addon.addonPrice}</option>
                        `;
                    });
                    addonRow += `
                            </select>
                            <button type="button" class="btn btn-light pt-2 delete-addon">
                                <i class="fi fi-rr-trash text-danger"></i>
                            </button>
                    `;
                }

                addonRow += `
                            </div>
                        </div>
                    </div>
                </div>`;
                if(data.length === 0) {
                    addonContainer.insertAdjacentHTML('beforeend', addonRow);
                }
                // Attach delete functionality to the new addon rows
                attachDeleteAddonListeners();
            })
            .catch(error => console.error('Error fetching addons:', error));
    }

    // Function to attach delete functionality to addon rows
    function attachDeleteAddonListeners() {
        const deleteButtons = addonContainer.querySelectorAll('.delete-addon');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const rowToDelete = this.closest('.addonRow'); // Find the closest parent row

                // Show SweetAlert2 confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this add-on?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, remove the row
                        if (rowToDelete) {
                            rowToDelete.remove();
                        }
                        Swal.fire(
                            'Deleted!',
                            'The add-on has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    }

    // Handle Add-on Selection in Add Product Modal
    addAddonButton.addEventListener('click', function () {
        addonContainer.insertAdjacentHTML('beforeend', addonRow);
        attachDeleteAddonListeners(); // Reattach delete functionality
    });

    const addAddonModal = document.getElementById('addAddonModal');
    const addProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));

    // Listen for the 'hidden.bs.modal' event on the addAddonModal
    addAddonModal.addEventListener('hidden.bs.modal', function () {
        // Show the addProductModal when the addAddonModal is closed
        document.getElementById('addonName').value = '';
        document.getElementById('addonPriceValue').value = '';
        addProductModal.show();
    });
    
    // Listen for the 'show.bs.modal' event on the addAddonModal
    addAddonModal.addEventListener('show.bs.modal', function () {
        const menuClassName = document.getElementById('menuClassName');
        menuClassName.innerHTML = ''; // Clear previous options
        const selectedMenuText = menuSelect.options[menuSelect.selectedIndex].text;

        menuClassName.value = selectedMenuText;
    });


    const addAddonConfirm = document.getElementById('addAddonConfirm');
    const cancelAddonConfirm = document.getElementById('cancelAddonConfirm');
    const closeAddonModal = document.getElementById('closeAddonModal');

    // Handle Add Add-on Confirmation
    addAddonConfirm.addEventListener('click', function () {
        let addonName = document.getElementById('addonName').value;
        let addonPrice = document.getElementById('addonPriceValue').value;
        let menuClassName = document.getElementById('menuClassName').value;

        if(!addonName || !addonPrice || !menuClassName) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        }
        else{
            Swal.fire({
                title: 'Add New Add-on',
                text: "Are you sure you want to add a new add-on?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logic to add the new add-on
                    fetch('scripts/addAddon.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            addonName: addonName,
                            addonPrice: addonPrice,
                            menuClassName: menuClassName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Successfully added the add-on
                            Swal.fire(
                                'Added!',
                                'The new add-on has been added successfully.',
                                'success'
                            );
                            // Optionally, you can close the modal or refresh the page
                            const addAddonModal = bootstrap.Modal.getInstance(document.getElementById('addAddonModal'));
                            addAddonModal.hide();
                            document.getElementById('addonName').value = '';
                            document.getElementById('addonPriceValue').value = '';
                            // Refresh the add-ons list or perform any other action
                            fetchAddons(menuSelect.value);
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'An error occurred while adding the add-on.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error adding add-on:', error);
                        Swal.fire(
                            'Error!',
                            data.message || 'An error occurred while adding the add-on.',
                            'error'
                        );
                    });
                }
            });
        } 
    });
    // Handle Cancel Add-on Confirmation
    cancelAddonConfirm.addEventListener('click', function (event) {
        
        Swal.fire({
            title: 'Cancel Add-on?',
            text: "Are you sure you want to cancel adding this add-on?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Close the modal
                const addAddonModal = bootstrap.Modal.getInstance(document.getElementById('addAddonModal'));
                addAddonModal.hide();
            }
        });

        closeAddonModal.addEventListener('click', function (event) {
            
            Swal.fire({
                title: 'Cancel Add-on?',
                text: "Are you sure you want to cancel adding this add-on?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Close the modal
                    const addAddonModal = bootstrap.Modal.getInstance(document.getElementById('addAddonModal'));
                    addAddonModal.hide();
                }
            });
        });
    });

    // Handle Add Variation Button in Add Product Modal
    const addVariationButton = document.getElementById('addVariationButton');
    const addVariationContainer = document.querySelector('.addVariationContainer'); 

    // Add variation card when button is clicked
    addVariationButton.addEventListener('click', function() {
        const variationCard = `
            <div id="variationCard" class="card mb-3">
                <div class="card-header fw-bold" id="variationHeader">
                    <div class="row">
                        <div class="col-6 fs-5 mt-1">Variation</div>
                        <div class="col-6 ms-auto" style="text-align: right;"> 
                            <button type="button" class="btn btn-danger removeVariationButton" aria-label="Close">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <span class="fw-bold">Variation Name</span>
                    <input type="text" class="form-control mb-3 variationName" placeholder="ex. 12oz or 15 pax">
                    <span class="fw-bold">Variation Price</span>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-success text-white">₱</span>
                        <input type="text" 
                        pattern="\\d*\\.?\\d{0,2}"
                        class="form-control variationPrice"
                        placeholder="ex. 200"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..{0,2}).*/g, '$1')"
                        >
                    </div>
                </div>
            </div>
        `;

        addVariationContainer.insertAdjacentHTML('beforeend', variationCard);
        attachRemoveVariationListeners();
    });

    const variationCardIdentifier = document.querySelectorAll('#variationCard');
    

    // Function to attach event listeners to remove buttons
    function attachRemoveVariationListeners() {
        const removeButtons = document.querySelectorAll('.removeVariationButton');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                Swal.fire({
                    title: 'Remove Variation?',
                    text: "Are you sure you want to remove this variation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const card = this.closest('#variationCard');
                        card.remove();
                        Swal.fire(
                            'Removed!',
                            'The variation has been removed.',
                            'success'
                        );
                    }
                });
            });
        });
    }

    //Format Price Input Function
    function formatPriceInput(input) {
        input.addEventListener('blur', function() {
            let value = this.value.trim();
            
            if (value === '') return;
    
            // If there's no decimal point, add '.00'
            if (!value.includes('.')) {
                this.value = value + '.00';
            }
            // If there's a decimal point but no numbers after it, add '00'
            else if (value.endsWith('.')) {
                this.value = value + '00';
            }
            // If there's only one digit after decimal point, add '0'
            else if (/\.\d$/.test(value)) {
                this.value = value + '0';
            }
        });
    }

    // Format default price
    const defaultPriceInput = document.getElementById('defaultPrice');
    if (defaultPriceInput) {
        formatPriceInput(defaultPriceInput);
    }

    // Format addon price
    const addonPriceInput = document.getElementById('addonPriceValue');
    if (addonPriceInput) {
        formatPriceInput(addonPriceInput);
    }

    // Format variation prices (including dynamically added ones)
    if (addVariationButton) {
        addVariationButton.addEventListener('click', function() {
            // Wait for the DOM to update
            setTimeout(() => {
                document.querySelectorAll('.variationPrice').forEach(input => {
                    formatPriceInput(input);
                });
            }, 100);
        });
    }

    //Add Product Confirmation

    const addProductButton = document.getElementById('addProductButton');
    addProductButton.addEventListener('click', function() {

        // Get all product data
        const productName = document.getElementById('exampleFormControlInput1').value;
        const menuSelect = document.getElementById('menuSelect');
        const categorySelect = document.getElementById('categorySelect');
        const defaultPrice = document.getElementById('defaultPrice').value;

        if (!productName) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Product Name',
                text: 'Please enter a product name!'
            });
            return;
        }

        // Validate menu and category selection
        if (menuSelect.selectedIndex === 0 || categorySelect.selectedIndex === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Selection',
                text: 'Please select both menu class and category!'
            });
            return;
        }

        // Validate default price
        if (!defaultPrice) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Price',
                text: 'Please enter a default price!'
            });
            return;
        }

        // Get selected addons
        const addonSelects = document.querySelectorAll('.addon-dropdown');
        const selectedAddons = Array.from(addonSelects).map(select => ({
            id: select.value,
            name: select.options[select.selectedIndex].dataset.name,
            price: select.options[select.selectedIndex].dataset.price
        }));

        // Check for duplicate addons
        const addonIds = selectedAddons.map(addon => addon.id);
        if (new Set(addonIds).size !== addonIds.length) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Add-ons',
                text: 'Please remove duplicate add-ons!'
            });
            return;
        }

        // Get variations
        const variationCards = document.querySelectorAll('#variationCard');
        const variations = Array.from(variationCards).map(card => ({
            name: card.querySelector('.variationName').value,
            price: card.querySelector('.variationPrice').value
        }));

        const emptyVariations = variations.find(v => !v.name || isNaN(v.price));
        if (emptyVariations) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Variations',
                text: 'Please fill in all variation names and prices!'
            });
            return;
        }

        // Check for duplicate variation names
        const variationNames = variations.map(v => v.name);
        if (new Set(variationNames).size !== variationNames.length) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Variations',
                text: 'Each variation must have a unique name!'
            });
            return;
        }

        const variationPrices = variations.map(v => v.price);
        if (new Set(variationPrices).size !== variationPrices.length) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Variation Prices',
                text: 'Each variation must have a unique price!'
            });
            return;
        }


        // Validate variations against default price
        if (variations.length > 0) {
            const defaultPriceValue = defaultPrice;
            
            if (variations.length === 1) {
                // If only one variation, price must match default price
                if (variations[0].price !== defaultPriceValue) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Price Mismatch',
                        text: 'With single variation, its price must match the default price!'
                    });
                    return;
                }
            } else {
                
                // If multiple variations, lowest price must match default price
                const lowestPrice = Math.min(...variations.map(v => parseFloat(v.price))).toFixed(2);
                const defaultPriceValue = parseFloat(defaultPrice).toFixed(2);
                console.log(lowestPrice);
                if (lowestPrice !== defaultPriceValue) {
                    console.log(lowestPrice, defaultPriceValue);
                    Swal.fire({
                        icon: 'error',
                        title: 'Price Mismatch',
                        text: 'The lowest variation price must match the default price!'
                    });
                    return;
                }
            }
        }

        // If all validations pass, compile the data
        const productData = {
            productName: productName,
            menuID: menuSelect.value,
            menuName: menuSelect.options[menuSelect.selectedIndex].dataset.menuname,
            categoryID: categorySelect.value,
            categoryName: categorySelect.options[categorySelect.selectedIndex].dataset.categoryname,
            defaultPrice: defaultPrice,
            addons: selectedAddons,
            variations: variations
        };
        console.log(productData); // For debugging

        Swal.fire({
            title: 'Product Data Preview',
            html: `
                <div style="text-align: left; font-family: 'Arial', sans-serif;">
                    <div style="background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="card" style="margin-bottom: 20px;">
                            <div class="card-body">
                                <h3 class="fw-bold" style="color: #2c3e50; margin-bottom: 15px; font-size: 1.2em;">Basic Information</h3>
                                <hr />
                                <div style="padding-left: 15px;">
                                    <p style="margin: 8px 0;"><strong>Product Name:</strong> <span style="color: #2c3e50">${productData.productName}</span></p>
                                    <p style="margin: 8px 0;"><strong>Menu Class:</strong> <span style="color: #2c3e50">${productData.menuName}</span></p>
                                    <p style="margin: 8px 0;"><strong>Category:</strong> <span style="color: #2c3e50">${productData.categoryName}</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card" style="margin-bottom: 20px;">
                            <div class="card-body">
                                <h3 class="fw-bold" style="color: #2c3e50; margin-bottom: 15px; font-size: 1.2em;">Add-ons</h3>
                                <hr />
                                <div>
                                    ${productData.addons.length ? 
                                        productData.addons.map(addon => `
                                            <div style="display: flex; justify-content: space-between; margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                                                <span style="color: #2c3e50">${addon.name}</span>
                                                <span style="color: #27ae60">₱${addon.price}</span>
                                            </div>
                                        `).join('') : 
                                        '<p style="color: #7f8c8d; font-style: italic;">No add-ons selected</p>'
                                    }
                                </div>
                            </div>
                        </div>
        
                        <div class="card"style="margin-bottom: 20px;">
                            <div class="card-body">
                                <h3 class="fw-bold" style="color: #2c3e50; margin-bottom: 15px; font-size: 1.2em;">Variations</h3>
                                <hr />
                                <div>
                                    ${productData.variations.length ? 
                                        productData.variations.map(v => `
                                            <div style="display: flex; justify-content: space-between; margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                                                <span style="color: #2c3e50">${v.name}</span>
                                                <span style="color: #27ae60">₱${v.price}</span>
                                            </div>
                                        `).join('') : 
                                        '<p style="color: #7f8c8d; font-style: italic;">No variations added</p>'
                                    }
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 20px;">
                            <div class="card-body">
                                <div style="display: flex; justify-content: space-between; margin: 8px 0; padding: 8px; border-radius: 4px;">
                                    <span style="color: #2c3e50"><strong>Default Price:</strong></span>
                                    <span class="fw-bold" style="color: #27ae60">₱${productData.defaultPrice}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            padding: '20px',
            background: '#f8f9fa',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check"></i> Proceed',
            cancelButtonText: '<i class="fas fa-times"></i> Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                //Send data to the server
                fetch('scripts/addProduct.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(productData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Product has been added successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Close modal and reset form
                            const addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                            addProductModal.hide();
                            // Optionally refresh the page or product list
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to add product');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message
                    });
                });
            }
        });
    });

    const editAddonContainer = document.querySelector('.editAddonSelectContainer');

    function fetchEditMenuClasses() {
        fetch('scripts/fetchMenuClasses.php')
            .then(response => response.json())
            .then(data => {
                editMenuSelect.innerHTML = '<option selected>Choose a menu class</option>';
                data.forEach(menu => {
                    editMenuSelect.innerHTML += `<option value="${menu.menuID}" data-menuname="${menu.menuName}">${menu.menuName}</option>`;
                });
            })
            .catch(error => console.error('Error fetching menu classes:', error));
    }

    // Fetch categories based on selected menu
    function fetchEditCategories(menuID) {
        return fetch(`scripts/fetchCategories.php?menuID=${menuID}`)
            .then(response => response.json())
            .then(data => {
                editCategorySelect.innerHTML = '<option selected>Choose a category</option>';
                data.forEach(category => {
                    editCategorySelect.innerHTML += `<option value="${category.categoryID}" data-categoryname="${category.categoryName}">${category.categoryName}</option>`;
                });
            })
            .catch(error => console.error('Error fetching categories:', error));
    }

    function addAddonSelect(container, addons) {
        // Get the current menuID for the edit modal
        const menuID = document.getElementById('editMenuSelect').value;
    
        fetch(`scripts/fetchAddons.php?menuID=${menuID}`)
            .then(response => response.json())
            .then(data => {
                addons.forEach(addon => {
                    let addonRow = `
                        <div class="row mb-3 addonRow">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 d-flex">
                                        <select class="form-select addon-dropdown" aria-label="Default select example">
                    `;
        
                    data.forEach(opt => {
                        addonRow += `
                            <option value="${opt.addonID}" data-name="${opt.addonName}" data-price="${opt.addonPrice}" ${opt.addonID == addon.addonID ? 'selected' : ''}>
                                ${opt.addonName} - ₱${opt.addonPrice}
                            </option>
                        `;
                    });

                    addonRow += `
                                        </select>
                                        <button type="button" class="btn btn-light pt-2 delete-addon">
                                            <i class="fi fi-rr-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
        
                    container.insertAdjacentHTML('beforeend', addonRow);
                    editAttachDeleteAddonListeners();
                });
            })
            .catch(error => console.error('Error fetching addons:', error));
    }
    
    function fetchEditAddons(menuID) {
        fetch(`scripts/fetchAddons.php?menuID=${menuID}`)
            .then(response => response.json())
            .then(data => {
                editAddonContainer.innerHTML = '';
                addonRow = ``;
                addonRow += `
                    <div class="row mb-3 addonRow">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 d-flex">
                `;
                if(data.length === 0) {
                    addonRow += `<span class="text-danger">No add-ons available for this menu.</span>`;
                    document.getElementById('editAddonButton').disabled = true;
                } else {   
                    document.getElementById('editAddonButton').disabled = false;
                    addonRow += `
                        <select class="form-select addon-dropdown" aria-label="Default select example">
                    `;             
                    data.forEach(addon => {
                        addonRow += `
                            <option value="${addon.addonID}" data-name="${addon.addonName}"
                                data-price="${addon.addonPrice}">${addon.addonName} - ₱${addon.addonPrice}</option>
                        `;
                    });
                    addonRow += `
                            </select>
                            <button type="button" class="btn btn-light pt-2 delete-addon">
                                <i class="fi fi-rr-trash text-danger"></i>
                            </button>
                    `;
                }
                addonRow += `
                                </div>
                            </div>
                        </div>
                    </div>`;
                if(data.length === 0) {
                    editAddonContainer.insertAdjacentHTML('beforeend', addonRow);
                }
                // Attach delete functionality to the new addon rows
                editAttachDeleteAddonListeners();
            })
            .catch(error => console.error('Error fetching addons:', error));
    }

    function editAttachDeleteAddonListeners() {
        const deleteButtons = editAddonContainer.querySelectorAll('.delete-addon');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const rowToDelete = this.closest('.addonRow'); // Find the closest parent row

                // Show SweetAlert2 confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this add-on?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, remove the row
                        if (rowToDelete) {
                            rowToDelete.remove();
                        }
                        Swal.fire(
                            'Deleted!',
                            'The add-on has been deleted.',
                            'success'
                        );
                    }
                });
            });
        });
    }


    function initializeEditProductModal() {
        // Initialize elements for edit product modal

        const editMenuSelect = document.getElementById('editMenuSelect');
        const editCategorySelect = document.getElementById('editCategorySelect');
        const editAddonContainer = document.querySelector('.editAddonSelectContainer');
        const editAddonButton = document.getElementById('editAddonButton');
        const editDefaultPriceInput = document.getElementById('editDefaultPrice');
        let addonRow = ``;
    
        // Format price inputs
        if (editDefaultPriceInput) {
            formatPriceInput(editDefaultPriceInput);
        }
    
        fetchEditMenuClasses();
        // Fetch and populate menu classes
        
    
        // Handle menu selection change
        editMenuSelect.addEventListener('change', function() {
            const selectedMenuID = editMenuSelect.value;
            if (selectedMenuID) {
                fetchEditCategories(selectedMenuID);
                fetchEditAddons(selectedMenuID);
            } else {
                editCategorySelect.innerHTML = '<option selected>Choose a category</option>';
                editAddonContainer.innerHTML = '';
            }
        });

        
    
        editAddonButton.addEventListener('click', function () {
            editAddonContainer.insertAdjacentHTML('beforeend', addonRow);
            editAttachDeleteAddonListeners(); // Reattach delete functionality
        });


        // Handle add variation button
        const editVariationButton = document.getElementById('editVariationButton');
        const editVariationContainer = document.querySelector('.editVariationContainer');
    
        if (editVariationButton) {
            editVariationButton.addEventListener('click', function() {
                const variationCard = `
                    <div id="variationCard" class="card mb-3">
                        <div class="card-header fw-bold" id="variationHeader">
                            <div class="row">
                                <div class="col-6 fs-5 mt-1">Variation</div>
                                <div class="col-6 ms-auto" style="text-align: right;"> 
                                    <button type="button" class="btn btn-danger removeVariationButton" aria-label="Close">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="fw-bold">Variation Name</span>
                            <input type="text" class="form-control mb-3 variationName" placeholder="ex. 12oz">
                            <span class="fw-bold">Variation Price</span>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success text-white">₱</span>
                                <input type="text" 
                                    pattern="\\d*\\.?\\d{0,2}"
                                    class="form-control variationPrice"
                                    placeholder="ex. 200"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..{0,2}).*/g, '$1')"
                                >
                            </div>
                        </div>
                    </div>
                `;
    
                editVariationContainer.insertAdjacentHTML('beforeend', variationCard);
                attachRemoveVariationListeners();
    
                // Format the price input in the new variation card
                setTimeout(() => {
                    const newPriceInput = editVariationContainer.querySelector('.variationPrice:last-child');
                    if (newPriceInput) {
                        formatPriceInput(newPriceInput);
                    }
                }, 100);
            });
        }
    
        // Handle Save Changes button
        const saveChangesButton = document.getElementById('saveChangesButton');
        if (saveChangesButton) {
            saveChangesButton.addEventListener('click', function() {
                // Gather all the updated data
                const editedData = {
                    productName: document.getElementById('editProductName').value,
                    menuID: editMenuSelect.value,
                    menuName: editMenuSelect.options[editMenuSelect.selectedIndex].dataset.menuname,
                    categoryID: editCategorySelect.value,
                    categoryName: editCategorySelect.options[editCategorySelect.selectedIndex].dataset.categoryname,
                    defaultPrice: editDefaultPriceInput.value,
                    addons: Array.from(editAddonContainer.querySelectorAll('.addon-dropdown')).map(select => ({
                        id: select.value,
                        name: select.options[select.selectedIndex].dataset.name,
                        price: select.options[select.selectedIndex].dataset.price
                    })),
                    variations: Array.from(editVariationContainer.querySelectorAll('#variationCard')).map(card => ({
                        name: card.querySelector('.variationName').value,
                        price: card.querySelector('.variationPrice').value
                    }))
                };
    
                // Perform the same validations as in Add Product
                // ... (copy validation code from add product)
    
                // If validations pass, show confirmation dialog
                Swal.fire({
                    title: 'Save Changes?',
                    text: "Are you sure you want to save these changes?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save changes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send updated data to server
                        fetch('scripts/updateProduct.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(editedData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Product has been updated successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    const editProductModal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                                    editProductModal.hide();
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Failed to update product');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message
                            });
                        });
                    }
                });
            });
        }

    }

    initializeEditProductModal();

    document.querySelectorAll('#editProductBtn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const menuId = this.dataset.menuId;
            
            // Populate basic information
            document.getElementById('editProductName').value = this.dataset.productName;
            document.getElementById('editDefaultPrice').value = this.dataset.productPrice;
            //document.getElementById('editProductImage').src = this.dataset.productImage;

            // Fetch product details including addons and variations
            fetch(`scripts/fetchProductDetails.php?productId=${productId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate menu select
                    const editMenuSelect = document.getElementById('editMenuSelect');
                    editMenuSelect.value = menuId;
                    console.log(menuId);
                    let editCategorySelect = document.getElementById('editCategorySelect');
                    // Fetch and populate categories for selected menu
                    fetchEditCategories(menuId).then(() => {
                        console.log(editCategorySelect);
                        editCategorySelect.value = data.product.categoryID;
                    }).then(() => {
                        let editAddonButton = document.getElementById('editAddonButton');
                        let addonRow = `
                            <div class="row mb-3 addonRow">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <select class="form-select addon-dropdown" aria-label="Default select example">
                        `;
                        if (data.length === 0) {
                            addonRow += `<option disabled selected>No add-ons available for this menu.</option>`;
                            editAddonButton.disabled = true;
                        } else {
                            editAddonButton.disabled = false;
                            console.log(data.addons);
                            data.addons.forEach(addon => {
                                addonRow += `
                                    <option value="${addon.addonID}" data-name="${addon.addonName}" data-price="${addon.addonPrice}">
                                        ${addon.addonName} - ₱${addon.addonPrice}
                                    </option>
                                `;
                                console.log(addon.addonID, addon.addonName, addon.addonPrice);
                            });
                        }
                        addonRow += `
                                            </select>
                                            <button type="button" class="btn btn-light pt-2 delete-addon">
                                                <i class="fi fi-rr-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        editAddonContainer.insertAdjacentHTML('beforeend', addonRow);
                        editAttachDeleteAddonListeners();
                    }).then(() => {
                        const editVariationContainer = document.querySelector('.editVariationContainer');
                        editVariationContainer.innerHTML = '';
                        if (data.variations.length > 0) {
                            data.variations.forEach(variation => {
                                const variationCard = `
                                    <div id="variationCard" class="card mb-3">
                                        <div class="card-header fw-bold">
                                            <div class="row">
                                                <div class="col-6 fs-5 mt-1">Variation</div>
                                                <div class="col-6 ms-auto" style="text-align: right;"> 
                                                    <button type="button" class="btn btn-danger removeVariationButton">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <span class="fw-bold">Variation Name</span>
                                            <input type="text" class="form-control mb-3 variationName" 
                                                value="${variation.variationName}" placeholder="ex. 12oz">
                                            <span class="fw-bold">Variation Price</span>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-success text-white">₱</span>
                                                <input type="text" 
                                                    pattern="\\d*\\.?\\d{0,2}"
                                                    class="form-control variationPrice"
                                                    value="${variation.variationPrice}"
                                                    placeholder="ex. 200"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..{0,2}).*/g, '$1')"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                `;
                                editVariationContainer.insertAdjacentHTML('beforeend', variationCard);
                            });
                        }
                    });
                })
                .catch(error => console.error('Error fetching product details:', error));
        });
    });
});

