document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const productItems = document.querySelectorAll(".product-item");
  const categoryButtons = document.querySelectorAll(".category-btn");

  let currentCategory = "all";

  function filterItems() {
    const searchQuery = searchInput.value.toLowerCase();

    productItems.forEach((item) => {
      const name = item.getAttribute("data-name")?.toLowerCase() || "";
      const category = item.getAttribute("data-category")?.toLowerCase() || "";
      const categoryId = item.getAttribute("data-category-id") || "";

      const matchesSearch =
        name.includes(searchQuery) || category.includes(searchQuery);
      const matchesCategory =
        currentCategory === "all" || categoryId === currentCategory;

      // Only show if both filters match
      if (matchesSearch && matchesCategory) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  }

  // Trigger on typing in search bar
  searchInput.addEventListener("input", filterItems);

  // Trigger on clicking category button
  categoryButtons.forEach((button) => {
    button.addEventListener("click", function () {
      currentCategory = this.getAttribute("data-category");

      // Optional: visually mark active category
      categoryButtons.forEach((btn) => btn.classList.remove("btn-primary"));
      this.classList.add("btn-primary");

      filterItems(); // Run combined filter
    });
  });
});
