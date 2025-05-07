const tableData = [
  {
    Employee: "Daryl Tumaneng",
    "Amount Added": "5000",
    "Date and Time": "2025-05-07 14:30:00",
    "Pre-Add Budget": "1000",
    "Updated Budget": "60000",
  },
];

let inventoryLogs = new DataTable("#inventoryLogsTable", {
  responsive: true,
  data: tableData,
  columns: [
    { data: "Employee", title: "Employee" },
    { data: "Amount Added", title: "Amount Added" },
    { data: "Date and Time", title: "Date and Time" },
    { data: "Pre-Add Budget", title: "Pre-Add Budget" },
    { data: "Updated Budget", title: "Updated Budget" },
  ],
});
