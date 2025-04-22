
let editButton = document.getElementById("editItemModal");

/*
editButton.addEventListener("click", function() {
    let productId = this.getAttribute("data-product-id");
    let productName = this.getAttribute("data-product-name");
    let productPrice = this.getAttribute("data-product-price");
    let productDescription = this.getAttribute("data-product-description");
    let productImage = this.getAttribute("data-product-image");

    document.getElementById("editProductName").value = productName;
    document.getElementById("productPrice").value = productPrice;
    document.getElementById("productDescription").value = productDescription;
    document.getElementById("productImage").value = productImage;
}
);
*/

document.addEventListener('DOMContentLoaded', function () {
    // Get the "Add Another Add-on" button
    const addAddonButton = document.querySelector('.addonSelectAdd');
    console.log(addAddonButton); // Log the button to check if it's selected correctly
    const addonContainer = document.querySelector('.addonSelectContainer'); // Container for the add-ons

    console.log(addonContainer); // Log the container to check if it's selected correctly

    // Add event listener to the "Add Another Add-on" button
    addAddonButton.addEventListener('click', function () {
        // Create a new row for the add-on
        console.log(addAddonButton);
        console.log(addonContainer);
        
        const addonRow=`
            <div class="row mb-3 addonRow">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 d-flex ">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Choose an Add-on</option>
                                <option value="1">One - ₱100</option>
                                <option value="2">Two - ₱100</option>
                                <option value="3">Three - ₱100</option>
                            </select>
                            <button type="button" class="btn btn-light pt-2 delete-addon"><i class="fi fi-rr-trash text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        addonContainer.insertAdjacentHTML('beforeend', addonRow); // Append the new row to the container

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

    });

    newAddonButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the page from reloading
        const addAddonModal = new bootstrap.Modal(document.getElementById('addAddonModal'));
        addAddonModal.show(); // Show the modal programmatically
    });
});