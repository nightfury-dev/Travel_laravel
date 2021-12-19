$(document).ready(function () {
	if ($(".pickadate").length) {
		$(".pickadate").pickadate({
			format: "mm/dd/yyyy"
		});
	}

	var price = $("#price").val();
	price = parseInt(price);

	var tax = $("#tax").val();
	tax = parseFloat(tax);

	var amount = price + tax;

	$("#tax_description").text(tax);
	$("#amount_description").text(amount);
});
