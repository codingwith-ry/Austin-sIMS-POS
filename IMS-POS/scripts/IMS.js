/***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
function format(d) {
  return `
    <div class="row row-cols-6" style="align-items:center;">
      <div class="col"><img src="${d.Item_Image}" class="rounded border" style="width: 50px; height: auto;" /></div>
      <div class="col"><div style="font-weight: bold;">${d.Item_Name}</div><div>${d.Record_ItemVolume} ${d.Unit_Name}</div></div>
      <div class="col"><div style="font-weight: bold;">Category</div><div>${d.Category_Name}</div></div>
      <div class="col"><div style="font-weight: bold;">Quantity</div><div>${d.Record_ItemQuantity} units</div></div>
      <div class="col"><div style="font-weight: bold;">Expiration Date</div><div>${d.Record_ItemExpirationDate}</div></div>
      <div class="col"><div style="font-weight: bold;">Price/unit</div><div>${d.Record_ItemPrice}</div></div>
    </div>
  `;
}

let itemRecords = new DataTable("#itemRecords", {
  responsive: true,
  data: window.inventoryData, // ✅ USE THE PHP JSON DATA INSTEAD OF HARDCODED
  columns: [
    {
      data: null,
      orderable: false,
      className: `dt-checkbox-column`,
      render: function (data, type, row, meta) {
        return `<div class="form-check">
                  <input class="form-check-input row-checkbox" type="checkbox" value="${row.Record_ID}">
                </div>`;
      },
    },
    { data: "Record_ID" },
    { data: "Record_ItemPurchaseDate" },
    { data: "Record_EmployeeAssigned" },
    {
      className: "dt-control",
      orderable: false,
      data: null,
      defaultContent: '<i class="fa-solid fa-circle-chevron-down"></i>',
    },
  ],
  layout: {
    topEnd: {
      search: {
        placeholder: "Search Item",
      },
    },
    dom: '<"top"f>rt<"bottom"lp><"clear">',
    topStart: {
      buttons: [
        {
          extend: "collection",
          text: "Export",
          buttons: [
            {
              extend: "pdfHtml5",
              text: '<div><span><i class="bi bi-file-earmark-pdf-fill" style="color: red; font-size: 20px; margin-right: 5%;"></i>Export PDF</span></div>',
              titleAttr: "PDF",
            },
            {
              extend: "excelHtml5",
              text: '<div><span><i class="bi bi-file-earmark-excel-fill" style="color: green; font-size: 20px; margin-right: 5%;"></i>Export Excel</span></div>',
              titleAttr: "Excel",
            },
            {
              extend: "print",
              text: '<div><span><i class="bi bi-printer-fill" style="font-size: 20px; margin-right: 5%;"></i>Print Document</span></div>',
              titleAttr: "Print",
            },
          ],
        },
        {
          text: "Add Record",
          action: function (e, dt, node, config) {
            var addRecordModal = new bootstrap.Modal(
              document.getElementById("addRecordForm")
            );
            addRecordModal.show();
          },
        },
        {
          text: "Delete Record",
          action: function (e, dt, node, config) {
            console.log("Delete Record button clicked!");
            const selectedRecords = Array.from(
              document.querySelectorAll(".row-checkbox:checked")
            ).map((checkbox) => checkbox.value);

            console.log("Selected Records: ", selectedRecords);
        
            if (selectedRecords.length === 0) {
              Swal.fire({
                icon: "warning",
                title: "No Records Selected",
                text: "Please select at least one record to delete.",
              });
              return;
            }
        
            Swal.fire({
              title: "Are you sure?",
              text: "This action cannot be undone.",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d33",
              cancelButtonColor: "#3085d6",
              confirmButtonText: "Yes, delete it!",
            }).then((result) => {
              if (result.isConfirmed) {
                // Perform the delete operation via AJAX
                console.log("Sending AJAX request with data:", { recordIds: selectedRecords });
                $.ajax({
                  url: "../IMS-POS/scripts/deleteRecord.php", // Replace with your server-side delete endpoint
                  type: "POST",
                  data: { recordIds: selectedRecords },
                  success: function (response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                      Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "The selected records have been deleted.",
                      });
        
                      // Update the DataTable with the new data
                      itemRecords.clear().rows.add(res.updatedData).draw();
                    } else {
                      Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message || "Failed to delete the selected records.",
                      });
                    }
                  },
                  error: function () {
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Failed to delete the selected records.",
                    });
                  },
                });
              }
            });
          },
        },

        {
          text: "Edit Record",
          action: function (e, dt, node, config) {
            const selectedRecords = Array.from(
              document.querySelectorAll(".row-checkbox:checked")
            ).map((checkbox) => checkbox.value);
        
            console.log("Selected Records for Editing: ", selectedRecords);
        
            if (selectedRecords.length !== 1) {
              Swal.fire({
                icon: "warning",
                title: "Invalid Selection",
                text: "Please select exactly one record to edit.",
              });
              return;
            }
        
            const recordId = selectedRecords[0];
        
            // Fetch the record data for editing
            $.ajax({
              url: "../IMS-POS/scripts/fetchRecord.php", // Create this endpoint to fetch record details
              type: "POST",
              data: { recordId: recordId },
              success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                  if (res.record) {
                    // Populate the edit modal with the record data
                    $("#editRecordModal #recordId").val(res.record.Record_ID);
                    $("#editRecordModal #itemName").val(res.record.Item_Name);
                    $("#editRecordModal #itemVolume").val(res.record.Record_ItemVolume);
                    $("#editRecordModal #itemQuantity").val(res.record.Record_ItemQuantity);
                    $("#editRecordModal #itemPrice").val(res.record.Record_ItemPrice);
                    $("#editRecordModal #itemExpirationDate").val(res.record.Record_ItemExpirationDate);
              
                    // Populate the dropdown with all available items
                    const itemNameDropdown = document.getElementById("itemName");
                    itemNameDropdown.innerHTML = ""; // Clear existing options
              
                    // Add a default "Select Item" option
                    const defaultOption = document.createElement("option");
                    defaultOption.value = "";
                    defaultOption.textContent = "Select Item";
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    itemNameDropdown.appendChild(defaultOption);
              
                    // Add options dynamically
                    res.items.forEach((item) => {
                      const option = document.createElement("option");
                      option.value = item.Item_Name;
                      option.textContent = `${item.Item_Name} (${item.Unit_Name})`;
                      itemNameDropdown.appendChild(option);
                    });
              
                    // Set the selected value to the current record's item
                    itemNameDropdown.value = res.record.Item_Name;
              
                    // Show the edit modal
                    const editRecordModal = new bootstrap.Modal(
                      document.getElementById("editRecordModal")
                    );
                    editRecordModal.show();
                  } else {
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Record details are missing.",
                    });
                  }
                } else {
                  Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message || "Failed to fetch record details.",
                  });
                }
              },
              error: function () {
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Failed to fetch record details.",
                });
              },
            });
          },
        },



      ],
    },
  },
});

itemRecords.on("click", "td.dt-control", function (e) {
  let tr = e.target.closest("tr");
  let row = itemRecords.row(tr);
  if (row.child.isShown()) {
    row.child.hide();
  } else {
    row.child(format(row.data())).show();
  }
});

$("#select-all").on("change", function () {
  const isChecked = $(this).is(":checked");
  $(".row-checkbox").prop("checked", isChecked);
});
/***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/

/***************************INITIALIZATION IMAGE PREVIEW********************************/
function previewImage(event) {
  var reader = new FileReader();
  var preview = document.getElementById("imagePreview");

  reader.onload = function () {
    var image = new Image();
    image.src = reader.result;

    // Clear any previous image
    preview.innerHTML = "";

    // Append the new image to the preview div
    preview.appendChild(image);

    // Optionally, set a fixed size for the preview
    image.style.width = "100%";
    image.style.height = "100%";
    image.style.objectFit = "contain";
  };

  // Read the uploaded file
  reader.readAsDataURL(event.target.files[0]);
}

document.addEventListener("DOMContentLoaded", function () {
  // Attach click event listeners to all category buttons
  document.querySelectorAll(".category-btn").forEach((button) => {
    button.addEventListener("click", function () {
      // Remove active class from all buttons
      document.querySelectorAll(".category-btn").forEach((btn) => {
        btn.classList.remove("btn-primary"); // Remove 'active' class (btn-primary)
        btn.classList.add("btn-custom-outline"); // Reset to non-active button style
      });

      // Add active class to the clicked button
      this.classList.remove("btn-custom-outline");
      this.classList.add("btn-primary"); // Set as active

      // Get the selected category ID
      const categoryId = this.getAttribute("data-category");

      // Call the function to filter items
      filterItems(categoryId);
    });
  });

  function filterItems(categoryId) {
    const items = document.querySelectorAll(".product-item");

    // If "All" is selected, show all items
    if (categoryId === "all") {
      items.forEach((item) => {
        item.style.display = "block";
      });
    } else {
      items.forEach((item) => {
        const itemCategory = item.getAttribute("data-category");
        if (itemCategory === categoryId) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    }
  }

  // Default display of all items on page load
  filterItems("all");
});
