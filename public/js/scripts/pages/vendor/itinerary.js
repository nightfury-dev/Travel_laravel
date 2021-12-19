$(document).ready(function () {
	if ($("#task_table").length) {
		var taskListView = $("#task_table").DataTable({
			columnDefs: [
				{
					targets: 0,
					className: "control"
				},
				{
					orderable: true,
					targets: 1,
					// checkboxes: { selectRow: true }
				},
				{
					targets: [0, 1],
					orderable: false
				},
			],
			order: [2, 'asc'],
			dom:
				'<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
			language: {
				search: "",
				searchPlaceholder: "Search Task"
			},
			select: {
				style: "multi",
				selector: "td:first-child",
				items: "row"
			},
			responsive: {
				details: {
					type: "column",
					target: 0
				}
			}
		});
	}
	$(".current").find(".step-icon").addClass("bx bx-time-five");
	$(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
		strokeColor: '#5A8DEE'
	});

	if ($("#task_detail_table").length) {
		var taskDetailView = $("#task_detail_table").DataTable({
			columnDefs: [
				{
					targets: 0,
					className: "control"
				},
				{
					orderable: true,
					targets: 1,
					// checkboxes: { selectRow: true }
				},
				{
					targets: [0, 1],
					orderable: false
				},
			],
			order: [2, 'asc'],
			dom:
				'<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"p>',
			language: {
				search: "",
				searchPlaceholder: "Search Task Detail"
			},
			select: {
				style: "multi",
				selector: "td:first-child",
				items: "row"
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
