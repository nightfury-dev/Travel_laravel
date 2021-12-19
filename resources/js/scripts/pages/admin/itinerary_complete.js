$(document).ready(function () {

  $("#save_btn").hide();
  $("#margin_price_container").hide()
    
  $("#currency_converter").on('click', function() {

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

        var currency = $("#currency").val();
        $.ajax({
          url: base_url + '/currency_converter',
          type: 'POST',
          data: {
            _token: $("[name='_token']").val(),
            currency: currency,
            budget: budget
          },
          dataType: 'json',
          success: function(response) {
            var flag = response.flag;
            if(flag == 'success') {
              var total_budget = response.total_budget;

              $("#budget_div").empty();
              var html = '<h6>Budget</h6>'+
                '<div class="form-group">'+
                  '<input type="text" class="form-control" id="total_budget" name="total_budget" value="'+ total_budget +'" required  data-validation-required-message="This field is required">'+
                '</div>';

              $("#currency").prop( "readonly", true );
              $("#save_btn").show();
              $("#margin_price").val(0);
              $("#margin_price_container").show();
              $("#budget_div").html(html);
            }
          }
        })
      }
    })
    
  });
});


