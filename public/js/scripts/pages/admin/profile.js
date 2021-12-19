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
    $('.select2-selection__rendered').css('margin-left', '26px');
    // $('.select2-selection__rendered').css('color', 'rgba(0, 0, 0, 0.4)');
    $('.select2-selection__rendered').css('color', '#475F7B');
    $('.select2-selection__rendered').css('font-weight', 400);
    $('.bx').css('top', '9px');
    $('.bx-up-arrow-alt').css('top', '0px'); 
    
    /** Start Avatar Image Crop */

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
                url: base_url + '/crm/avatar',
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
        // This function will call immediately after model close
        // To ensure that old croppie instance is destroyed on every model close
        setTimeout(function() { croppie.destroy(); }, 100);
    })

    /**End Avatar Image Crop */


    $('.select2-selection__rendered').css('margin-left', '26px');
    $('.select2-selection__rendered').css('color', '#475F7B');
    $('.select2-selection__rendered').css('font-weight', 400);

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
        dropdownAutoWidth: true,
        width: '100%'
    });
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
})