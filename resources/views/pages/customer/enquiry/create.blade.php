@extends('layouts.customer')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">

<style>
@media (min-width: 992px){
    .modal-lg, .modal-xl {
        max-width: 1810px;
    }
}

.mr-2, .mx-2 {
    margin-right: 0.5rem !important;
}

label{
    font-size: 0.86rem;
}

.pac-container {
    background-color: #FFF;
    z-index: 20;
    position: fixed;
    display: inline-block;
    float: left;
}

.modal{
    z-index: 20;
    top: 5%;   
}

.modal-backdrop{
    z-index: 10;        
}

.nounderline, .violet{
    color: #7c4dff !important;
}
.btn-dark {
    background-color: #7c4dff !important;
    border-color: #7c4dff !important;
}
.btn-dark .file-upload {
    padding: 10px 0px;
    position: absolute;
    left: 0;
    opacity: 0;
    cursor: pointer;
}
.profile-img img{
    width: 200px;
    height: 200px;
    border-radius: 50%;
}   
</style>

@endsection

@section('content')
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    <form class="form-horizontal" method="post" action="{{route('customer.enquiry.add')}}" novalidate>
        @csrf
        <div class="card">
            <div class="card-header" style="padding-bottom: 0;">
                <h3>Create an Enquiry</h3>
            </div>
            <hr>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Title</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <div class="controls">
                                    <input type="text" class="form-control" id="title" name="title" required
                                        placeholder="Enquery Title" data-validation-required-message="This title field is required">
                                </div>
                                <div class="form-control-position">
                                    <i class="bx bx-purchase-tag-alt"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <div style="display: flex;">
                                <h6>Staff Name</h6>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <select class="select2 form-control" id="staff_id" name="staff_id" required data-validation-required-message="This title field is required">
                                        <option value="">--- Please select staff ---</option>
                                        @foreach($staff as $each_one)
                                        <option value="{{$each_one->id}}">
                                            {{$each_one->first_name.' '.$each_one->last_name}}, {{$each_one->main_email}}, {{$each_one->get_account_type->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Pick up duration</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control showdropdowns" placeholder="Select Date" id="duration" name="duration" value="">
                                <div class="form-control-position">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </fieldset>
                            <p id="num_days">Total days: 0 day</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Budget</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mt-1 mb-1 mr-2">
                                    <fieldset>
                                    <div class="radio radio-shadow">
                                        <input type="radio" id="radioshadow1" name="budget_per_total" value="per_person" checked>
                                        <label for="radioshadow1" style="font-size: 12px;">Per Person</label>
                                    </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mb-1 mt-1 mr-2">
                                    <fieldset>
                                    <div class="radio radio-shadow">
                                        <input type="radio" id="radioshadow2" value="total" name="budget_per_total">
                                        <label for="radioshadow2" style="font-size: 12px;">Total</label>
                                    </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Price(£):</h6>
                                <input type="text" class="form-control" min="0" step="1" value="0" id="budget" name="budget">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Adults Number:</h6>
                                <input type="number" class="form-control" min="0" step="1" value="0" id="adults_num" name="adults_num">
                            </div>
                            <p id="traveler_total">Total travelers: 0</p>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Children Number:</h6>
                                <input type="number" class="form-control" min="0" step="1" value="0" id="children_num" name="children_num">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Single Rooms:</h6>
                                <div class="d-inline-block mb-1 mr-1" style="width:100%">
                                    <input type="number" class="form-control" min="0" step="1" value="0" id="single_room" name="single_room">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Double Rooms:</h6>
                                <div class="d-inline-block mb-1 mr-1" style="width:100%">
                                    <input type="number" class="form-control" min="0" step="1" value="0" id="double_room" name="double_room">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Twin Rooms:</h6>
                                <div class="d-inline-block mb-1 mr-1" style="width:100%">
                                    <input type="number" class="form-control" min="0" step="1" value="0" id="twin_room" name="twin_room">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Triple Rooms:</h6>
                                <div class="d-inline-block mb-1 mr-1" style="width:100%">
                                    <input type="number" class="form-control" min="0" step="1" value="0" id="triple_room" name="triple_room">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <h6>Family Rooms:</h6>
                                <div class="d-inline-block mb-1 mr-1" style="width:100%">
                                    <input type="number" class="form-control" min="0" step="1" value="0" id="family_room" name="family_room">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h6 id="number_rooms">Numbers of rooms: 0</h6>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6>Note:</h6>
                                <textarea class="form-control" id="horizontalTextarea" rows="5" placeholder="Please type note about enquiry." name="note"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end mb-3">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">Create</button>
                            <a href="{{ route('customer.enquiry') }}" class="btn btn-light mr-1 mb-1">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
  </section>
  <!-- Dashboard Analytics end -->
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
@endsection

@section('page-scripts')
<script>
    var base_url = "{{ url('/customer') }}";
</script>
<script src="{{asset('js/scripts/pages/customer/enquiry.js')}}"></script>
@endsection
