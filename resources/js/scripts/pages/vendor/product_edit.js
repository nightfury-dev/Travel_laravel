Dropzone.options.dpzRemoveAllThumb = {
	maxFilesize: 10,
	acceptedFiles: "image/png,image/jpeg",
	paramName: "file", // The name that will be used to transfer the file
	dataType: 'json',
	uploadMultiple: true,
	parallelUploads: 100,
	addRemoveLinks: true,
	dictRemoveFile: "Trash",
	init: function () {
		for (var i = 0; i < image_gallery.length; i++) {
			var mockFile = {
				name: image_gallery[i].path.split('/')[1],
				size: 100,
				type: 'image/*',
				serverID: i,
				accepted: true
			}; // use actual id server uses to identify the file (e.g. DB unique identifier)

			this.emit("addedfile", mockFile);

			this.emit("thumbnail", mockFile, base_path_url + 'storage/' + image_gallery[i].path);
			this.emit("success", mockFile);
			this.emit("complete", mockFile);
			this.files.push(mockFile);
		}

		this.on('addedfile', function (file) {
			// var gallery_product_id = $("#gallery_product_id").val();
			// if(gallery_product_id == '') {
			//   toastr.warning('Please Fill General Form', 'Warning', { "closeButton": true });
			//   this.removeFile(file);
			// }
		});

		this.on('success', function (file, data) {
			if (data.success == true) {
				toastr.success(data.messages, 'Success', { "closeButton": true });
				$("#general_product_id").val(data.product_id);
				$("#description_product_id").val(data.product_id);
				$("#gallery_product_id").val(data.product_id);
				$("#price_product_id").val(data.product_id);
			}
			else {
				toastr.error(data.messages, 'Error', { "closeButton": true });
			}
		});

		this.on("removedfile", function (file) {
			var file_name = file.name
			$.ajax({
				url: base_url + '/product/gallery/delete',
				type: 'POST',
				data: {
					_token: $("[name='_token']").val(),
					file_name: file_name
				},
				success: function (message) {
					if (message == 'success') {
						toastr.success('Delete Success', 'Success', { "closeButton": true });
					}
					else {
						toastr.error('Delete Failure', 'Error', { "closeButton": true });
					}
				}
			});
		});
	}
}

var description = [];

function delete_description(lang) {
	confirm('Are you really Ok?');
	$("#description_wrapper_" + lang).slideUp(500, function () {
		$("#description_wrapper_" + lang).remove();
	});
	description[lang] = null;
}

var pricing_data = [];

function deleteset(index) {
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
			var from_date = pricing_data[index - 1].from_date;
			var to_date = pricing_data[index - 1].to_date;

			if (pricing_data[index] != undefined) {
				var ii = index;
				while (pricing_data[ii] == null) {
					ii++;

					if (pricing_data[ii] == undefined) {
						break;
					}
				}

				if (pricing_data[ii] != undefined) {
					pricing_data[ii].from_date = from_date;
					$("#pricingset_" + (ii + 1) + " #fromdate_" + (ii + 1)).val(from_date);
				}
			}

			pricing_data[index - 1] = null;
			$("#pricingset_" + index).slideUp(500, function () {
				$("#pricingset_" + index).remove();
			});
		}
	})

}

function addpricetype(index) {
	var real_pricing = $("[id ^= 'pricinglist_" + index + "_']");

	if (real_pricing.length == 0) {
		var idx = 1;
	}
	else {
		var last_obj = real_pricing[real_pricing.length - 1];
		var last_obj_id = $(last_obj).attr('id');

		var idx = last_obj_id.split('_')[2];
		idx = parseInt(idx) + 1;
	}

	var tag_html = '';
	for (var i = 0; i < tag.length; i++) {
		tag_html += '<option value="' + tag[i].id + '">' + tag[i].title + '</option>';
	}

	var html = '<div class="row" id="pricinglist_' + index + '_' + idx + '">' +
		'<input type="hidden" id="priceID_' + index + '_' + idx + '" name="priceID[' + (index - 1) + '][]" value="" />' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<select class="select2 form-control" id="tag_' + index + '_' + idx + '" name="tag[' + (index - 1) + '][]">' +
		tag_html +
		'</select>' +
		'<div class="form-control-position">' +
		'<i class="bx bx-search"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control" id="description_' + index + '_' + idx + '" name="description[' + (index - 1) + '][]" value="" placeholder="Description">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-info-circle"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="number" id="price_' + index + '_' + idx + '" name="price[' + (index - 1) + '][]" class="form-control" value="" data-bts-step="0.5" data-bts-decimals="2" data-bts-prefix="$" placeholder="Price" min="0">' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3 form-group">' +
		'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteprice_' + index + '_' + idx + '" onclick="deleteprice(' + index + ', ' + idx + ')">' +
		'<i class="bx bx-trash"></i>' +
		'</button>' +
		'</div>' +
		'</div>';

	$("#pricelist_" + index).append(html);

	$(".select2").select2({
		dropdownAutoWidth: true,
		width: '100%'
	});

	// $(".touchspin-icon").TouchSpin({
	//   buttondown_txt: '<i class="bx bx-minus"></i>',
	//   buttonup_txt: '<i class="bx bx-plus"></i>',
	//   min: 0
	// });
}

function deleteprice(index, idx) {
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
			pricing_data[index - 1].blackout_date[idx - 1] = null;
			$("#pricinglist_" + index + "_" + idx).slideUp(500, function () {
				$("#pricinglist_" + index + "_" + idx).remove();
			});
		}
	})
}

function addblackoutdate(index) {
	if (pricing_data[index - 1].from_date == '' || pricing_data[index - 1].to_date == '') {
		toastr.warning('Please enter from and to date firstly', 'Warning', { "closeButton": true });
		return;
	}

	var real_blackrecord = $("[id ^= 'blackoutdaterecord_" + index + "_']");

	if (real_blackrecord.length == 0) {
		var idx = 1;
	}
	else {
		var last_obj = real_blackrecord[real_blackrecord.length - 1];
		var last_obj_id = $(last_obj).attr('id');

		var idx = last_obj_id.split('_')[2];
		idx = parseInt(idx) + 1;

	}

	var html = '<div class="row" id="blackoutdaterecord_' + index + '_' + idx + '">' +
		'<div class="col-md-3 form-group">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control daterange" placeholder="Select BlackOut Date" id="blackoutdate_' + index + '_' + idx + '" name="blackoutdate[' + (index - 1) + '][]" value="">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-calendar-check"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-6">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control" id="blackoutmsg_' + index + '_' + idx + '" name="blackoutmsg[' + (index - 1) + '][]" value="Unavailable date!">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-info-circle"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3 form-group">' +
		'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteblackoutdate_' + index + '_' + idx + '" onclick="deleteblackoutdate(' + index + ', ' + idx + ')">' +
		'<i class="bx bx-trash"></i>' +
		'</button>' +
		'</div>' +
		'</div>';

	$("#blackoutdatelist_" + index).append(html);
	$('.daterange').daterangepicker({
		autoUpdateInput: false,
		locale: {
			cancelLabel: 'Clear'
		}
	});

	$('.daterange').on('apply.daterangepicker', function (ev, picker) {
		var obj_id = $(this).attr('id');
		obj_id = obj_id.split('_');
		var index = obj_id[1];
		var idx = obj_id[2];

		var black_from_date = picker.startDate.format('YYYY/MM/DD');
		var black_to_date = picker.endDate.format('YYYY/MM/DD');

		var black_from_date_time = new Date(black_from_date).getTime();
		var black_to_date_time = new Date(black_to_date).getTime();

		var from_date = new Date(pricing_data[index - 1].from_date).getTime();
		var to_date = new Date(pricing_data[index - 1].to_date).getTime();

		console.log(black_from_date_time);
		console.log(black_to_date_time);

		console.log(from_date);
		console.log(to_date);

		if (from_date > black_from_date_time || to_date < black_to_date_time) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			console.log('here');
			$(this).val('');
			return;
		}

		var flag = 0;

		var blackout_date_iteration;

		console.log(pricing_data);

		for (blackout_date_iteration of pricing_data[index - 1].blackout_date) {

			var flag_start_date = blackout_date_iteration.split(' - ')[0];
			var flag_end_date = blackout_date_iteration.split(' - ')[1];

			if (flag_end_date == undefined) {
				for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
					if (e.getTime() == new Date(flag_start_date).getTime()) {
						flag = 1;
						break;
					}
				}
			}
			else {
				for (var d = new Date(flag_start_date); d <= new Date(flag_end_date); d.setDate(d.getDate() + 1)) {
					for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
						if (d.getTime() == e.getTime()) {
							flag = 1;
							break;
						}
					}
				}
			}
		}

		if (flag == 1) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			$(this).val('');
			return;
		}

		if (picker.startDate.format('YYYY/MM/DD') == picker.endDate.format('YYYY/MM/DD')) {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD'));
		}
		else {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
		}
	});

	$('.daterange').on('cancel.daterangepicker', function (ev, picker) {
		$(this).val('');
	});
}

function deleteblackoutdate(index, idx) {
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
			pricing_data[index - 1].blackout_date[idx - 1] = null;

			$("#blackoutdaterecord_" + index + "_" + idx).slideUp(500, function () {
				$("#blackoutdaterecord_" + index + "_" + idx).remove();
			});
		}
	})


}

function copypriceset(copy_index) {

	var target_obj = $("#pricingset_" + copy_index);

	var currency_obj = target_obj.find("#currency_" + copy_index);
	var currency_val = $(currency_obj).val();

	var obj = $("[id ^= 'pricingset_']");

	if (obj.length == 0) {
		index = 1;
	}
	else {
		var first_obj = obj[0];
		var first_obj_id = $(first_obj).attr('id');

		var index = first_obj_id.split('_')[1];
		index = parseInt(index) + 1;

		if (pricing_data[index - 2].from_date == '' || pricing_data[index - 2].to_date == '') {
			toastr.warning('Please fill pervious from and to date!', 'Warning', { "closeButton": true });
			return;
		}
	}

	var ii = index - 2;
	while (pricing_data[ii] == null) {
		if (ii == 0) {
			break;
		}

		ii--;
	}

	var new_day = '';
	if (ii == 0 && pricing_data[ii] == null) {
		pricing_data[index - 1] = {
			from_date: '',
			to_date: '',
			blackout_date: new Array()
		};
	}
	else {
		var aa = new Date(pricing_data[ii].to_date);

		aa.setDate(aa.getDate() + 1);

		var new_year = aa.getFullYear();
		var new_month = '';
		if (aa.getMonth() + 1 < 10) {
			new_month = '0' + (aa.getMonth() + 1);
		}
		else {
			new_month = '' + (aa.getMonth() + 1) + '';
		}
		var new_date = ''
		if (aa.getDate() < 10) {
			new_date = '0' + aa.getDate();
		}
		else {
			new_date = '' + aa.getDate() + '';
		}

		new_day = new_year + '/' + new_month + '/' + new_date;

		pricing_data[index - 1] = {
			from_date: new_day,
			to_date: '',
			blackout_date: new Array()
		};
	}



	var pricing_list = target_obj.find("[id^='pricinglist_" + copy_index + "_']");

	var currency_html = '';
	for (var j = 0; j < currency.length; j++) {
		if (currency_val == currency[j].id) {
			currency_html += '<option value="' + currency[j].id + '" selected>' + currency[j].title + '</option>';
		}
		else {
			currency_html += '<option value="' + currency[j].id + '">' + currency[j].title + '</option>';
		}
	}

	var sub_html = '';
	for (var i = 0; i < pricing_list.length; i++) {
		var tag_obj = $(pricing_list[i]).find('#tag_' + copy_index + '_' + (i + 1));
		var tag_value = $(tag_obj).val();

		var tag_html = '';
		for (var k = 0; k < tag.length; k++) {
			if (tag_value == tag[k].id) {
				tag_html += '<option value="' + tag[k].id + '" selected>' + tag[k].title + '</option>';
			}
			else {
				tag_html += '<option value="' + tag[k].id + '">' + tag[k].title + '</option>';
			}
		}

		var price_obj = $(pricing_list[i]).find('#price_' + copy_index + '_' + (i + 1));
		var price_val = $(price_obj).val();

		sub_html += '<div class="row" id="pricinglist_' + index + '_' + (i + 1) + '">' +
			'<input type="hidden" id="priceID_' + index + '_' + (i + 1) + '" name="priceID[' + (index - 1) + '][]" value="" />' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<select class="select2 form-control" id="tag_' + index + '_' + (i + 1) + '" name="tag[' + (index - 1) + '][]">' +
			tag_html +
			'</select>' +
			'<div class="form-control-position">' +
			'<i class="bx bx-search"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control" id="description_' + index + '_' + (i + 1) + '" name="description[' + (index - 1) + '][]" value="" placeholder="Description">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-info-circle"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="number" id="price_' + index + '_' + (i + 1) + '" name="price[' + (index - 1) + '][]" class="form-control" value="' + price_val + '" data-bts-step="0.5" data-bts-decimals="2" data-bts-prefix="$" placeholder="Price" min="0">' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3 form-group">' +
			'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteprice_' + index + '_' + (i + 1) + '" onclick="deleteprice(' + index + ', ' + (i + 1) + ')">' +
			'<i class="bx bx-trash"></i>' +
			'</button>' +
			'</div>' +
			'</div>';
	}

	var title = 'Category Tag';

	var html = '<div class="row mb-1" id="pricingset_' + index + '" style="border: 1px solid #ccc; padding-top: 20px;">' +
		'<div class="col-md-12"><h6>Season</h6></div>' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control pickadate" placeholder="Select From Date" id="fromdate_' + index + '" name="fromdate[]" value="' + new_day + '" onchange="fromdate_trigger(this, ' + index + ')">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-calendar-check"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control pickadate" placeholder="Select To Date" id="todate_' + index + '" name="todate[]" value="" onchange="todate_trigger(this, ' + index + ')">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-calendar-check"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<select class="select2 form-control" id="currency_' + index + '" name="currency[]">' +
		'<option value="">Currency</option>' +
		currency_html +
		'</select>' +
		'<div class="form-control-position">' +
		'<i class="bx bx-search"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3 form-group d-flex align-items-center justify-content-start">' +
		'<button type="button" id="copyset_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="copypriceset(' + index + ')">' +
		'<i class="bx bx-copy"></i>' +
		'<span class="align-middle ml-25">Copy</span>' +
		'</button>' +
		'<button type="button" id="deleteset_' + index + '" class="btn btn-outline-danger mr-1 mb-1" onclick="deleteset(' + index + ')">' +
		'<i class="bx bx-trash"></i>' +
		'<span class="align-middle ml-25">Delete</span>' +
		'</button>' +
		'</div>' +
		'<div class="col-md-12" id="blackoutdatelist_' + index + '">' +
		'<h6>Blackout Date</h6>' +
		'<div class="row" id="blackoutdaterecord_' + index + '_1">' +
		'<div class="col-md-3 form-group">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control daterange" placeholder="Select BlackOut Date" id="blackoutdate_' + index + '_1" name="blackoutdate[' + (index - 1) + '][]" value="">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-calendar-check"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-6">' +
		'<fieldset class="form-group position-relative has-icon-left">' +
		'<input type="text" class="form-control" id="blackoutmsg_' + index + '_1" name="blackoutmsg[' + (index - 1) + '][]" value="Unavailable date!">' +
		'<div class="form-control-position">' +
		'<i class="bx bx-info-circle"></i>' +
		'</div>' +
		'</fieldset>' +
		'</div>' +
		'<div class="col-md-3 form-group">' +
		'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteblackoutdate_' + index + '_1" onclick="deleteblackoutdate(' + index + ', 1)">' +
		'<i class="bx bx-trash"></i>' +
		'</button>' +
		'</div>' +
		'</div>' +
		'</div>' +
		'<div class="col-md-6 form-group">' +
		'<button type="button" id="addblackoutdate_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="addblackoutdate(' + index + ')">' +
		'<i class="bx bx-plus"></i>' +
		'<span class="align-middle ml-25">Add</span>' +
		'</button>' +
		'</div>' +
		'<div class="col-md-12" id="pricelist_' + index + '">' +
		'<h6>' + title + '</h6>' +
		sub_html +
		'</div>' +
		'<div class="col-md-6 form-group">' +
		'<button type="button" id="addtype_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="addpricetype(' + index + ')">' +
		'<i class="bx bx-plus"></i>' +
		'<span class="align-middle ml-25">Add</span>' +
		'</button>' +
		'</div>' +
		'</div>';

	$("#priceset_list").prepend(html);

	$(".select2").select2({
		dropdownAutoWidth: true,
		width: '100%'
	});

	// $(".touchspin-icon").TouchSpin({
	//   buttondown_txt: '<i class="bx bx-minus"></i>',
	//   buttonup_txt: '<i class="bx bx-plus"></i>',
	//   min: 0
	// });

	$('.daterange').daterangepicker({
		"autoUpdateInput": false,
		"startDate": "02/15/2021",
		"endDate": "02/21/2021",
		locale: {
			cancelLabel: 'Clear'
		}
	});

	$('.daterange').on('apply.daterangepicker', function (ev, picker) {
		var obj_id = $(this).attr('id');
		obj_id = obj_id.split('_');
		var index = obj_id[1];
		var idx = obj_id[2];

		var black_from_date = picker.startDate.format('YYYY/MM/DD');
		var black_to_date = picker.endDate.format('YYYY/MM/DD');

		var black_from_date_time = new Date(black_from_date).getTime();
		var black_to_date_time = new Date(black_to_date).getTime();

		var from_date = new Date(pricing_data[index - 1].from_date).getTime();
		var to_date = new Date(pricing_data[index - 1].to_date).getTime();

		console.log(black_from_date_time);
		console.log(black_to_date_time);

		console.log(from_date);
		console.log(to_date);

		if (from_date > black_from_date_time || to_date < black_to_date_time) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			$(this).val('');
			return;
		}

		var flag = 0;

		var blackout_date_iteration

		for (blackout_date_iteration of pricing_data[index - 1].blackout_date) {

			var flag_start_date = blackout_date_iteration.split(' - ')[0];
			var flag_end_date = blackout_date_iteration.split(' - ')[1];

			if (flag_end_date == undefined) {
				for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
					if (e.getTime() == new Date(flag_start_date).getTime()) {
						flag = 1;
						break;
					}
				}
			}
			else {
				for (var d = new Date(flag_start_date); d <= new Date(flag_end_date); d.setDate(d.getDate() + 1)) {
					for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
						if (d.getTime() == e.getTime()) {
							flag = 1;
							break;
						}
					}
				}
			}
		}

		if (flag == 1) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			$(this).val('');
			return;
		}

		if (picker.startDate.format('YYYY/MM/DD') == picker.endDate.format('YYYY/MM/DD')) {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD'));
		}
		else {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
		}
	});

	$('.daterange').on('cancel.daterangepicker', function (ev, picker) {
		$(this).val('');
	});

	$('.pickadate').pickadate({
		format: 'yyyy/mm/dd',
		min: new Date(),
		selectYears: true,
		selectMonths: true
	});
}

function fromdate_trigger(obj, index) {

	var ii = index - 2;
	while (pricing_data[ii] == null) {
		if (ii <= 0) {
			break;
		}

		ii--;
	}

	var new_day = '';

	if (ii <= 0 && pricing_data[ii] == null) {
		pricing_data[index - 1].from_date = $(obj).val();
	}
	else {
		var new_to_date = new Date($(obj).val());

		if (new_to_date.getTime() < new Date(pricing_data[ii].from_date).getTime()) {
			toastr.warning('already exist season', 'Warning', { "closeButton": true });
			$(obj).val(pricing_data[index - 1].from_date);
			return;
		}

		new_to_date.setDate(new_to_date.getDate() - 1);

		var new_to_year = new_to_date.getFullYear();
		var new_to_month = new_to_date.getMonth() + 1;
		if (new_to_month < 10) {
			new_to_month = '0' + new_to_month;
		}
		var new_to_day = new_to_date.getDate();
		if (new_to_day < 10) {
			new_to_day = '0' + new_to_day;
		}

		pricing_data[ii].to_date = new_to_year + '/' + new_to_month + '/' + new_to_day;
		$("#pricingset_" + (ii + 1) + " #todate_" + (ii + 1)).val(new_to_year + '/' + new_to_month + '/' + new_to_day);

		pricing_data[index - 1].from_date = $(obj).val();
	}
}

function todate_trigger(obj, index) {

	if (pricing_data[index - 1].from_date == "") {
		toastr.warning('Please Select From date firstly', 'Warning', { "closeButton": true });
		$(obj).val(pricing_data[index - 1].to_date);
		return;
	}

	var from_date = new Date(pricing_data[index - 1].from_date);
	var to_date = new Date($(obj).val());

	if (from_date.getTime() > to_date.getTime()) {
		toastr.warning('From date can not bigger more than To date', 'Warning', { "closeButton": true });
		$(obj).val(pricing_data[index - 1].to_date);
		return;
	}

	if (pricing_data[index] != undefined) {
		var ii = index;
		while (pricing_data[ii] == null) {
			ii++;

			if (pricing_data[ii] == undefined) {
				break;
			}
		}

		if (pricing_data[ii] != undefined) {
			var new_from_date = new Date($(obj).val());

			if (new_from_date.getTime() > new Date(pricing_data[ii].to_date).getTime()) {
				toastr.warning('already exist season', 'Warning', { "closeButton": true });
				$(obj).val(pricing_data[index - 1].to_date);
				return;
			}

			new_from_date.setDate(new_from_date.getDate() + 1);

			var new_from_year = new_from_date.getFullYear();
			var new_from_month = new_from_date.getMonth() + 1;
			if (new_from_month < 10) {
				new_from_month = '0' + new_from_month;
			}
			var new_from_day = new_from_date.getDate();
			if (new_from_day < 10) {
				new_from_day = '0' + new_from_day;
			}

			pricing_data[ii].from_date = new_from_year + '/' + new_from_month + '/' + new_from_day;
			$("#pricingset_" + (ii + 1) + " #fromdate_" + (ii + 1)).val(new_from_year + '/' + new_from_month + '/' + new_from_day);
		}
	}

	pricing_data[index - 1].to_date = $(obj).val();
}


let map;
let marker;

const componentForm = {
	street_number: "short_name",
	route: "long_name",
	locality: "long_name",
	administrative_area_level_1: "short_name",
	country: "long_name",
	postal_code: "short_name",
};

function initAutocomplete() {
	autocomplete = new google.maps.places.Autocomplete(
		document.getElementById("autocomplete"),
		{ types: ["geocode"] }
	);
	autocomplete.setFields(["address_component", "geometry"]);
	autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
	const place = autocomplete.getPlace();

	var location = JSON.parse(JSON.stringify(place.geometry.location));

	var position = location.lat + ", " + location.lng;
	$("#position").val(position);

	deleteMarkers();
	map.panTo(location);
	addMarker(location)

	for (const component in componentForm) {
		document.getElementById(component).value = "";
		// document.getElementById(component).disabled = false;
	}

	for (const component of place.address_components) {
		const addressType = component.types[0];

		if (componentForm[addressType]) {
			const val = component[componentForm[addressType]];
			document.getElementById(addressType).value = val;
		}
	}
}

function addMarker(location) {
	marker = new google.maps.Marker({
		position: location,
		map: map,
	});
}

function deleteMarkers() {
	marker.setMap(null);
}

function show_error() {
	var error_obj = $(".error-message");
	for (var i = 0; i < error_obj.length; i++) {
		var error = $(error_obj).val();
		toastr.error(error, 'Error', { "closeButton": true });
	}
}

function show_alert() {
	var alert = $("#alert").val();
	if (alert != '') {
		toastr.success(alert, 'Success', { "closeButton": true });
	}
}

jQuery(document).ready(function () {
	// variables
	var appContent = $(".app-content"),
		appContentOverlay = $(".app-file-overlay"),
		sideBarLeft = $(".sidebar-left"),
		app_file_application = $(".file-manager-application"),
		sideBarInfo = $(".app-file-sidebar-info"),
		app_file_content = $(".app-file-content"),
		app_file_sidebar_left = $(".app-file-sidebar-left"),
		$primary = "#5A8DEE",
		$transparent = "transparent",
		$danger = '#FF5B5C',
		$warning = '#FDAC41',
		$primary_light = '#6999f3',
		$warning_light = '#FFEED9',
		$dark_primary = '#2c6de9',
		$hover_warning = '#fed8a6',
		$white = '#fff';

	// To add Perfect Scrollbar
	// ---------------------------

	// App-File Content Area
	if (app_file_content.length > 0) {
		var users_list = new PerfectScrollbar(".app-file-content");
	}

	// App File Left Sidebar
	if (app_file_sidebar_left.length > 0) {
		var app_file_sidebar_content = new PerfectScrollbar(".app-file-sidebar-content");
	}

	// Edit File Sidebar - Right Side
	if (sideBarInfo.length > 0) {
		var edit_file_sidebar = new PerfectScrollbar(".app-file-sidebar-info");
	}


	// Sidebar visiblility and content area overlay
	// ----------------------------------------------
	$('.menu-toggle, .close-icon, .app-file-overlay').on('click', function (e) {
		sideBarLeft.removeClass('show');
		appContentOverlay.removeClass('show');
		sideBarInfo.removeClass('show');
	});

	// On click of "app-file-info" class from file-content-area, visible edit sidebar and hide left sidebar
	$('.app-file-info').on('click', function () {
		sideBarInfo.addClass('show');
		appContentOverlay.addClass('show');
	});

	// Sidebar menu close button on click remove show class form both sidebar-left and App content overlay
	$(".app-file-sidebar-close").on('click', function () {
		sideBarLeft.removeClass('show');
		appContentOverlay.removeClass('show');
	});

	// on click of Sidebar-toggle, add class show to content overlay and toggle class to app sidebar left
	$('.sidebar-toggle').on('click', function (e) {
		e.stopPropagation();
		sideBarLeft.addClass('show');
		appContentOverlay.addClass('show');
	});

	// Add class active on click of sidebar list folder and label
	var $active_label = $(".app-file-sidebar-content .list-group-messages a,.list-group-labels a");
	$($active_label).on('click', function () {
		var $this = $(this);
		$active_label.removeClass('active');
		$this.addClass("active");
		// livicon change color on active state
		$this.find(".livicon-evo").updateLiviconEvo({
			strokeColor: $primary
		});
		$active_label.not(".active").find(".livicon-evo").updateLiviconEvo({
			strokeColor: '#475f7b'
		});
	});

	// On screen Resize JS
	// -----------------------------------
	$(window).on("resize", function () {
		// remove show classes from sidebar and overlay if size is > 768
		if ($(window).width() > 768) {
			if (appContentOverlay.hasClass('show')) {
				sideBarLeft.removeClass('show');
				appContentOverlay.removeClass('show');
				sideBarInfo.removeClass('show');
			}
		}
	});

	show_alert();
	show_error();

	// My Code

	const myLatlng = { lat: 51.50231401187977, lng: -0.08439206478083161 };

	map = new google.maps.Map(document.getElementById("basic-map"), {
		zoom: 15,
		center: myLatlng,
	});

	var position = product.position;
	console.log(position);
	location_latlng = position.split(', ');
	var mark_latlng = {
		lat: parseFloat(location_latlng[0]),
		lng: parseFloat(location_latlng[1])
	};

	map.panTo(mark_latlng);
	addMarker(mark_latlng);

	$(".select2").select2({
		dropdownAutoWidth: true,
		width: '100%'
	});

	$('.pickatime-format').pickatime({
		// Escape any “rule” characters with an exclamation mark (!).
		format: 'h:i a',
		formatLabel: 'HH:i a',
		formatSubmit: 'HH:i'
	});

	$("#general-form").on('submit', function (e) {
		e.preventDefault();

		var form = $(this);
		var url = form.attr('action');

		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(), // serializes the form's elements.
			dataType: 'json',
			success: function (data) {
				if(data.success == true) {
					toastr.success(data.messages, 'Success', { "closeButton": true });
					$("#general_product_id").val(data.product_id);
					$("#description_product_id").val(data.product_id);
					$("#gallery_product_id").val(data.product_id);
					$("#price_product_id").val(data.product_id);
				}
				else {
					if(Array.isArray(data.messages)) {
						for(var i=0; i<data.messages.length; i++) {
							toastr.warning(data.messages[i], 'Warning', { "closeButton": true });
						}
					}
					else {
						toastr.error(data.messages, 'Error', { "closeButton": true });
					}
				}
			}
		});
	})

	if (description_list.length != 0) {
		for (var i = 0; i < description_list.length; i++) {
			description[description_list[i].language] = description_list[i].language;
			CKEDITOR.replace('description_' + description_list[i].language);
		}
	}
	else {
		description['gb'] = 'gb';
		CKEDITOR.replace('description_gb');
	}

	$("#description_add").on('click', function () {
		var lang = $("#alllanguage_list").val();
		if (lang == '') {
			toastr.warning('Please Select the language', 'Warning', { "closeButton": true });
			return;
		}

		if (description[lang] != null) {
			toastr.warning('Same Language exist', 'Warning', { "closeButton": true });
			return;
		}

		var html = '<div class="row" id="description_wrapper_' + lang + '">' +
			'<div class="col-md-12 d-flex align-items-center justify-content-between">' +
			'<h6 class="d-flex align-items-center">' +
			'<span>Description</span>' +
			'<div class="avatar mr-1 avatar-lg">' +
			'<img src="' + base_path_url + 'images/flags/' + lang + '.png" alt="avtar img holder" class="flag">' +
			'</div>' +
			'</h6>' +
			'<button id="delete_description_' + lang + '" class="btn btn-danger text-nowrap px-1" type="button" onclick="delete_description(\'' + lang + '\')">' +
			'<i class="bx bx-x"></i>' +
			'Delete' +
			'</button>' +
			'</div>' +
			'<div class="col-md-12">' +
			'<input type="hidden" id="descriptionID_' + lang + '" name="group[\'' + lang + '\'][\'descriptionID\']" value="" />' +
			'<input type="hidden" id="language_' + lang + '" name="group[\'' + lang + '\'][\'language\']" class="description_language" value="' + lang + '" />' +
			'<div class="form-group">' +
			'<textarea id="description_' + lang + '" name="group[\'' + lang + '\'][\'description\']"  cols="30" rows="10" class="description_wrapper form-control"></textarea>' +
			'</div>' +
			'</div>';

		$("#description_list").append(html);
		CKEDITOR.replace("description_" + lang);

		description[lang] = lang;
	});

	$("#description_form").on('submit', function (e) {
		e.preventDefault();
		// var description_product_id = $("#description_product_id").val();
		// if (description_product_id == '') {
		// 	toastr.warning('Please Fill General Form', 'Warning', { "closeButton": true });
		// 	return;
		// }

		var form = $(this);
		var url = form.attr('action');

		var lang_obj = $("[id^='language_']");
		console.log(lang_obj);
		for(var i=0; i<lang_obj.length; i++) {
			var obj = lang_obj[i];
			var lang = $(obj).val();
			
			CKEDITOR.instances['description_' + lang].updateElement();
		}

		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(), // serializes the form's elements.
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.success == true) {
					toastr.success(data.messages, 'Success', { "closeButton": true });
					$("#general_product_id").val(data.product_id);
					$("#description_product_id").val(data.product_id);
					$("#gallery_product_id").val(data.product_id);
					$("#price_product_id").val(data.product_id);
				}
				else {
					if(Array.isArray(data.messages)) {
						for(var i=0; i<data.messages.length; i++) {
							toastr.warning(data.messages[i], 'Warning', { "closeButton": true });
						}
					}
					else {
						toastr.error(data.messages, 'Error', { "closeButton": true });
					}
				}
			}
		});
	});

	// $(".touchspin-icon").TouchSpin({
	// 	buttondown_txt: '<i class="bx bx-minus"></i>',
	// 	buttonup_txt: '<i class="bx bx-plus"></i>',
	// 	min: 0
	// });

	$(".form-group.error input").on('focus', function () {
		$(this).parent().removeClass('error');
		$(this).parent().find('.help-block').remove();
	});

	$(".form-group.error .select2-container").on('click', function () {
		$(this).parent().removeClass('error');
		$(this).parent().find('.help-block').remove();
	});

	$("#country").on('change', function () {
		country = $(this).val();

		$.ajax({
			url: base_url + '/product/city',
			type: 'POST',
			data: {
				_token: $("[name='_token']").val(),
				country: country
			},
			dataType: 'json',
			success: function (response) {
				var city = response;
				var html = '';
				$("#city").empty();

				for (var i = 0; i < city.length; i++) {
					html += "<option value='" + city[i].id + "'>" + city[i].title + "</option>"
				}

				$("#city").html(html);
			}
		})
	});

	if (pricing_server_data.length > 0) {
		for (var i = 0; i < pricing_server_data.length; i++) {
			var duration = pricing_server_data[i].duration.split(' ~ ');
			var from_date = duration[0];
			var to_date = duration[1];

			var blackout_date = pricing_server_data[i].blackout.split(', ');

			var temp_out_date = new Array();

			for (var j = 0; j < blackout_date.length; j++) {
				temp_out_date.push(blackout_date[j]);
			}

			pricing_data[i] = {
				from_date: from_date,
				to_date: to_date,
				blackout_date: temp_out_date
			}
		}
	}
	else {
		pricing_data[0] = {
			from_date: '',
			to_date: '',
			blackout_date: new Array()
		}
	}

	console.log(pricing_data);

	$('.pickadate').pickadate({
		format: 'yyyy/mm/dd',
		min: new Date(),
		selectYears: true,
		selectMonths: true
	});

	$('.daterange').daterangepicker({
		autoUpdateInput: false,
		locale: {
			cancelLabel: 'Clear',
			format: 'YYYY/MM/DD'
		}
	});

	$('.daterange').on('apply.daterangepicker', function (ev, picker) {
		var obj_id = $(this).attr('id');
		obj_id = obj_id.split('_');
		var index = obj_id[1];
		var idx = obj_id[2];

		var black_from_date = picker.startDate.format('YYYY/MM/DD');
		var black_to_date = picker.endDate.format('YYYY/MM/DD');

		var black_from_date_time = new Date(black_from_date).getTime();
		var black_to_date_time = new Date(black_to_date).getTime();

		var from_date = new Date(pricing_data[index - 1].from_date).getTime();
		var to_date = new Date(pricing_data[index - 1].to_date).getTime();

		console.log(black_from_date_time);
		console.log(black_to_date_time);

		console.log(from_date);
		console.log(to_date);

		if (from_date > black_from_date_time || to_date < black_to_date_time) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			$(this).val('');
			return;
		}

		var flag = 0;

		var blackout_date_iteration

		for (blackout_date_iteration of pricing_data[index - 1].blackout_date) {

			var flag_start_date = blackout_date_iteration.split(' - ')[0];
			var flag_end_date = blackout_date_iteration.split(' - ')[1];

			if (flag_end_date == undefined) {
				for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
					if (e.getTime() == new Date(flag_start_date).getTime()) {
						flag = 1;
						break;
					}
				}
			}
			else {
				for (var d = new Date(flag_start_date); d <= new Date(flag_end_date); d.setDate(d.getDate() + 1)) {
					for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
						if (d.getTime() == e.getTime()) {
							flag = 1;
							break;
						}
					}
				}
			}
		}

		if (flag == 1) {
			toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
			$(this).val('');
			return;
		}

		if (picker.startDate.format('YYYY/MM/DD') == picker.endDate.format('YYYY/MM/DD')) {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD'));
		}
		else {
			pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD');
			$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
		}
	});

	$('.daterange').on('cancel.daterangepicker', function (ev, picker) {
		$(this).val('');
	});

	$('#pricing_form_save').on('click', function (e) {
		e.preventDefault();
		var pricing_form = $("#pricing_form");
		var todate = $("input[id^='todate']");
		var blackoutdate = $("input[id^='blackoutdate']");
		var description = $("input[id^='description_1']");
		var price_1 = $("input[id^='price_1']");

		if (todate.val() == "") {
			toastr.warning('Please select the season end date!', 'Warning', { "closeButton": true });
		}
		else if (blackoutdate.val() == "") {
			toastr.warning('Please select the season blackout date!', 'Warning', { "closeButton": true });
		}
		else if (description.val() == "") {
			toastr.warning('Please fill the season description!', 'Warning', { "closeButton": true });
		}
		else if (price_1.val() == "") {
			toastr.warning('Please fill the season price!', 'Warning', { "closeButton": true });
		}
		else {
			pricing_form.submit();
		}

	});

	$("#priceset_add").on('click', function () {

		var obj = $("[id ^= 'pricingset_']");

		if (obj.length == 0) {

			index = 1;
		}
		else {
			var first_obj = obj[0];
			var first_obj_id = $(first_obj).attr('id');

			var index = first_obj_id.split('_')[1];
			index = parseInt(index) + 1;

			if (pricing_data[index - 2].from_date == '' || pricing_data[index - 2].to_date == '') {
				toastr.warning('Please fill pervious from and to date!', 'Warning', { "closeButton": true });
				return;
			}
		}

		var ii = index - 2;
		while (pricing_data[ii] == null) {
			if (ii <= 0) {
				break;
			}

			ii--;
		}

		var new_day = '';

		if (ii <= 0 && pricing_data[ii] == null) {
			pricing_data[index - 1] = {
				from_date: new_day,
				to_date: '',
				blackout_date: new Array()
			};
		}
		else {
			var aa = new Date(pricing_data[ii].to_date);

			aa.setDate(aa.getDate() + 1);

			var new_year = aa.getFullYear();
			var new_month = '';
			if (aa.getMonth() + 1 < 10) {
				new_month = '0' + (aa.getMonth() + 1);
			}
			else {
				new_month = '' + (aa.getMonth() + 1) + '';
			}
			var new_date = ''
			if (aa.getDate() < 10) {
				new_date = '0' + aa.getDate();
			}
			else {
				new_date = '' + aa.getDate() + '';
			}

			var new_day = new_year + '/' + new_month + '/' + new_date;

			pricing_data[index - 1] = {
				from_date: new_day,
				to_date: '',
				blackout_date: new Array()
			};
		}

		var tag_html = '';
		for (var i = 0; i < tag.length; i++) {
			tag_html += '<option value="' + tag[i].id + '">' + tag[i].title + '</option>';
		}

		var currency_html = '';
		for (var j = 0; j < currency.length; j++) {
			var isSelected = default_currency_id == currency[j].id ? "selected" : "";
			currency_html += '<option ' + isSelected + ' value="' + currency[j].id + '">' + currency[j].title + '</option>';
		}

		var title = 'Category Tag';

		var html = '<div class="row mb-1" id="pricingset_' + index + '" style="border: 1px solid #ccc; padding-top: 20px;">' +
			'<div class="col-md-12"><h6>Season</h6></div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control pickadate" placeholder="Select From Date" id="fromdate_' + index + '" name="fromdate[]" value="' + new_day + '" onchange="fromdate_trigger(this, ' + index + ')">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-calendar-check"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control pickadate" placeholder="Select To Date" id="todate_' + index + '" name="todate[]" value="" onchange="todate_trigger(this, ' + index + ')">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-calendar-check"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<select class="select2 form-control" id="currency_' + index + '" name="currency[]">' +
			'<option value="">Currency</option>' +
			currency_html +
			'</select>' +
			'<div class="form-control-position">' +
			'<i class="bx bx-search"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3 form-group d-flex align-items-center justify-content-start">' +
			'<button type="button" id="copyset_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="copypriceset(' + index + ')">' +
			'<i class="bx bx-copy"></i>' +
			'<span class="align-middle ml-25">Copy</span>' +
			'</button>' +
			'<button type="button" id="deleteset_' + index + '" class="btn btn-outline-danger mr-1 mb-1" onclick="deleteset(' + index + ')">' +
			'<i class="bx bx-trash"></i>' +
			'<span class="align-middle ml-25">Delete</span>' +
			'</button>' +
			'</div>' +
			'<div class="col-md-12" id="blackoutdatelist_' + index + '">' +
			'<h6>Blackout Date</h6>' +
			'<div class="row" id="blackoutdaterecord_' + index + '_1">' +
			'<div class="col-md-3 form-group">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control daterange" placeholder="Select BlackOut Date" id="blackoutdate_' + index + '_1" name="blackoutdate[' + (index - 1) + '][]" value="">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-calendar-check"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-6">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control" id="blackoutmsg_' + index + '_1" name="blackoutmsg[' + (index - 1) + '][]" value="Unavailable date!">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-info-circle"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3 form-group">' +
			'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteblackoutdate_' + index + '_1" onclick="deleteblackoutdate(' + index + ', 1)">' +
			'<i class="bx bx-trash"></i>' +
			'</button>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'<div class="col-md-6 form-group">' +
			'<button type="button" id="addblackoutdate_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="addblackoutdate(' + index + ')">' +
			'<i class="bx bx-plus"></i>' +
			'<span class="align-middle ml-25">Add</span>' +
			'</button>' +
			'</div>' +
			'<div class="col-md-12" id="pricelist_' + index + '">' +
			'<h6>' + title + '</h6>' +
			'<div class="row" id="pricinglist_' + index + '_1">' +
			'<input type="hidden" id="priceID_' + index + '_1" name="priceID[' + (index - 1) + '][]" value="" />' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<select class="select2 form-control" id="tag_' + index + '_1" name="tag[' + (index - 1) + '][]">' +
			tag_html +
			'</select>' +
			'<div class="form-control-position">' +
			'<i class="bx bx-search"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="text" class="form-control" id="description_' + index + '_1" name="description[' + (index - 1) + '][]" value="" placeholder="Description">' +
			'<div class="form-control-position">' +
			'<i class="bx bx-info-circle"></i>' +
			'</div>' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3">' +
			'<fieldset class="form-group position-relative has-icon-left">' +
			'<input type="number" id="price_' + index + '_1" name="price[' + (index - 1) + '][]" class="form-control" value="" data-bts-step="0.5" data-bts-decimals="2" data-bts-prefix="$" placeholder="Price" min="0">' +
			'</fieldset>' +
			'</div>' +
			'<div class="col-md-3 form-group">' +
			'<button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteprice_' + index + '_1" onclick="deleteprice(' + index + ', 1)">' +
			'<i class="bx bx-trash"></i>' +
			'</button>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'<div class="col-md-6 form-group">' +
			'<button type="button" id="addtype_' + index + '" class="btn btn-outline-primary mr-1 mb-1" onclick="addpricetype(' + index + ')">' +
			'<i class="bx bx-plus"></i>' +
			'<span class="align-middle ml-25">Add</span>' +
			'</button>' +
			'</div>' +
			'</div>';

		$("#priceset_list").prepend(html);

		$(".select2").select2({
			dropdownAutoWidth: true,
			width: '100%'
		});

		// $(".touchspin-icon").TouchSpin({
		//   buttondown_txt: '<i class="bx bx-minus"></i>',
		//   buttonup_txt: '<i class="bx bx-plus"></i>',
		//   min: 0
		// });

		$('.daterange').daterangepicker({
			"autoUpdateInput": false,
			"startDate": "02/15/2021",
			"endDate": "02/21/2021",
			locale: {
				cancelLabel: 'Clear'
			}
		});
		$('.daterange').on('apply.daterangepicker', function (ev, picker) {
			var obj_id = $(this).attr('id');
			obj_id = obj_id.split('_');
			var index = obj_id[1];
			var idx = obj_id[2];

			var black_from_date = picker.startDate.format('YYYY/MM/DD');
			var black_to_date = picker.endDate.format('YYYY/MM/DD');

			var black_from_date_time = new Date(black_from_date).getTime();
			var black_to_date_time = new Date(black_to_date).getTime();

			var from_date = new Date(pricing_data[index - 1].from_date).getTime();
			var to_date = new Date(pricing_data[index - 1].to_date).getTime();

			console.log(black_from_date_time);
			console.log(black_to_date_time);

			console.log(from_date);
			console.log(to_date);

			if (from_date > black_from_date_time || to_date < black_to_date_time) {
				toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
				$(this).val('');
				return;
			}

			var flag = 0;

			var blackout_date_iteration

			for (blackout_date_iteration of pricing_data[index - 1].blackout_date) {

				var flag_start_date = blackout_date_iteration.split(' - ')[0];
				var flag_end_date = blackout_date_iteration.split(' - ')[1];

				if (flag_end_date == undefined) {
					for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
						if (e.getTime() == new Date(flag_start_date).getTime()) {
							flag = 1;
							break;
						}
					}
				}
				else {
					for (var d = new Date(flag_start_date); d <= new Date(flag_end_date); d.setDate(d.getDate() + 1)) {
						for (var e = new Date(black_from_date); e <= new Date(black_to_date); e.setDate(e.getDate() + 1)) {
							if (d.getTime() == e.getTime()) {
								flag = 1;
								break;
							}
						}
					}
				}
			}

			if (flag == 1) {
				toastr.warning('Please enter the exact blackout date', 'Warning', { "closeButton": true });
				$(this).val('');
				return;
			}

			if (picker.startDate.format('YYYY/MM/DD') == picker.endDate.format('YYYY/MM/DD')) {
				pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD');
				$(this).val(picker.startDate.format('YYYY/MM/DD'));
			}
			else {
				pricing_data[index - 1].blackout_date[idx - 1] = picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD');
				$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
			}
		});

		$('.daterange').on('cancel.daterangepicker', function (ev, picker) {
			$(this).val('');
		});

		$('.pickadate').pickadate({
			format: 'yyyy/mm/dd',
			min: new Date(),
			selectYears: true,
			selectMonths: true
		});
	});

	$("#pricing_form").on('submit', function (e) {
		e.preventDefault();

		// var price_product_id = $("#price_product_id").val();
		// if (price_product_id == '') {
		// 	toastr.warning('Please Fill General Form', 'Warning', { "closeButton": true });
		// 	return;
		// }

		var form = $(this);
		var url = form.attr('action');

		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(), // serializes the form's elements.
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.success == true) {
					toastr.success(data.messages, 'Success', { "closeButton": true });
					$("#general_product_id").val(data.product_id);
					$("#description_product_id").val(data.product_id);
					$("#gallery_product_id").val(data.product_id);
					$("#price_product_id").val(data.product_id);
				}
				else {
					if(Array.isArray(data.messages)) {
						for(var i=0; i<data.messages.length; i++) {
							toastr.warning(data.messages[i], 'Warning', { "closeButton": true });
						}
					}
					else {
						toastr.error(data.messages, 'Error', { "closeButton": true });
					}
				}
			}
		});
	});

});
