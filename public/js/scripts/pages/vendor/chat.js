var chatSidebarListWrapper = $(".chat-sidebar-list-wrapper"),
	chatOverlay = $(".chat-overlay"),
	chatContainer = $(".chat-container"),
	chatProfileToggle = $(".chat-profile-toggle"),
	chatSidebarClose = $(".chat-sidebar-close"),
	chatProfile = $(".chat-profile"),
	chatProfileClose = $(".chat-profile-close"),
	chatSidebar = $(".chat-sidebar"),
	chatArea = $(".chat-area"),
	chatStart = $(".chat-start"),
	chatSidebarToggle = $(".chat-sidebar-toggle"),
	chatMessageSend = $(".chat-message-send");

var group_data = [];

// Add message to chat
// function chatMessagesSend(source) {
// 	var message = chatMessageSend.val();
// 	if (message != "") {
// 		var html = '<div class="chat-message">' + "<p>" + message + "</p>" + "<div class=" + "chat-time" + ">3:35 AM</div></div>";
// 		$(".chat-wrapper .chat:last-child .chat-body").append(html);
// 		chatMessageSend.val("");
// 		chatContainer.scrollTop($(".chat-container > .chat-content").height());
// 	}
// }

function get_all_contacts() {
    var session_user_id = $("#session_user_id").val();
    var html = '';
    firebase.firestore().collection("group").where('members', 'array-contains', session_user_id).get().then(function(querySnapshot) {
        querySnapshot.forEach(function(doc) {
            var document = doc.data();
            group_data.push(document.group_id);

			var display_id = document.from_id;
			var display_name = document.from_user_name;
            var display_avatar = document.from_user_avatar;

            var time = new Date(document.last_time.seconds*1000);
            var time_year = time.getFullYear();
            var time_month = time.getMonth()+1;
            if(time_month < 10) {
                time_month = '0'+time_month;
            }
            var time_day = time.getDate();
            if(time_day < 10) {
                time_day = '0' + time_day;
            }

            var time_hour = time.getHours();
            if(time_hour < 10) {
                time_hour = '0' + time_hour;
            }
            var time_minutes = time.getMinutes();
            if(time_minutes < 10) {
                time_minutes = '0' + time_minutes;
            }
            var time_seconds = time.getSeconds();
            if(time_seconds < 10) {
                time_seconds = '0' + time_seconds;
            }

            var time_html = time_month + '/'+ time_day +'/' + time_year + ' ' + time_hour + ':'+ time_minutes;

            if(display_avatar == '') {
				var name_arr = display_name.split(' ');
				var first_name_key = name_arr[0].charAt(0).toUpperCase();
				var last_name_key = name_arr[1].charAt(0).toUpperCase();

				var key = first_name_key + last_name_key;

				html += '<li id="'+ document.group_id +'" onClick="chat_details(\''+ document.group_id +'\', this, 0, \''+ key +'\', \''+ display_id +'\', \''+ display_name +'\', \''+ display_avatar +'\');">'+
					'<div class="d-flex align-items-center justify-content-between">'+
						'<div class="avatar bg-info m-0 mr-50">'+
							'<span class="avatar-content">'+ key +'</span>'+
							'<span class="avatar-status-online"></span>'+
						'</div>'+
						'<div class="chat-sidebar-name">'+
							'<h6 class="mb-0">'+ display_name +'</h6><div class="text-muted">'+ document.last_content.substring(0, 20) +'... </div><div style="font-size:10px;">'+ time_html +'</div>'+
						'</div>'+
					'</div>'+
				'</li>';
			}
			else {
				html += '<li id="'+ document.group_id +'" onClick="chat_details(\''+ document.group_id +'\', this, 1, \''+ display_avatar +'\', \''+ display_id +'\', \''+ display_name +'\', \''+ display_avatar +'\');">' +
					'<div class="d-flex align-items-center justify-content-between">' +
						'<div class="avatar m-0 mr-50"><img src="' + assetBaseUrl +'storage/' + display_avatar +'" height="36" width="36" alt="sidebar user image">'+
							'<span class="avatar-status-online"></span>'+
						'</div>'+
						'<div class="chat-sidebar-name">'+
							'<h6 class="mb-0">'+ display_name +'</h6><div class="text-muted">'+ document.last_content.substring(0, 20) +'... </div><div style="font-size:10px;">'+ time_html +'</div>'+
						'</div>'+
					'</div>'+
				'</li >';
			}
        });

        $("#contact_list").html(html);
    });
}

var global_time;
var global_miletime;
var global_direction;

function chat_details(group_id, obj, flag, avatar, display_id, display_name, display_avatar) {

	if($(obj).hasClass('active')){
		return;
	}
	
	if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
		$(".chat-sidebar-list-wrapper ul li").removeClass("active");
	}

	$(obj).addClass("active");
	
	if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
		chatStart.addClass("d-none");
		chatArea.removeClass("d-none");
	}
	else {
		chatStart.removeClass("d-none");
		chatArea.addClass("d-none");
	}

	if(flag == 0) {
		var avatar_html = '<span class="avatar-content">'+ avatar +'</span><span class="avatar-status-online"></span>';
		$("#contact_person_avatar").html(avatar_html);
		$("#contact_person_name").text(display_name);
	}
	else {
		var avatar_html = '<img src="' + assetBaseUrl +'storage/' + avatar +'" height="36" width="36"><span class="avatar-status-online"></span>';
		$("#contact_person_avatar").html(avatar_html);
		$("#contact_person_name").text(display_name);
	}

	$("#staff_id").val(display_id);
	$("#staff_name").val(display_name);
	$("#staff_avatar").val(display_avatar);

	$("#group_id").val(group_id);

	var session_user_id = $("#session_user_id").val();
	var all_chart_data = [];

	// $(".chat-wrapper .chat-content").empty();

	firebase.firestore().collection("chat").where('group_id', '==', group_id).get().then(function (querySnapshot) {
		querySnapshot.forEach(function (doc) {
			var document = doc.data();
			all_chart_data.push(document);

			firebase.firestore().collection("chat").doc(doc.id).update({
				flag: 1
			})
		});

		var temp = '';

		for (var i = 0; i < all_chart_data.length - 1; i++) {
			for (var j = i + 1; j < all_chart_data.length; j++) {
				if (all_chart_data[i].send_time.seconds > all_chart_data[j].send_time.seconds) {
					temp = all_chart_data[i];
					all_chart_data[i] = all_chart_data[j];
					all_chart_data[j] = temp;
				}
			}
		}

		var html = '';
		for (var i = 0; i < all_chart_data.length; i++) {
			var time = new Date(all_chart_data[i].send_time.seconds * 1000);

			var time_year = time.getFullYear();
			var time_month = time.getMonth() + 1;

			if (time_month < 10) {
				time_month = '0' + time_month;
			}
			var time_day = time.getDate();

			if (time_day < 10) {
				time_day = '0' + time_day;
			}

			var time_hour = time.getHours();
			var time_mark = 'AM';
			if (time_hour > 12) {
				var time_mark = 'PM'
			}

			if (time_hour < 10) {
				time_hour = '0' + time_hour;
			}
			var time_minutes = time.getMinutes();
			if (time_minutes < 10) {
				time_minutes = '0' + time_minutes;
			}

			var time_html = time_hour + ':' + time_minutes + ' ' + time_mark;

			var now_date = new Date();

			if (now_date.getFullYear() == time.getFullYear() && now_date.getMonth() == time.getMonth() && now_date.getDay() == time.getDay()) {
				var divider_html = '<div class="badge badge-pill badge-light-secondary my-1">Today</div>';
			}
			else if (now_date.getFullYear() == time.getFullYear() && now_date.getMonth() == time.getMonth() && now_date.getDay() == time.getDay() + 1) {
				var divider_html = '<div class="badge badge-pill badge-light-secondary my-1">Yesterday</div>';
			}
			else {
				var divider_html = '<div class="badge badge-pill badge-light-secondary my-1">' + time_month + '/' + time_day + '/' + time_year + '</div>';
			}

			if (global_time != time_year + '-' + time_month + '-' + time_day) {
				global_time = time_year + '-' + time_month + '-' + time_day;
				$(".chat-wrapper .chat-content").append(divider_html);
			}

			var diffMins = Math.abs(time - global_miletime);
			var diffMins = Math.round(((diffMins % 86400000) % 3600000) / 60000); // minutes

			var html_flag = 0
			if (diffMins != 0) {
				global_miletime = time;
				html_flag = 1;
			}

			if (all_chart_data[i].created_by == session_user_id) {

				var html_with_time = '';
				var html_without_time = '';

				if (all_chart_data[i].type != 'text') {
					if (all_chart_data[i].type.indexOf('image') === -1) {
						var file_src = 'data:' + all_chart_data[i].type + ';base64' + all_chart_data[i].file_content;

						html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + all_chart_data[i].content + '<p>' +
							'<p class="down_file_size">' + all_chart_data[i].file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';

						html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + all_chart_data[i].content + '<p>' +
							'<p class="down_file_size">' + all_chart_data[i].file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
					else {
						var img_src = 'data:' + all_chart_data[i].type + ';base64' + all_chart_data[i].file_content;

						html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';

						html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
				}
				else {
					html_with_time = '<div class="chat-message-wrap">' +
						'<p class="chat-time-text">' + time_html + '</p>' +
						'<p class="chat-text">' + all_chart_data[i].content + '</p>' +
						'</div>';

					html_without_time = '<div class="chat-message-wrap">' +
						'<p class="chat-text">' + all_chart_data[i].content + '</p>' +
						'</div>';
				}

				if (html_flag == 0) {
					if (global_direction = 'r') {
						$(".chat-wrapper .chat-content .chat-right:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-right">' +
							'<div class="chat-avatar">'+
								'<a class="avatar m-0">'+
									'<img src="" alt="avatar" height="36" width="36" />'+
								'</a>'+
							'</div>'+
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".chat-wrapper .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-right">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".chat-wrapper .chat-content").append(html);
				}

				global_direction = 'r';
			}
			else {
				var html_with_time = '';
				var html_without_time = '';

				if (all_chart_data[i].type != 'text') {
					if (all_chart_data[i].type.indexOf('image') === -1) {
						var file_src = 'data:' + all_chart_data[i].type + ';base64' + all_chart_data[i].file_content;

						html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + all_chart_data[i].content + '<p>' +
							'<p class="down_file_size">' + all_chart_data[i].file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';

						html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + all_chart_data[i].content + '<p>' +
							'<p class="down_file_size">' + all_chart_data[i].file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
					else {
						var img_src = 'data:' + all_chart_data[i].type + ';base64' + all_chart_data[i].file_content;
						html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';

						html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + all_chart_data[i].content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
				}
				else {
					html_with_time = '<div class="chat-message-wrap">' +
						'<p class="chat-time-text">' + time_html + '</p>' +
						'<p class="chat-text">' + all_chart_data[i].content + '</p>' +
						'</div>';

					html_without_time = '<div class="chat-message-wrap">' +
						'<p class="chat-text">' + all_chart_data[i].content + '</p>' +
						'</div>';
				}

				if (html_flag == 0) {
					if (global_direction = 'l') {
						$(".chat-wrapper .chat-content .chat-left:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-left">' +
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".chat-wrapper .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-left">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".chat-wrapper .chat-content").append(html);
				}

				global_direction = 'l';
			}
		}

		chatMessageSend.val("");
		chatContainer.scrollTop($(".chat-container .chat-content").height());
	});
}

function widgetMessageSend(source) {
	var session_user_id = $("#session_user_id").val();
	var session_user_name = $("#session_user_name").val();
	var session_user_avatar = $("#session_user_avatar").val();

	var staff_id = $("#staff_id").val();
	var staff_name = $("#staff_name").val();
	var staff_avatar = $("#staff_avatar").val();

	var general_group_id = $("#group_id").val();

	var message = $(".chat-message-send").val();

	if (message != "") {
		firebase.firestore().collection('group').where('group_id', '==', general_group_id).get().then(function (querySnapshot) {
			querySnapshot.forEach(function (doc) {
				group_id = doc.id;
				return;
			});

			var time = new Date();
			var time_year = time.getFullYear();
			var time_month = time.getMonth() + 1;

			var time_mark = 'AM';
			if (time_month < 10) {
				time_month = '0' + time_month;
			}
			var time_day = time.getDate();
			if (time_day < 10) {
				time_day = '0' + time_day;
			}

			var time_hour = time.getHours();
			if (time_hour > 12) {
				time_mark = 'PM';
			}

			if (time_hour < 10) {
				time_hour = '0' + time_hour;
			}

			var time_minutes = time.getMinutes();
			if (time_minutes < 10) {
				time_minutes = '0' + time_minutes;
			}
			var time_seconds = time.getSeconds();
			if (time_seconds < 10) {
				time_seconds = '0' + time_seconds;
			}

			var mini_time_html = time_hour + ':' + time_minutes + ' ' + time_mark;

			firebase.firestore().collection("group").doc(group_id).update({
				last_type: 'text',
				last_content: message,
				last_time: firebase.firestore.FieldValue.serverTimestamp(),
			});

			firebase.firestore().collection("chat").add({
				group_id: general_group_id,
				type: 'text',
				content: message,
				created_by: session_user_id,
				created_name: session_user_name,
				created_avatar: session_user_avatar,
				read_by: staff_id,
				read_name: staff_name,
				read_avatar: staff_avatar,
				send_time: firebase.firestore.FieldValue.serverTimestamp(),
				flag: 0,
			})
			.then((ref) => {
				var diffMins = Math.abs(time - global_miletime);
				var diffMins = Math.round(((diffMins % 86400000) % 3600000) / 60000); // minutes

				if (diffMins != 0) {
					var html = '<div class="chat chat-right">' +
						'<div class="chat-body">' +
						'<div class="chat-message-wrap">' +
						'<p class="chat-time-text">' + mini_time_html + '</p>' +
						'<p class="chat-text">' + message + '</p>' +
						'</div>' +
						'</div>' +
						'</div>';

						$(".chat-wrapper .chat-content").append(html);
					global_miletime = time;
				}
				else {
					if (global_direction == 'r') {
						var html = '<div class="chat-message-wrap">' +
							'<p class="chat-text">' + message + '</p>' +
							'</div>';

						$(".chat-content .chat-right:last-child .chat-body").append(html);
					}
					else {
						var html = '<div class="chat chat-right">' +
							'<div class="chat-body">' +
							'<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + mini_time_html + '</p>' +
							'<p class="chat-text">' + message + '</p>' +
							'</div>' +
							'</div>' +
							'</div>';

							$(".chat-wrapper .chat-content").append(html);
					}
				}

				global_direction = 'r';

				$(".chat-message-send").val("");
				chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
			})
		});
	}
}

function get_file(event) {
	var file = event.target.files;

	var file_name = file[0].name;
	var file_size = file[0].size;
	var file_type = file[0].type;


	const reader = new FileReader();
	reader.addEventListener('load', (event) => {
		const file_content = event.target.result;

		var session_user_id = $("#session_user_id").val();
		var session_user_name = $("#session_user_name").val();
		var session_user_avatar = $("#session_user_avatar").val();

		var staff_id = $("#staff_id").val();
		var staff_name = $("#staff_name").val();
		var staff_avatar = $("#staff_avatar").val();

		var group_id = '';
		var general_group_id = $("#group_id").val();

		if (file_content != '') {
			firebase.firestore().collection('group').where('group_id', '==', general_group_id).get()
				.then(function (querySnapshot) {

					querySnapshot.forEach(function (doc) {
						group_id = doc.id;
						return;
					});

					var time = new Date();
					var time_year = time.getFullYear();
					var time_month = time.getMonth() + 1;
					var time_mark = 'AM';
					if (time_month < 10) {
						time_month = '0' + time_month;
					}
					var time_day = time.getDate();
					if (time_day < 10) {
						time_day = '0' + time_day;
					}

					var time_hour = time.getHours();
					if (time_hour > 12) {
						time_mark = 'PM';
					}

					if (time_hour < 10) {
						time_hour = '0' + time_hour;
					}

					var time_minutes = time.getMinutes();
					if (time_minutes < 10) {
						time_minutes = '0' + time_minutes;
					}
					var time_seconds = time.getSeconds();
					if (time_seconds < 10) {
						time_seconds = '0' + time_seconds;
					}

					var mini_time_html = time_hour + ':' + time_minutes + ' ' + time_mark;

					if (group_id != 0) {
						firebase.firestore().collection("group").doc(group_id).update({
							last_type: file_type,
							last_content: file_name,
							last_time: firebase.firestore.FieldValue.serverTimestamp(),
						});

						firebase.firestore().collection("chat").add({
							group_id: general_group_id,
							type: file_type,
							content: file_name,
							file_content: file_content,
							file_size: file_size,
							created_by: session_user_id,
							created_name: session_user_name,
							created_avatar: session_user_avatar,
							read_by: staff_id,
							read_name: staff_name,
							read_avatar: staff_avatar,
							send_time: firebase.firestore.FieldValue.serverTimestamp(),
							flag: 0,
						})
						.then((ref) => {
							var diffMins = Math.abs(time - global_miletime);
							var diffMins = Math.round(((diffMins % 86400000) % 3600000) / 60000); // minutes

							if (file_type.indexOf('image') === -1) {
								var file_src = 'data:' + file_type + ';base64' + file_content;

								var html_with_time = '<div class="chat-message-wrap">' +
									'<p class="chat-time-text">' + mini_time_html + '</p>' +
									'<div class="chat-file">' +
									'<p class="down_file_name">' + file_name + '<p>' +
									'<p class="down_file_size">' + file_size + '(KB)</p>' +
									'<hr>' +
									'<a href="' + file_src + '" download="' + file_name + '">Download</a>' +
									'</div>' +
									'</div>';

								var html_without_time = '<div class="chat-message-wrap">' +
									'<div class="chat-file">' +
									'<p class="down_file_name">' + file_name + '<p>' +
									'<p class="down_file_size">' + file_size + '(KB)</p>' +
									'<hr>' +
									'<a href="' + file_src + '" download="' + file_name + '">Download</a>' +
									'</div>' +
									'</div>';
							}
							else {
								var img_src = 'data:' + file_type + ';base64' + file_content;

								var html_with_time = '<div class="chat-message-wrap">' +
									'<p class="chat-time-text">' + mini_time_html + '</p>' +
									'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
									'<a href="' + img_src + '" download="' + file_name + '">Download</a>' +
									'</div>' +
									'</div>';

								var html_without_time = '<div class="chat-message-wrap">' +
									'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
									'<a href="' + img_src + '" download="' + file_name + '">Download</a>' +
									'</div>' +
									'</div>';
							}


							if (diffMins != 0) {
								var html = '<div class="chat chat-right">' +
									'<div class="chat-body">' +
									html_with_time +
									'</div>' +
									'</div>';

								$(".chat-wrapper .chat-content").append(html);
								global_miletime = time;
							}
							else {
								if (global_direction == 'r') {
									$(".chat-content .chat-right:last-child .chat-body").append(html_without_time);
								}
								else {
									var html = '<div class="chat chat-right">' +
										'<div class="chat-body">' +
										html_with_time +
										'</div>' +
										'</div>';

									$(".chat-wrapper .chat-content").append(html);
								}
							}

							global_direction = 'r';

							$(".chat-message-send").val("");
							chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
						})
					}
				});
		}
	});
	reader.readAsDataURL(file[0]);
}

function get_new_message() {
	var session_user_id = $("#session_user_id").val();

	firebase.firestore().collection("chat").orderBy('send_time', 'desc').onSnapshot(function (querySnapshot) {

		querySnapshot.forEach(function (doc) {
			console.log('New Message');
			var document = doc.data();

			if (document.read_by == session_user_id && document.flag == '0') {

				var time = new Date(document.send_time.seconds * 1000);
				var time_year = time.getFullYear();
				var time_month = time.getMonth() + 1;
				var time_mark = 'AM';
				if (time_month < 10) {
					time_month = '0' + time_month;
				}
				var time_day = time.getDate();
				if (time_day < 10) {
					time_day = '0' + time_day;
				}

				var time_hour = time.getHours();
				if (time_hour > 12) {
					time_mark = 'PM';
				}

				if (time_hour < 10) {
					time_hour = '0' + time_hour;
				}

				var time_minutes = time.getMinutes();
				if (time_minutes < 10) {
					time_minutes = '0' + time_minutes;
				}

				var time_seconds = time.getSeconds();
				if (time_seconds < 10) {
					time_seconds = '0' + time_seconds;
				}

				var time_html = time_hour + ':' + time_minutes + ' ' + time_mark;

				var diffMins = Math.abs(time - global_miletime);
				var diffMins = Math.round(((diffMins % 86400000) % 3600000) / 60000); // minutes

				var html_flag = 0
				if (diffMins != 0) {
					global_miletime = time;
					html_flag = 1;
				}

				if (document.type != 'text') {
					if (document.type.indexOf('image') === -1) {
						var file_src = 'data:' + document.type + ';base64' + document.file_content;

						var html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + document.content + '<p>' +
							'<p class="down_file_size">' + document.file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + document.content + '">Download</a>' +
							'</div>' +
							'</div>';

						var html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-file">' +
							'<p class="down_file_name">' + document.content + '<p>' +
							'<p class="down_file_size">' + document.file_size + '(KB)</p>' +
							'<hr>' +
							'<a href="' + file_src + '" download="' + document.content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
					else {
						var img_src = 'data:' + document.type + ';base64' + document.file_content;

						var html_with_time = '<div class="chat-message-wrap">' +
							'<p class="chat-time-text">' + time_html + '</p>' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + document.content + '">Download</a>' +
							'</div>' +
							'</div>';

						var html_without_time = '<div class="chat-message-wrap">' +
							'<div class="chat-image" style="background-image: url(' + img_src + ');">' +
							'<a href="' + img_src + '" download="' + document.content + '">Download</a>' +
							'</div>' +
							'</div>';
					}
				}
				else {
					var html_with_time = '<div class="chat-message-wrap">' +
						'<p class="chat-time-text">' + time_html + '</p>' +
						'<p class="chat-text">' + document.content + '</p>' +
						'</div>';

					var html_without_time = '<div class="chat-message-wrap">' +
						'<p class="chat-text">' + document.content + '</p>' +
						'</div>';
				}

				if (html_flag == 0) {
					if (global_direction = 'l') {
						$(".chat-content .chat-left:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-left">' +
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".chat-wrapper .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-left">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".chat-wrapper .chat-content").append(html);
				}

				global_direction = 'l';

				firebase.firestore().collection("chat").doc(doc.id).update({
					flag: 1
				});

				$(".chat-message-send").val("");
				chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
			}
		});
	});
}

$(document).ready(function () {
	"use strict";
	// menu user list perfect scrollbar initialization
	if (chatSidebarListWrapper.length > 0) {
		var menu_user_list = new PerfectScrollbar(".chat-sidebar-list-wrapper");
	}
	// user profile sidebar perfect scrollbar initialization
	if ($(".chat-user-profile-scroll").length > 0) {
		var profile_sidebar_scroll = new PerfectScrollbar(".chat-user-profile-scroll");
	}
	// chat area perfect scrollbar initialization
	if (chatContainer.length > 0) {
		var chat_user_user = new PerfectScrollbar(".chat-container");
	}
	if ($(".chat-profile-content").length > 0) {
		var chat_profile_content = new PerfectScrollbar(".chat-profile-content");
	}

	// user profile sidebar toggle
	chatProfileToggle.on("click", function () {
		chatProfile.addClass("show");
		chatOverlay.addClass("show");
	});
	// on profile close icon click
	chatProfileClose.on("click", function () {
		chatProfile.removeClass("show");
		if (!chatSidebar.hasClass("show")) {
			chatOverlay.removeClass("show");
		}
	});
	// On chat menu sidebar close icon click
	chatSidebarClose.on("click", function () {
		chatSidebar.removeClass("show");
		chatOverlay.removeClass("show");
	});
	// on overlay click
	chatOverlay.on("click", function () {
		chatSidebar.removeClass("show");
		chatOverlay.removeClass("show");
		chatProfile.removeClass("show");
	});
	// Add class active on click of Chat users list
	// $(".chat-sidebar-list-wrapper ul li").on("click", function () {
		
	// 	if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
	// 		$(".chat-sidebar-list-wrapper ul li").removeClass("active");
	// 	}
	// 	$(this).addClass("active");
	// 	if ($(".chat-sidebar-list-wrapper ul li").hasClass("active")) {
	// 		chatStart.addClass("d-none");
	// 		chatArea.removeClass("d-none");
	// 	}
	// 	else {
	// 		chatStart.removeClass("d-none");
	// 		chatArea.addClass("d-none");
	// 	}
	// });
	// app chat favorite star click
	$(".chat-icon-favorite i").on("click", function (e) {
		$(this).parent(".chat-icon-favorite").toggleClass("warning");
		$(this).toggleClass("bxs-star bx-star");
		e.stopPropagation();
	});
	// menu toggle till medium screen
	if ($(window).width() < 992) {
		chatSidebarToggle.on("click", function () {
			chatSidebar.addClass("show");
			chatOverlay.addClass("show");
		});
	}
	// autoscroll to bottom of Chat area
	// $(".chat-sidebar-list li").on("click", function () {
	// 	chatContainer.animate({ scrollTop: chatContainer[0].scrollHeight }, 400)
	// });

	// click on main menu toggle will remove sidebars & overlays
	$(".menu-toggle").click(function () {
		chatSidebar.removeClass("show");
		chatOverlay.removeClass("show");
		chatProfile.removeClass("show");
	});

	// chat search filter
	$("#chat-search").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		if (value != "") {
			$(".chat-sidebar-list-wrapper .chat-sidebar-list li").filter(function () {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		}
		else {
			// if search filter box is empty
			$(".chat-sidebar-list-wrapper .chat-sidebar-list li").show();
		}
	});
	// window resize
	$(window).on("resize", function () {
		// remove show classes from overlay when resize, if size is > 992
		if ($(window).width() > 992) {
			if (chatOverlay.hasClass("show")) {
				chatOverlay.removeClass("show");
			}
		}
		// menu toggle on resize till medium screen
		if ($(window).width() < 992) {
			chatSidebarToggle.on("click", function () {
				chatSidebar.addClass("show");
				chatOverlay.addClass("show");
			});
		}
		// disable on click overlay when resize from medium to large
		if ($(window).width() > 992) {
			chatSidebarToggle.on("click", function () {
				chatOverlay.removeClass("show");
			});
		}
	});

	/////////

	get_all_contacts();
	get_new_message();
});