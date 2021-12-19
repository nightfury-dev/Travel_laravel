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
		{ types: ["geocode"] }
	);

	billing_autocomplete = new google.maps.places.Autocomplete(
		document.getElementById("billing_location"),
		{ types: ["geocode"] }
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

		var obj_id = ""
		if (addressType == "postal_code") {
			obj_id = "main_postal_code";
		}
		else if (addressType == "administrative_area_level_1") {
			obj_id = "main_region_state";
		}
		else if (addressType == "country") {
			obj_id = "main_country";
		}
		else if (addressType == "locality") {
			obj_id = "main_city";
		}
		else if (addressType == "street_number") {
			obj_id = "main_street_number";
		}
		else if (addressType == "route") {
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

		var obj_id = ""
		if (addressType == "postal_code") {
			obj_id = "billing_postal_code";
		}
		else if (addressType == "administrative_area_level_1") {
			obj_id = "billing_region_state";
		}
		else if (addressType == "country") {
			obj_id = "billing_country";
		}
		else if (addressType == "locality") {
			obj_id = "billing_city";
		}
		else if (addressType == "street_number") {
			obj_id = "billing_street_number";
		}
		else if (addressType == "route") {
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

var detailMailEditor;

var appContent = $(".app-content"),
	email_app_details = $(".email-app-details"),
	email_application = $(".email-application"),
	email_user_list = $(".email-user-list"),
	email_app_list = $(".email-app-list"),
	checkbox_con = $(".user-action .checkbox-con"),
	$primary = "#5A8DEE";

const filter_by_status = (status) => {
	let search_string = $("#email-search").val();
	search_enquiry(status, search_string);
}

const search_enquiry = (status, search_string) => {
	$.ajax({
		url: base_url + '/enquiry',
		type: 'GET',
		data: {
			_token: $("[name='_token']").val(),
			status: status,
			search_string: search_string
		},
		success: function (response) {
			$('.media').css('animation', 'none')
			email_app_list.html(response);
			init();
		}
	})
}

const delete_enquiry = (enquiry_id) => {
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
				url: base_url + '/enquiry/delete',
				type: 'GET',
				data: {
					_token: $("[name='_token']").val(),
					enquiry_id: enquiry_id
				},
				success: function (response) {
					if (response == 'success') {
						console.log('asdfasdfasdf');
						location.reload()
						toastr.success('Enquiry is deleted successfully!', 'success', { 'closeButton': true, timeOut: 2000 });
					}
				}
			})
		}
		else if (result.dismiss === Swal.DismissReason.cancel) { }
	});
}

const delete_message = (message_id) => {
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
				url: base_url + '/enquiry/delete_message',
				type: 'GET',
				data: {
					_token: $("[name='_token']").val(),
					message_id: message_id
				},
				success: function (response) {
					if (response == 'success') {
						$(`#message_record_${message_id}`).remove();
						toastr.success('Enquiry is deleted successfully!', 'success', { 'closeButton': true, timeOut: 2000 });
					}
				}
			})
		}
		else if (result.dismiss === Swal.DismissReason.cancel) { }
	});
}

const message_send = () => {

	var fd = new FormData();
	var enquiry_id = $("#enquiry_id").val();
	var editor_content = $("#send_form_wrapper .snow-container .detail-view-editor .ql-editor").html();
	var files = $('#attach_file')[0].files;

	if (detailMailEditor.getText() == "") {
		toastr.error('Please enter message data...', 'error', { 'closeButton': true, timeOut: 2000 });
	}
	else {
		fd.append('_token', $("[name='_token']").val());
		fd.append('title', detailMailEditor.getText());
		fd.append('attach_file', files[0]);
		fd.append('enquiry_id', enquiry_id);
		fd.append('message', editor_content);

		$.ajax({
			url: base_url + '/enquiry/send_message',
			type: 'POST',
			data: fd,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				if (response.flag == 1) {
					let new_record = response.new_record;


					let avatar_html = '';
					if (new_record.from_avatar_path != '') {
						avatar_html = `<img src="${assets_url}storage/${new_record.from_avatar_path}" alt="avtar img holder" width="30" height="30">`;
					}
					else {
						avatar_html = `<img src="${assets_url}images/img/avatar.png" alt="avtar img holder" width="30" height="30">`;
					}

					let from_full_name = '';
					if (new_record.from_first_name != null && new_record.from_last_name != null) {
						from_full_name = new_record.from_first_name + ' ' + new_record.from_last_name;
					}
					else {
						from_full_name = new_record.from_username;
					}

					let to_full_name = '';
					if (new_record.to_first_name != null && new_record.to_last_name != null) {
						to_full_name = new_record.to_first_name + ' ' + new_record.to_last_name;
					}
					else {
						to_full_name = new_record.to_username;
					}

					let file_html = '';
					if (new_record.file != '') {
						let_filename_arr = new_record.file.split(',');
						let sub_html = '';
						for (let i = 0; i < let_filename_arr.length; i++) {
							let sub_filename_arr = let_filename_arr[i].split(':');

							sub_html += `<li class="cursor-pointer">
								<img src="${assets_url}images/icon/sketch.png" height="30" alt="sketch.png">
								<small class="text-muted ml-1 attchement-text">${sub_filename_arr[1]}</small>
							</li>`;
						}

						file_html = `<div class="card-footer pt-0 border-top">
							<label class="sidebar-label">Attached Files</label>
							<ul class="list-unstyled mb-0">
								${sub_html}
							</ul>
						</div>`;
					}

					let html = `<div class="card collapse-header" role="tablist" id="message_record_${new_record.id}">
						<div id="headingCollapse${new_record.id}" class="card-header d-flex justify-content-between align-items-center" data-toggle="collapse" role="tab" data-target="#collapse${new_record.id}" aria-expanded="false" aria-controls="collapse${new_record.id}">
							<div class="collapse-title media">
								<div class="pr-1">
									<div class="avatar mr-75">
										${avatar_html}
									</div>
								</div>
								<div class="media-body mt-25">
									<span class="text-primary">${from_full_name}</span>
									<span class="d-sm-inline d-none"> &lt;${new_record.from_email}&gt;</span>
									<small class="text-muted d-block">to ${to_full_name}</small>
								</div>
							</div>
							<div class="information d-sm-flex d-none align-items-center">
								<small class="text-muted mr-50">${new_record.format_created_at}</small>
								<div class="dropdown">
									<a href="#" class="dropdown-toggle" id="fisrt-open-submenu"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class='bx bx-dots-vertical-rounded mr-0'></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="fisrt-open-submenu">
										<a href="#" class="dropdown-item" onclick="delete_message(${new_record.id})">
											<i class='bx bx-trash'></i>
											Delete Message
										</a>
									</div>
								</div>
							</div>
						</div>
						<div id="collapse${new_record.id}" role="tabpanel" aria-labelledby="headingCollapse${new_record.id}" class="collapse">
							<div class="card-content">
								<div class="card-body py-1">
									${new_record.message}
								</div>
								${file_html}
							</div>
						</div>
					</div>`;

					$("#message_area").prepend(html);
					var quill_editor = $(".detail-view-editor .ql-editor");// quill editor content
					quill_editor[0].innerHTML = "";

					const container = document.querySelector('.email-scroll-area');
					container.scrollTop = 0;

					$("[id ^= 'message_record_']").on('click', function () {
						if ($(this).hasClass('open')) {
							$(this).removeClass('open');
						}
						else {
							$(this).addClass('open');
						}
					})
				}
			}
		});
	}
}

const click_message = () => {
	if (link_id != "0" && message_id != "0") {
		$("[data-message = '" + link_id + "']").click();
		link_id = "0";
		message_id = "0";
	}
}

const init = () => {
	// User list scroll

	if (email_user_list.length > 0) {
		var users_list = new PerfectScrollbar(".email-user-list", {
			wheelPropagation: false
		});
	}

	email_app_list.find('.email-user-list li .media-body').on('click', function () {
		let enquiry_id = $(this).data('id');

		$.ajax({
			url: base_url + '/enquiry/get_messages',
			type: 'POST',
			data: {
				_token: $("[name='_token']").val(),
				enquiry_id: enquiry_id
			},
			dataType: 'html',
			success: function (result) {
				if (result) {
					$(email_app_details).empty();
					$(email_app_details).html(result);

					detailMailEditor = new Quill('#send_form_wrapper .snow-container .detail-view-editor', {
						modules: {
							toolbar: '.detail-quill-toolbar'
						},
						placeholder: 'Type something..... ',
						theme: 'snow'
					});

					// on click of go button or inbox btn get back to inbox
					$('.go-back').on('click', function (e) {
						e.stopPropagation();
						email_app_details.removeClass('show');
						var quill_editor = $(".detail-view-editor .ql-editor");// quill editor content
						quill_editor[0].innerHTML = "";
					});

					// Email detail section
					if ($('.email-scroll-area').length > 0) {
						var email_scroll_area = new PerfectScrollbar(".email-scroll-area", {
							wheelPropagation: false
						});
					}

					$("[id ^= 'headingCollapse']").on('click', function () {
						if ($(this).parent('.collapse-header').hasClass('open')) {
							$(this).parent('.collapse-header').removeClass('open');
						}
						else {
							$(this).parent('.collapse-header').addClass('open');
						}
					});

					// Quill get focus when click on reply
					$('.mail-reply').on('click', function (e) {
						detailMailEditor.focus();
					});


					$(".custom-file-input").on("change", function () {
						var fileName = $(this).val().split("\\").pop();
						$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
					});

					email_app_details.toggleClass('show');
				}
			}
		})
	});

	checkbox_con.on("click", function (e) {
		e.stopPropagation();
	});

	checkbox_con.find("input").on('change', function () {
		var $this = $(this);
		if ($this.is(":checked")) {
			$this.closest(".media").addClass("selected-row-bg");
		}
		else {
			$this.closest(".media").removeClass("selected-row-bg");
		}
	});

	$(document).on("change", ".email-app-list .selectAll input", function () {
		if ($(this).is(":checked")) {
			checkbox_con.find("input").prop('checked', this.checked).closest(".media").addClass("selected-row-bg");
		}
		else {
			checkbox_con.find("input").prop('checked', "").closest(".media").removeClass("selected-row-bg");
		}
	});

	$("#delete_enquiry").on('click', function (e) {

		var checked_obj = checkbox_con.find("input:checked");
		let id_arr = [];
		for (let i = 0; i < checked_obj.length; i++) {
			let enq_id = $(checked_obj[i]).data('id');
			id_arr.push(enq_id);
		}

		if (id_arr.length == 0) {
			toastr.error('Please select the some records!', 'error', { 'closeButton': true, timeOut: 2000 });
		}
		else {
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
						url: base_url + '/enquiry/delete_some_enquiry',
						type: 'GET',
						data: {
							_token: $("[name='_token']").val(),
							id_arr: id_arr
						},
						success: function (response) {
							if (response == 'success') {
								toastr.success('Some Enquiries is deleted successfully!', 'success', { 'closeButton': true, timeOut: 2000 });
								location.reload()
							}
						}
					})
				}
				else if (result.dismiss === Swal.DismissReason.cancel) { }
			});
		}
	})

	email_app_list.find("#email-search").on("change", function () {
		var value = $(this).val().toLowerCase();
		search_enquiry(-1, value);
	});

	$(".email-detail-head .dropdown-item").on("click", function () {
		$(".dropdown-toggle").dropdown('hide');
	});

	$(".information .dropdown-menu a").on("click", function (e) {
		e.stopPropagation();
		$(this).parent().removeClass('show');
	});

	$(".enquiry-tool .dropdown .dropdown-toggle").on("click", function (e) {
		$(this).dropdown('toggle');
	});

	//////// Begin Create Update Form

	$('#adults_num').change(function () {
		var total_num = parseInt($('#adults_num').val()) + parseInt($('#children_num').val());
		$('#traveler_total').text("Total travelers: " + total_num);
	});

	$('#children_num').change(function () {
		var total_num = parseInt($('#adults_num').val()) + parseInt($('#children_num').val());
		$('#traveler_total').text("Total travelers: " + total_num);
	});

	$('#single_room').change(function () {
		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	});

	$('#double_room').change(function () {
		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	});

	$('#twin_room').change(function () {
		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	});

	$('#triple_room').change(function () {
		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	});

	$('#family_room').change(function () {
		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	});

	$('#is_assigned').change(function () {
		if (this.checked) {
			$('#assigned_user').prop('disabled', false);
		}
		else $('#assigned_user').prop('disabled', 'disabled');
	});

	$('.add_customer_btn').click(function () {
		var customer_modal = $('#customer_modal');
		customer_modal.modal()
	});

	if ($('.showdropdowns').length != 0) {
		$('.showdropdowns').daterangepicker({
			showDropdowns: true,
			drops: "down"
		});
	}

	if ($("#customer_id").length != 0) {
		$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
	}

	if (typeof (custom_enquiry) != 'undefined') {
		var duration = $('#duration').val();
		var start_date_str = duration.slice(0, 10);
		var end_date_str = duration.slice(13);

		var start_date_split = start_date_str.split('/');
		var end_date_split = end_date_str.split('/');

		var start_date = new Date(start_date_split[2], start_date_split[0] - 1, start_date_split[1]);
		var end_date = new Date(end_date_split[2], end_date_split[0] - 1, end_date_split[1]);

		var days = Math.round((end_date - start_date) / (1000 * 60 * 60 * 24));
		days++;
		$("#num_days").text('Total days: ' + days + ' days');

		var total_num = parseInt($('#adults_num').val()) + parseInt($('#children_num').val());
		$('#traveler_total').text("Total travelers: " + total_num);

		var number_rooms = parseInt($('#single_room').val()) + parseInt($('#double_room').val()) + parseInt($('#twin_room').val()) + parseInt($('#triple_room').val()) + parseInt($('#family_room').val());
		$('#number_rooms').text("Number of rooms: " + number_rooms);
		$('#number_rooms').show();
	}

	$("#duration").change(function () {
		var duration = $('#duration').val();
		var start_date_str = duration.slice(0, 10);
		var end_date_str = duration.slice(13);

		var start_date_split = start_date_str.split('/');
		var end_date_split = end_date_str.split('/');

		var start_date = new Date(start_date_split[2], start_date_split[0] - 1, start_date_split[1]);
		var end_date = new Date(end_date_split[2], end_date_split[0] - 1, end_date_split[1]);

		var days = Math.round((end_date - start_date) / (1000 * 60 * 60 * 24));
		days++;
		$("#num_days").text('Total days: ' + days + ' days');
		$("#num_days").show();
	});

	click_message();
}

$(document).ready(function () {
	init();
});

