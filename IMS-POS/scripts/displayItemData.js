// Format function to show child row details
function format(row) {
  return `
        <div class="p-2">
            <strong>Expiration Date:</strong> ${row.expiration || "N/A"}
        </div>
    `;
}

document.addEventListener("DOMContentLoaded", function () {
  const table = $("#recordTable").DataTable({
    responsive: true,
    columnDefs: [
      {
        targets: 0,
        className: "details-control",
        orderable: false,
        data: null,
        defaultContent:
          '<i class="fas fa-plus-circle text-primary" style="cursor:pointer;"></i>',
      },
    ],
    order: [[1, "asc"]],
  });

  // Expand/collapse row on control cell click
  $("#recordTable tbody").on("click", "td.details-control", function () {
    const tr = $(this).closest("tr");
    const row = table.row(tr);

    if (row.child.isShown()) {
      row.child.hide();
      tr.find("td.details-control i")
        .removeClass("fa-minus-circle text-danger")
        .addClass("fa-plus-circle text-primary");
    } else {
      const expiration = tr.data("expiration");
      row.child(format({ expiration: expiration })).show();
      tr.find("td.details-control i")
        .removeClass("fa-plus-circle text-primary")
        .addClass("fa-minus-circle text-danger");
    }
  });
});
