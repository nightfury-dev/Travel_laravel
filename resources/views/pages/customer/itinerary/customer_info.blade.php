@extends('layouts.customer')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css"
    href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">

@endsection

@section('page-styles')

@endsection

@section('content')
<section>
    <div class="sub-container">
        <div class="card">
            <div class="card-header">
                <h5>Send Customer Information</h5>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" method="post" action="{{ route('customer.itinerary.send_customer_info') }}">
                        @csrf
                        <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary_id }}">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea id="customer_information" name="customer_information" cols="30" rows="30"
                                            class="form-control" required
                                            data-validation-required-message="This field is required">{{ $customer_information? $customer_information->customer_infomation: '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Send</button>
                                    <a href="{{ route('customer.itinerary') }}"
                                        class="btn btn-light-secondary mr-1 mb-1">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('vendor-scripts')

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var base_url = "{{ url('/customer/itinerary') }}";
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>

<script src="{{asset('js/scripts/pages/customer/itinerary.js')}}"></script>
@endsection