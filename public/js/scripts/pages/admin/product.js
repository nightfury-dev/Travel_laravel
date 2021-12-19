
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

function search_product(checked_items, search_string) {
	var checkedID = new Array();
	for (var i = 0; i < checked_items.length; i++) {
		checkedID[i] = checked_items[i].checkID;
	}

	$.ajax({
		url: base_url + '/product',
		type: 'get',
		data: {
			_token: $("[name='_token']").val(),
			search_string: search_string,
			search_items: checkedID
		},
		success: function (response) {
			$("#product_list").empty();

			var spinner_html = '<div class="text-center">' +
				'<div class="spinner-border spinner-border-lg" role="status">' +
				'<span class="sr-only">Loading...</span>' +
				'</div>' +
				'</div>';

			$("#product_list").html(spinner_html);
			$(".app-file-overlay").addClass('show');

			var spinner = setInterval(function () {
				$(".app-file-overlay").removeClass('show');
				$("#product_list").empty();
				$("#product_list").html(response);

				clearInterval(spinner);
			}, 500);
		}
	})
}

let map;
let marker;

function addMarker(location) {
	marker = new google.maps.Marker({
		position: location,
		map: map,
	});
}

function get_detail(product_id) {
	$.ajax({
		url: base_url + '/product/detail',
		type: 'post',
		data: {
			_token: $("[name='_token']").val(),
			product_id: product_id,
		},
		success: function (response) {
			sideBarInfo.html(response);

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

			var spinner = setInterval(function () {
				sideBarInfo.addClass('show');
				appContentOverlay.addClass('show');
				clearInterval(spinner);
			}, 100);

		}
	})
}

function close_preview() {
	$(".app-file-sidebar-info").removeClass('show');
	appContentOverlay.removeClass('show');
}

function product_delete(obj) {
	var href = $(obj).data('href');
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
				url: href,
				method: 'get',
				dataType: 'json',
				success: function (data) {
					if (data.msg == 'success') {
						location.reload();
					}
            	}
			});
		}
	});
}

function show_alert() {
	var alert = $("#alert").val();
	if (alert != '' && alert != undefined) {
		toastr.success(alert, 'Success', { "closeButton": true });
	}
}


jQuery(document).ready(function () {

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
	// $('.app-file-info').on('click', function () {
	//   sideBarInfo.addClass('show');
	//   appContentOverlay.addClass('show');
	// });

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


	//////// My Code

	show_alert();

	$('#category-tree').treeview({
		data: tree_data,
		color: [$primary],
		backColor: [$primary_light],
		showBorder: false,
		selectedColor: [$primary],
		selectedBackColor: [$transparent],
		showCheckbox: true,
		uncheckedIcon: "bx bx-square"
	});

	$('#category-tree').treeview('collapseAll');

	$("#category-tree").on('nodeChecked', function (event, node) {

		if (typeof node['nodes'] != "undefined") {
			var children = node['nodes'];
			for (var i = 0; i < children.length; i++) {
				$("#category-tree").treeview('checkNode', [children[i].nodeId, { silent: true }]);
			}
		}

		var checked_items = $("#category-tree").treeview('getChecked');
		var search_string = $("#product_search").val();
		search_product(checked_items, search_string);
	});

	$("#category-tree").on('nodeUnchecked', function (event, node) {

		if (typeof node['nodes'] != "undefined") {
			var children = node['nodes'];
			for (var i = 0; i < children.length; i++) {
				$("#category-tree").treeview('uncheckNode', [children[i].nodeId, { silent: true }]);
			}
		}

		var checked_items = $("#category-tree").treeview('getChecked');
		var search_string = $("#product_search").val();
		search_product(checked_items, search_string);
	});

	$("#all_product").on('click', function () {
		var checked_items = $("#category-tree").treeview('getChecked');
		var search_string = $("#product_search").val();
		search_product(checked_items, search_string);
	});

	$("#my_product").on('click', function () {
		var checked_items = $("#category-tree").treeview('getChecked');
		var search_string = $("#product_search").val();
		search_product(checked_items, search_string);
	});

	$("#product_search").on('change', function () {
		var checked_items = $("#category-tree").treeview('getChecked');
		var search_string = $("#product_search").val();
		search_product(checked_items, search_string);
	})


});
