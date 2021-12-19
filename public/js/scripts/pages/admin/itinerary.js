function itinerary_del(val) {
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
				url: base_url + '/del_itinerary',
				method: 'post',
				data: {
					_token: $("[name='_token']").val(),
					itinerary_id: val,
				},
				success: function (data) {
					if (data == "success") {
						Swal.fire({
							type: "success",
							title: 'Deleted!',
							text: 'Selected itinerary has been deleted.',
							confirmButtonClass: 'btn btn-success',
						}).then(function (result) {
							if (result.value) {
								location.reload();
							}
						});
					}
					else if (data == 'approved') {
						toastr.warning("This itinerary is already approved. you can't delete this itinerary.", 'warning', {'closeButton': true});
					}
				}
			});
		}
		else if (result.dismiss === Swal.DismissReason.cancel) {
		}
	});
}

$(document).ready(function () {

	if ($("#itinerary_table").length) {
		var itineraryListView = $("#itinerary_table").DataTable({
			columnDefs: [{
				targets: 0,
				className: "control"
			}],
			dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
			language: {
				search: "",
				searchPlaceholder: "Search Itinerary"
			},
			responsive: {
				details: {
					type: "column",
					target: 0
				}
			}
		});
	}
});