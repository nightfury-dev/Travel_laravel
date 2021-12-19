
var str = new Array;
var k = new Array;
var index;
var filter_flg = 0;
var tag_option_html='';

var regex = /[.,']/g;

var currency_option_html = '<option value="0">Currency</option>';
for(var j=0; j<currency.length; j++) {
    currency_option_html += '<option value="'+ currency[j].id +'">'+ currency[j].title +'</option>';
}

var pick_date = 0;
var pick_product_id = 0;
var global_obj_id = '';
var global_custom_product = '';

var global_margin_price = 0;
var global_margin_price = 1;

var global_itinerary_daily_id = 0;
var global_task_id = null;
var global_task_status = null;
var global_task_detail_id = null;
var global_time = '';

var update_flag = 0;
var update_key_index = 0;

$(document).ready(function() {
    $('#filter_option').hide();
    
    for(index = 0; index < product.length; index ++){
        var str_product_each = "";
        str_product_each = `<li class="product-list-each item">` +
                `<div class="product-list-class">` +
                    `<input type="hidden" name="product_id" id="product_id" value="${product[index].id}">` +
                    `<div class="product-list-left" onClick="product_detail(${product[index].id})">` +
                        `<img class="product-list-img" src="${base_path_url}storage/${product[index].path}"/>` +
                        `<div class="product-list-explain">`+
                            `<div class="product-list-title" id="product-list-title">` +
                                product[index].title +
                            `</div>` +
                            `<div class="product-list-detail">` +
                                `${product[index].city}, ${product[index].country}` +
                            `</div>` +
                        `</div>` +
                    `</div>` +
                `</div>` +
            `</li>`;
        $('#product_list').append(str_product_each);
    }

    var offset = product.length % 6;
    var product_length = product.length;
    if(offset > 0){
        product_length += 6;
    }
    $('#product_pagination').rpmPagination({
        domElement: '.item',
        limit: 6,
        total: product_length,
    })

    $(".touchspin").TouchSpin({
        buttondown_class: "btn btn-danger",
        buttonup_class: "btn btn-danger",
    });

    $('.product-list-class').draggable({
        cancel: "a.ui-icon", // clicking an icon won't initiate dragging
        //revert: "invalid", // when not dropped, the item will revert back to its initial position
        revert: true, // bounce back when dropped
        helper: "clone", // create "copy" with original properties, but not a true clone
        cursor: "move",
        revertDuration: 0 // immediate snap
    });

    if(template_itinerary_data.length != 0) {
        for(var i = 0; i < template_itinerary_data.length; i++){
            var str_template_each = "";
            str_template_each = `<li class="template-list-each">` +
                    `<div class="template-list-class">` +
                    `<input type="hidden" name="group_id" id="group_id" value="${template_itinerary_data[i].group_id}">` +
                    `<div class="template-list-left" onclick="preview_template_itinerary(${template_itinerary_data[i].group_id})">` +
                        `<img class="template-list-img" src="${base_path_url}storage/${template_itinerary_data[i].path}"/>` +
                        `<div class="template-list-explain">`+
                        `<div class="template-list-title" id="template-list-title">` +
                            template_itinerary_data[i].title +
                        `</div>` +
                        `</div>` +
                    `</div>` +
                    `<div class="template-list-right">` +
                        `<div class="dropdown">` +
                        `<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>` +
                        `<div class="dropdown-menu dropdown-menu-right">` +
                            `<a class="dropdown-item" href="javascript: void(0)" onclick="set_schedule_with_template(${template_itinerary_data[i].group_id}, '${template_itinerary_data[i].title.replace(regex, '')}', ${template_itinerary_data[i].day_count})"><i class="bx bx-right-down-arrow-circle mr-1"></i> Set as Schedule</a>` +
                            `<a class="dropdown-item" href="javascript: void(0)" onclick="delete_template_itinerary(${template_itinerary_data[i].group_id})"><i class="bx bx-edit-alt mr-1"></i> Delete</a>` +
                        `</div>` +
                        `</div>` +
                    `</div>` +
                    `</div>` +
                `</li>`;
            $('#template_itinerary_list').append(str_template_each);
        }
    }

    if(template_itinerary_data.length > 6){
        $('#template_itinerary_pagination').show();
        var offset = template_itinerary_data.length % 6;
        var teplate_length = template_itinerary_data.length;
        if(offset > 0){
            teplate_length += 6;
        }
        $('#template_itinerary_pagination').rpmPagination({
            domElement: '.item',
            limit: 6,
            total: teplate_length,
        })
    }
    else {
        $('#template_itinerary_pagination').hide();
    }

    for(index = 0; index < days; index++)
    {
        k[index] = 0;
        str[index] = new Array();

        $('#each-day-products-' + index).droppable({
            accept: ".product-list-class",
            activeClass: "ui-state-highlight",
            drop: function( event, ui ) {
                update_flag = 0;

                var str_id = $(this).attr('id');
                var obj_id_arr = str_id.split('-');
                var obj_id = parseInt(obj_id_arr[obj_id_arr.length - 1]);
                global_obj_id = obj_id;
                str[obj_id][k[obj_id]] = "";
                var custom_product;
                for(i = 0; i < product.length; i ++){
                    if(product[i].id == ui.helper[0].children[0].value)
                        custom_product = product[i];
                }

                global_custom_product = custom_product;

                pick_date = $(this).parent().parent().find('.day-date').data('pick');
                pick_product_id = custom_product.id;

                $.ajax({
                    url: base_url + '/get_product_pricing_tag',
                    type: 'post',
                    data: {
                        _token: $("[name='_token']").val(),
                        product: custom_product
                    },
                    dataType: 'json',
                    success: function(response) {
                        var product_tag = response;
                        var tag_html = '<option value="0">Pricing Tag</option>';
                        for(var i=0; i<product_tag.length; i++) {
                            tag_html += '<option value="'+ product_tag[i].id +'">'+ product_tag[i].title +'</option>';
                        }

                        tag_option_html = tag_html;

                        $("#product_pricing_container").empty();
                        $("[id^='product_pricing_item_']").remove();
                        $("#product-pricing-modal").modal();
                    }
                });
            }
        });
    }

    if(daily_schedule_data.length != 0) {
        var schedule_index = 0;
        console.log(daily_schedule_data);
        for(dd in daily_schedule_data ) {
            str[schedule_index] = new Array;
            var str_tmp = `<ul class='product-list' id='daily-schedule-id-${schedule_index}'>`;
            for(var i=0; i<daily_schedule_data[dd].length; i++) {
                if(dd == daily_schedule_data[dd][i].date) {
                    
                    var bullet_html = '';
                    if(daily_schedule_data[dd][i].task_status == null || daily_schedule_data[dd][i].task_status == 1) {
                        var bullet_html = '<span class="bullet bullet-sm bullet-danger mr-50"></span>';
                    }
                    else if(daily_schedule_data[dd][i].task_status == -1) {
                        var bullet_html = '<span class="bullet bullet-sm bullet-secondary mr-50"></span>';
                    }
                    else {
                        var bullet_html = '<span class="bullet bullet-sm bullet-success mr-50"></span>';
                    }

                    var task_checked_flag = '';
                    if(daily_schedule_data[dd][i].task_detail_id == null || daily_schedule_data[dd][i].task_detail_id == 0) {
                        task_checked_flag = 'unchecked="true" enabled';
                    }
                    else {
                        task_checked_flag = 'checked="true" disabled';
                    }

                    k[schedule_index] = i;

                    var city = daily_schedule_data[dd][i].city? daily_schedule_data[dd][i].city : '' ;
                    var country = daily_schedule_data[dd][i].country? daily_schedule_data[dd][i].country : '';

                    var price_text = '';
                    var price_arr = daily_schedule_data[dd][i].product_price_id.split(':');
                    var curr_arr = daily_schedule_data[dd][i].product_price_currency.split(':');
                    
                    for(var j=0; j<price_arr.length; j++) {
                        price_text += price_arr[j] + '(' + currency_list[curr_arr[j]] + ')';
                        price_text += ' ';
                    }
                    
                    var margin_text = "";
                    if(daily_schedule_data[dd][i].itinerary_margin_type == 1) {
                        margin_text = daily_schedule_data[dd][i].itinerary_margin_price + '(%)';
                    }
                    else {
                        margin_text = daily_schedule_data[dd][i].itinerary_margin_price;
                    }

                    var html_each = '<li class="product-list-each" id="daily-list-' + schedule_index + '-' + k[schedule_index] + '">' +
                                        '<div class="daily-products-class">' +
                                        '<input type="hidden" name="product_id" id="product_id" value="' + daily_schedule_data[dd][i].id +'">' +
// new
                                        '<input type="hidden" name="itinerary_daily_id" id="itinerary_daily_id" value="' + daily_schedule_data[dd][i].daily_id +'">' +
                                        '<input type="hidden" name="product_price_tag" id="product_price_tag" value="' + daily_schedule_data[dd][i].product_price_tag +'">' +
                                        '<input type="hidden" name="product_price_season" id="product_price_season" value="' + daily_schedule_data[dd][i].product_price_season +'">' +
                                        '<input type="hidden" name="product_price_currency" id="product_price_currency" value="' + daily_schedule_data[dd][i].product_price_currency +'">' +
                                        '<input type="hidden" name="product_price_id" id="product_price_id" value="' + daily_schedule_data[dd][i].product_price_id +'">' +
// new
                                        '<input type="hidden" name="product_margin_type" id="product_margin_type" value="' + daily_schedule_data[dd][i].itinerary_margin_type +'">' +
                                        '<input type="hidden" name="product_margin_price" id="product_margin_price" value="' + daily_schedule_data[dd][i].itinerary_margin_price +'">' +
                                        
                                        '<div class="daily-products-left">'+
                                            '<i class="bx bx-grid-vertical" style="font-size: 25px; margin: auto 0; cursor: move"></i>'+
                                            '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ daily_schedule_data[dd][i].path +'"/>'+
                                            '<div class="daily-products-explain">'+
                                                '<div class="daily-products-title">'+
                                                    daily_schedule_data[dd][i].product_title + '<span class="daily-products-detail">('+ city +', ' + country +')</span>'+
                                                '</div>'+
                                                '<div class="daily-products-option">'+
                                                    `<i class="bx bx-dollar-circle" style="color: rgb(210, 77, 83);padding-top: 2px;"></i>` +
                                                    `<span style="font-weight: bold;" id="itinerary_price_real_${schedule_index}_${k[schedule_index]}">Price: ${price_text} </span>`+
                                                    `<span style="font-weight: bold;" id="itinerary_price_margin_${schedule_index}_${k[schedule_index]}">Price Margin: ${margin_text}</span>`+
                                                '</div>'+
                                                '<div class="daily-products-option">'+
                                                    `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_time_${schedule_index}_${k[schedule_index]}">${daily_schedule_data[dd][i].start_time}~${daily_schedule_data[dd][i].end_time}</span>&nbsp;&nbsp;`+
                                                    `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_travellers_${schedule_index}_${k[schedule_index]}">${daily_schedule_data[dd][i].adults_num}&nbsp;adults-${daily_schedule_data[dd][i].children_num}&nbsp;children</span>`+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="daily-products-right">'+
                                            bullet_html +
                                            `<input type="checkbox" class="daily_checkbox" id="daily_checkbox_${schedule_index}_${k[schedule_index]}" data-daily-id="${daily_schedule_data[dd][i].daily_id}" data-task-id="${daily_schedule_data[dd][i].task_detail_id}" ${task_checked_flag} onclick="check_validation(this);">`+
                                            '<div class="dropdown">'+
                                            '<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"'+
                                                'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>'+
                                            '<div class="dropdown-menu dropdown-menu-right">'+
                                                `<a class="dropdown-item dropdown-edit-time" href="javascript:void(0)" onClick="edit_product_time(${schedule_index}, ${k[schedule_index]})"><i class="bx bx-edit-alt mr-1"></i> Edit Time</a>`+
                                                `<a class="dropdown-item dropdown-edit-travellers" href="javascript:void(0)" onClick="edit_product_travellers(${schedule_index}, ${k[schedule_index]})"><i class="bx bx-edit-alt mr-1"></i> Travellers</a>`+
// new
                                                `<a class="dropdown-item dropdown-edit-price" href="javascript:void(0)" onClick="edit_product_price(${schedule_index}, ${k[schedule_index]}, ${daily_schedule_data[dd][i].daily_id}, ${daily_schedule_data[dd][i].task_id}, ${daily_schedule_data[dd][i].task_status}, ${daily_schedule_data[dd][i].task_detail_id}, '${daily_schedule_data[dd][i].product_price_tag}', '${daily_schedule_data[dd][i].product_price_season}', '${daily_schedule_data[dd][i].product_price_currency}', '${daily_schedule_data[dd][i].product_price_id}', ${daily_schedule_data[dd][i].itinerary_margin_price}, ${daily_schedule_data[dd][i].itinerary_margin_type})"><i class="bx bx-edit-alt mr-1"></i> Edit Price</a>`+
// bew
                                                `<a class="dropdown-item dropdown-edit-margin" href="javascript:void(0)" onClick="edit_margin_price(${schedule_index}, ${k[schedule_index]}, ${daily_schedule_data[dd][i].itinerary_margin_price}, ${daily_schedule_data[dd][i].itinerary_margin_type})"><i class="bx bx-edit-alt mr-1"></i> Edit Margin</a>`+
                                                `<a class="dropdown-item dropdown-del-product" href="javascript:void(0)" onClick="daily_product_del(${schedule_index}, ${k[schedule_index]}, ${daily_schedule_data[dd][i].task_id})"><i class="bx bx-trash mr-1"></i> delete</a>`+
                                            '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '</div>'+
                                    '</li>';
                    str[schedule_index][k[schedule_index]] = html_each;
                    k[schedule_index]++;
                    str_tmp += html_each;
                }
            }

            str_tmp += '</ul>';
            $("#each-day-products-" + schedule_index).prepend(str_tmp);
            var obj_id = schedule_index;

            dragula([document.getElementById('daily-schedule-id-' + obj_id)])
            .on('drop', function (e, el){
                var ul_string = el.innerHTML;
                var str_id = el.id;
                var str_temp_arr = str_id.split('-');
                var obj_id = parseInt(str_temp_arr[3]);

                var ul_string_arr = ul_string.split('</li>');
                var temp_string = "";
                for(i = 0; i < ul_string_arr.length - 1; i ++)
                {
                    str[obj_id][i] = ul_string_arr[i] + '</li>';
                    str[obj_id][i] = str[obj_id][i].replace(' gu-transit', "");

                    var str_index = str[obj_id][i].indexOf(`daily-list-${obj_id}-`);
                    var str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    var length = `daily-list-${obj_id}-`.length;
                    var substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`daily-list-${obj_id}-${substr}`,`daily-list-${obj_id}-${i}`);

                    /////////////////
                    str_index = str[obj_id][i].indexOf(`itinerary_price_real_${obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `itinerary_price_real_${obj_id}_`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`itinerary_price_real_${obj_id}_${substr}`,`itinerary_price_real_${obj_id}_${i}`);
                    ////////////
                    
                    /////////////////
                    str_index = str[obj_id][i].indexOf(`itinerary_price_margin_${obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `itinerary_price_margin_${obj_id}_`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`itinerary_price_margin_${obj_id}_${substr}`,`itinerary_price_margin_${obj_id}_${i}`);
                    ////////////

                    str_index = str[obj_id][i].indexOf(`product_time_${obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `product_time_${obj_id}_`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`product_time_${obj_id}_${substr}`,`product_time_${obj_id}_${i}`);
                    /////////////

                    str_index = str[obj_id][i].indexOf(`product_travellers_${obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `product_travellers_${obj_id}_`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`product_travellers_${obj_id}_${substr}`,`product_travellers_${obj_id}_${i}`);
                    /////////////

                    str_index = str[obj_id][i].indexOf(`daily_checkbox_${obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == '>')
                            break;
                        str_index_temp ++;
                    }
                    length = `daily_checkbox_${obj_id}_`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(" ");
                    str[obj_id][i] = str[obj_id][i].replace(`daily_checkbox_${obj_id}_${substr}`, `daily_checkbox_${obj_id}_${i}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
                    /////////////

                    str_index = str[obj_id][i].indexOf(`edit_product_time(${obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_time(${obj_id}, `.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`edit_product_time(${obj_id}, ${substr})`,`edit_product_time(${obj_id}, ${i})`);
                    /////////////

                    str_index = str[obj_id][i].indexOf(`edit_product_travellers(${obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_travellers(${obj_id}, `.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    str[obj_id][i] = str[obj_id][i].replace(`edit_product_travellers(${obj_id}, ${substr})`,`edit_product_travellers(${obj_id}, ${i})`);
                    //////////////


                    str_index = str[obj_id][i].indexOf(`edit_product_price(${obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_price(${obj_id}, `.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
// new
                    str[obj_id][i] = str[obj_id][i].replace(`edit_product_price(${obj_id}, ${substr})`, `edit_product_price(${obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);
// new
                    ////////////

                    str_index = str[obj_id][i].indexOf(`edit_margin_price(${obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_margin_price(${obj_id}, `.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
                    
                    str[obj_id][i] = str[obj_id][i].replace(`edit_margin_price(${obj_id}, ${substr})`, `edit_margin_price(${obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]})`);
                    ////////////

                    str_index = str[obj_id][i].indexOf(`daily_product_del(${obj_id},`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `daily_product_del(${obj_id},`.length;
                    substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
                    str[obj_id][i] = str[obj_id][i].replace(`daily_product_del(${obj_id},${substr})`,`daily_product_del(${obj_id}, ${i}, ${substr_arr[1]})`);
                    /////////////

                    temp_string += str[obj_id][i];
                }
                $(`#daily-schedule-id-${obj_id}`).empty();
                $(`#daily-schedule-id-${obj_id}`).append(temp_string);
            });
            schedule_index ++;
        }
    }
})

$('#search_product').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        $.ajax({
            url: base_url + '/itinerary_add_daily_search',
            method: 'get',
            data: {
                _token: $("[name='_token']").val(),
               search_string: $('#search_product').val(),
            },
            success: function(result){
               var temp_product = result.product;
               var images = result.images;
               var country = result.country;
               var city = result.city;
                var str_product_each = "";
                $('#product_list').empty();
                for(index = 0; index < temp_product.length; index ++)
                {
                    str_product_each = `<li class="product-list-each item">` +
                                        `<div class="product-list-class">` +
                                        `<input type="hidden" name="product_id" id="product_id" value="${temp_product[index].id}">` +
                                        `<div class="product-list-left" onClick="product_detail(${temp_product[index].id})">` +
                                            `<img class="product-list-img" src="${base_path_url}storage/${images[index].path}"/>` +
                                            `<div class="product-list-explain">`+
                                            `<div class="product-list-title" id="product-list-title">` +
                                            temp_product[index].title +
                                            `</div>` +
                                            `<div class="product-list-detail">` +
                                                `${temp_product[index].country}, ${temp_product[index].city}` +
                                            `</div>` +
                                            `</div>` +
                                        `</div>` +
                                        `</div>` +
                                    `</li>`;

                    $('#product_list').append(str_product_each);
                }
                if(temp_product.length > 6){

                    $('#product_pagination').show();
                    var offset = temp_product.length % 6;
                    var product_length = temp_product.length;
                    if(offset > 0){
                        product_length += 6;
                    }
                    $('#product_pagination').rpmPagination({
                        domElement: '.item',
                        limit: 6,
                        total: product_length,
                    })
                }
                else {
                    $('#product_pagination').hide();
                }
                $('.product-list-class').draggable({
                    cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                    //revert: "invalid", // when not dropped, the item will revert back to its initial position
                    revert: true, // bounce back when dropped
                    helper: "clone", // create "copy" with original properties, but not a true clone
                    cursor: "move"
                    , revertDuration: 0 // immediate snap
                });
            }
        });
    }
});

$('#search_template_itinerary').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        
        $.ajax({
            url: base_url + '/itinerary_template_search',
            method: 'get',
            data: {
                _token: $("[name='_token']").val(),
               search_string: $('#search_template_itinerary').val(),
            },
            success: function(result){
                var template_itinerary = result.template_itinerary;
                var str_product_each = "";
                $('#template_itinerary_list').empty();
                for(var i = 0; i < template_itinerary.length; i ++)
                {
                    str_product_each = `<li class="template-list-each">` +
                                    `<div class="template-list-class">` +
                                    `<input type="hidden" name="group_id" id="group_id" value="${template_itinerary[i].group_id}">` +
                                    `<div class="template-list-left" onclick="preview_template_itinerary(${template_itinerary[i].group_id})">` +
                                        `<img class="template-list-img" src="${base_path_url}storage/${template_itinerary[i].path}"/>` +
                                        `<div class="template-list-explain">`+
                                        `<div class="template-list-title" id="template-list-title">` +
                                        template_itinerary[i].title +
                                        `</div>` +
                                        `</div>` +
                                    `</div>` +
                                    `<div class="template-list-right">` +
                                        `<div class="dropdown">` +
                                        `<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>` +
                                        `<div class="dropdown-menu dropdown-menu-right">` +
                                            `<a class="dropdown-item" href="javascript: void(0)" onclick="set_schedule_with_template(${template_itinerary[i].group_id}, '${template_itinerary[i].title.replace(regex, '')}', ${template_itinerary[i].day_count})"><i class="bx bx-right-down-arrow-circle mr-1"></i> Set as Schedule</a>` +
                                            `<a class="dropdown-item" href="javascript: void(0)" onclick="delete_template_itinerary(${template_itinerary[i].group_id})"><i class="bx bx-edit-alt mr-1"></i> Delete</a>` +
                                        `</div>` +
                                        `</div>` +
                                    `</div>` +
                                    `</div>` +
                                `</li>`;

                    $('#template_itinerary_list').append(str_product_each);
                }
                if(template_itinerary.length > 6){

                    $('#template_itinerary_pagination').show();
                    var offset = template_itinerary.length % 6;
                    var template_length = template_itinerary.length;
                    if(offset > 0){
                        template_length += 6;
                    }
                    $('#template_itinerary_pagination').rpmPagination({
                        domElement: '.item',
                        limit: 6,
                        total: template_length,
                    })
                }
                else {
                    $('#template_itinerary_pagination').hide();
                }
            }
        });
    }
});

function filter_change(){
    var flg_accommodation = $('#check_accommodation').is(':checked');
    var flg_transport = $('#check_transport').is(':checked');
    var flg_activity_attraction = $('#check_activity_attraction').is(':checked');
    var flg_guide = $('#check_guide').is(':checked');
    var flg_other = $('#check_other').is(':checked');

    $.ajax({
        url: base_url + '/itinerary_add_daily_filter',
        method: 'get',
        data: {
            _token: $("[name='_token']").val(),
            flg_accommodation: flg_accommodation,
            flg_transport: flg_transport,
            flg_activity_attraction: flg_activity_attraction,
            flg_guide: flg_guide,
            flg_other: flg_other,
        },
        success: function(result){
            var temp_product = result.product;
            var images = result.images;
            var str_product_each = "";

            $('#product_list').empty();

            for(index = 0; index < temp_product.length; index ++)
            {
                str_product_each = `<li class="product-list-each item">` +
                                    `<div class="product-list-class">` +
                                    `<input type="hidden" name="product_id" id="product_id" value="${temp_product[index].id}">` +
                                    `<div class="product-list-left" onClick="product_detail(${temp_product[index].id})">` +
                                        `<img class="product-list-img" src="${base_path_url}storage/${images[index].path}"/>` +
                                        `<div class="product-list-explain">`+
                                        `<div class="product-list-title" id="product-list-title">` +
                                        temp_product[index].title +
                                        `</div>` +
                                        `<div class="product-list-detail">` +
                                            `${temp_product[index].country}, ${temp_product[index].city}` +
                                        `</div>` +
                                        `</div>` +
                                    `</div>` +
                                    `</div>` +
                                `</li>`;

                $('#product_list').append(str_product_each);
            }

            if(temp_product.length > 6){

                $('#product_pagination').show();
                var offset = temp_product.length % 6;
                var product_length = temp_product.length;
                if(offset > 0){
                    product_length += 6;
                }
                $('#product_pagination').rpmPagination({
                    domElement: '.item',
                    limit: 6,
                    total: product_length,
                })
            }
            else {
                $('#product_pagination').hide();
            }

            $('.product-list-class').draggable({
                cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                //revert: "invalid", // when not dropped, the item will revert back to its initial position
                revert: true, // bounce back when dropped
                helper: "clone", // create "copy" with original properties, but not a true clone
                cursor: "move"
                , revertDuration: 0 // immediate snap
            });
        }
    });
}

$('#filter_button').click(function() {
    filter_flg = filter_flg ? 0 : 1;
    if(filter_flg) {
        $('#product_list_container').removeClass('col-12');
        $('#product_list_container').addClass('col-9');
        $('#filter_option').css('display', 'block');
        $('#filter_option').show();
    }
    else{
        $('#product_list_container').removeClass('col-9');
        $('#product_list_container').addClass('col-12');
        $('#filter_option').hide();
        $('#filter_option').css('display', 'none');
    }
});

function daily_product_del(obj_id, list_id, task_id){
    
    if(task_id != null || task_id != undefined) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You would be able to lost the task data!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                var index_i = 0;
    
                jQuery('.product-list-each').each(function(index, customElement) {
                    var str_id = customElement.id;
                    var str_id_arr = str_id.split('-');
                    if(obj_id == parseInt(str_id_arr[2])){
                        if(index_i == list_id){
                            $(this).remove();
                        }
                        index_i ++;
                    }
                });

                str[obj_id].splice(list_id, 1);
                k[obj_id] --;

                for(index = 0; index < k[obj_id]; index ++){
                    var substr_search = `daily-list-${obj_id}-`;
                    var substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    var temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    var substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`daily-list-${obj_id}-${substr}`, `daily-list-${obj_id}-${index}`);

                    /////////////
                    substr_search = `itinerary_price_real_${obj_id}_`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`itinerary_price_real_${obj_id}_${substr}`, `itinerary_price_real_${obj_id}_${index}`);
                    /////////////

                    /////////////
                    substr_search = `itinerary_price_margin_${obj_id}_`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`itinerary_price_margin_${obj_id}_${substr}`, `itinerary_price_margin_${obj_id}_${index}`);
                    /////////////

                    substr_search = `product_time_${obj_id}_`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`product_time_${obj_id}_${substr}`, `product_time_${obj_id}_${index}`);
                    /////////////

                    substr_search = `product_travellers_${obj_id}_`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`product_travellers_${obj_id}_${substr}`, `product_travellers_${obj_id}_${index}`);
                    /////////////

                    substr_search = `daily_checkbox_${obj_id}_`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true)
                    {
                        if(str[obj_id][index][temp_substr_index] == '>')///////////////
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    var substr_arr = substr.split(" ");
                    str[obj_id][index] = str[obj_id][index].replace(`daily_checkbox_${obj_id}_${substr}`, `daily_checkbox_${obj_id}_${index}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
                    /////////////


                    substr_search = `edit_product_time(${obj_id}, `;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == ')')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`edit_product_time(${obj_id}, ${substr}`, `edit_product_time(${obj_id}, ${index})`);
                    /////////////

                    substr_search = `edit_product_travellers(${obj_id}, `;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == ')')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    str[obj_id][index] = str[obj_id][index].replace(`edit_product_travellers(${obj_id}, ${substr}`, `edit_product_travellers(${obj_id}, ${index})`);
                    /////////////
                    
                    substr_search = `edit_product_price(${obj_id}, `;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true)
                    {
                        if(str[obj_id][index][temp_substr_index] == ')')///////////////
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    var substr_arr = substr.split(", ");
            // new
                    str[obj_id][index] = str[obj_id][index].replace(`edit_product_price(${obj_id}, ${substr})`, `edit_product_price(${obj_id}, ${index}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);
            // new
                    /////////////

                    substr_search = `edit_margin_price(${obj_id}, `;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    while(true)
                    {
                        if(str[obj_id][index][temp_substr_index] == ')')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    var substr_arr = substr.split(", ");
                    str[obj_id][index] = str[obj_id][index].replace(`edit_margin_price(${obj_id}, ${substr})`, `edit_margin_price(${obj_id}, ${index}, ${substr_arr[1]}, ${substr_arr[2]})`);
                    ///////////////

                    substr_search = `daily_product_del(${obj_id},`;
                    substr_index = str[obj_id][index].indexOf(substr_search);
                    substr_index += substr_search.length;
                    temp_substr_index = substr_index;
                    while(true){
                        if(str[obj_id][index][temp_substr_index] == '"')
                            break;
                        temp_substr_index ++;
                    }
                    substr = str[obj_id][index].substring(substr_index, temp_substr_index);
                    var substr_arr = substr.split(", ");
                    str[obj_id][index] = str[obj_id][index].replace(`daily_product_del(${obj_id},${substr}`, `daily_product_del(${obj_id}, ${index}, ${substr_arr[1]})`);
                    ////////////////
                }

                $(`#daily-schedule-id-${obj_id}`).empty();
                var str_temp = "";
                for(index = 0; index < k[obj_id]; index ++) {
                    str_temp += str[obj_id][index];
                }
                $(`#daily-schedule-id-${obj_id}`).append(str_temp);
            }
        })
    }
    else {
        var index_i = 0;
    
        jQuery('.product-list-each').each(function(index, customElement) {
            var str_id = customElement.id;
            var str_id_arr = str_id.split('-');
            if(obj_id == parseInt(str_id_arr[2])){
                if(index_i == list_id){
                    $(this).remove();
                }
                index_i ++;
            }
        });

        str[obj_id].splice(list_id, 1);
        k[obj_id] --;

        for(index = 0; index < k[obj_id]; index ++){
            var substr_search = `daily-list-${obj_id}-`;
            var substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            var substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`daily-list-${obj_id}-${substr}`, `daily-list-${obj_id}-${index}`);

            /////////////
            substr_search = `itinerary_price_real_${obj_id}_`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`itinerary_price_real_${obj_id}_${substr}`, `itinerary_price_real_${obj_id}_${index}`);
            /////////////
            
            /////////////
            substr_search = `itinerary_price_margin_${obj_id}_`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`itinerary_price_margin_${obj_id}_${substr}`, `itinerary_price_margin_${obj_id}_${index}`);
            /////////////

            substr_search = `product_time_${obj_id}_`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`product_time_${obj_id}_${substr}`, `product_time_${obj_id}_${index}`);
            /////////////

            substr_search = `product_travellers_${obj_id}_`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`product_travellers_${obj_id}_${substr}`, `product_travellers_${obj_id}_${index}`);
            /////////////

            substr_search = `daily_checkbox_${obj_id}_`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true)
            {
                if(str[obj_id][index][temp_substr_index] == '>')///////////////
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            var substr_arr = substr.split(" ");
            str[obj_id][index] = str[obj_id][index].replace(`daily_checkbox_${obj_id}_${substr}`, `daily_checkbox_${obj_id}_${index}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
            /////////////

            substr_search = `edit_product_time(${obj_id}, `;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == ')')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`edit_product_time(${obj_id}, ${substr}`, `edit_product_time(${obj_id}, ${index})`);
            /////////////

            substr_search = `edit_product_travellers(${obj_id}, `;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == ')')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            str[obj_id][index] = str[obj_id][index].replace(`edit_product_travellers(${obj_id}, ${substr}`, `edit_product_travellers(${obj_id}, ${index})`);
            /////////////
            
            substr_search = `edit_product_price(${obj_id}, `;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true)
            {
                if(str[obj_id][index][temp_substr_index] == ')')///////////////
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            var substr_arr = substr.split(", ");
    // new
            str[obj_id][index] = str[obj_id][index].replace(`edit_product_price(${obj_id}, ${substr})`, `edit_product_price(${obj_id}, ${index}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);
    // new
            /////////////

            substr_search = `edit_margin_price(${obj_id}, `;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            while(true)
            {
                if(str[obj_id][index][temp_substr_index] == ')')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            var substr_arr = substr.split(", ");
            str[obj_id][index] = str[obj_id][index].replace(`edit_margin_price(${obj_id}, ${substr})`, `edit_margin_price(${obj_id}, ${index}, ${substr_arr[1]}, ${substr_arr[2]})`);
            ///////////////

            substr_search = `daily_product_del(${obj_id},`;
            substr_index = str[obj_id][index].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[obj_id][index][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[obj_id][index].substring(substr_index, temp_substr_index);
            var substr_arr = substr.split(", ");
            str[obj_id][index] = str[obj_id][index].replace(`daily_product_del(${obj_id},${substr}`, `daily_product_del(${obj_id}, ${index}, ${substr_arr[1]})`);
            ////////////////
        }

        $(`#daily-schedule-id-${obj_id}`).empty();
        var str_temp = "";
        for(index = 0; index < k[obj_id]; index ++) {
            str_temp += str[obj_id][index];
        }
        $(`#daily-schedule-id-${obj_id}`).append(str_temp);
    }
}

let map;
let marker;

function addMarker(location) {
	marker = new google.maps.Marker({
	  position: location,
	  map: map,
	});
}

function product_detail(product_id){
    $('#pricing-data').empty();
    var custom_product;

    for(var i = 0; i < product.length; i ++)
    {
        if(product[i].id == product_id){
            custom_product = product[i];
            break;
        }
    }

    //title
    $('#myModalLabel17').empty();
    $('#myModalLabel17').append(custom_product.title);
    //end title

    //category title
    $('#category_title').empty();
    var category_title = "";
    for(i = 0; i < categories.length; i ++)
    {
        if(categories[i].id == custom_product.category){
            category_title = categories[i].title;
            break;
        }
    }
    $('#category_title').append(category_title);
    //end category title

    //location
    $('#location').empty();
    $('#location').append(custom_product.country + ' ' + custom_product.city + ' (' + custom_product.street_address + ')');
    $('#location_info').val(custom_product.position);
    //end location

    //set time
    $('#product_time').empty();
    $('#product_time').append(`${custom_product.start_time} ~ ${custom_product.end_time}`);
    //end set time

    // google maps
    var location = $("#location_info").val();

    location_latlng = location.split(', ');
    var product_latlng = {
        lat: parseFloat(location_latlng[0]),
        lng: parseFloat(location_latlng[1])
    };

    map = new google.maps.Map(document.getElementById("basic-map"), {
        zoom: 7,
        center: product_latlng,
    });

    addMarker(product_latlng);
    // end google map

    //image carousel
    var custom_gallery = new Array;
    var gallery_count = 0;
    for(i = 0; i < product_gallery.length; i ++)
    {
        if(product_gallery[i].product_id == product_id){
            custom_gallery[gallery_count] = product_gallery[i];
            gallery_count ++;
        }
    }
    var str_image_carousel = "";
    str_image_carousel = '<ol class="carousel-indicators">';

    for(i = 0; i < gallery_count; i ++){
        if(i == 0){
            str_image_carousel += '<li data-target="#carousel-example-generic" data-slide-to="' + i + '" class="active"></li>'
        }
        else {
            str_image_carousel += '<li data-target="#carousel-example-generic" data-slide-to="' + i +'"></li>'
        }
    }

    str_image_carousel += "</ol>";

    str_image_carousel += '<div class="carousel-inner" role="listbox">';
    for(i = 0; i < gallery_count; i ++)
    {
        if(i == 0){
            str_image_carousel += '<div class="carousel-item active">' +
                                       '<img class="img-fluid" src="'+ base_path_url+'storage/'+ custom_gallery[i].path +'" alt="First slide" style="width: 100%; height: 300px; object-fit: cover;">' +
                                  '</div>';
        }
        else {
            str_image_carousel += '<div class="carousel-item">' +
                                       '<img class="img-fluid" src="'+ base_path_url+'storage/'+ custom_gallery[i].path +'" alt="Second slide" style="width: 100%; height: 300px; object-fit: cover;">' +
                                   '</div>';
        }
    }
    str_image_carousel +=   '</div>' +
                            '<a class="carousel-control-prev" href="#carousel-example-generic" role="button" data-slide="prev">' +
                            '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                            '<span class="sr-only">Previous</span>' +
                            '</a>' +
                            '<a class="carousel-control-next" href="#carousel-example-generic" role="button" data-slide="next">' +
                            '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                            '<span class="sr-only">Next</span>' +
                            '</a>';
    $('#carousel-example-generic').empty();
    $('#carousel-example-generic').append(str_image_carousel);

    //end image carousel

    //product description
    var str_description = "";
    str_description = '<p style="margin-right: 20px;" class="text-danger">Description</p>';
    var description_count = 0;
    var custom_description = new Array;
    for(i = 0; i < product_description.length; i ++){
        if(product_description[i].product_id == product_id){
            custom_description[description_count] = product_description[i];
            description_count ++;
        }
    }
    for(i = 0; i < description_count ; i ++)
    {
        var language_name = "";
        for(var j = 0; j < language.length; j ++)
        {
            if(language[j].title == custom_description[i].language)
            {
                language_name = language[j].name;
                break;
            }
        }
        str_description += '<div class="d-flex align-items-center justify-content-between">' +
                                `<p class="font-weight-bold">${language_name}</p>` +
                                '<div class="avatar mr-1 avatar-lg">' +
                                    `<img src="/images/flags/${custom_description[i].language}.png" alt="avtar img holder" class="flag">` +
                                '</div>' +
                            '</div>'
        str_description += `<div>${custom_description[i].description}</div>`;
    }

    $('#product_description').empty();
    $('#product_description').append(str_description);

    //end product description
    //pricing section
    var cnt = 0, flg = -1;
    var pricing_group_data = new Array;
    for(i = 0; i < product_pricing.length; i ++)
    {
        if(product_pricing[i].product_id == product_id)
        {
            pricing_group_data[0] = product_pricing[i].duration;
            flg = i;
            break;
        }
    }
    if(flg >= 0)
    {
        for(i = flg; i < product_pricing.length; i ++)
        {
            if( product_id == product_pricing[i].product_id && product_pricing[i].duration != pricing_group_data[cnt])
            {
                cnt ++;
                pricing_group_data[cnt] = product_pricing[i].duration;
            }
        }
    }

    pricing_group_data = pricing_group_data.reverse();
    var pricing_data = new Array;
    for(i = 0; i < pricing_group_data.length; i++)
    {
        var pricing_group = new Array;
        cnt = 0;
        for(j = 0; j < product_pricing.length; j ++){
            if(product_pricing[j].duration == pricing_group_data[i])
            {
                pricing_group[cnt] = product_pricing[j];
                cnt ++;
            }
        }

        var temp = new Array;
        temp['duration'] = pricing_group_data[i];
        temp['currency'] = pricing_group[0].currency;
        temp['pricing_data'] = new Array;

        for(j = 0; j < pricing_group.length; j ++)
        {
            tt = new Array;
            tt['id'] = pricing_group[j].id;
            tt['tag'] = pricing_group[j].tag;
            tt['price'] = pricing_group[j].price;

            temp['pricing_data'][j] = tt;
        }
        pricing_data[i] = temp;
    }

    var str_price_section = "";
    for(i=0; i < pricing_data.length; i++)
    {
        var cur, category;
        for(var k = 0; k < currency.length; k ++)
        {
            if(currency[k].id == pricing_data[i]['currency']){
                cur = currency[k].title;
                break;
            }
        }
        str_price_section += '<div style="border: 1px solid #ddd;" class="mb-1 pl-1">' +
                                '<div class="d-flex align-items-center mt-75 mb-1">' +
                                    '<p class="font-weight-bold mr-1 text-primary">' + pricing_data[i]['duration'] + '</p>' +
                                    '<p class="font-weight-bold text-primary">' + cur + '</p>'+
                                '</div>';
        var pricing_sub_data = pricing_data[i]['pricing_data'];
        for(k = 0; k < pricing_sub_data.length; k ++) {

            for(var p = 0; p < categoryTag.length; p ++){
                if(categoryTag[p].id == pricing_sub_data[k]['tag']){
                    category = categoryTag[p].title;
                    break;
                }
            }
            str_price_section += '<div class="d-flex align-items-center mt-75 mb-1">' +
                                    '<p class="font-weight-bold mr-1">' + category + '</p>' +
                                    '<p class="font-weight-bold">' + pricing_sub_data[k]['price'] + '(' + cur + ')</p>' +
                                '</div>';
        }
        str_price_section += '</div>';
    }
    $('#pricing-data').append(str_price_section);
    //pricing section
    $('#product_detail_modal').modal();
}

$('#itinerary_save').click(function(){

    var data = new Array();
    var from_my_date = new Date(from_date);
    for(var i = 0; i < str.length; i ++){
        data[i] = new Array();
        for(var j = 0; j < str[i].length; j ++){
            var substr_search = `"product_id" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_id = substr;
            ///////////

            var substr_search = `"itinerary_daily_id" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var itinerary_daily_id = substr;
            ///////////

            var substr_search = `"product_price_tag" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_price_tag = substr;
            ////////////

            var substr_search = `"product_price_season" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_price_season = substr;
            ////////////

            var substr_search = `"product_price_currency" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_price_currency = substr;
            ////////////

            var substr_search = `"product_price_id" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_price_id = substr;
            ////////////

            var substr_search = `"product_margin_price" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var itinerary_margin_price = substr;
            ////////////

            var substr_search = `"product_margin_type" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var itinerary_margin_type = substr;
            ////////////

            substr_search = `product_time_${i}_${j}">`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '<')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            substr = substr.split("~");
            var start_time = substr[0];
            var end_time = substr[1];
            /////////////

            substr_search = `product_travellers_${i}_${j}">`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '&')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var adults_num = substr;
            ////////////

            substr_search = `product_travellers_${i}_${j}">${substr}&nbsp;adults-`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '&')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var children_num = substr;
            var year = from_my_date.getFullYear();
            var month = from_my_date.getMonth() + 1;
            var day = from_my_date.getDate();
            /////////////

            if(month.length < 2)
                month = '0' + month;
            if(day.length < 2)
                day = '0' + day;
            var date_str = year + '/' + month + '/' + day;

            data[i][j] = {
                product_id: product_id,
                itinerary_daily_id: itinerary_daily_id,
                product_price_tag: product_price_tag,
                product_price_season: product_price_season,
                product_price_currency: product_price_currency,
                product_price_id: product_price_id,
                itinerary_margin_price: itinerary_margin_price,
                itinerary_margin_type: itinerary_margin_type,
                start_time: start_time,
                end_time: end_time,
                adults_num: adults_num,
                children_num: children_num,
                mydate: date_str,
                itinerary_id: itinerary.id,
            }
        }

        from_my_date.setDate(from_my_date.getDate() + 1);
    }

    jQuery.ajax({
        url: base_url + '/itinerary_daily_save',
        method: 'post',
        data: {
            _token: $("[name='_token']").val(),
            itinerary_id: $("#itinerary_id").val(),
            daily_itinerary: data,
        },
        success: function(data){
           if(data.result == 'success') {
                url = "/" + data.itinerary_id + "/" + "1";
                location.reload();
           }
           else {
                toastr.warning(data.message, 'warning', {'closeButton': true});
           }
        }
    });
});

$("#template_itinerary_save").on('click', function() {
    $("#template-itinerary-modal").modal();
});

$("#set_task").on('click', function() {
    var obj = $(".daily_checkbox:checked:enabled");

    if(obj.length == 0) {
        toastr.warning('you have to select one more daily item!', 'warning', {'closeButton': true, timeOut: 2000});
        return;
    }
    
    var daily_id_str = '';
    for(let i=0; i<obj.length; i++) {
        let id = $(obj[i]).data('daily-id');
        if(i==0) {
            daily_id_str += id
        }
        else {
            daily_id_str += ':'+id
        }
    }

    var str_assign_by = '<option value="">--- Please select ---</option>'
    itinerary_id = $('#itinerary_id').val();
    $.ajax({
        url: document.location.origin + '/admin/task/edit',
        method: 'post',
        data: {
            _token: $("[name='_token']").val(),
            itinerary_id: itinerary_id,
        },
        dataType: 'JSON',
        success: function (data) {
            icon_array = [];
            icon_array = ['bx bx-phone', 'bx bxs-user-voice', 'bx bx-timer', 'bx bx-mail-send'];

            if (data['result'] == 'success') {

                $('#daily_id').val(daily_id_str);

                if (data['task'].length == 0) {

                    current_account_str = data.current_account.first_name + ' ' + data.current_account.last_name + ', ' + data.current_account.main_email + ', ' + data.current_account.title;
                    $('#assign_by').val(current_account_str);
                    $('#assign_to').empty();
                    $('#assign_to').append(str_assign_by);

                    var accounts = data.account;
                    for (index = 0; index < accounts.length; index++) {
                        str_assign_by = '<option value = "' + accounts[index].user_id + '">' + accounts[index].first_name + ' ' + accounts[index].last_name + ', ' + accounts[index].main_email + ', ' + accounts[index].title + '</option>';

                        $('#assign_to').append(str_assign_by);
                    }

                    $('#itinerary_ref_num').val(data.itinerary_ref_num);
                }
                else {

                    current_account_str = data.current_account.first_name + ' ' + data.current_account.last_name + ', ' + data.current_account.main_email + ', ' + data.current_account.title;
                    $('#assign_by').val(current_account_str);
                    $('#assign_to').empty();
                    $('#assign_to').append(str_assign_by);

                    var accounts = data.account;
                    for (index = 0; index < accounts.length; index++) {
                        if (data['task'].assigned_to == accounts[index].user_id) {
                            str_assign_by = '<option value = "' + accounts[index].user_id + '" selected>' + accounts[index].first_name + ' ' + accounts[index].last_name + ', ' + accounts[index].main_email + ', ' + accounts[index].title + '</option>';
                        }
                        else {
                            str_assign_by = '<option value = "' + accounts[index].user_id + '">' + accounts[index].first_name + ' ' + accounts[index].last_name + ', ' + accounts[index].main_email + ', ' + accounts[index].title + '</option>';
                        }

                        $('#assign_to').append(str_assign_by);
                    }

                    $("#task_detail_modal #task_id").val(data['task'].id);
                    $("#task_detail_modal #task_name").val(data['task'].task_name);
                    $("#task_detail_modal #from_date").val(data['task'].start_date);
                    $("#task_detail_modal #start_time").val(data['task'].start_time);
                    $("#task_detail_modal #end_date").val(data['task'].end_date);
                    $("#task_detail_modal #end_time").val(data['task'].end_time);
                    CKEDITOR.instances.note.setData(data['task'].task_des);
                    $("[name = 'radioPriority']").val(data['task'].priority);
                    $("[name = 'radioStatus']").val(data['task'].status);
                    $('#itinerary_ref_num').val(data.itinerary_ref_num);
                }

                $('#task_detail_modal').modal();
            }
        }
    });
});

function save_task(){
    //task section
    var task_data = {};
    var itinerary_id = $('#itinerary_id').val();
    var daily_id = $("#daily_id").val();

    var task_id = $("#task_detail_modal #task_id").val();
    var task_name = $('#task_detail_modal #task_name').val();
    var from_date = $('#task_detail_modal #from_date').val();
    var start_time =$('#task_detail_modal #start_time').val();
    var end_date = $('#task_detail_modal #end_date').val();
    var end_time =$('#task_detail_modal #end_time').val();
    var priority = $('input[name="radioPriority"]:checked').val();
    var status = $('input[name="radioStatus"]:checked').val();
    var assign_to = $('#task_detail_modal #assign_to').val();
    var note_value = CKEDITOR.instances.note.getData();
    
    task_data['itinerary_id'] = itinerary_id;
    task_data['task_name'] = task_name;
    task_data['start_date'] = from_date;
    task_data['start_time'] = start_time;
    task_data['end_date'] = end_date;
    task_data['end_time'] = end_time;
    task_data['priority'] = priority;
    task_data['status'] = status;
    task_data['assigned_to'] = assign_to;
    task_data['task_des'] = note_value;
    task_data['reference_number'] = $('#task_detail_modal #itinerary_ref_num').val();
    
    if(task_name=="")
    {
        toastr.warning('The Task Name is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

    }
    else if(start_time=="")
    {
        toastr.warning('The Start Time is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

    }
    else if(end_time=="")
    {
        toastr.warning('The End Time is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

    }
    else if(assign_to=="")
    {
        toastr.warning('The Assign To is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});

    }
    else if(note_value=="")
    {
        toastr.warning('The Description is empty! Please fill that!', 'warning', {'closeButton': true, timeOut: 2000});
    }
    else
    {
        var start_date = from_date.split("-");
        var date1 = new Date( start_date[2], start_date[1] - 1, start_date[0]);

        end_date = end_date.split("-");
        var date2 = new Date(end_date[2], end_date[1] - 1, end_date[0]);

        var delta = date2.getTime() - date1.getTime();

        if(delta < 0)
        {
            toastr.warning('The end date must be greater than start date!', 'warning', {'closeButton': true, timeOut: 2000});
        }
        else {
            $.ajax({
                url: document.location.origin + '/admin/task/save',
                method: 'post',
                data: {
                    _token: $("[name='_token']").val(),
                    task_data: task_data,
                    task_id: task_id,
                    daily_id: daily_id
                },
                dataType: "JSON",
                success: function(data){
                    if(data.result == 'success') {
                        toastr.success('Saved New Task Successfully!', 'Success', {'closeButton': true, timeOut: 2000});
                        $('#task_detail_modal').modal('hide');
                        location.reload();
                    }
                    else if(data.result == "failure") {
                        toastr.warning(data.message, 'warning', {'closeButton': true});
                    }
                    else if(data.result == 'error')
                    {
                        toastr.warning('The task already exist!', 'warning', {'closeButton': true, timeOut: 2000});
                    }
                }
            });
        }
    }
}

function save_template_itinerary() {
    var data = new Array();

    for(var i = 0; i < str.length; i ++){
        data[i] = new Array();
        for(var j = 0; j < str[i].length; j ++){
            var substr_search = `"product_id" value="`;
            var substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            var temp_substr_index = substr_index;

            while(true){
                if(str[i][j][temp_substr_index] == '"')
                    break;
                temp_substr_index ++;
            }

            substr = str[i][j].substring(substr_index, temp_substr_index);
            var product_id = substr;

            substr_search = `product_time_${i}_${j}">`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '<')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            substr = substr.split("~");
            var start_time = substr[0];
            var end_time = substr[1];

            substr_search = `product_travellers_${i}_${j}">`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '&')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var adults_num = substr;

            substr_search = `product_travellers_${i}_${j}">${substr}&nbsp;adults-`;
            substr_index = str[i][j].indexOf(substr_search);
            substr_index += substr_search.length;
            temp_substr_index = substr_index;
            while(true){
                if(str[i][j][temp_substr_index] == '&')
                    break;
                temp_substr_index ++;
            }
            substr = str[i][j].substring(substr_index, temp_substr_index);
            var children_num = substr;

            data[i][j] = {
                product_id: product_id,
                start_time: start_time,
                end_time: end_time,
                adults_num: adults_num,
                children_num: children_num
            }
        }
    }
    jQuery.ajax({
        url: base_url + '/itinerary_template_save',
        method: 'post',
        data: {
            _token: $("[name='_token']").val(),
            template_id: $("#template_id").val(),
            template_title: $("#template_title").val(),
            template_itinerary: data,
        },
        success: function(data){
           if(data.result == 'success') {
             location.reload();
           }
        }
    });
}

function edit_day_title(day_title_id) {
    var val = $(`#day-title-val-${day_title_id}`).val();
    $('#day-title-modal-id').val(day_title_id);
    $('#day-title-input').val(val);
    $('#day-title-modal').modal();
}

function set_day_title() {
    var id = $('#day-title-modal-id').val();
    var val = $('#day-title-input').val();
    $(`#day-title-val-${id}`).val(val);
    $(`#day-title-${id}`).empty();
    $(`#day-title-${id}`).append(val);
}

function edit_product_time(obj_id, list_id) {
    $('#product-time-id').val(`product_time_${obj_id}_${list_id}`);
    $('#product-time-modal').modal();
    $('.pickatime').pickatime();
    $('.picker__holder').css({'position' : "fixed"});
}

function set_product_time() {
    var start_time = $('#start_schedule_time').val();
    var end_time = $('#end_schedule_time').val();
    var start_time_str;
    var end_time_str;
    var str_opt = start_time.substring(start_time.length - 2, start_time.length);
    if(str_opt == 'AM'){
        start_time_str = start_time.substring(0, start_time.length - 3);
        if(start_time_str.length == 4){
            start_time_str = '0' + start_time_str;
        }
    }
    else if(str_opt == 'PM')
    {
        start_time_str = start_time.substring(0, start_time.length - 3);
        var hour = start_time_str.substring(0,start_time_str.length - 3);
        var minute = start_time_str.substring(start_time_str.length - 3, start_time_str.length);
        hour = parseInt(hour);
        hour += 12;
        start_time_str = hour + minute;
    }

    str_opt = end_time.substring(end_time.length - 2, end_time.length);
    if(str_opt == 'AM'){
        end_time_str = end_time.substring(0, end_time.length - 3);
        if(end_time_str.length == 4){
            end_time_str = '0' + end_time_str;
        }
    }
    else if(str_opt == 'PM')
    {
        end_time_str = end_time.substring(0, end_time.length - 3);
        var hour = end_time_str.substring(0,end_time_str.length - 3);
        var minute = end_time_str.substring(end_time_str.length - 3, end_time_str.length);
        hour = parseInt(hour);
        hour += 12;
        end_time_str = hour + minute;

    }

    var product_time_id = $('#product-time-id').val();
    var obj_id_array = product_time_id.split('_');
    var obj_id = parseInt(obj_id_array[2]);
    var list_id = parseInt(obj_id_array[3]);
    $(`#${product_time_id}`).empty();
    $(`#${product_time_id}`).append(`${start_time_str}~${end_time_str}`);

    var str_temp = str[obj_id][list_id];
    var time_index = str_temp.indexOf('</span>&nbsp;&nbsp;');
    var sub_str = str_temp.substring(time_index - 11, time_index);
    start_end_time = `${start_time_str}~${end_time_str}`;
    str_temp = str_temp.replace(sub_str, start_end_time);
    str[obj_id][list_id] = str_temp;
}

function edit_product_travellers(obj_id, list_id){
    $('#product-travellers-id').val(`product_travellers_${obj_id}_${list_id}`);
    $('#adults_num_modal').val(0);
    $('#children_num_modal').val(0);
    $('#product-travellers-modal').modal();
}

function set_product_travellers() {
    var adults_num = $('#adults_num_modal').val();
    var children_num = $('#children_num_modal').val();
    var product_travellers_id = $('#product-travellers-id').val();
    $(`#${product_travellers_id}`).empty()
    $(`#${product_travellers_id}`).append(`${adults_num} adults-${children_num} children`);

    var obj_id_array = product_travellers_id.split('_');
    var obj_id = parseInt(obj_id_array[2]);
    var list_id = parseInt(obj_id_array[3]);

    var str_temp = str[obj_id][list_id];
    var travellers_index = str_temp.indexOf('&nbsp;adults-');
    var cnt = travellers_index;
    while(true){
        if(str_temp[cnt] == '>')
            break;
        cnt--;
    }
    var substr_adults = str_temp.substring(cnt + 1, travellers_index);
    travellers_index += 13;
    cnt = travellers_index;
    while(true){
        if(str_temp[cnt] == '&')
            break;
        cnt ++;
    }
    var substr_children = str_temp.substring(travellers_index, cnt);

    str_temp = str_temp.replace(`${substr_adults}&nbsp;adults-${substr_children}&nbsp;children</span>`, `${adults_num}&nbsp;adults-${children_num}&nbsp;children</span>`);
    str[obj_id][list_id] = str_temp;
}

function edit_margin_price(obj_id, list_id, margin_price) {
    $('#itinerary-margin-id').val(`itinerary_price_margin_${obj_id}_${list_id}`);
    $('#modal_itinerary_margin_price').val(margin_price);
    $('#itinerary-margin-modal').modal();
}

function set_price_margin() {
    var price_margin = $('#modal_itinerary_margin_price').val();
    var itinerary_margin_id = $('#itinerary-margin-id').val();

    $(`#${itinerary_margin_id}`).empty();
    if($("#modal_itinerary_margin_type_percent").prop('checked')) {
        $(`#${itinerary_margin_id}`).append(`Price Margin: ${price_margin}(%)`);        
    }
    else {
        $(`#${itinerary_margin_id}`).append(`Price Margin: ${price_margin}`);        
    }

    var obj_id_array = itinerary_margin_id.split('_');
    var obj_id = parseInt(obj_id_array[3]);
    var list_id = parseInt(obj_id_array[4]);

    var str_temp = str[obj_id][list_id];
    var margin_index = str_temp.indexOf('Price Margin: ');

    var cnt = margin_index;
    while(true){
        if(str_temp[cnt] == '<')
            break;
        cnt++;
    }
    var substr_margin_price = str_temp.substring(margin_index, cnt);

    var margin_type = 1;
    if($("#modal_itinerary_margin_type_percent").prop('checked')) {
        str_temp = str_temp.replace(`${substr_margin_price}`, `Price Margin: ${price_margin}(%)`);
        margin_type = 1;        
    }
    else {
        str_temp = str_temp.replace(`${substr_margin_price}`, `Price Margin: ${price_margin}`);      
        margin_type = 2;
    }
    

    // ///////////////////
    $("#daily-list-"+ obj_id +"-"+ list_id +" #product_margin_type").val(margin_type);
    var margin_index = str_temp.indexOf('"product_margin_type" value=');
    var cnt = margin_index;
    while(true){
        if(str_temp[cnt] == '>')
            break;
        cnt++;
    }

    var substr_margin_price = str_temp.substring(margin_index, cnt);
    str_temp = str_temp.replace(`${substr_margin_price}`, `"product_margin_type" value="${margin_type}"`);
    
    // ///////////////////
    $("#daily-list-"+ obj_id +"-"+ list_id +" #product_margin_price").val(price_margin);
    var margin_index = str_temp.indexOf('"product_margin_price" value=');
    var cnt = margin_index;
    while(true){
        if(str_temp[cnt] == '>')
            break;
        cnt++;
    }

    var substr_margin_price = str_temp.substring(margin_index, cnt);
    str_temp = str_temp.replace(`${substr_margin_price}`, `"product_margin_price" value="${price_margin}"`);

    ///////////////////

    var margin_index = str_temp.indexOf('edit_product_price(');
    var cnt = margin_index;
    while(true){
        if(str_temp[cnt] == ')')
            break;
        cnt++;
    }

    var substr_margin_price = str_temp.substring(margin_index, cnt);
    var sub_str_arr = substr_margin_price.split(', ');

    str_temp = str_temp.replace(`${substr_margin_price}`, `edit_product_price(${obj_id}, ${list_id}, ${sub_str_arr[1]}, ${sub_str_arr[2]}, ${sub_str_arr[3]}, ${sub_str_arr[4]}, ${sub_str_arr[5]}, ${sub_str_arr[6]}, ${sub_str_arr[7]}, ${sub_str_arr[8]} ${price_margin}, ${margin_type},`);
    $("#daily-list-"+ obj_id +"-"+ list_id +" .dropdown-edit-price").attr('onClick', `edit_product_price(${obj_id}, ${list_id}, ${sub_str_arr[1]}, ${sub_str_arr[2]}, ${sub_str_arr[3]}, ${sub_str_arr[4]}, ${sub_str_arr[5]}, ${sub_str_arr[6]}, ${sub_str_arr[7]}, ${sub_str_arr[8]} ${price_margin}, ${margin_type})`);


    // ///////////////////
    var margin_index = str_temp.indexOf('edit_margin_price(');
    var cnt = margin_index;
    while(true){
        if(str_temp[cnt] == ')')
            break;
        cnt++;
    }

    var substr_margin_price = str_temp.substring(margin_index, cnt);
    str_temp = str_temp.replace(`${substr_margin_price}`, `edit_margin_price(${obj_id}, ${list_id}, ${price_margin}, ${margin_type}`);
    $("#daily-list-"+ obj_id +"-"+ list_id +" .dropdown-edit-margin").attr('onClick', `edit_margin_price(${obj_id}, ${list_id}, ${price_margin}, ${margin_type})`);

    str[obj_id][list_id] = str_temp;
}

function day_selection_change() {
    var selected_day_id = $('#day_select').val();
    if(selected_day_id == 0){
        jQuery('.each-day').each(function(index, day_select) {
            $(`#${day_select.id}`).css('display', 'block');
        });
    }
    else {
        jQuery('.each-day').each(function(index, day_select) {
            if((index + 1) == selected_day_id)
                $(`#${day_select.id}`).css('display', 'block');
            else
                $(`#${day_select.id}`).css('display', 'none');
        });
    }
}

function delete_pricing_item(index) {
    $("#product_pricing_item_" + index).remove();
}

function check_valid(index) {
    var flag = $("#pricing_item_save_" + index).data('category');
    if(flag != undefined) {
        $("#pricing_item_save_" + index).data('category', 0);
        $("#pricing_item_save_" + index).html('<i class="bx bx-save"></i>');
    }
}

function save_pricing_item(index, pick_product_id, pick_date) {
    var flag = $("#pricing_item_save_" + index).data('category');
    var category_tag = $("#pricing_tag_" + index).val();
    
    if(flag != 0) {
        toastr.warning('Already assigned item, Please Modify the Categoroy', 'Warnning', { "closeButton": true });
    }
    else if(category_tag == 0) {
        toastr.warning('Please Select Category Tag', 'Warnning', { "closeButton": true });
    }
    else {
        $.ajax({
            url: base_url + '/check_itinerary_product_season',
            type: 'post',
            data: {
                _token: $("[name='_token']").val(),
                product_id: pick_product_id,
                from_date: from_date,
                to_date: to_date,
                pick_date: pick_date,
                category_tag: category_tag
            },
            dataType: 'json',
            success: function(response) {
                if(response.flag == 'blackout') {
                    toastr.warning(response.blackout_msg, 'Blackout Date', { "closeButton": true });
                }
                else if(response.flag == 'season'){
                    toastr.warning("Unavailable Season. Season unset for this period", 'Season Warning', { "closeButton": true });
                }
                else if(response.flag == 'success') {

                    var price_season = response.price_season;
                    var price_amount = response.price_amount;
                    var price_currency = response.price_currency;

                    $("#pricing_season_" + index).text('season: ' + price_season);
                    $("#pricing_product_" + index).val(price_amount);
                    $("#pricing_currency_" + index).val(price_currency);
                    
                    $("#pricing_item_save_" + index).html('<i class="bx bx-check"></i>');
                    $("#pricing_item_save_" + index).data('category', category_tag);
                }
            }
        })
    }
}

function new_product_pricing() {
    var item_obj = $("[id^='product_pricing_item_']");
    var container_obj_html = $("#product_pricing_container").html();

    var index;
    if(container_obj_html == '') {
        index = 0;
    }
    else{
        var item_obj_length = item_obj.length;
        var last_item_id = $(item_obj[item_obj_length-1]).attr('id');

        index = last_item_id.split('_');
        index = index[3];
        index = parseInt(index) + 1;
    }

    var pricing_content_html = '<div class="row" id="product_pricing_item_'+ index +'">'+
            '<input type="hidden" id="pricing_id_'+ index+'">'+
            '<div class="col-md-3">'+
                '<fieldset class="form-group">'+
                    '<select id="pricing_tag_'+ index +'" class="form-control" onchange="check_valid('+ index +')">' + tag_option_html + '</select>'+
                '</fieldset>'+
            '</div>'+
            '<div class="col-md-3">'+
                '<div id="pricing_season_'+ index +'" style="margin-top:7px;"></div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<fieldset class="form-group">'+
                    '<select id="pricing_currency_'+ index +'" class="form-control">' + currency_option_html + '</select>'+
                '</fieldset>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<fieldset class="form-group">'+
                    '<input type="text" class="form-control" id="pricing_product_'+ index +'" value="">'+
                '</fieldset>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="d-flex align-item-center justify-content-between">'+
                    '<button type="button" class="btn btn-icon rounded-circle btn-outline-primary" data-category="0" onclick="save_pricing_item('+ index +', '+ pick_product_id +', \''+ pick_date +'\')" id="pricing_item_save_'+ index +'">'+
                        '<i class="bx bx-save"></i>'+
                    '</button>'+
                    '<button type="button" class="btn btn-icon rounded-circle btn-outline-danger" onclick="delete_pricing_item('+ index +')">'+
                        '<i class="bx bx-trash"></i>'+
                    '</button>'+
                '</div>'+
            '</div>'+
        '</div>';

    $("#product_pricing_container").append(pricing_content_html);
}

function set_product_price() {
    var valid_obj = $("[id ^= 'pricing_item_save_']");
    var valid_obj_length = valid_obj.length;

    if(valid_obj_length == 0) {
        toastr.warning("Please set the Price with available category. if not, you can not set the price.", 'Warning', { "closeButton": true });
    }
    else {
        var flag = 0;
        for(var i=0; i<valid_obj_length; i++) {
            var category = $(valid_obj[i]).data('category');
            if(category == 0) {
                flag = 1;
                break;
            }
        }

        if(flag == 1) {
            toastr.warning("Please set exactly all Price with available category. Seams like to be non-seting items.", 'Warning', { "closeButton": true });
        }
        else {
            var price_tag_obj = $("[id ^= 'pricing_tag_']");
            var price_season_obj = $("[id ^= 'pricing_season_']");
            var price_currency_obj = $("[id ^= 'pricing_currency_']");
            var price_id_obj = $("[id ^= 'pricing_product_']");
            

            var product_price_id = '';
            var product_price_tag = '';
            var product_price_currency = '';
            var product_price_season = '';

            var price_text = '';

            for(var i=0; i<price_id_obj.length; i++) {
                if(i == 0) {
                    product_price_id += $(price_id_obj[i]).val();
                    product_price_tag += $(price_tag_obj[i]).val();
                    
                    var temp_season = $(price_season_obj[i]).text();
                    temp_season = temp_season.split(': ');
                    product_price_season += temp_season[1];

                    product_price_currency += $(price_currency_obj[i]).val();
                    price_text += $(price_id_obj[i]).val() + '(' + currency_list[$(price_currency_obj[i]).val()] + ')';
                    price_text += ' '; 
                }
                else {
                    product_price_id += ':' + $(price_id_obj[i]).val();
                    product_price_tag += ':' + $(price_tag_obj[i]).val();
                    
                    var temp_season = $(price_season_obj[i]).text();
                    temp_season = temp_season.split(': ');
                    product_price_season += ':'+temp_season[1];

                    product_price_currency += ':'+$(price_currency_obj[i]).val();
                    price_text += $(price_id_obj[i]).val() + '(' + currency_list[$(price_currency_obj[i]).val()] + ')';
                    price_text += ' '; 
                }
            }

            var city = global_custom_product.city? global_custom_product.city : '';
            var country = global_custom_product.country? global_custom_product.country : '';

            if(update_flag == 0) {
                var html_each = '<li class="product-list-each" id="daily-list-' + global_obj_id + '-' + k[global_obj_id] + '">' +
                                '<div class="daily-products-class">' +
                                '<input type="hidden" name="product_id" id="product_id" value="' + global_custom_product.id +'">' +
// new 
                                '<input type="hidden" name="itinerary_daily_id" id="itinerary_daily_id" value="0">' +
                                '<input type="hidden" name="product_price_tag" id="product_price_tag" value="' + product_price_tag +'">' +
                                '<input type="hidden" name="product_price_season" id="product_price_season" value="' + product_price_season +'">' +
                                '<input type="hidden" name="product_price_currency" id="product_price_currency" value="' + product_price_currency +'">' +
                                '<input type="hidden" name="product_price_id" id="product_price_id" value="' + product_price_id +'">' +
// new
                                '<input type="hidden" name="product_margin_type" id="product_margin_type" value="1">' +
                                '<input type="hidden" name="product_margin_price" id="product_margin_price" value="0">' +
                                

                                '<div class="daily-products-left">'+
                                    '<i class="bx bx-grid-vertical" style="font-size: 25px; margin: auto 0; cursor: move"></i>'+
                                    '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ global_custom_product.get_first_image.path +'"/>'+
                                    '<div class="daily-products-explain">'+
                                        '<div class="daily-products-title">'+
                                            global_custom_product.title + '<span class="daily-products-detail">('+ city +', ' + country +')</span>'+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-info-circle" style="color: rgb(210, 77, 83);padding-top: 2px;"></i>`+ 
                                            `<span style="font-weight: bold;" id="itinerary_price_real_${global_obj_id}_${k[global_obj_id]}">Price: ${price_text} </span>`+
                                            `<span style="font-weight: bold;" id="itinerary_price_margin_${global_obj_id}_${k[global_obj_id]}">Price Margin: 0(%)</span>`+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_time_${global_obj_id}_${k[global_obj_id]}">00:00~00:00</span>&nbsp;&nbsp;`+
                                            `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_travellers_${global_obj_id}_${k[global_obj_id]}">${itinerary.adult_number}&nbsp;adults-${itinerary.children_number}&nbsp;children</span>`+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="daily-products-right">'+
                                    '<span class="bullet bullet-sm bullet-danger mr-50"></span>'+
                                    `<input type="checkbox" class="daily_checkbox" id="daily_checkbox_${global_obj_id}_${k[global_obj_id]}" data-daily-id="null" data-task-id="null" unchecked="true" enabled onclick="check_validation(this);">`+
                                    '<div class="dropdown">'+
                                    '<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"'+
                                        'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>'+
                                    '<div class="dropdown-menu dropdown-menu-right">'+
                                        `<a class="dropdown-item dropdown-edit-time" href="javascript:void(0)" onClick="edit_product_time(${global_obj_id}, ${k[global_obj_id]})"><i class="bx bx-edit-alt mr-1"></i> Edit Time</a>`+
                                        `<a class="dropdown-item dropdown-edit-travellers" href="javascript:void(0)" onClick="edit_product_travellers(${global_obj_id}, ${k[global_obj_id]})"><i class="bx bx-edit-alt mr-1"></i> Travellers</a>`+
// new
                                        `<a class="dropdown-item dropdown-edit-price" href="javascript:void(0)" onClick="edit_product_price(${global_obj_id}, ${k[global_obj_id]}, 0, null, null, null '${product_price_tag}', '${product_price_season}', '${product_price_currency}', '${product_price_id}', 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Price</a>`+
// new
                                        `<a class="dropdown-item dropdown-edit-margin" href="javascript:void(0)" onClick="edit_margin_price(${global_obj_id}, ${k[global_obj_id]}, 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Margin</a>`+
                                        `<a class="dropdown-item dropdown-del-product" href="javascript:void(0)" onClick="daily_product_del(${global_obj_id}, ${k[global_obj_id]}, 0)"><i class="bx bx-trash mr-1"></i> delete</a>`+
                                    '</div>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</li>';

                str[global_obj_id][k[global_obj_id]] = html_each;

                var str_tmp = `<ul class='product-list' id='daily-schedule-id-${global_obj_id}'>`;
                for(var j = 0; j < k[global_obj_id] + 1; j ++) {
                    str_tmp += str[global_obj_id][j];
                }
                str_tmp += '</ul>';
                str_tmp +=  '<div class="drag-explain-contain">' +
                                '<sapn>Drag a product here to add it to the itinerary</span>' +
                            '</div>';

                k[global_obj_id] ++;
            }
            else {
                var bullet_html = '';
                if(global_task_status == null || global_task_status == 0) {
                    var bullet_html = '<span class="bullet bullet-sm bullet-danger mr-50"></span>';
                }
                else if(global_task_status == -1) {
                    var bullet_html = '<span class="bullet bullet-sm bullet-secondary mr-50"></span>';
                }
                else {
                    var bullet_html = '<span class="bullet bullet-sm bullet-success mr-50"></span>';
                }

                var margin_text = "";
                if(global_margin_type == 1) {
                    margin_text = global_margin_price + '(%)';
                }
                else {
                    margin_text = global_margin_price;
                }

                var task_checked_flag = 'unchecked="true"';
                if(global_task_detail_id == null || global_task_detail_id == 0) {
                    task_checked_flag = 'unchecked="true" enabled';
                }
                else {
                    task_checked_flag = 'checked="true" disabled';
                }
                
                var html_each = '<li class="product-list-each" id="daily-list-' + global_obj_id + '-' + update_key_index + '">' +
                                '<div class="daily-products-class">' +
                                '<input type="hidden" name="product_id" id="product_id" value="' + global_custom_product.id +'">' +
// new
                                '<input type="hidden" name="itinerary_daily_id" id="itinerary_daily_id" value="'+ global_itinerary_daily_id +'">' +
                                '<input type="hidden" name="product_price_tag" id="product_price_tag" value="' + product_price_tag +'">' +
                                '<input type="hidden" name="product_price_season" id="product_price_season" value="' + product_price_season +'">' +
                                '<input type="hidden" name="product_price_currency" id="product_price_currency" value="' + product_price_currency +'">' +
                                '<input type="hidden" name="product_price_id" id="product_price_id" value="' + product_price_id +'">' +
// new

                                '<input type="hidden" name="product_margin_type" id="product_margin_type" value="' + global_margin_type +'">' +                                
                                '<input type="hidden" name="product_margin_price" id="product_margin_price" value="' + global_margin_price +'">' +
                                
                                '<div class="daily-products-left">'+
                                    '<i class="bx bx-grid-vertical" style="font-size: 25px; margin: auto 0; cursor: move"></i>'+
                                    '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ global_custom_product.get_first_image.path +'"/>'+
                                    '<div class="daily-products-explain">'+
                                        '<div class="daily-products-title">'+
                                            global_custom_product.title + '<span class="daily-products-detail">('+ city +', ' + country +')</span>'+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-info-circle" style="color: rgb(210, 77, 83);padding-top: 2px;"></i>`+ 
                                            `<span style="font-weight: bold;" id="itinerary_price_real_${global_obj_id}_${update_key_index}">Price: ${price_text} </span>`+
                                            `<span style="font-weight: bold;" id="itinerary_price_margin_${global_obj_id}_${update_key_index}">Price Margin: ${margin_text}</span>`+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_time_${global_obj_id}_${update_key_index}">${global_time}</span>&nbsp;&nbsp;`+
                                            `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span style="font-weight: bold;" id="product_travellers_${global_obj_id}_${update_key_index}">${itinerary.adult_number}&nbsp;adults-${itinerary.children_number}&nbsp;children</span>`+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="daily-products-right">'+
                                    bullet_html +
                                    `<input type="checkbox" class="daily_checkbox" id="daily_checkbox_${global_obj_id}_${update_key_index}" data-daily-id="${global_itinerary_daily_id}" data-task-id="${global_task_detail_id}" ${task_checked_flag} onclick="check_validation(this);">`+
                                    '<div class="dropdown">'+
                                    '<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"'+
                                        'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>'+
                                    '<div class="dropdown-menu dropdown-menu-right">'+
                                        `<a class="dropdown-item dropdown-edit-time" href="javascript:void(0)" onClick="edit_product_time(${global_obj_id}, ${update_key_index})"><i class="bx bx-edit-alt mr-1"></i> Edit Time</a>`+
                                        `<a class="dropdown-item dropdown-edit-travellers" href="javascript:void(0)" onClick="edit_product_travellers(${global_obj_id}, ${update_key_index})"><i class="bx bx-edit-alt mr-1"></i> Travellers</a>`+
// new
                                        `<a class="dropdown-item dropdown-edit-price" href="javascript:void(0)" onClick="edit_product_price(${global_obj_id}, ${update_key_index}, ${global_itinerary_daily_id}, ${global_task_id}, ${global_task_status}, ${global_task_detail_id}, '${product_price_tag}', '${product_price_season}', '${product_price_currency}', '${product_price_id}', ${global_margin_price}, ${global_margin_type})"><i class="bx bx-edit-alt mr-1"></i> Edit Price</a>`+
// new
                                    
                                        `<a class="dropdown-item dropdown-edit-margin" href="javascript:void(0)" onClick="edit_margin_price(${global_obj_id}, ${update_key_index}, ${global_margin_price}, ${global_margin_type})"><i class="bx bx-edit-alt mr-1"></i> Edit Margin</a>`+
                                        `<a class="dropdown-item dropdown-del-product" href="javascript:void(0)" onClick="daily_product_del(${global_obj_id}, ${update_key_index}, ${global_task_id})"><i class="bx bx-trash mr-1"></i> delete</a>`+
                                    '</div>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</li>';

                str[global_obj_id][update_key_index] = html_each;

                var str_tmp = `<ul class='product-list' id='daily-schedule-id-${global_obj_id}'>`;
                for(var j = 0; j < k[global_obj_id]; j ++) {
                    str_tmp += str[global_obj_id][j];
                }
                str_tmp += '</ul>';
                str_tmp +=  '<div class="drag-explain-contain">' +
                                '<sapn>Drag a product here to add it to the itinerary</span>' +
                            '</div>';
            }

            $("#each-day-products-" + global_obj_id).addClass('has-drop').html(str_tmp);

            dragula([document.getElementById('daily-schedule-id-' + global_obj_id)])
            .on('drop', function (e, el){
                var ul_string = el.innerHTML;
                var ul_string_arr = ul_string.split('</li>');
                var temp_string = "";
                for(i = 0; i < ul_string_arr.length - 1; i ++)
                {
                    str[global_obj_id][i] = ul_string_arr[i] + '</li>';
                    str[global_obj_id][i] = str[global_obj_id][i].replace(' gu-transit', "");

                    var str_index = str[global_obj_id][i].indexOf(`daily-list-${global_obj_id}-`);
                    var str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    var length = `daily-list-${global_obj_id}-`.length;
                    var substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`daily-list-${global_obj_id}-${substr}`,`daily-list-${global_obj_id}-${i}`);
                    
                    /////////////////
                    str_index = str[global_obj_id][i].indexOf(`itinerary_price_real_${global_obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `itinerary_price_real_${global_obj_id}_`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`itinerary_price_real_${global_obj_id}_${substr}`,`itinerary_price_real_${global_obj_id}_${i}`);
                    ////////////

                    /////////////////
                    str_index = str[global_obj_id][i].indexOf(`itinerary_price_margin_${global_obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `itinerary_price_margin_${global_obj_id}_`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`itinerary_price_margin_${global_obj_id}_${substr}`,`itinerary_price_margin_${global_obj_id}_${i}`);
                    ////////////



                    str_index = str[global_obj_id][i].indexOf(`product_time_${global_obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `product_time_${global_obj_id}_`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`product_time_${global_obj_id}_${substr}`,`product_time_${global_obj_id}_${i}`);
                    /////////////

                    str_index = str[global_obj_id][i].indexOf(`product_travellers_${global_obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '"')
                            break;
                        str_index_temp ++;
                    }
                    length = `product_travellers_${global_obj_id}_`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`product_travellers_${global_obj_id}_${substr}`,`product_travellers_${global_obj_id}_${i}`);
                    /////////////

                    str_index = str[global_obj_id][i].indexOf(`daily_checkbox_${global_obj_id}_`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == '>')
                            break;
                        str_index_temp ++;
                    }
                    length = `daily_checkbox_${global_obj_id}_`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(" ");
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`daily_checkbox_${global_obj_id}_${substr}`, `daily_checkbox_${global_obj_id}_${i}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
                    /////////////

                    str_index = str[global_obj_id][i].indexOf(`edit_product_time(${global_obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_time(${global_obj_id}, `.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`edit_product_time(${global_obj_id}, ${substr})`,`edit_product_time(${global_obj_id}, ${i})`);
                    //////////////

                    str_index = str[global_obj_id][i].indexOf(`edit_product_travellers(${global_obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_travellers(${global_obj_id}, `.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`edit_product_travellers(${global_obj_id}, ${substr})`,`edit_product_travellers(${global_obj_id}, ${i})`);
                    /////////////
                    
                    str_index = str[global_obj_id][i].indexOf(`edit_product_price(${global_obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_product_price(${global_obj_id}, `.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
// new
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`edit_product_price(${global_obj_id}, ${substr})`, `edit_product_price(${global_obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);
// new
                    //////////////

                    str_index = str[global_obj_id][i].indexOf(`edit_margin_price(${global_obj_id}, `);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `edit_margin_price(${global_obj_id}, `.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
                    
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`edit_margin_price(${global_obj_id}, ${substr})`, `edit_margin_price(${global_obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]})`);
                    ////////////

                    str_index = str[global_obj_id][i].indexOf(`daily_product_del(${global_obj_id},`);
                    str_index_temp = str_index;
                    while(true)
                    {
                        if(str[global_obj_id][i][str_index_temp] == ')')
                            break;
                        str_index_temp ++;
                    }
                    length = `daily_product_del(${global_obj_id},`.length;
                    substr = str[global_obj_id][i].substring(str_index + length, str_index_temp);
                    var substr_arr = substr.split(", ");
                    str[global_obj_id][i] = str[global_obj_id][i].replace(`daily_product_del(${global_obj_id},${substr})`,`daily_product_del(${global_obj_id}, ${i}, ${substr_arr[1]})`);

                    temp_string += str[global_obj_id][i];
                }

                $(`#daily-schedule-id-${global_obj_id}`).empty();
                $(`#daily-schedule-id-${global_obj_id}`).append(temp_string);
            });

            $('#product-pricing-modal').modal('hide');
        }
    }
}

function set_all_product_price() {
    var valid_obj = $("[id ^= 'pricing_item_save_']");
    var valid_obj_length = valid_obj.length;

    if(valid_obj_length == 0) {
        toastr.warning("Please set the Price with available category. if not, you can not set the price.", 'Warning', { "closeButton": true });
    }
    else {
        var flag = 0;
        for(var i=0; i<valid_obj_length; i++) {
            var category = $(valid_obj[i]).data('category');
            if(category == 0) {
                flag = 1;
                break;
            }
        }

        if(flag == 1) {
            toastr.warning("Please set exactly all Price with available category. Seams like to be non-seting items.", 'Warning', { "closeButton": true });
        }
        else {
            var price_tag_obj = $("[id ^= 'pricing_tag_']");
            var price_season_obj = $("[id ^= 'pricing_season_']");
            var price_currency_obj = $("[id ^= 'pricing_currency_']");
            var price_id_obj = $("[id ^= 'pricing_product_']");
            

            var product_price_id = '';
            var product_price_tag = '';
            var product_price_currency = '';
            var product_price_season = '';

            var price_text = '';

            for(var i=0; i<price_id_obj.length; i++) {
                if(i == 0) {
                    product_price_id += $(price_id_obj[i]).val();
                    product_price_tag += $(price_tag_obj[i]).val();
                    
                    var temp_season = $(price_season_obj[i]).text();
                    temp_season = temp_season.split(': ');
                    product_price_season += temp_season[1];

                    product_price_currency += $(price_currency_obj[i]).val();

                    price_text += $(price_id_obj[i]).val() + '(' + currency_list[$(price_currency_obj[i]).val()] + ')';
                    price_text += ' '; 
                }
                else {
                    product_price_id += ':'+$(price_id_obj[i]).val();
                    product_price_tag += ':'+$(price_tag_obj[i]).val();
                    
                    var temp_season = $(price_season_obj[i]).text();
                    temp_season = temp_season.split(': ');
                    product_price_season += ':'+temp_season[1];

                    product_price_currency += ':'+$(price_currency_obj[i]).val();

                    price_text += $(price_id_obj[i]).val() + '(' + currency_list[$(price_currency_obj[i]).val()] + ')';
                    price_text += ' '; 
                }
            }

            // new test

            var city = global_custom_product.city? global_custom_product.city : '';
            var country = global_custom_product.country? global_custom_product.country : '';

            var all_product_container = $("[id^='each-day-products-']");
            for(var i=0; i<all_product_container.length; i++) {
                var obj_id = $(all_product_container[i]).attr('id');
                var obj_index = obj_id.split('-')[3];

                var html_each = '<li class="product-list-each" id="daily-list-' + obj_index + '-' + k[obj_index] + '">' +
                                '<div class="daily-products-class">' +
                                '<input type="hidden" name="product_id" id="product_id" value="' + global_custom_product.id +'">' +
                // new 
                                '<input type="hidden" name="itinerary_daily_id" id="itinerary_daily_id" value="0">' +
                                '<input type="hidden" name="product_price_tag" id="product_price_tag" value="' + product_price_tag +'">' +
                                '<input type="hidden" name="product_price_season" id="product_price_season" value="' + product_price_season +'">' +
                                '<input type="hidden" name="product_price_currency" id="product_price_currency" value="' + product_price_currency +'">' +
                                '<input type="hidden" name="product_price_id" id="product_price_id" value="' + product_price_id +'">' +
                // new
                                '<input type="hidden" name="product_margin_type" id="product_margin_type" value="1">' +
                                '<input type="hidden" name="product_margin_price" id="product_margin_price" value="0">' +

                                '<div class="daily-products-left">'+
                                    '<i class="bx bx-grid-vertical" style="font-size: 25px; margin: auto 0; cursor: move"></i>'+
                                    '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ global_custom_product.get_first_image.path +'"/>'+
                                    '<div class="daily-products-explain">'+
                                        '<div class="daily-products-title">'+
                                            global_custom_product.title + '<span class="daily-products-detail">('+ city +', ' + country +')</span>'+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-info-circle" style="color: rgb(210, 77, 83);padding-top: 2px;"></i>`+ 
                                            `<span id="itinerary_price_real_${obj_index}_${k[obj_index]}">Price: ${price_text} </span>`+
                                            `<span id="itinerary_price_margin_${obj_index}_${k[obj_index]}">Price Margin: 0(%)</span>`+
                                        '</div>'+
                                        '<div class="daily-proudcts-option">'+
                                            `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span id="product_time_${obj_index}_${k[obj_index]}">00:00~00:00</span>&nbsp;&nbsp;`+
                                            `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span id="product_travellers_${obj_index}_${k[obj_index]}">${itinerary.adult_number}&nbsp;adults-${itinerary.children_number}&nbsp;children</span>`+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="daily-products-right">'+
                                    '<span class="bullet bullet-sm bullet-danger mr-50"></span>'+
                                    `<input type="checkbox" class="daily_checkbox" id="daily_checkbox_${obj_index}_${k[obj_index]}" data-daily-id="null" data-task-id="null" unchecked="true" enabled onclick="check_validation(this);">`+
                                    '<div class="dropdown">'+
                                    '<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"'+
                                        'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>'+
                                    '<div class="dropdown-menu dropdown-menu-right">'+
                                        `<a class="dropdown-item dropdown-edit-time" href="javascript:void(0)" onClick="edit_product_time(${obj_index}, ${k[obj_index]})"><i class="bx bx-edit-alt mr-1"></i> Edit Time</a>`+
                                        `<a class="dropdown-item dropdown-edit-travellers" href="javascript:void(0)" onClick="edit_product_travellers(${obj_index}, ${k[obj_index]})"><i class="bx bx-edit-alt mr-1"></i> Travellers</a>`+
                // new
                                        `<a class="dropdown-item dropdown-edit-price" href="javascript:void(0)" onClick="edit_product_price(${obj_index}, ${k[obj_index]}, 0, null, null, null, '${product_price_tag}', '${product_price_season}', '${product_price_currency}', '${product_price_id}', 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Price</a>`+
                // new
                                        `<a class="dropdown-item dropdown-edit-margin" href="javascript:void(0)" onClick="edit_margin_price(${obj_index}, ${k[obj_index]}, 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Margin</a>`+
                                        `<a class="dropdown-item dropdown-del-product" href="javascript:void(0)" onClick="daily_product_del(${obj_index}, ${k[obj_index]}, 0)"><i class="bx bx-trash mr-1"></i> delete</a>`+
                                    '</div>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</li>';

                str[obj_index][k[obj_index]] = html_each;

                var str_tmp = `<ul class='product-list' id='daily-schedule-id-${obj_index}'>`;
                for(var j = 0; j < k[obj_index] + 1; j ++) {
                    str_tmp += str[obj_index][j];
                }
                str_tmp += '</ul>';
                str_tmp +=  '<div class="drag-explain-contain">' +
                                '<sapn>Drag a product here to add it to the itinerary</span>' +
                            '</div>';

                k[obj_index] ++;


                $("#each-day-products-" + obj_index).addClass('has-drop').html(str_tmp);

                dragula([document.getElementById('daily-schedule-id-' + obj_index)])
                .on('drop', function (e, el){
                    var ul_string = el.innerHTML;
                    var ul_string_arr = ul_string.split('</li>');
                    var temp_string = "";
                    for(i = 0; i < ul_string_arr.length - 1; i ++)
                    {
                        str[obj_index][i] = ul_string_arr[i] + '</li>';
                        str[obj_index][i] = str[obj_index][i].replace(' gu-transit', "");

                        var str_index = str[obj_index][i].indexOf(`daily-list-${obj_index}-`);
                        var str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '"')
                                break;
                            str_index_temp ++;
                        }
                        var length = `daily-list-${obj_index}-`.length;
                        var substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`daily-list-${obj_index}-${substr}`,`daily-list-${obj_index}-${i}`);

                        /////////////////
                        str_index = str[obj_index][i].indexOf(`itinerary_price_real_${obj_index}_`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '"')
                                break;
                            str_index_temp ++;
                        }
                        length = `itinerary_price_real_${obj_index}_`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`itinerary_price_real_${obj_index}_${substr}`,`itinerary_price_real_${obj_index}_${i}`);
                        ////////////

                        /////////////////
                        str_index = str[obj_index][i].indexOf(`itinerary_price_margin_${obj_index}_`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '"')
                                break;
                            str_index_temp ++;
                        }
                        length = `itinerary_price_margin_${obj_index}_`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`itinerary_price_margin_${obj_index}_${substr}`,`itinerary_price_margin_${obj_index}_${i}`);
                        ////////////

                        str_index = str[obj_index][i].indexOf(`product_time_${obj_index}_`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '"')
                                break;
                            str_index_temp ++;
                        }
                        length = `product_time_${obj_index}_`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`product_time_${obj_index}_${substr}`,`product_time_${obj_index}_${i}`);
                        /////////////

                        str_index = str[obj_index][i].indexOf(`product_travellers_${obj_index}_`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '"')
                                break;
                            str_index_temp ++;
                        }
                        length = `product_travellers_${obj_index}_`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`product_travellers_${obj_index}_${substr}`,`product_travellers_${obj_index}_${i}`);
                        /////////////

                        str_index = str[obj_index][i].indexOf(`daily_checkbox_${obj_index}_`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == '>')
                                break;
                            str_index_temp ++;
                        }
                        length = `daily_checkbox_${obj_index}_`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        var substr_arr = substr.split(" ");
                        str[obj_index][i] = str[obj_index][i].replace(`daily_checkbox_${obj_index}_${substr}`, `daily_checkbox_${obj_index}_${i}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
                        /////////////

                        str_index = str[obj_index][i].indexOf(`edit_product_time(${obj_index}, `);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == ')')
                                break;
                            str_index_temp ++;
                        }
                        length = `edit_product_time(${obj_index}, `.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`edit_product_time(${obj_index}, ${substr})`,`edit_product_time(${obj_index}, ${i})`);
                        //////////////

                        str_index = str[obj_index][i].indexOf(`edit_product_travellers(${obj_index}, `);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == ')')
                                break;
                            str_index_temp ++;
                        }
                        length = `edit_product_travellers(${obj_index}, `.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        str[obj_index][i] = str[obj_index][i].replace(`edit_product_travellers(${obj_index}, ${substr})`,`edit_product_travellers(${obj_index}, ${i})`);
                        /////////////
                        
                        str_index = str[obj_index][i].indexOf(`edit_product_price(${obj_index}, `);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == ')')
                                break;
                            str_index_temp ++;
                        }
                        length = `edit_product_price(${obj_index}, `.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        var substr_arr = substr.split(", ");
                // new
                        str[obj_index][i] = str[obj_index][i].replace(`edit_product_price(${obj_index}, ${substr})`, `edit_product_price(${obj_index}, ${i}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);
                // new
                        //////////////

                        str_index = str[obj_index][i].indexOf(`edit_margin_price(${obj_index}, `);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == ')')
                                break;
                            str_index_temp ++;
                        }
                        length = `edit_margin_price(${obj_index}, `.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        var substr_arr = substr.split(", ");
                        
                        str[obj_index][i] = str[obj_index][i].replace(`edit_margin_price(${obj_index}, ${substr})`, `edit_margin_price(${obj_index}, ${i}, ${substr_arr[1]}, ${substr_arr[2]})`);
                        ////////////

                        str_index = str[obj_index][i].indexOf(`daily_product_del(${obj_index},`);
                        str_index_temp = str_index;
                        while(true)
                        {
                            if(str[obj_index][i][str_index_temp] == ')')
                                break;
                            str_index_temp ++;
                        }
                        length = `daily_product_del(${obj_index},`.length;
                        substr = str[obj_index][i].substring(str_index + length, str_index_temp);
                        var substr_arr = substr.split(", ");
                        str[obj_index][i] = str[obj_index][i].replace(`daily_product_del(${obj_index},${substr})`,`daily_product_del(${obj_index}, ${i}, ${substr_arr[1]})`);

                        temp_string += str[obj_index][i];
                    }

                    $(`#daily-schedule-id-${obj_index}`).empty();
                    $(`#daily-schedule-id-${obj_index}`).append(temp_string);
                });
            }

            $('#product-pricing-modal').modal('hide');
        }
    }
}
 
function edit_product_price(obj_id, key_id, itinerary_daily_id, task_id, task_status, task_detail_id, product_price_tag, product_price_season, product_price_currency, product_price_id, margin_price, margin_type) {
    var product_id = $("#daily-list-"+obj_id+"-"+key_id+" #product_id").val();

    pick_product_id = product_id;
    global_margin_price = margin_price;
    global_margin_type = margin_type;
    global_itinerary_daily_id = itinerary_daily_id;
    global_task_id = task_id;
    global_task_status = task_status;
    global_task_detail_id = task_detail_id;
    global_time = $("#product_time_"+obj_id+"_"+key_id).text();
    
    pick_date = $("#each_day_container_" + obj_id + " .day-date").data('pick');

    $.ajax({
        url: base_url + '/get_product_pricing_and_tag',
        type: 'post',
        data: {
            _token: $("[name='_token']").val(),
            product_id: product_id
        },
        dataType: 'json',
        success: function(response) {
            var product_data = response.product_data;
            var product_tag = response.product_tag;
            
            global_custom_product = product_data;
            global_custom_product.get_first_image = {};
            global_custom_product.get_first_image.path = product_data.path;
            
            global_obj_id = obj_id;

            update_key_index = key_id;
            update_flag = 1;

            product_price_tag = product_price_tag.split(':');
            product_price_season = product_price_season.split(':');
            product_price_currency = product_price_currency.split(':');
            product_price_id = product_price_id.split(':');

            $("#product_pricing_container").empty();

            var total_html = '';
            for(var i=0; i<product_price_id.length; i++) {
                var tag_html = '<option value="0">Pricing Tag</option>';
                for(var j=0; j<product_tag.length; j++) {
                    if(product_tag[j].id == product_price_tag[i]) {
                        tag_html += '<option selected value="'+ product_tag[j].id +'">'+ product_tag[j].title +'</option>';
                    }
                    else {
                        tag_html += '<option value="'+ product_tag[j].id +'">'+ product_tag[j].title +'</option>';
                    }
                }

                tag_option_html = tag_html;

                var currency_html = '<option value="0">Currency</option>';
                for(var j=0; j<currency.length; j++) {
                    if(currency[j].id == product_price_currency[i]) {
                        currency_html += '<option selected value="'+ currency[j].id +'">'+ currency[j].title +'</option>';
                    }
                    else {
                        currency_html += '<option value="'+ currency[j].id +'">'+ currency[j].title +'</option>';
                    }
                }

                currency_option_html = currency_html;

                total_html += '<div class="row" id="product_pricing_item_'+ i +'">'+
                    '<div class="col-md-3">'+
                        '<fieldset class="form-group">'+
                            '<select id="pricing_tag_'+ i +'" class="form-control" onchange="check_valid('+ i +')">' + tag_html + '</select>'+
                        '</fieldset>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<div id="pricing_season_'+ i +'" style="margin-top: 7px;">season: '+ product_price_season[i] +'</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<fieldset class="form-group">'+
                            '<select id="pricing_currency_'+ i +'" class="form-control">' + currency_html + '</select>'+
                        '</fieldset>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<fieldset class="form-group">'+
                            '<input type="text" class="form-control" id="pricing_product_'+ i +'" value="'+ product_price_id[i] +'">'+
                        '</fieldset>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="d-flex align-item-center justify-content-between">'+
                            '<button type="button" class="btn btn-icon rounded-circle btn-outline-primary" data-category="'+ product_price_tag[i] +'" onclick="save_pricing_item('+ i +', '+ product_id +', \''+ pick_date +'\')" id="pricing_item_save_'+ i +'">'+
                                '<i class="bx bx-check"></i>'+
                            '</button>'+
                            '<button type="button" class="btn btn-icon rounded-circle btn-outline-danger" onclick="delete_pricing_item('+ i +')">'+
                                '<i class="bx bx-trash"></i>'+
                            '</button>'+
                        '</div>'+
                    '</div>'+
                '</div>';
            }

            $("#product_pricing_container").append(total_html);
            $("#set_all_product_price").hide();
            $("#product-pricing-modal").modal();
        }
    });
}

function preview_template_itinerary(group_id) {
    $.ajax({
        url: base_url + '/preview_template_itinerary',
        type: 'POST',
        data: {
            _token: $("[name='_token']").val(),
            group_id: group_id
        },
        dataType: 'json',
        success: function(response) {
            var template_itinerary = response;

            var html = '';
            for (temp in template_itinerary) {

                var itinerary_record = template_itinerary[temp]

                html += '<div class="each-day">'+
                '<div class="day-header">'+
                  '<div class="day-header-left">'+
                    '<div class="day-title-contain">'+
                      '<div>'+ temp +'th Day</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<div class="day-body">'+
                  '<div class="each-day-products">';

                var sub_html = '';
                for(var i=0; i<itinerary_record.length; i++) {
                    var template_itinerary_title =  itinerary_record[i].template_title;

                    sub_html += '<li class="product-list-each" style="list-style:none;">' +
                        '<div class="daily-products-class">' +
                        '<div class="daily-products-left">'+
                            '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ itinerary_record[i].product_path +'"/>'+
                            '<div class="daily-products-explain">'+
                            '<div class="daily-products-title">'+
                                itinerary_record[i].product_title +
                            '</div>'+
                            '<div class="daily-products-detail">'+
                                itinerary_record[i].city_title +', ' + itinerary_record[i].country_title+
                            '</div>'+
                            '<div class="daily-proudcts-option">'+
                                `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span>${itinerary_record[i].start_time}~${itinerary_record[i].end_time}</span>&nbsp;&nbsp;`+
                                `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span>${itinerary_record[i].adults_num}&nbsp;adults-${itinerary_record[i].children_num}&nbsp;children</span>`+
                            '</div>'+
                            '</div>'+
                        '</div>'+
                        '</div>'+
                    '</li>';
                }
                html += sub_html;
                html += '</div></div></div>';
            }

            $("#template_itinerary_title_caption").text(template_itinerary_title);
            $("#template_preview_container").html(html);

            $("#template_detail_modal").modal();
        }
    })
}

function delete_template_itinerary(group_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete!',
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-danger ml-1',
        buttonsStyling: false,
      }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: base_url + '/delete_template_itinerary',
                type: 'POST',
                data: {
                    _token: $("[name='_token']").val(),
                    group_id: group_id
                },
                success: function(response) {
                    if(response == 'success') {
                        var itinerary_id = $("#itinerary_id").val();
                        document.location.href = base_url + "/itinerary_add_info/" + itinerary_id + "/1";
                    }
                }
            })
        }
      })
}

function set_schedule_with_template(group_id, template_title, day_count) {
    console.log('Okay');
    if(day_count > days) {
        toastr.warning('This template is not fit with this itinerary. more days exist in this template.', 'Warnning', { "closeButton": true });
    }
    else {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: base_url + '/preview_template_itinerary',
                    type: 'POST',
                    data: {
                        _token: $("[name='_token']").val(),
                        group_id: group_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var template_itinerary_data = response;

                        $("#template_id").val(group_id);
                        $("#template_title").val(template_title);

                        k = new Array;
                        str = new Array;

                        for(index = 0; index < days; index++)
                        {
                            k[index] = 0;
                            str[index] = new Array();

                            $("#each-day-products-" + index).empty();
                            var inital_html = '<div class="drag-explain-contain">'+
                                '<sapn>Drag a product here to add it to the itinerary</span>'+
                            '</div>';

                            $("#each-day-products-" + index).append(inital_html);

                            $('#each-day-products-' + index).droppable({
                                accept: ".product-list-class",
                                activeClass: "ui-state-highlight",
                                drop: function( event, ui ) {

                                    update_flag = 0;

                                    var str_id = $(this).attr('id');
                                    var obj_id_arr = str_id.split('-');
                                    var obj_id = parseInt(obj_id_arr[obj_id_arr.length - 1]);
                                    global_obj_id = obj_id;
                                    str[obj_id][k[obj_id]] = "";
                                    var custom_product;

                                    for(i = 0; i < product.length; i ++){
                                        if(product[i].id == ui.helper[0].children[0].value)
                                            custom_product = product[i];
                                    }

                                    global_custom_product = custom_product;

                                    pick_date = $(this).parent().parent().find('.day-date').data('pick');
                                    pick_product_id = custom_product.id;

                                    $.ajax({
                                        url: base_url + '/get_product_pricing_tag',
                                        type: 'post',
                                        data: {
                                            _token: $("[name='_token']").val(),
                                            product: custom_product
                                        },
                                        dataType: 'json',
                                        success: function(response) {
                                            var product_tag = response;
                                            var tag_html = '<option value="0">Pricing Tag</option>';
                                            for(var i=0; i<product_tag.length; i++) {
                                                tag_html += '<option value="'+ product_tag[i].id +'">'+ product_tag[i].title +'</option>';
                                            }

                                            tag_option_html = tag_html;

                                            $("#product_pricing_container").empty();
                                            $("[id^='product_pricing_item_']").remove();
                                            $("#product-pricing-modal").modal();
                                        }
                                    });
                                }
                            });
                        }

                        if(template_itinerary_data.length != 0) {
                            var schedule_index = 0;
                            for(dd in template_itinerary_data ) {
                                str[schedule_index] = new Array;
                                var str_tmp = `<ul class='product-list' id='daily-schedule-id-${schedule_index}'>`;

                                for(var i=0; i<template_itinerary_data[dd].length; i++) {
                                    k[schedule_index] = i;

                                    var city = template_itinerary_data[dd][i].city_title ? template_itinerary_data[dd][i].city_title : '';
                                    var country = template_itinerary_data[dd][i].country_title ? template_itinerary_data[dd][i].country_title : '';

                                    var html_each = '<li class="product-list-each" id="daily-list-' + schedule_index + '-' + k[schedule_index] + '">' +
                                                        '<div class="daily-products-class">' +
                                                        '<input type="hidden" name="product_id" id="product_id" value="' + template_itinerary_data[dd][i].product_id +'">' +
// new
                                                        '<input type="hidden" name="itinerary_daily_id" id="itinerary_daily_id" value="">' +
                                                        '<input type="hidden" name="product_price_tag" id="product_price_tag" value="">' +
                                                        '<input type="hidden" name="product_price_season" id="product_price_season" value="">' +
                                                        '<input type="hidden" name="product_price_currency" id="product_price_currency" value="">' +
                                                        '<input type="hidden" name="product_price_id" id="product_price_id" value="">' +
// new
                                                        '<input type="hidden" name="product_margin_price" id="product_margin_price" value="0">' +
                                                        '<input type="hidden" name="product_margin_price" id="product_margin_price" value="1">' +

                                                        '<div class="daily-products-left" onClick="product_detail('+ template_itinerary_data[dd][i].product_id +')">'+
                                                            '<i class="bx bx-grid-vertical" style="font-size: 25px; margin: auto 0; cursor: move"></i>'+
                                                            '<img class="daily-products-img" src="'+ base_path_url +'storage/'+ template_itinerary_data[dd][i].product_path +'"/>'+
                                                            '<div class="daily-products-explain">'+
                                                                '<div class="daily-products-title">'+
                                                                    template_itinerary_data[dd][i].product_title + '<span class="daily-products-detail">('+ city +', ' +template_itinerary_data[dd][i].country +')</span>'+
                                                                '</div>'+
                                                                '<div class="daily-proudcts-option">'+
                                                                    `<i class="bx bx-info-circle" style="color: rgb(210, 77, 83);padding-top: 2px;"></i>`+ 
                                                                    `<span id="itinerary_price_real_${schedule_index}_${k[schedule_index]}">Price: 0(---) </span>`+
                                                                    `<span id="itinerary_price_margin_${schedule_index}_${k[schedule_index]}">Price Margin: 0(%)</span>`+
                                                                '</div>'+
                                                                '<div class="daily-proudcts-option">'+
                                                                    `<i class="bx bx-time" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span id="product_time_${schedule_index}_${k[schedule_index]}">${template_itinerary_data[dd][i].start_time}~${template_itinerary_data[dd][i].end_time}</span>&nbsp;&nbsp;`+
                                                                    `<i class="bx bx-group" style="color: rgb(210, 77, 83);padding-top: 2px;"></i> <span id="product_travellers_${schedule_index}_${k[schedule_index]}">${template_itinerary_data[dd][i].adults_num}&nbsp;adults-${template_itinerary_data[dd][i].children_num}&nbsp;children</span>`+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="daily-products-right">'+
                                                            '<span class="bullet bullet-sm bullet-danger mr-50"></span>'+
                                                            `<input type="checkbox" class="daily_checkbox" id="daily_checkbox_${schedule_index}_${k[schedule_index]}" data-daily-id="null" data-task-id="null" unchecked="true" enabled onclick="check_validation(this);">`+
                                                            '<div class="dropdown">'+
                                                            '<span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"'+
                                                                'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>'+
                                                            '<div class="dropdown-menu dropdown-menu-right">'+
                                                                `<a class="dropdown-item dropdown-edit-time" href="javascript:void(0)" onClick="edit_product_time(${schedule_index}, ${k[schedule_index]})"><i class="bx bx-edit-alt mr-1"></i> Edit Time</a>`+
                                                                `<a class="dropdown-item dropdown-edit-travellers" href="javascript:void(0)" onClick="edit_product_travellers(${schedule_index}, ${k[schedule_index]})"><i class="bx bx-edit-alt mr-1"></i> Travellers</a>`+
                                                                `<a class="dropdown-item dropdown-edit-price" href="javascript:void(0)" onClick="edit_product_price(${schedule_index}, ${k[schedule_index]}, 0, null, null, null '', '', '', '', 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Price</a>`+
                                                                `<a class="dropdown-item dropdown-edit-margin" href="javascript:void(0)" onClick="edit_margin_price(${schedule_index}, ${k[schedule_index]}, 0, 1)"><i class="bx bx-edit-alt mr-1"></i> Edit Margin</a>`+
                                                                `<a class="dropdown-item dropdown-del-product" href="javascript:void(0)" onClick="daily_product_del(${schedule_index}, ${k[schedule_index]}, null)"><i class="bx bx-trash mr-1"></i> delete</a>`+
                                                            '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '</div>'+
                                                    '</li>';
                                    str[schedule_index][k[schedule_index]] = html_each;
                                    k[schedule_index]++;
                                    str_tmp += html_each;
                                }

                                str_tmp += '</ul>';
                                $("#each-day-products-" + schedule_index).prepend(str_tmp);
                                var obj_id = schedule_index;

                                dragula([document.getElementById('daily-schedule-id-' + obj_id)])
                                .on('drop', function (e, el){
                                    var ul_string = el.innerHTML;
                                    var str_id = el.id;
                                    var str_temp_arr = str_id.split('-');
                                    var obj_id = parseInt(str_temp_arr[3]);

                                    var ul_string_arr = ul_string.split('</li>');
                                    var temp_string = "";
                                    for(i = 0; i < ul_string_arr.length - 1; i ++)
                                    {
                                        str[obj_id][i] = ul_string_arr[i] + '</li>';
                                        str[obj_id][i] = str[obj_id][i].replace(' gu-transit', "");

                                        var str_index = str[obj_id][i].indexOf(`daily-list-${obj_id}-`);
                                        var str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '"')
                                                break;
                                            str_index_temp ++;
                                        }
                                        var length = `daily-list-${obj_id}-`.length;
                                        var substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`daily-list-${obj_id}-${substr}`,`daily-list-${obj_id}-${i}`);

                                        /////////////////
                                        str_index = str[obj_id][i].indexOf(`itinerary_price_real_${obj_id}_`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '"')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `itinerary_price_real_${obj_id}_`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`itinerary_price_real_${obj_id}_${substr}`,`itinerary_price_real_${obj_id}_${i}`);
                                        ////////////

                                        /////////////////
                                        str_index = str[obj_id][i].indexOf(`itinerary_price_margin_${obj_id}_`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '"')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `itinerary_price_margin_${obj_id}_`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`itinerary_price_margin_${obj_id}_${substr}`,`itinerary_price_margin_${obj_id}_${i}`);
                                        ////////////

                                        str_index = str[obj_id][i].indexOf(`product_time_${obj_id}_`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '"')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `product_time_${obj_id}_`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`product_time_${obj_id}_${substr}`,`product_time_${obj_id}_${i}`);

                                        str_index = str[obj_id][i].indexOf(`product_travellers_${obj_id}_`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '"')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `product_travellers_${obj_id}_`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`product_travellers_${obj_id}_${substr}`,`product_travellers_${obj_id}_${i}`);
                                        ///////////////

                                        str_index = str[obj_id][i].indexOf(`daily_checkbox_${obj_id}_`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == '>')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `daily_checkbox_${obj_id}_`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        var substr_arr = substr.split(" ");
                                        str[obj_id][i] = str[obj_id][i].replace(`daily_checkbox_${obj_id}_${substr}`, `daily_checkbox_${obj_id}_${i}" ${substr_arr[1]} ${substr_arr[2]} ${substr_arr[3]} ${substr_arr[4]} ${substr_arr[5]}`);
                                        /////////////
                                        
                                        str_index = str[obj_id][i].indexOf(`edit_product_time(${obj_id}, `);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == ')')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `edit_product_time(${obj_id}, `.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`edit_product_time(${obj_id}, ${substr})`,`edit_product_time(${obj_id}, ${i})`);

                                        str_index = str[obj_id][i].indexOf(`edit_product_travellers(${obj_id}, `);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == ')')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `edit_product_travellers(${obj_id}, `.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        str[obj_id][i] = str[obj_id][i].replace(`edit_product_travellers(${obj_id}, ${substr})`,`edit_product_travellers(${obj_id}, ${i})`);

                                        str_index = str[obj_id][i].indexOf(`edit_product_price(${obj_id}, `);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == ')')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `edit_product_price(${obj_id}, `.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        var substr_arr = substr.split(", ");
                                        str[obj_id][i] = str[obj_id][i].replace(`edit_product_price(${obj_id}, ${substr})`, `edit_product_price(${obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]}, ${substr_arr[3]}, ${substr_arr[4]}, ${substr_arr[5]}, ${substr_arr[6]}, ${substr_arr[7]}, ${substr_arr[8]}, ${substr_arr[9]}, ${substr_arr[10]})`);

                                        str_index = str[obj_id][i].indexOf(`edit_margin_price(${obj_id}, `);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == ')')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `edit_margin_price(${obj_id}, `.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        var substr_arr = substr.split(", ");
                                        str[obj_id][i] = str[obj_id][i].replace(`edit_margin_price(${obj_id}, ${substr})`, `edit_margin_price(${obj_id}, ${i}, ${substr_arr[1]}, ${substr_arr[2]})`);
                                        
                                        str_index = str[obj_id][i].indexOf(`daily_product_del(${obj_id},`);
                                        str_index_temp = str_index;
                                        while(true)
                                        {
                                            if(str[obj_id][i][str_index_temp] == ')')
                                                break;
                                            str_index_temp ++;
                                        }
                                        length = `daily_product_del(${obj_id},`.length;
                                        substr = str[obj_id][i].substring(str_index + length, str_index_temp);
                                        var substr_arr = substr.split(", ");
                                        str[obj_id][i] = str[obj_id][i].replace(`daily_product_del(${obj_id},${substr})`,`daily_product_del(${obj_id}, ${i}, ${substr_arr[1]})`);

                                        temp_string += str[obj_id][i];
                                    }
                                    $(`#daily-schedule-id-${obj_id}`).empty();
                                    $(`#daily-schedule-id-${obj_id}`).append(temp_string);
                                });
                                schedule_index ++;
                            }
                        }
                    }
                })
            }
        })
    }
}

function check_validation(obj) {
    
    if(obj.hasAttribute("unchecked")) {
        var daily_id = $(obj).data('daily-id');
        
        if(daily_id == null || daily_id == 0) {
            toastr.warning('Not saved itinerary could not setted as task!', 'warning', {'closeButton': true, timeOut: 2000});
            $(obj).prop('checked', false);
            return;
        }
        
        obj.removeAttribute('unchecked');
        obj.setAttribute('checked', true);
    }
    else {
        obj.removeAttribute('checked');
        obj.setAttribute('unchecked', true);
    }
}