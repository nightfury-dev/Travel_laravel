$(document).ready(function () {
	$(".current").find(".step-icon").addClass("bx bx-time-five");
	$(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
		strokeColor: '#5A8DEE'
	});
	CKEDITOR.replace("note");
	CKEDITOR.replace("note1");

	$('.showdropdowns').daterangepicker({
		//autoUpdateInput: false,
		locale: {
			cancelLabel: 'Clear'
		}
	});

	$('.single-daterange').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		minYear: 1901,
		locale: {
		  format: 'DD-MM-YYYY'
		},
		maxYear: parseInt(moment().format('YYYY'),10)
	}, function(start, end, label) {
		var years = moment().diff(start, 'years');
		//alert("You are " + years + " years old!");
	});

	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

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
		$('#days_list').empty();
		var str = "";
		for (var i = 0; i < days; i++) {
	
			var year = start_date.getFullYear();
			var month = start_date.getMonth() + 1;
			var day = start_date.getDate();
			if (i == 0)
				str += "<li class='days-list-each' id='list_" + i + "' active>" + year + '/' + month + '/' + day + "</li>";
			else
				str += "<li class='days-list-each' id='list_" + i + "'>" + year + '/' + month + '/' + day + "</li>";
			str += "hr";
			start_date.setDate(start_date.getDate() + 1);
		}
		$('#days_list').append(str);
	});

	$("#itinerary_basic_form").submit(function (e) {

		e.preventDefault(); // avoid to execute the actual submit of the form.

		var form = $(this);
		var url = form.attr('action');
		// var it_id = $('#itinerary_id').val();

		var form_data = new FormData(document.getElementById("itinerary_basic_form"));


		var note_value = CKEDITOR.instances.note1.getData();
		form_data.append('note', note_value);


		$.ajax({
			type: "POST",
			url: url,
			dataType: "JSON",
			contentType: false,
			processData: false,
			enctype: 'multipart/form-data',
			data: form_data, // serializes the form's elements.
			success: function (data) {
				$('#itinerary_id').val(data);
				location.reload();
			}
		});
	});
});