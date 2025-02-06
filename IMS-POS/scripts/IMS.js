/***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
function format(d) {
  return (
    "<div style'align-items:center;'>" +
    // Image Section
    '<div class="row row-cols-6" style="align-items:center;">' +
    '<div class="col">' +
    '<img src="images/milk.jpg" class="rounded border" style="width: 50px; height: auto;" />' +
    "</div>" +
    // Product Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Fresh Milk</div>' +
    "<div>1000mL</div>" +
    "</div>" +
    '<div class="col">' +
    '<div style="font-weight: bold;">Category</div>' +
    "<div>Dairy</div>" +
    "</div>" +
    // Quantity Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Quantity</div>' +
    "<div>10 cartons</div>" +
    "</div>" +
    // Expiration Date
    '<div class="col">' +
    '<div style="font-weight: bold;">Expiration Date</div>' +
    "<div>10/24/2026</div>" +
    "</div>" +
    // Price Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Price/unit</div>' +
    "<div>120.00</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "<hr />" +
    "<div>" +
    // Image Section
    '<div class="row row-cols-6" style="align-items:center;">' +
    '<div class="col">' +
    '<img src="images/milk.jpg" class="rounded border" style="width: 50px; height: auto;" />' +
    "</div>" +
    // Product Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Fresh Milk</div>' +
    "<div>1000mL</div>" +
    "</div>" +
    '<div class="col">' +
    '<div style="font-weight: bold;">Category</div>' +
    "<div>Dairy</div>" +
    "</div>" +
    // Quantity Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Quantity</div>' +
    "<div>10 cartons</div>" +
    "</div>" +
    // Expiration Date
    '<div class="col">' +
    '<div style="font-weight: bold;">Expiration Date</div>' +
    "<div>10/24/2026</div>" +
    "</div>" +
    // Price Info
    '<div class="col">' +
    '<div style="font-weight: bold;">Price/unit</div>' +
    "<div>120.00</div>" +
    "</div>" +
    "</div>" +
    "</div>"
  );
}

let itemRecords = new DataTable("#itemRecords", {
  responsive: true,
  data: [
    {
      Inventory_ID: "1",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Owen Trinidaddy",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Daryl Tuminang",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Dominican Adino",
    },
    {
      Inventory_ID: "1",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Owen Trinidaddy",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Daryl Tuminang",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Dominican Adino",
    },
    {
      Inventory_ID: "1",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Owen Trinidaddy",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Daryl Tuminang",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Dominican Adino",
    },
    {
      Inventory_ID: "1",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Owen Trinidaddy",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Daryl Tuminang",
    },
    {
      Inventory_ID: "2",
      Purchase_Date: "12/10/2022",
      Employee_Assigned: "Dominican Adino",
    },
  ],
  columns: [
    {
      data: null,
      orderable: false,
      className: `dt-checkbox-column`,
      render: function (data, type, row, meta) {
        return `<div class="form-check">
                  <input class="form-check-input row-checkbox" type="checkbox" value="${row.Inventory_ID}">
                </div>`;
      },
    },
    { data: "Inventory_ID" },
    { data: "Purchase_Date" },
    { data: "Employee_Assigned" },
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
              text: '<div><span><i class="bi bi-file-earmark-pdf-fill" style="color: red; font-size: 20px; margin-right: 5%;""></i>Export PDF</span></div>',
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
          text: "Add Item",
          action: function (e, dt, node, config, cb) {
            var addItemHTML = `
                            <div class="modal" id="addItemForm">
                                <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Item</h5>
                                        </div>
                                    <div class="modal-body">
                                        <form id="myForm">
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Item Name</span>
                                                <div class="col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Category</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select Category</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Units</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select unit of measurement</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Unit Sales Price</span>
                                                <div class="col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Purchase Date</span>
                                                <div class="flatpickr col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Select Date" data-input class="dateInputField">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Expiration Date</span>
                                                <div class="flatpickr col-sm-8">
                                                    <input class="form-control" id="focusedInput" type="text" placeholder="Select Date" data-input class="dateInputField">
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:flex">
                                                <span class="col-sm-4 control-label">Employee Assigned</span>
                                                <div class="col-sm-8">
                                                    <select class="form-select">
                                                        <option selected>Select Employee</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            `;
            $("body").append(addItemHTML);

            flatpickr(".flatpickr", {
              enableTime: false,
              dateFormat: "Y-M-D",
            });

            var myModal = new bootstrap.Modal(
              document.getElementById("addItemForm")
            );
            myModal.show();
          },
        },
      ],
    },
  },
});

itemRecords.on("click", "td.dt-control", function (e) {
  let tr = e.target.closest("tr");
  let row = itemRecords.row(tr);
  let icon = $(this).find("i");

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
