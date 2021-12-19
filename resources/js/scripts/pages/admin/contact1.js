$(document).ready(function () {
	$(".current").find(".step-icon").addClass("bx bx-time-five");
	$(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
		strokeColor: '#5A8DEE'
	});

	CKEDITOR.replace("note1");
	
	if($("#task_type").val() == 1) {
		CKEDITOR.replace("call");
	}

	$(".touchspin").TouchSpin({
		buttondown_class: "btn btn-primary",
		buttonup_class: "btn btn-primary",
	});

	$('.showdropdowns').daterangepicker({
		showDropdowns: true,
		drops: "up"
	});

	// Earnings Swiper
	// ---------------
	var swiperLength = $(".swiper-slide").length;

	// Swiper js for this page
	var mySwiper = new Swiper('.widget-earnings-swiper', {
		slidesPerView: 'auto',
		centeredSlides: true,
		spaceBetween: 30,
		slideToClickedSlide: true,

		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		}
	});

	activeSlide(0);
	get_new_message();

	// Active slide change on swipe
	mySwiper.on('slideChange', function () {
		activeSlide(mySwiper.realIndex);
	});

	//add class active content of active slide
	function activeSlide(index) {
		var session_user_id = $("#session_user_id").val();

		var slideEl = mySwiper.slides[index]
		var confirm_id = $(slideEl).attr('id');

		$(".wrapper-content").removeClass("active");
		$("[data-earnings=" + confirm_id + "]").addClass('active')

		var task_id = $(slideEl).data('task-id');
		var supplier_id = $(slideEl).data('user-id');
		var supplier_name = $(slideEl).data('user-name');
		var supplier_avatar = $(slideEl).data('user-avatar');

		confirm_id = confirm_id.split('_');
		confirm_id = confirm_id[1];

		$("#confirm_id").val(confirm_id);
		$("#task_id").val(task_id);
		$("#supplier_id").val(supplier_id);
		$("#supplier_name").val(supplier_name);
		$("#supplier_avatar").val(supplier_avatar);

		$("#chat_supplier_name").text(supplier_name);
		if (supplier_avatar == '') {
			supplier_avatar = 'images/img/avatar.png';
			$("#chat_supplier_avatar").attr('src', base_path_url + supplier_avatar);
		}
		else {
			$("#chat_supplier_avatar").attr('src', base_path_url + 'storage/' + supplier_avatar);
		}

		var general_group_id = generate_group_id(session_user_id, supplier_id, task_id, confirm_id);
		var task_type = $("#task_type").val();
		if(task_type == 1) {
			call_details(general_group_id);
		}
		else if(task_type == 2) {
			chat_details(general_group_id);
		}
 		else {
			
		}
	};

	// Perfect Scrollbar
	//------------------
	// Widget - User Details -Perfect Scrollbar X
	if ($('.widget-user-details .table-responsive').length > 0) {
		var user_details = new PerfectScrollbar('.widget-user-details .table-responsive');
	}

	// Widget - Card Overlay - Perfect Scrollbar X - on initial level
	if ($('.widget-overlay-content .table-responsive').length > 0) {
		var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
	}

	// Widget - Card Overlay - Perfect Scrollbar X - on active tab-pane
	$('.widget-overlay-content a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var card_overlay = new PerfectScrollbar('.widget-overlay-content .tab-pane.active .table-responsive');
	})

	// Widget - timeline perfect scrollbar initialization
	if ($(".widget-timeline").length > 0) {
		var widget_chat_scroll = new PerfectScrollbar(".widget-timeline", { wheelPropagation: false });
	}
	// Widget - chat area perfect scrollbar initialization
	if ($(".widget-chat-scroll").length > 0) {
		var widget_chat_scroll = new PerfectScrollbar(".widget-chat-scroll", { wheelPropagation: false });
	}
	// Widget - earnings perfect scrollbar initialization
	if ($(".widget-earnings-scroll").length > 0) {
		var widget_earnings = new PerfectScrollbar(".widget-earnings-scroll",
			// horizontal scroll with mouse wheel
			{
				suppressScrollY: true,
				useBothWheelAxes: true
			});
	}
	// Widget - chat autoscroll to bottom of Chat area on page initialization
	$(".widget-chat-scroll").animate({ scrollTop: $(".widget-chat-scroll")[0].scrollHeight }, 800);
});

function confirm_check(id) {
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
				url: base_url + '/save_status',
				type: 'post',
				data: {
					_token: $("[name='_token']").val(),
					confirm_id: id
				},
				success: function (response) {
					if (response == 'success') {
						location.reload();
					}
				}
			})
		}
	})
}

function generate_group_id(user_id1, user_id2, task_id, confirm_id) {
	if (parseInt(user_id1) > parseInt(user_id2)) {
		return 'group_' + user_id1 + '_' + user_id2 + '_' + task_id + '_' + confirm_id;
	}
	else {
		return 'group_' + user_id2 + '_' + user_id1 + '_' + task_id + '_' + confirm_id;
	}
}

var global_time;
var global_miletime;
var global_direction;

function chat_details(group_id) {

	var session_user_id = $("#session_user_id").val();
	var all_chart_data = [];

	$(".widget-chat-messages .chat-content").empty();

	firebase.firestore().collection("chat").where('group_id', '==', group_id).get().then(function(querySnapshot) {
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
				$(".widget-chat-messages .chat-content").append(divider_html);
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
						$(".widget-chat-messages .chat-right:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-right">' +
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".widget-chat-messages .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-right">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".widget-chat-messages .chat-content").append(html);
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
						$(".widget-chat-messages .chat-left:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-left">' +
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".widget-chat-messages .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-left">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".widget-chat-messages .chat-content").append(html);
				}

				global_direction = 'l';
			}
		}

		$(".widget-chat-message").val("");
		$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
	});
}

function call_details(group_id) {
	firebase.firestore().collection("call").where('group_id', '==', group_id).get().then(function (querySnapshot) {
		querySnapshot.forEach(function (doc) {
			var document = doc.data();
			var call_content = document.last_content;
			CKEDITOR.instances['call'].setData(call_content);
		});
	});
}

function widgetMessageSend(source) {
	var session_user_id = $("#session_user_id").val();
	var session_user_name = $("#session_user_name").val();
	var session_user_avatar = $("#session_user_avatar").val();

	var supplier_id = $("#supplier_id").val();
	var supplier_name = $("#supplier_name").val();
	var supplier_avatar = $("#supplier_avatar").val();

	var confirm_id = $("#confirm_id").val();
	var task_id = $("#task_id").val();

	var group_id = '';
	var general_group_id = generate_group_id(session_user_id, supplier_id, task_id, confirm_id)

	var message = $(".widget-chat-message").val();

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

			if (group_id != 0) {
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
					read_by: supplier_id,
					read_name: supplier_name,
					read_avatar: supplier_avatar,
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

							$(".widget-chat-messages .chat-content").append(html);
							global_miletime = time;
						}
						else {
							if (global_direction == 'r') {
								var html = '<div class="chat-message-wrap">' +
									'<p class="chat-text">' + message + '</p>' +
									'</div>';

								$(".widget-chat-messages .chat-right:last-child .chat-body").append(html);
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

								$(".widget-chat-messages .chat-content").append(html);
							}
						}

						global_direction = 'r';

						$(".widget-chat-message").val("");
						$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
					})
			}
			else {
				firebase.firestore().collection("group")
					.add({
						group_id: general_group_id,
						members: [session_user_id, supplier_id],
						from_id: session_user_id,
						from_user_name: session_user_name,
						from_user_avatar: session_user_avatar,
						to_id: supplier_id,
						to_user_name: supplier_name,
						to_user_avatar: supplier_avatar,
						last_type: 'text',
						last_content: message,
						last_time: firebase.firestore.FieldValue.serverTimestamp()
					})
					.then((ref) => {
						console.log("Added doc with ID: ", ref.id);

						firebase.firestore().collection('chat').add({
							group_id: general_group_id,
							type: 'text',
							content: message,
							created_by: session_user_id,
							created_name: session_user_name,
							created_avatar: session_user_avatar,
							read_by: supplier_id,
							read_name: supplier_name,
							read_avatar: supplier_avatar,
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

									$(".widget-chat-messages .chat-content").append(html);

									global_miletime = time;
								}
								else {
									if (global_direction == 'r') {
										var html = '<div class="chat-message-wrap">' +
											'<p class="chat-text">' + message + '</p>' +
											'</div>';

										$(".widget-chat-messages .chat-right:last-child .chat-body").append(html);
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

										$(".widget-chat-messages .chat-content").append(html);
									}
								}

								global_direction = 'r';

								$(".widget-chat-message").val("");
								$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
							});
					});
			}
		});
	}
}

function savecallcontent() {
	var session_user_id = $("#session_user_id").val();
	var session_user_name = $("#session_user_name").val();
	var session_user_avatar = $("#session_user_avatar").val();

	var supplier_id = $("#supplier_id").val();
	var supplier_name = $("#supplier_name").val();
	var supplier_avatar = $("#supplier_avatar").val();

	var confirm_id = $("#confirm_id").val();
	var task_id = $("#task_id").val();

	var group_id = 0;
	var general_group_id = generate_group_id(session_user_id, supplier_id, task_id, confirm_id)

	var call_content = CKEDITOR.instances['call'].getData();
	if(call_content) {
		firebase.firestore().collection('call').where('group_id', '==', general_group_id).get().then(function (querySnapshot) {
			querySnapshot.forEach(function (doc) {
				group_id = doc.id;
				return;
			});

			if (group_id != 0) {
				firebase.firestore().collection("call").doc(group_id).update({
					last_content: call_content,
					last_time: firebase.firestore.FieldValue.serverTimestamp(),
				});

				toastr.success('Update Success!', 'success', {'closeButton': true, timeOut: 2000});
			}
			else {
				firebase.firestore().collection("call")
					.add({
						group_id: general_group_id,
						members: [session_user_id, supplier_id],
						from_id: session_user_id,
						from_user_name: session_user_name,
						from_user_avatar: session_user_avatar,
						to_id: supplier_id,
						to_user_name: supplier_name,
						to_user_avatar: supplier_avatar,
						last_type: 'text',
						last_content: call_content,
						last_time: firebase.firestore.FieldValue.serverTimestamp()
					})
					.then((ref) => {
						toastr.warning('Add Success!', 'success', {'closeButton': true, timeOut: 2000});
					});
			}
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

		var supplier_id = $("#supplier_id").val();
		var supplier_name = $("#supplier_name").val();
		var supplier_avatar = $("#supplier_avatar").val();

		var confirm_id = $("#confirm_id").val();
		var task_id = $("#task_id").val();

		var group_id = '';
		var general_group_id = generate_group_id(session_user_id, supplier_id, task_id, confirm_id);

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
							read_by: supplier_id,
							read_name: supplier_name,
							read_avatar: supplier_avatar,
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

									$(".widget-chat-messages .chat-content").append(html);
									global_miletime = time;
								}
								else {
									if (global_direction == 'r') {
										$(".widget-chat-messages .chat-right:last-child .chat-body").append(html_without_time);
									}
									else {
										var html = '<div class="chat chat-right">' +
											'<div class="chat-body">' +
											html_with_time +
											'</div>' +
											'</div>';

										$(".widget-chat-messages .chat-content").append(html);
									}
								}

								global_direction = 'r';


								$(".widget-chat-message").val("");
								$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
							})
					}
					else {
						firebase.firestore().collection("group")
							.add({
								group_id: general_group_id,
								members: [session_user_id, to_user_id],
								from_id: session_user_id,
								from_user_name: session_user_name,
								from_user_avatar: session_user_avatar,
								to_id: supplier_id,
								to_user_name: supplier_name,
								to_user_avatar: supplier_avatar,
								last_type: file_type,
								last_content: file_name,
								last_time: firebase.firestore.FieldValue.serverTimestamp(),
							})
							.then((ref) => {

								firebase.firestore().collection('chat').add({
									group_id: general_group_id,
									type: file_type,
									content: file_name,
									file_content: file_content,
									file_size: file_size,
									created_by: session_user_id,
									created_name: session_user_name,
									created_avatar: session_user_avatar,
									read_by: supplier_id,
									read_name: supplier_name,
									read_avatar: supplier_avatar,
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

											$(".widget-chat-messages .chat-content").append(html);
											global_miletime = time;
										}
										else {
											if (global_direction == 'r') {
												$(".widget-chat-messages .chat-right:last-child .chat-body").append(html_without_time);
											}
											else {
												var html = '<div class="chat chat-right">' +
													'<div class="chat-body">' +
													html_with_time +
													'</div>' +
													'</div>';

												$(".widget-chat-messages .chat-content").append(html);
											}
										}

										global_direction = 'r';

										$(".widget-chat-message").val("");
										$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
									});
							});
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
						$(".widget-chat-messages .chat-left:last-child .chat-body").append(html_without_time);
					}
					else {
						var html = '<div class="chat chat-left">' +
							'<div class="chat-body">' +
							html_with_time +
							'</div>' +
							'</div>';

						$(".widget-chat-messages .chat-content").append(html);
					}
				}
				else {
					var html = '<div class="chat chat-left">' +
						'<div class="chat-body">' +
						html_with_time +
						'</div>' +
						'</div>';

					$(".widget-chat-messages .chat-content").append(html);
				}

				global_direction = 'l';

				firebase.firestore().collection("chat").doc(doc.id).update({
					flag: 1
				});

				$(".widget-chat-message").val("");
				$(".widget-chat-scroll").scrollTop($(".widget-chat-scroll > .chat-content").height());
			}
		});
	});
}