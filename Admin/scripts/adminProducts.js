

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
                    menuSelect.innerHTML += `<option value="${menu.menuID}">${menu.menuName}</option>`;
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
                    categorySelect.innerHTML += `<option value="${category.categoryID}">${category.categoryName}</option>`;
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
                                    <option value="${addon.addonID}">${addon.addonName} - ₱${addon.addonPrice}</option>
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

    


});

