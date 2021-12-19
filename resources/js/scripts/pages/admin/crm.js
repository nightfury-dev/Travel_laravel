
function onPasswordBtnClick(user_id) {
    $('#user_id_pwd').val(user_id);
    $('#change_password_modal').modal();
}

function account_del(val) {
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
                url: base_url + '/crm/delete',
                method: 'get',
                data: {
                    _token: $("[name='_token']").val(),
                    user_id: val,
                },
                success: function (data) {
                    if (data == "Success!") {
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            text: 'Your file has been deleted.',
                            confirmButtonClass: 'btn btn-success',
                        }).then(function (result) {
                            if (result.value) {
                                location.reload();
                            }
                        });


                    }
                }
            });


        }
        else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
}

function exportCSVFile(headers, items, fileTitle) {
    if (headers) {
        items.unshift(headers);
    }

    // Convert Object to JSON
    var jsonObject = JSON.stringify(items);

    var csv = convertToCSV(jsonObject);

    var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, exportedFilenmae);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", exportedFilenmae);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

function convertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';

    for (var i = 0; i < array.length; i++) {
        var line = '';
        for (var index in array[i]) {
            if (line != '') line += ','

            line += array[i][index];
        }

        str += line + '\r\n';
    }

    return str;
}

const main_componentForm = {
    main_postal_code: "short_name",
    main_region_state: "short_name",
    main_country: "long_name",
    main_city: "long_name",
    main_street_number: "short_name",
    main_street_address: "long_name",
};

const billing_componentForm = {
    billing_postal_code: "short_name",
    billing_region_state: "short_name",
    billing_country: "long_name",
    billing_city: "long_name",
    billing_street_number: "short_name",
    billing_street_address: "long_name",
};

function initAutocomplete() {

    main_autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("main_location"),
        {types: ["geocode"]}
    );

    billing_autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("billing_location"),
        {types: ["geocode"]}
    );

    main_autocomplete.setFields(["address_component", "geometry"]);
    main_autocomplete.addListener("place_changed", main_fillInAddress);

    billing_autocomplete.setFields(["address_component", "geometry"]);
    billing_autocomplete.addListener("place_changed", billing_fillInAddress);
}

function main_fillInAddress() {
    const place = main_autocomplete.getPlace();
    
    for (const component in main_componentForm) {
        document.getElementById(component).value = "";
    }

    for (const component of place.address_components) {
        const addressType = component.types[0];

        var  obj_id = ""
        if(addressType == "postal_code") {
            obj_id = "main_postal_code";
        }
        else if(addressType == "administrative_area_level_1") {
            obj_id = "main_region_state";
        }
        else if(addressType == "country") {
            obj_id = "main_country";
        }
        else if(addressType == "locality") {
            obj_id = "main_city";
        }
        else if(addressType == "street_number") {
            obj_id = "main_street_number";
        }
        else if(addressType == "route") {
            obj_id = "main_street_address";
        }
        else {
            obj_id = "";
        }
        
        if (main_componentForm[obj_id]) {
            const val = component[main_componentForm[obj_id]];
            
            document.getElementById(obj_id).value = val;
        }
    }
}

function billing_fillInAddress() {
    const place = billing_autocomplete.getPlace();

    for (const component in billing_componentForm) {
        document.getElementById(component).value = "";
    }

    for (const component of place.address_components) {
        const addressType = component.types[0];

        var  obj_id = ""
        if(addressType == "postal_code") {
            obj_id = "billing_postal_code";
        }
        else if(addressType == "administrative_area_level_1") {
            obj_id = "billing_region_state";
        }
        else if(addressType == "country") {
            obj_id = "billing_country";
        }
        else if(addressType == "locality") {
            obj_id = "billing_city";
        }
        else if(addressType == "street_number") {
            obj_id = "billing_street_number";
        }
        else if(addressType == "route") {
            obj_id = "billing_street_address";
        }
        else {
            obj_id = "";
        }

        if (billing_componentForm[obj_id]) {
            const val = component[billing_componentForm[obj_id]];
            document.getElementById(obj_id).value = val;
        }
    }
}

$(document).ready(function () {
    if ($("#table-user").length) {
        var oTable_account = $("#table-user").DataTable({
            "columns": [
                { "data": "" },
                { "data": "User_name" },
                { "data": "Full_name" },
                { "data": "Email" },
                { "data": "Password" },
                { "data": "Phone" },
                { "data": "Country" },
                { "data": "Account_type" },
                { "data": "Action" },
            ],
            columnDefs: [{
                targets: 0,
                className: "control"
            }],
            dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns-user d-flex align-items-center">><"clear">rt<"bottom"p>',
            language: {
                search: "",
                searchPlaceholder: "Search User"
            },
            responsive: {
                details: {
                    type: "column",
                    target: 0
                }
            }
        });
    }

    var invoiceFilterAction_user = $("#invoice-filter-action-user");
    var invoiceOptions_user = $("#invoice-options-user");
    $(".action-btns-user").append(invoiceFilterAction_user, invoiceOptions_user);

    $("#export_csv_user").on('click', function () {
        if (oTable_account) {
            var rows = oTable_account.rows().data();

            var header_data = new Array();
            for (x in rows[0]) {
                x = x.replace(/,/g, '');
                header_data.push(x);
            }
            var main_data = new Array();
            for (var i = 0; i < rows.length; i++) {
                main_data.push({
                    No: rows[i].replace(/,/g, ''),
                    User_name: rows[i].User_name.replace(/,/g, ''),
                    Full_name: rows[i].Full_name.replace(/,/g, ''),
                    Email: rows[i].Email.replace(/,/g, ''),
                    Password: "unkown",
                    Phone: rows[i].Phone.replace(/,/g, ''),
                    Country: rows[i].Country.replace(/,/g, ''),
                    Account_type: rows[i].Account_type.replace(/,/g, ''),
                    Action: '',
                })
            }

            var fileTitle = 'Users'; // or 'my-unique-title'

            exportCSVFile(header_data, main_data, fileTitle); // call the exportCSVFile() function to process the JSON and trigger the download
        }
        else
            alert("There is no data to export CSVFile!");
    })

    var croppie = null;
    var el = document.getElementById('resizer');

    $.base64ImageToBlob = function(str) {
        // extract content type and base64 payload from original string
        var pos = str.indexOf(';base64,');
        var type = str.substring(5, pos);
        var b64 = str.substr(pos + 8);
      
        // decode base64
        var imageContent = atob(b64);
      
        // create an ArrayBuffer and a view (as unsigned 8-bit)
        var buffer = new ArrayBuffer(imageContent.length);
        var view = new Uint8Array(buffer);
      
        // fill the view, using the decoded base64
        for (var n = 0; n < imageContent.length; n++) {
          view[n] = imageContent.charCodeAt(n);
        }
      
        // convert ArrayBuffer to Blob
        var blob = new Blob([buffer], { type: type });
      
        return blob;
    }

    $.getImage = function(input, croppie) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {  
                croppie.bind({
                    url: e.target.result,
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file-upload").on("change", function(event) {
        $("#myModal").modal();
        // Initailize croppie instance and assign it to global variable
        croppie = new Croppie(el, {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },
                boundary: {
                    width: 250,
                    height: 250
                },
                enableOrientation: true
            });
        $.getImage(event.target, croppie); 
    });

    $("#upload").on("click", function() {
        croppie.result('base64').then(function(base64) {
            $("#myModal").modal("hide"); 
            $("#profile-pic").attr("src","/images/img/smush-lazyloader-1.gif");

            var formData = new FormData();
            formData.append("profile_picture", $.base64ImageToBlob(base64));
            // This step is only needed if you are using Laravel
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: base_url + '/avatar',
                method: 'post',
                data: formData,
                contentType: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (data.flag == "uploaded") {
                        $("#profile-pic").attr("src", base64); 
                        $("#avatar_path").val(data.file_path);
                    } else {
                        $("#profile-pic").attr("src","/assets/img/smush-lazyloader-1.gif"); 
                    }
                },
                error: function(error) {
                    console.log(error);
                    $("#profile-pic").attr("src","/images/img/avatar.png"); 
                }
            });
        });
    });

    // To Rotate Image Left or Right
    $(".rotate").on("click", function() {
        croppie.rotate(parseInt($(this).data('deg'))); 
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        setTimeout(function() { croppie.destroy(); }, 100);
    })

    /**End Avatar Image Crop */

    $('.select2-selection__rendered').css('margin-left', '26px');
    $('.select2-selection__rendered').css('color', '#475F7B');
    $('.select2-selection__rendered').css('font-weight', 400);

    if(typeof(account) != 'undefined'){
        if(account.account_type == 3){
            $("#company_div").removeClass("col-sm-6").addClass("col-sm-3");
            $("#category_div").show();
        }
        else {
            $('#company_name').prop("disabled", true);
            $("#category_div").hide();
            $("#company_div").removeClass("col-sm-3").addClass("col-sm-6");
        }
    }
    
    $(document).on("change", "#account_type", function () {
        if($('#account_type').val() == 3)
        {
            $('#company_name').prop("disabled", false);
            $("#company_div").removeClass("col-sm-6").addClass("col-sm-3");
            $("#category_div").show();
        }
        else
        {            
            $('#company_name').prop("disabled", true);
            $("#category_div").hide();
            $("#company_div").removeClass("col-sm-3").addClass("col-sm-6");
        }
    });

    $(document).on("change", "#main_city", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    $(document).on("change", "#billing_city", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    $(document).on("change", "#main_country", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    $(document).on("change", "#billing_country", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    $(document).on("change", "#main_region", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    $(document).on("change", "#billing_region", function() {
        $('.select2-selection__rendered').css('margin-left', '26px');
    });

    // Basic Select2 select
	$(".select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        dropdownAutoWidth: true,
        width: '100%'
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
});


