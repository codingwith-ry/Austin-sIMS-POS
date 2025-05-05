/***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
function printTableWithChildren() {
  // Expand all child rows
  itemRecords.rows().every(function () {
    if (!this.child.isShown()) {
      this.child(format(this.data().items)).show();
    }
  });

  // Open new window for printing
  const printWindow = window.open("", "", "height=600,width=800");

  let htmlContent = `
    <html>
      <head>
        <title>Print Inventory</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
          body {
            font-family: 'Arial', sans-serif;
            margin: 30px;
            color: #333;
          }
          h3 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
          }
          .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
          }
          .table th, .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
          }
          .table th {
            background-color: #007bff;
            color: white;
          }
          .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
          }
          .table tbody tr:hover {
            background-color: #f1f1f1;
          }
          /* Custom styles for child rows to display horizontally */
          .child-row {
            display: flex;
            justify-content: space-between; /* Align items horizontally */
            flex-wrap: wrap;
            margin-bottom: 15px;
          }
          .child-row .col {
            flex: 1;
            min-width: 180px; /* Adjust this value based on your desired width for each column */
          }
          .child-row img {
            width: 60px;
            height: auto;
            margin-right: 10px;
          }
          .child-row div {
            font-size: 14px;
            padding: 5px;
          }
          .child-row .category,
          .child-row .quantity,
          .child-row .expiration,
          .child-row .price {
            text-align: center;
          }
          .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
          }
        </style>
      </head>
      <body>
        <h3>Inventory Records</h3>
        <div>
          <!-- Only include the table content, no other settings -->
          ${document.querySelector("#itemRecords_wrapper .table").outerHTML}
        </div>
        <div class="footer">
          <p>Printed on ${new Date().toLocaleString()}</p>
        </div>
      </body>
    </html>
  `;

  // Write the content to the print window and trigger the print process
  printWindow.document.write(htmlContent);
  printWindow.document.close();

  printWindow.focus();
  printWindow.print();
  printWindow.close();
}

// Trigger this function from a custom button
let customPrintBtn = document.getElementById("customPrintBtn");

if (customPrintBtn) {
  customPrintBtn.addEventListener("click", printTableWithChildren);
}

function format(groupItems) {
  let content = "";
  groupItems.forEach((d) => {
    content += `
      <div class="row" style="align-items: center; margin-bottom: 5px;">
        <div class="col-auto" style="display: flex; align-items: center;">
          <input class="form-check-input child-checkbox" type="checkbox" data-record-id="${d.Record_ID}" value="${d.Record_ID}">
        </div>
        <div class="col" style="display: flex; align-items: center;">
          <img src="${d.Item_Image}" class="rounded border" style="width: 40px; height: auto; margin-right: 5px;" />
          <div>
            <div style="font-weight: bold;">${d.Item_Name}</div>
            <div style="font-size: 12px;">${d.Record_ItemVolume} ${d.Unit_Name}</div>
          </div>
        </div>
        <div class="col" style="text-align: center;">
          <div style="font-weight: bold;">Category</div>
          <div>${d.Category_Name}</div>
        </div>
        <div class="col" style="text-align: center;">
          <div style="font-weight: bold;">Quantity</div>
          <div>${d.Record_ItemQuantity} units</div>
        </div>
        <div class="col" style="text-align: center;">
          <div style="font-weight: bold;">Expiration Date</div>
          <div>${d.Record_ItemExpirationDate}</div>
        </div>
        <div class="col" style="text-align: center;">
          <div style="font-weight: bold;">Price/unit</div>
          <div>${d.Record_ItemPrice}</div>
        </div>
      </div>
    `;
  });
  return content;
}
// ✅ Group data by purchase date
const groupedData = {};
if (window.inventoryData) {
  window.inventoryData.forEach((record) => {
    if (!groupedData[record.Record_ItemPurchaseDate]) {
      groupedData[record.Record_ItemPurchaseDate] = [];
    }
    groupedData[record.Record_ItemPurchaseDate].push(record);
  });
}

// ✅ Prepare rows - one per purchase date
const tableData = Object.keys(groupedData).map((purchaseDate, index) => {
  return {
    groupId: index,
    Record_ItemPurchaseDate: purchaseDate,
    Record_EmployeeAssigned: groupedData[purchaseDate][0].Employee_Name,
    items: groupedData[purchaseDate],
    Record_IDs: groupedData[purchaseDate]
      .map((item) => item.Record_ID)
      .join(","), // For checkbox selection
  };
});

let itemRecords = new DataTable("#itemRecords", {
  responsive: true,
  data: tableData,
  columns: [
    {
      data: null,
      orderable: false,
      className: `dt-checkbox-column`,
      render: function (data, type, row, meta) {
        return `<div class="form-check">
                  <input class="form-check-input row-checkbox" type="checkbox" data-record-ids="${row.Record_IDs}">
                </div>`;
      },
    },
    { data: "groupId", title: "Group ID" },
    { data: "Record_ItemPurchaseDate", title: "Purchase Date" },
    {
      data: "Record_EmployeeAssigned",
      title: "Employee Assigned",
      className: "text-end",
    },
    {
      className: "dt-control",
      orderable: false,
      data: null,
      defaultContent: '<i class="fa-solid fa-circle-chevron-down"></i>',
      title: "Details",
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

            // Select both .row-checkbox and .child-checkbox checkboxes
            const selectedRecords = Array.from(
              document.querySelectorAll(
                ".row-checkbox:checked, .child-checkbox:checked"
              )
            ).map((checkbox) => checkbox.getAttribute("data-record-id")); // Use data-record-id

            console.log("Selected Records: ", selectedRecords);

            // Check if no records are selected
            if (selectedRecords.length === 0) {
              Swal.fire({
                icon: "warning",
                title: "No Records Selected",
                text: "Please select at least one record to delete.",
              });
              return;
            }

            // Confirm delete action
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
                console.log("Sending AJAX request with data:", {
                  recordIds: selectedRecords,
                });
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
                      }).then(() => {
                        window.location.reload(); // Refresh the page after successful deletion
                      });
                    } else {
                      Swal.fire({
                        icon: "error",
                        title: "Error",
                        text:
                          res.message ||
                          "Failed to delete the selected records.",
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
              document.querySelectorAll(
                ".row-checkbox:checked, .child-checkbox:checked"
              ) // Include child-checkbox here
            ).map((checkbox) => checkbox.getAttribute("data-record-id")); // Get data-record-id instead of value

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
              url: "../IMS-POS/scripts/fetchRecord.php",
              type: "POST",
              data: { recordId: recordId },
              success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                  if (res.record) {
                    // Populate the edit modal with the record data
                    $("#editRecordModal #recordId").val(res.record.Record_ID);
                    $("#editRecordModal #itemVolume").val(
                      res.record.Record_ItemVolume
                    );
                    $("#editRecordModal #itemQuantity").val(
                      res.record.Record_ItemQuantity
                    );
                    $("#editRecordModal #itemPrice").val(
                      res.record.Record_ItemPrice
                    );
                    $("#editRecordModal #itemExpirationDate").val(
                      res.record.Record_ItemExpirationDate
                    );

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

// ✅ Expand/Collapse event to show grouped items
itemRecords.on("click", "td.dt-control", function (e) {
  let tr = e.target.closest("tr");
  let row = itemRecords.row(tr);
  if (row.child.isShown()) {
    row.child.hide();
  } else {
    row.child(format(row.data().items)).show();
  }
});

// ✅ Select All Checkbox logic
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

document.addEventListener("DOMContentLoaded", function () {
  const scrollContainer = document.querySelector(".custom-scrollbar");

  if (scrollContainer) {
    scrollContainer.addEventListener(
      "wheel",
      function (e) {
        // Only if there's horizontal overflow
        if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
          e.preventDefault(); // Prevent vertical scroll
          scrollContainer.scrollLeft += e.deltaY; // Scroll horizontally
        }
      },
      { passive: false }
    ); // passive: false is needed to use preventDefault
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#addItemModal form");
  const category = form.querySelector('select[name="item_category"]');
  const unit = form.querySelector('select[name="item_unit"]');
  const name = form.querySelector('input[name="item_name"]');
  const image = form.querySelector('input[name="image"]');

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!category.value || category.selectedIndex === 0) {
      Swal.fire("Missing Field", "Please select a category.", "warning");
      return;
    }

    if (!unit.value || unit.selectedIndex === 0) {
      Swal.fire(
        "Missing Field",
        "Please select a unit of measurement.",
        "warning"
      );
      return;
    }

    if (!name.value.trim()) {
      Swal.fire("Missing Field", "Please enter an item name.", "warning");
      return;
    }

    if (!image.files || image.files.length === 0) {
      Swal.fire("Missing Field", "Please upload an item image.", "warning");
      return;
    }

    // Optional: Confirmation before submitting
    Swal.fire({
      title: "Confirm Add",
      text: "Are you sure you want to add this item?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes, add it!",
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // now submit the form
      }
    });
  });
});
