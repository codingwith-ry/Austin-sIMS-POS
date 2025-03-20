/***************************INITIALIZATION OF ITEM RECORDS TABLE********************************/
function format(d) {
  return `
    <div class="row row-cols-6" style="align-items:center;">
      <div class="col"><img src="${
        d.Item_Image
      }" class="rounded border" style="width: 50px; height: auto;" /></div>
      <div class="col"><div style="font-weight: bold;">${
        d.Item_Name
      }</div><div>${d.Unit_Name}</div></div>
      <div class="col"><div style="font-weight: bold;">Category</div><div>${
        d.Category_Name
      }</div></div>
      <div class="col"><div style="font-weight: bold;">Quantity</div><div>${
        d.Record_ItemQuantity
      } units</div></div>
      <div class="col"><div style="font-weight: bold;">Expiration Date</div><div>${new Date(
        d.Record_ItemExpirationDate * 1000
      ).toLocaleDateString()}</div></div>
      <div class="col"><div style="font-weight: bold;">Price/unit</div><div>${
        d.Record_ItemPrice
      }</div></div>
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
