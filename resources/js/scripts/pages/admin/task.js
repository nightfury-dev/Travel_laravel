function task_del(val) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    confirmButtonClass: "btn btn-warning",
    cancelButtonClass: "btn btn-danger ml-1",
    buttonsStyling: false
  }).then(function(result) {
    if (result.value) {
      $.ajax({
        url: base_url + "/delete",
        method: "post",
        data: {
          _token: $("[name='_token']").val(),
          task_id: val
        },
        success: function(data) {
          if (data == "success") {
            Swal.fire({
              type: "success",
              title: "Deleted!",
              text: "Selected task has been deleted.",
              confirmButtonClass: "btn btn-success"
            }).then(function(result) {
              if (result.value) {
                location.reload();
              }
            });
          } else if (data == "confirm") {
            toastr.warning(
              "The Task Name has been confirmed already, So you can't delete this Task",
              "warning",
              {
                closeButton: true,
                timeOut: 2000
              }
            );
          }
        }
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

function task_detail_del(val) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    confirmButtonClass: "btn btn-warning",
    cancelButtonClass: "btn btn-danger ml-1",
    buttonsStyling: false
  }).then(function(result) {
    if (result.value) {
      $.ajax({
        url: base_url + "/detail_delete",
        method: "post",
        data: {
          _token: $("[name='_token']").val(),
          detail_id: val
        },
        success: function(data) {
          if (data == "success") {
            Swal.fire({
              type: "success",
              title: "Deleted!",
              text: "Selected task detail has been removed from this task.",
              confirmButtonClass: "btn btn-success"
            }).then(function(result) {
              if (result.value) {
                location.reload();
              }
            });
          } else if (data == "confirm") {
            toastr.warning(
              "The Task Name has been confirmed already, So you can't delete this Task",
              "warning",
              {
                closeButton: true,
                timeOut: 2000
              }
            );
          }
        }
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

function task_detail(product_id) {
  var itinerary_id = $("#itinerary_id").val();

  $("#product_id").val(product_id);
  $.ajax({
    url: base_url + "/detail",
    method: "get",
    data: {
      _token: $("[name='_token']").val(),
      product_id: product_id,
      itinerary_id: itinerary_id
    },
    dataType: "JSON",
    success: function(data) {
      icon_array = [];
      icon_array = [
        "bx bx-phone",
        "bx bxs-user-voice",
        "bx bx-timer",
        "bx bx-mail-send"
      ];
      if (data["result"] == "success") {
        $("#task_id").val(0);

        $("#radioStatus2").attr("disabled");
        $("#radioStatus3").attr("disabled");

        current_account_str =
          data.current_account.first_name +
          " " +
          data.current_account.last_name +
          ", " +
          data.current_account.main_email +
          ", " +
          data.current_account.title;

        $("#assign_by").val(current_account_str);
        $("#assign_to").empty();

        var str_assign_by = '<option value="">--- Please select ---</option>';
        $("#assign_to").append(str_assign_by);
        var accounts = data.account;
        for (var index = 0; index < accounts.length; index++) {
          str_assign_by =
            '<option value = "' +
            accounts[index].user_id +
            '">' +
            accounts[index].first_name +
            " " +
            accounts[index].last_name +
            ", " +
            accounts[index].main_email +
            ", " +
            accounts[index].title +
            "</option>";
          $("#assign_to").append(str_assign_by);
        }

        $("#itinerary_ref_num").val(data.itinerary_ref_num);
        $("#product_title").hide();
        $("#head_title").hide();

        $("#task_detail_modal").modal();
      }
    }
  });
}

function save_task() {
  //task section
  var task_id = $("#task_id").val();

  var task_data = {};

  var itinerary_id = $("#itinerary_id").val();
  var task_name = $("#task_name").val();
  var from_date = $("#from_date").val();
  var start_time = $("#start_time").val();
  var end_date = $("#end_date").val();
  var end_time = $("#end_time").val();
  var priority = $('input[name="radioPriority"]:checked').val();
  var status = $('input[name="radioStatus"]:checked').val();
  var assign_to = $("#assign_to").val();
  var note_value = CKEDITOR.instances.note.getData();

  task_data["itinerary_id"] = itinerary_id;
  task_data["task_name"] = task_name;
  task_data["start_date"] = from_date;
  task_data["start_time"] = start_time;
  task_data["end_date"] = end_date;
  task_data["end_time"] = end_time;
  task_data["priority"] = priority;
  task_data["status"] = status;
  task_data["assigned_to"] = assign_to;
  task_data["task_des"] = note_value;
  task_data["reference_number"] = $("#itinerary_ref_num").val();

  if (task_name == "") {
    toastr.warning("The Task Name is empty! Please fill that!", "warning", {
      closeButton: true,
      timeOut: 2000
    });
  } else if (start_time == "") {
    toastr.warning("The Start Time is empty! Please fill that!", "warning", {
      closeButton: true,
      timeOut: 2000
    });
  } else if (end_time == "") {
    toastr.warning("The End Time is empty! Please fill that!", "warning", {
      closeButton: true,
      timeOut: 2000
    });
  } else if (assign_to == "") {
    toastr.warning("The Assign To is empty! Please fill that!", "warning", {
      closeButton: true,
      timeOut: 2000
    });
  } else if (note_value == "") {
    toastr.warning("The Description is empty! Please fill that!", "warning", {
      closeButton: true,
      timeOut: 2000
    });
  } else {
    var start_date = from_date.split("-");
    var date1 = new Date(start_date[2], start_date[1] - 1, start_date[0]);

    end_date = end_date.split("-");
    var date2 = new Date(end_date[2], end_date[1] - 1, end_date[0]);

    var delta = date2.getTime() - date1.getTime();
    if (delta < 0) {
      toastr.warning(
        "The end date must be greater than start date!",
        "warning",
        { closeButton: true, timeOut: 2000 }
      );
    } else {
      $.ajax({
        url: base_url + "/save",
        method: "post",
        data: {
          _token: $("[name='_token']").val(),
          task_data: task_data,
          task_id: task_id
        },
        dataType: "JSON",
        success: function(data) {
          if (data.result == "success") {
            console.log(data);
            if (data.mode == "create")
              toastr.success("Saved New Task Successfully!", "Success", {
                closeButton: true,
                timeOut: 2000
              });
            else if (data.mode == "update")
              toastr.success("Updated The Task Successfully!", "Success", {
                closeButton: true,
                timeOut: 2000
              });

            location.reload();
          } else if (data.result == "error") {
            toastr.warning("The task already exist!", "warning", {
              closeButton: true,
              timeOut: 2000
            });
          }
        }
      });
    }
  }
}

function task_edit(task_id, task_status) {
  if (task_status == 2) {
    Swal.fire({
      title: "Warning?",
      text: "You could not be able to edit this task. this is completed task!",
      type: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Ok!",
      confirmButtonClass: "btn btn-warning",
      buttonsStyling: false
    });
    return;
  }

  if (task_status == -1) {
    Swal.fire({
      title: "Warning?",
      text: "You could not be able to edit this task. this is closed task!",
      type: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Ok!",
      confirmButtonClass: "btn btn-warning",
      buttonsStyling: false
    });
    return;
  }

  //task section
  var str_assign_by = '<option value="">--- Please select ---</option>';
  $.ajax({
    url: base_url + "/edit",
    method: "post",
    data: {
      _token: $("[name='_token']").val(),
      task_id: task_id
    },
    dataType: "JSON",
    success: function(data) {
      icon_array = [
        "bx bx-phone",
        "bx bxs-user-voice",
        "bx bx-timer",
        "bx bx-mail-send"
      ];
      if (data["result"] == "success") {
        $("#task_id").val(data.task["id"]);

        $("#task_name").val(data.task["task_name"]);
        $("#from_date").val(data.task["start_date"]);
        $("#end_date").val(data.task["end_date"]);
        $("#start_time").val(data.task["start_time"]);
        $("#end_time").val(data.task["end_time"]);

        var ss =
          "input[name=radioPriority][value=" + data.task["priority"] + "]";
        $(ss).prop("checked", "checked");

        ss = "input[name=radioStatus][value=" + data.task["status"] + "]";
        $(ss).prop("checked", "checked");

        current_account_str =
          data.current_account.first_name +
          " " +
          data.current_account.last_name +
          ", " +
          data.current_account.main_email +
          ", " +
          data.current_account.title;

        $("#assign_by").val(current_account_str);
        $("#assign_to").empty();
        $("#assign_to").append(str_assign_by);
        var accounts = data.account;
        for (index = 0; index < accounts.length; index++) {
          var selected =
            data.task["assigned_to"] == accounts[index].user_id
              ? "selected"
              : "";
          str_assign_by =
            "<option " +
            selected +
            ' value = "' +
            accounts[index].user_id +
            '">' +
            accounts[index].first_name +
            " " +
            accounts[index].last_name +
            ", " +
            accounts[index].main_email +
            ", " +
            accounts[index].title +
            "</option>";

          $("#assign_to").append(str_assign_by);
        }

        $("#itinerary_ref_num").val(data.itinerary_ref_num);
        CKEDITOR.instances.note.setData(data.task["task_des"]);
        $("#task_detail_modal").modal();
      }
    }
  });
}

$(document).ready(function() {
  if ($("#task_table").length) {
    var taskListView = $("#task_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1
          // checkboxes: { selectRow: true }
        },
        {
          targets: [0, 1],
          orderable: false
        }
      ],
      order: [2, "asc"],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Task"
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
  $(".current")
    .find(".step-icon")
    .addClass("bx bx-time-five");
  $(".current")
    .find(".fonticon-wrap .livicon-evo")
    .updateLiviconEvo({
      strokeColor: "#5A8DEE"
    });

  if ($("#note").length > 0) {
    CKEDITOR.replace("note");
  }

  if ($("#task_detail_table").length) {
    var taskDetailView = $("#task_detail_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1
          // checkboxes: { selectRow: true }
        },
        {
          targets: [0, 1],
          orderable: false
        }
      ],
      order: [2, "asc"],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Task Detail"
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
