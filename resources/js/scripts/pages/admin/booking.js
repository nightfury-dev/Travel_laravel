$(document).ready(function () {
  if ($(".pickadate").length) {
    $(".pickadate").pickadate({
      format: "mm/dd/yyyy"
    });
  }

  if ($("#booking_table").length) {
    var dataListView = $("#booking_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          targets: [0],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Booking"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "column",
          target: 0
        }
      }
    });
  }
});
