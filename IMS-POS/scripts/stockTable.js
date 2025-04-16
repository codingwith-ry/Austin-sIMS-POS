$(document).ready(function () {
  // Initialize the DataTable
  const table = $("#stockTable").DataTable({
    ajax: {
      url: "fetchRecentStockPurchases.php", // Path to your PHP script
      dataSrc: "data", // Data will be inside the "data" key of the JSON response
      data: function (d) {
        // Get the selected date from the input (if available), otherwise default to today
        const selectedDate =
          $("#startDate").val() || new Date().toISOString().split("T")[0];

        // Get the selected period (weekly, monthly, or yearly)
        const selectedPeriod = $('input[name="btnradio"]:checked').val();

        d.startDate = selectedDate; // Send the startDate as a parameter to the backend
        d.period = selectedPeriod; // Send the selected period (weekly, monthly, yearly)
      },
    },
    columns: [
      {
        data: null,
        render: function (data, type, row) {
          return (
            '<input type="checkbox" class="itemCheckbox" value="' +
            row.Item_ID +
            '">'
          );
        },
      },
      { data: "Item_Name" }, // Item Name
      { data: "Category_Name" }, // Category Name
      { data: "Record_ItemQuantity" }, // Quantity
      { data: "Record_ItemVolume" }, // Volume
    ],
    columnDefs: [
      {
        targets: 0, // Target the checkbox column
        orderable: false, // Disable sorting for this column
      },
    ],
    order: [[1, "asc"]], // Default sorting by the Item Name column (index 1)
  });

  // Add event listener for opening and closing child rows
  $("#stockTable tbody").on("click", "tr", function () {
    var tr = $(this);
    var row = table.row(tr);

    // Toggle the child row (expand/collapse)
    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass("shown");
    } else {
      var rowData = row.data();

      // Build the content for the child row (image and employee assigned)
      var childRowContent = `
                    <div class="child-row">
                        <div class="image">
                            <img src="${rowData.Item_Image}" alt="Item Image" width="100">
                        </div>
                        <div class="employee">
                            <p><strong>Assigned Employee:</strong> ${rowData.Record_EmployeeAssigned}</p>
                        </div>
                    </div>
                `;

      // Show the child row
      row.child(childRowContent).show();
      tr.addClass("shown");
    }
  });

  // Event listener for radio buttons (Weekly, Monthly, Yearly)
  $('input[name="btnradio"]').on("change", function () {
    table.ajax.reload(); // Reload the table data when the radio button changes
  });
});
