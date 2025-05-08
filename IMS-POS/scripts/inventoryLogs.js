$(document).ready(function () {
  // Initialize the DataTable
  const inventoryLogsTable = $("#inventoryLogsTable").DataTable({
      ajax: {
          url: "../../IMS-POS/scripts/fetchInventoryLogs.php", // Path to the PHP script
          dataSrc: "data", // Data will be inside the "data" key of the JSON response
      },
      columns: [
          { data: "Employee_Name", title: "Employee Name" }, // Employee Name
          { data: "Amount_Added", title: "Amount Added" }, // Amount Added
          { data: "Date_Time", title: "Date and Time" }, // Date and Time
          { data: "Previous_Sum", title: "Pre-Add Budget" }, // Previous Sum
          { data: "Stock_ID", title: "Stock ID" }, // Stock ID
      ],
      responsive: true, // Makes the table responsive
      paging: true, // Enables pagination
      searching: true, // Enables search functionality
      ordering: true, // Enables column sorting
      lengthChange: true, // Allows changing the number of rows displayed
      pageLength: 10, // Default number of rows per page
      language: {
          emptyTable: "No inventory logs available",
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          paginate: {
              previous: "Previous",
              next: "Next",
          },
      },
  });
});