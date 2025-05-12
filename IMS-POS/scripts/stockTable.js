$(document).ready(function () {
  const table = $("#stockTable").DataTable({
    ajax: {
      url: "fetchRecentStockPurchases.php",
      dataSrc: "data",
      data: function (d) {
        const selectedDate =
          $("#startDate").val() || new Date().toISOString().split("T")[0];
        const selectedPeriod =
          $('input[name="btnradio"]:checked').val() || "weekly";

        d.startDate = selectedDate;
        d.period = selectedPeriod;
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
      { data: "Item_Name" },
      { data: "Category_Name" },
      { data: "Total_Quantity" },
      { data: "Total_Volume" },
    ],

    columnDefs: [
      {
        targets: 0,
        orderable: false,
      },
    ],
    order: [[1, "asc"]],
    initComplete: function () {
      updateLabel(); // Set initial label after first load
    },
  });

  function updateLabel() {
    const period = $('input[name="btnradio"]:checked').val() || "weekly";
    const periodLabels = {
      weekly: "Weekly Stock Expenses",
      monthly: "Monthly Stock Expenses",
      yearly: "Yearly Stock Expenses",
      daily: "Daily Stock Expenses",
    };
    $("#stockTableLabel").text(periodLabels[period] || "Stock Expenses");
  }

  // Update and reload on filter change
  $('input[name="btnradio"]').on("change", function () {
    updateLabel();
    table.ajax.reload();
  });

  $("#startDate").on("change", function () {
    // Optional: If you want date input to imply a "daily" view
    $('input[name="btnradio"]').prop("checked", false); // uncheck all
    updateLabel();
    table.ajax.reload();
  });

  // Optional: trigger load once at start
  updateLabel();
});
