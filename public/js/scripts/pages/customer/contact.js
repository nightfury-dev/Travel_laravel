var new_message = () => {
	$("#send_modal").modal();
}

$(document).ready(function () {
	CKEDITOR.replace("message");
	$("textarea").not("[type=submit]").jqBootstrapValidation();
	
	$("#searchbar").on("change", function () {
		var search_string = $(this).val();
		
		$.ajax({
			type: "GET",
			url: base_url + '/itinerary/messages',
			data: {
				_token: $("[name='_token']").val(),
				id: $("#itinerary_id").val(),
				search_string: search_string
			},
			success: function (html) {
				$("#message_list").empty();

				var spinner_html = '<div class="text-center">' +
					'<div class="spinner-border spinner-border-lg" role="status">' +
					'<span class="sr-only">Loading...</span>' +
					'</div>' +
					'</div>';

				$("#message_list").html(spinner_html);

				var spinner = setInterval(function () {
					$("#message_list").empty();
					$("#message_list").html(html);

					clearInterval(spinner);
				}, 500);
			}
		});
	});

	$("#send_form").on('submit', function(e) {
		e.preventDefault();
		if($("#to_id").val() == 0) {
			toastr.error('The Supplier is not setted! Please Check the task detail again!', 'error', { 'closeButton': true, timeOut: 2000 });
			return;
		}

		var message = CKEDITOR.instances.message.getData();
		var title = CKEDITOR.instances.message.document.getBody().getText()
		if(!$("#attach_file").val() && message == '') {
			toastr.error('Please fill the message field again!', 'error', { 'closeButton': true, timeOut: 2000 });
			return;
		}

		var form = $(this);
		var url = form.attr('action');
		
		var form_data = new FormData(document.getElementById("send_form"));
		form_data.append('message', message);
		form_data.append('title', title);

		$.ajax({
			type: "POST",
			url: url,
			contentType: false,
			processData: false,
			enctype: 'multipart/form-data',
			data: form_data, // serializes the form's elements.
			success: function (message) {
				console.log(message);
				if(message == 'success') {
					console.log('aaaaaaaaaaaaaaaaaaaa');
					document.location.reload();
				}
			}
		});
	});

	$(".delete_message").on('click', function(e) {
		e.stopPropagation();
		let message_id = $(this).data('id');
		
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
					url: base_url + '/itinerary/delete_message',
					method: 'post',
					data: {
						_token: $("[name='_token']").val(),
						message_id: message_id,
					},
					success: function (data) {
						if (data == "success") {
							location.reload();
						}
					}
				});
			}
		});
	});

	$(".mail-reply").on('click', function(e) {
		e.stopPropagation();
		$("#send_modal").modal();
	});
});

