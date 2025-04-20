
let editButton = document.getElementById("editItemModal");

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