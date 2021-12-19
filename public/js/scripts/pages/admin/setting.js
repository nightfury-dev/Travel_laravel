$(window).on("load", function () {
  var $primary = '#5A8DEE';
  var $success = '#39DA8A';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $label_color = '#475f7b';
  var $primary_light = '#E2ECFF';
  var $danger_light = '#ffeed9';
  var $gray_light = '#828D99';
  var $sub_label_color = "#596778";
  var $radial_bg = "#e7edf3";
  var $secondary = '#828D99';
  var $secondary_light = '#e7edf3';
  var $light_primary = "#E2ECFF";
});
$(document).ready(function() {
  if ($("#account_type_table").length) {
    var accountListView = $("#account_type_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "row",
          target: 0
        }
      }
    });
  }
  
  if ($("#currency_table").length) {
    var taskListView = $("#currency_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "row",
          target: 0
        }
      }
    });
  }

  if ($("#language_table").length) {
    var taskListView = $("#language_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "row",
          target: 0
        }
      }
    });
  }

  if ($("#category_table").length) {
    var taskListView = $("#category_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "row",
          target: 0
        }
      }
    });
  }
  
  if ($("#category_tag_table").length) {
    var taskListView = $("#category_tag_table").DataTable({
      columnDefs: [
        {
          targets: 0,
          className: "control"
        },
        {
          orderable: true,
          targets: 1,
        },
        {
          targets: [0, 1],
          orderable: false
        },
      ],
      order: [2, 'asc'],
      dom:
        '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
      language: {
        search: "",
        searchPlaceholder: "Search Invoice"
      },
      select: {
        style: "multi",
        selector: "td:first-child",
        items: "row"
      },
      responsive: {
        details: {
          type: "row",
          target: 0
        }
      }
    });
  }
});
/**account */
function detail_account_type(id)
{
  //account_type section
  $('#account_type_name').val("");

  $.ajax({
    url: base_url + '/settings/detail_account_type',
    method: 'get',
    data: {
      _token: $("[name='_token']").val(),
      account_type_id: id,
    },
    dataType: 'JSON',
    success: function(data){
      if(data['result'] == 'success' && data['mode'] == 'create') {
          $('#account_type_id').val(id);
          $('#account_type_name').empty();
      }
      else if(data['result'] == 'success' && data['mode'] == 'edit') {
        $('#account_type_id').val(id);
        $('#account_type_name').val(data['title']);
      }
    }
  });

  $('#account_type_detail_modal').modal();
}

function save_account_type()
{
  //account_type section
  var account_type_id = $('#account_type_id').val();
  var account_type_name = "";
  account_type_name = $('#account_type_name').val();
  if(account_type_name == "")
    toastr.warning('The Name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else{
    $.ajax({
      url: base_url + '/settings/save_account_type',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        account_type_id: account_type_id,
        account_type_name: account_type_name,
      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            $('#account_type_id').val(account_type_id);
            toastr.success('Saved The AccountType Successfully!',  'Success', {'closeButton': true, timeOut: 2000});
            location.reload();
        }
        else if(data['result'] == "error_exist")
        {
          toastr.warning('The name exist!', 'warning', {'closeButton': true, timeOut: 2000});
        }
      }
    });
  }

}

function account_type_del(id)
{
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    confirmButtonClass: 'btn btn-warning',
    cancelButtonClass: 'btn btn-danger ml-1',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
        $.ajax({
        url: base_url + '/settings/account_type_del',
        method: 'post',
        data: {
          _token: $("[name='_token']").val(),
          account_type_id: id,
        },
        success: function(data){
            if(data == "success")
            {
                Swal.fire({
                    type: "success",
                    title: 'Deleted!',
                    text: 'Selected task has been deleted.',
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result){
                    if(result.value){
                        location.reload();
                    }
                });


            }
        }});
    }
    else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

/****currency */
function detail_currency(id)
{
  //currency section
  $('#currency_name').val("");

  $.ajax({
    url: base_url + '/settings/detail_currency',
    method: 'get',
    data: {
      _token: $("[name='_token']").val(),
      currency_id: id,
    },
    dataType: 'JSON',
    success: function(data){
      if(data['result'] == 'success' && data['mode'] == 'create') {
          $('#currency_id').val(id);
          $('#currency_name').empty();
      }
      else if(data['result'] == 'success' && data['mode'] == 'edit') {
        $('#currency_id').val(id);
        $('#currency_name').val(data['title']);
      }
    }
  });

  $('#currency_detail_modal').modal();
}

function save_currency()
{
  //currency section
  var currency_id = $('#currency_id').val();
  var currency_name = "";
  currency_name = $('#currency_name').val();
  if(currency_name == "")
      toastr.warning('The name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});
  else{
    $.ajax({
      url: base_url + '/settings/save_currency',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        currency_id: currency_id,
        currency_name: currency_name,
      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            $('#currency_id').val(currency_id);
            toastr.success('Saved The Currency Successfully!',  'Success', {'closeButton': true, timeOut: 2000});
            location.reload();
        }
        else if(data['result'] == "error_exist")
        {
          toastr.warning('The name already exist!', 'warning', {'closeButton': true, timeOut: 2000});

        }
      }
    });
  }

}

function currency_del(id)
{
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    confirmButtonClass: 'btn btn-warning',
    cancelButtonClass: 'btn btn-danger ml-1',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
        $.ajax({
        url: base_url + '/settings/currency_del',
        method: 'post',
        data: {
          _token: $("[name='_token']").val(),
          _token: $("[name='_token']").val(),
          currency_id: id,
        },
        success: function(data){
            if(data == "success")
            {
                Swal.fire({
                    type: "success",
                    title: 'Deleted!',
                    text: 'Selected task has been deleted.',
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result){
                    if(result.value){
                        location.reload();
                    }
                });


            }
        }});
    }
    else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

/****language */
function detail_language(id)
{
  //language section
  $('#language_name').val("");
  $('#language_title').val("");

  $.ajax({
    url: base_url + '/settings/detail_language',
    method: 'get',
    data: {
      _token: $("[name='_token']").val(),
      language_id: id,
    },
    dataType: 'JSON',
    success: function(data){
      if(data['result'] == 'success' && data['mode'] == 'create') {
          $('#language_id').val(id);
          $('#language_name').empty();
          $('#language_title').empty();

      }
      else if(data['result'] == 'success' && data['mode'] == 'edit') {
        $('#language_id').val(id);
        $('#language_name').val(data['name']);
        $('#language_title').val(data['title']);

      }
    }
  });

  $('#language_detail_modal').modal();
}

function save_language()
{
  //language section
  var language_id = $('#language_id').val();
  var language_name = "";
  var language_title = "";
  language_name = $('#language_name').val();
  language_title = $('#language_title').val();

  if(language_name == "")
    toastr.warning('The name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else if(language_title == "")
    toastr.warning('The title is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else{
    $.ajax({
      url: base_url + '/settings/save_language',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        language_id: language_id,
        language_name: language_name,
        language_title: language_title,

      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            $('#language_id').val(language_id);
            toastr.success('Saved The Language Successfully!',  'Success', {'closeButton': true, timeOut: 2000});

            location.reload();
        }
        else if(data['result'] == "error_exist")
        {
          toastr.warning('The name already exist!', 'warning', {'closeButton': true, timeOut: 2000});

        }
      }
    });
  }

}

function language_del(id)
{
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    confirmButtonClass: 'btn btn-warning',
    cancelButtonClass: 'btn btn-danger ml-1',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
        $.ajax({
        url: base_url + '/settings/language_del',
        method: 'post',
        data: {
          _token: $("[name='_token']").val(),
          language_id: id,
        },
        success: function(data){
            if(data == "success")
            {
                Swal.fire({
                    type: "success",
                    title: 'Deleted!',
                    text: 'Selected task has been deleted.',
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result){
                    if(result.value){
                        location.reload();
                    }
                });


            }
        }});
    }
    else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

/****category */
function detail_category(id)
{
  //category section
  var str_category_parent = '<option value="">--- Please select ---</option>';
  $('#category_name').val("");

  $.ajax({
    url: base_url + '/settings/detail_category',
    method: 'get',
    data: {
      _token: $("[name='_token']").val(),
      category_id: id,
    },
    dataType: 'JSON',
    success: function(data){
      if(data['result'] == 'success' && data['mode'] == 'create') {
          $('#category_id').val(id);
          $('#category_name').empty();
          $('#category_parent').empty();
          $('#category_parent').append(str_category_parent);
          var category_parents = data.category_parents;
          for(index = 0; index < category_parents.length; index ++)
          {
            str_category_parent = '<option value = "' + (index+1) + '">' + category_parents[index] + '</option>';

              $('#category_parent').append(str_category_parent);
          }
      }
      else if(data['result'] == 'success' && data['mode'] == 'edit') {
        $('#category_id').val(id);
        $('#category_name').val(data['title']);
        $('#category_parent').empty();
        $('#category_parent').append(str_category_parent);
        var category_parents = data.category_parents;
        for(index = 0; index < category_parents.length; index ++)
        {
          var selected = (index+1) == data.category_parent ? 'selected' : "";
          str_category_parent= '<option ' + selected + ' value = "' + (index+1) + '">' + category_parents[index] + '</option>';

            $('#category_parent').append(str_category_parent);
        }
      }
    }
  });

  $('#category_detail_modal').modal();
}

function save_category()
{
  //category section
  var category_id = $('#category_id').val();
  var category_name = "";
  category_name = $('#category_name').val();
  var category_parent = "";
  category_parent= $('#category_parent').val();

  if(category_name == "")
    toastr.warning('The name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else if(category_parent == "")
    toastr.warning('The parent is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else{
    $.ajax({
      url: base_url + '/settings/save_category',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        category_id: category_id,
        category_name: category_name,
        category_parent: category_parent,

      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            $('#category_id').val(category_id);
            toastr.success('Saved The Category Successfully!',  'Success', {'closeButton': true, timeOut: 2000});

            location.reload();
        }
        else if(data['result'] == "error_exist")
        {
          toastr.warning('The name already exist!', 'warning', {'closeButton': true, timeOut: 2000});

        }
      }
    });
  }

}

function category_del(id)
{
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    confirmButtonClass: 'btn btn-warning',
    cancelButtonClass: 'btn btn-danger ml-1',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
        $.ajax({
        url: base_url + '/settings/category_del',
        method: 'post',
        data: {
          _token: $("[name='_token']").val(),
          category_id: id,
        },
        success: function(data){
            if(data == "success")
            {
                Swal.fire({
                    type: "success",
                    title: 'Deleted!',
                    text: 'Selected task has been deleted.',
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result){
                    if(result.value){
                        location.reload();
                    }
                });


            }
        }});
    }
    else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

/****category_tag */
function detail_category_tag(id)
{
  //category_tag section
  var str_category_tag_parent = '<option value="">--- Please select ---</option>';
  $('#category_tag_name').val("");
  $('#category_tag_title').val("");


  $.ajax({
    url: base_url + '/settings/detail_category_tag',
    method: 'get',
    data: {
      _token: $("[name='_token']").val(),
      category_tag_id: id,
    },
    dataType: 'JSON',
    success: function(data){
      if(data['result'] == 'success' && data['mode'] == 'create') {
          $('#category_tag_id').val(id);
          $('#category_tag_name').empty();
          $('#category_tag_title').empty();

          $('#category_tag_parent').empty();
          $('#category_tag_parent').append(str_category_tag_parent);
          var category_tag_parents = data.category_tag_parents;
          for(index = 0; index < category_tag_parents.length; index ++)
          {
            str_category_tag_parent = '<option value = "' + (index+1) + '">' + category_tag_parents[index] + '</option>';

            $('#category_tag_parent').append(str_category_tag_parent);
          }
      }
      else if(data['result'] == 'success' && data['mode'] == 'edit') {
        $('#category_tag_id').val(id);
        $('#category_tag_name').val(data['name']);
        $('#category_tag_title').val(data['title']);

        $('#category_tag_parent').empty();
        $('#category_tag_parent').append(str_category_tag_parent);
        var category_tag_parents = data.category_tag_parents;
        for(index = 0; index < category_tag_parents.length; index ++)
        {
          var selected = (index+1) == data.category_tag_parent ? 'selected' : "";
          str_category_tag_parent= '<option ' + selected + ' value = "' + (index+1) + '">' + category_tag_parents[index] + '</option>';

            $('#category_tag_parent').append(str_category_tag_parent);
        }
      }
    }
  });

  $('#category_tag_detail_modal').modal();
}

function save_category_tag()
{
  //category_tag section
  var category_tag_id = $('#category_tag_id').val();
  var category_tag_name = "";
  category_tag_name = $('#category_tag_name').val();
  var category_tag_parent = "";
  category_tag_title = $('#category_tag_title').val();
  var category_tag_parent = "";
  category_tag_parent= $('#category_tag_parent').val();

  if(category_tag_title == "")
    toastr.warning('The name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else if(category_tag_name == "")
    toastr.warning('The value is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else if(category_tag_parent == "")
    toastr.warning('The parent is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else{
    $.ajax({
      url: base_url + '/settings/save_category_tag',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        category_tag_id: category_tag_id,
        category_tag_name: category_tag_name,
        category_tag_title: category_tag_title,
        category_tag_parent: category_tag_parent,

      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            $('#category_tag_id').val(category_tag_id);
            toastr.success('Saved The Category Tag Successfully!',  'Success', {'closeButton': true, timeOut: 2000});

            location.reload();
        }
        else if(data['result'] == "error_exist")
        {
          toastr.warning('The name already exist!', 'warning', {'closeButton': true, timeOut: 2000});

        }
      }
    });
  }

}

function category_tag_del(id)
{
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    confirmButtonClass: 'btn btn-warning',
    cancelButtonClass: 'btn btn-danger ml-1',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
        $.ajax({
        url: base_url + '/settings/category_tag_del',
        method: 'post',
        data: {
          _token: $("[name='_token']").val(),
          category_tag_id: id,
        },
        success: function(data){
            if(data == "success")
            {
                Swal.fire({
                    type: "success",
                    title: 'Deleted!',
                    text: 'Selected task has been deleted.',
                    confirmButtonClass: 'btn btn-success',
                }).then(function (result){
                    if(result.value){
                        location.reload();
                    }
                });


            }
        }});
    }
    else if (result.dismiss === Swal.DismissReason.cancel) {
    }
  });
}

function save_default_settings()
{
  var current_language = $('#current_language').val();
  var current_currency = $('#current_currency').val();
  if(current_language == "")
    toastr.warning('The Language is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else if(current_currency == "")
    toastr.warning('The Currency is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

  else{
    $.ajax({
      url: base_url + '/settings/save_default_settings',
      method: 'post',
      data: {
        _token: $("[name='_token']").val(),
        current_language: current_language,
        current_currency: current_currency,

      },
      dataType: 'JSON',
      success: function(data){
        if(data['result'] == 'success') {
            toastr.success('Saved The Setting Successfully!',  'Success', {'closeButton': true, timeOut: 2000});

            location.reload();
        }
        else if(data['result'] == "error")
        {
          toastr.warning('This setting is invalied!', 'warning', {'closeButton': true, timeOut: 2000});

        }
      }
    });
  }
}
