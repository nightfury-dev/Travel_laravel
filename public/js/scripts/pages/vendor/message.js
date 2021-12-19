function load_unseen_notification() {
	$.ajax({
		url: base_message_url + "/fetch_message",
		method: "POST",
		data: {
			_token: $("[name='_token']").val()
		},
		dataType: "json",
		success: function (data) {
			var messages = data.new_messages;
			var html = "";
			for (var i = 0; i < messages.length; i++) {
				html += `<a class="d-flex justify-content-between" href="${base_message_url}/read_message/${messages[i].id}">
				  <div class="media d-flex align-items-center">
					  <div class="media-left pr-0">
						  <div class="avatar mr-1 m-0"><img src="${assetBaseUrl}storage/${messages[i].avatar_path
					}" alt="avatar" height="39" width="39"></div>
					  </div>
					  <div class="media-body">
						  <h6 class="media-heading"><span class="text-bold-500">${messages[i].title
					}</span></h6>
						  <small class="notification-text">${new Date(
						messages[i].created_at
					).toLocaleString()}</small>
					  </div>
				  </div></a>`;
			}

			$("#message_alarm_list").html(html);
			if (messages.length > 0) {
				$("#message_alarm_count").html(messages.length);
			}
		}
	});
}


$(document).ready(function () {
	$(document).on("click", "#mark_allmessage", function () {
		$.ajax({
			url: base_message_url + "/mark_allmessage",
			method: "POST",
			data: {
				_token: $("[name='_token']").val()
			},
			success: function (data) {
				if (data == "success") {
					$("#message_alarm_list").empty();
					$("#message_alarm_count").empty();
				}
			}
		});
	});

	setInterval(function () {
		load_unseen_notification();
	}, 5000);

	if (typeof (msg) != "undefined") {
		if (msg == "task approve") {
			toastr.success('Service for Itinerary is approved successfully!', 'Success', { "closeButton": true });
		}
		else if (msg == 'task decline') {
			toastr.warning('Service for Itinerary is declined successfully!', 'Warning', { "closeButton": true });
		}
	}
})