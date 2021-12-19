@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/animate/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/wizard.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/itinerary.css')}}">
<style>
@media (min-width: 992px) {
    .modal-lg,
    .modal-xl {
        max-width: 1410px;
    }
}

.mr-2,
.mx-2 {
    margin-right: 0.5rem !important;
}

label {
    font-size: 0.86rem;
}

.daily-products-left {
    align-items: center;
}
</style>

@endsection

@section('content')
<input type="hidden" name="daily_id" id="daily_id">

<div class="modal fade text-left" id="task_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Task Details For Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card widget-state-multi-radial">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 d-flex flex-column justify-content-around">
                                    <div class="tab-content">
                                        <div class="row mb-1">
                                            <input type="hidden" id="task_id" name="task_id" value="">
                                            <div class="col-md-2">
                                                <label>Task Name</label>
                                            </div>
                                            <div class="col-md-10 form-group" style="padding-left: 70px;">
                                                <input type="text" id="task_name" name="task_name" class="form-control"
                                                    placeholder="Task Name" required>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Start Date & Time</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div
                                                            class="form-group position-relative has-icon-left w-75 ml-auto">
                                                            <input type="text" class="form-control single-daterange"
                                                                id="from_date" name="from_date">
                                                            <div class="form-control-position">
                                                                <i class='bx bx-calendar'></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <fieldset class="form-group position-relative has-icon-left">
                                                            <input type="text" class="form-control"
                                                                placeholder="Start Time" id="start_time"
                                                                name="start_time" required>
                                                            <div class="form-control-position">
                                                                <i class='bx bx-history'></i>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>End Date & Time</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div
                                                            class="form-group position-relative has-icon-left w-75 ml-auto">
                                                            <input type="text" class="form-control single-daterange"
                                                                id="end_date" name="end_date">
                                                            <div class="form-control-position">
                                                                <i class='bx bx-calendar'></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <fieldset class="form-group position-relative has-icon-left">
                                                            <input type="text" class="form-control"
                                                                placeholder="End Time" id="end_time" name="end_time"
                                                                required>
                                                            <div class="form-control-position">
                                                                <i class='bx bx-history'></i>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Priority</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-primary radio-glow">
                                                                        <input type="radio" id="radioPriority1"
                                                                            name="radioPriority" checked value="1">
                                                                        <label for="radioPriority1">HIGH</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-secondary radio-glow">
                                                                        <input type="radio" id="radioPriority2"
                                                                            name="radioPriority" value="2">
                                                                        <label for="radioPriority2">MEDIUM</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-success radio-glow">
                                                                        <input type="radio" id="radioPriority3"
                                                                            name="radioPriority" value="3">
                                                                        <label for="radioPriority3">LOW</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Status</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-primary radio-glow">
                                                                        <input type="radio" id="radioStatus1"
                                                                            name="radioStatus" checked value="1">
                                                                        <label for="radioStatus1">Pending</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-secondary radio-glow">
                                                                        <input type="radio" id="radioStatus2"
                                                                            name="radioStatus" value="2">
                                                                        <label for="radioStatus2">Completed</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio radio-success radio-glow">
                                                                        <input type="radio" id="radioStatus3"
                                                                            name="radioStatus" value="-1">
                                                                        <label for="radioStatus3">Closed</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Assign By</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <input type="text" disabled id="assign_by"
                                                                    name="assign_by" class="form-control">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Assign To</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <select class="select2 form-control" id="assign_to"
                                                            name="assign_to" required
                                                            data-validation-required-message="This title field is required">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label>Linked To</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" readonly id="service_name"
                                                            name="service_name" class="form-control" value="Quotation">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" readonly id="itinerary_ref_num"
                                                            name="itinerary_ref_num" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Description</label>
                                                    </div>
                                                    <div class="col-md-10" style="padding-left: 65px;">
                                                        <textarea class="form-control" rows="6"
                                                            placeholder="Please type note about enquiry." id="note"
                                                            name="note" required>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onClick="save_task()" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Save</span>
                </button>
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>

            </div>
        </div>
    </div>
</div>

<section id="basic-tabs-components">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-md-6 col-sm-12">
                <h5 class="content-header-title float-left pr-1 mb-0">Travel Quoting</h5>
                <div class="breadcrumb-wrapper col-12">
                    <?php $breadcrumbs1 = [
						["link" => "/", "name" => "Home"], ["name" => "Itinerary"],
					];?>
                    <ol class="breadcrumb p-0 mb-0">
                        @isset($breadcrumbs1)
                            @foreach ($breadcrumbs1 as $breadcrumb)
                            <li class="breadcrumb-item {{ !isset($breadcrumb['link']) ? 'active' :''}}">
                                @if(isset($breadcrumb['link']))
                                <a href="{{asset($breadcrumb['link'])}}">@if($breadcrumb['name'] == "Home")<i
                                        class="bx bx-home-alt"></i>@else{{$breadcrumb['name']}}@endif</a>
                                @else{{$breadcrumb['name']}}@endif
                            </li>
                            @endforeach
                        @endisset
                    </ol>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <ul class="nav nav-tabs float-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link btn-primary glow white" id="account-tab" href="{{ route('admin.itinerary') }}">
                            <i class="bx bx-left-arrow align-middle"></i>
                            <span class="align-middle">Back</span>
                        </a>    
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-primary glow white" id="account-tab" href="{{ route('admin.task', ['itinerary_id' => $itinerary->id, 'type' => $type]) }}">
                            <i class="bx bx-right-arrow align-middle"></i>
                            <span class="align-middle">All Tasks</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Analytics Start -->
<section>
    <div class="collapsible collapse-icon accordion-icon-rotate">
        <div class="card collapse-header">
            <div id="headingCollapse6" class="card-header" data-toggle="collapse" role="button" data-target="#collapse6"
                aria-expanded="false" aria-controls="collapse6">
                <span class="collapse-title">
                    <i class='bx bx-help-circle align-middle'></i>
                    <span class="align-middle">
                        Itinerary Information | Ref.No:
                        <?php
							$str = "I";
							for ($i = 1; $i < strlen($enquiry->reference_number); $i++) {
								$str[$i] = $enquiry->reference_number[$i];
							}
							echo $str;
						?>
                    </span>
                </span>
            </div>

            <div id="collapse6" role="tabpanel" aria-labelledby="headingCollapse6" class="collapse">
                <div class="card-content">
                    <div class="card-body">

                        <form class="form-horizontal" method="post"
                            action="{{route('admin.itinerary.save_basic_info')}}" novalidate id="itinerary_basic_form">
                            @csrf
                            <input type="hidden" name="enquiry_id" id="enquiry_id" value="{{ $itinerary->enquiry_id }}">

                            <input type="hidden" name="itinerary_id" id="itinerary_id" value="{{ $itinerary->id }}">

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <h6>Itinerary Title</h6>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <div class="controls">
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="{{ $itinerary->title }}" placeholder="Itinerary Title" required
                                                data-validation-required-message="This Title field is required"
                                                aria-invalid="false">
                                        </div>
                                        <div class="form-control-position">
                                            <i class="bx bx-purchase-tag-alt"></i>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <h6>Pick up duration</h6>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        @php
                                        $from_date = $itinerary->from_date;
                                        $from_date= $from_date;
                                        $from_date = date_format($from_date,"m/d/Y");

                                        $to_date = $itinerary->to_date;
                                        $to_date = $to_date;
                                        $to_date = date_format($to_date,"m/d/Y");

                                        $duration = $from_date . ' - ' . $to_date;
                                        @endphp
                                        <input type="text" class="form-control showdropdowns"
                                            placeholder="Select duration" id="duration" name="duration"
                                            value="{{ $duration }}" required
                                            data-validation-required-message="This Duration field is required"
                                            aria-invalid="false">
                                        <div class="form-control-position">
                                            <i class='bx bx-calendar-check'></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Adults Number:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->adult_number }}"
                                            id="adults_num" name="adults_num" required
                                            data-validation-required-message="This Title field is required"
                                            aria-invalid="false">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Children Number:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control"
                                            value="{{ $itinerary->children_number}}" id="children_num"
                                            name="children_num">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <h6>Single Rooms:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->single_count }}"
                                            id="single_room" name="single_room">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Double Rooms:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->double_count }}"
                                            id="double_room" name="double_room">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Twin Rooms:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->twin_count }}"
                                            id="twin_room" name="twin_room">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Triple Rooms:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->triple_count }}"
                                            id="triple_room" name="triple_room">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Family Rooms:</h6>
                                    <div class="d-inline-block mb-1 mr-1">
                                        <input type="number" class="form-control" value="{{ $itinerary->family_count }}"
                                            id="family_room" name="family_room">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h6>Note:</h6>
                                    <textarea class="form-control" rows="6" name="note1" id="note1">
										{{ $itinerary->note }}
									</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button id="itinerary_basic_info" class="btn btn-primary mr-1 mb-1">SAVE</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section>
    <!-- modal section -->
    <div class="modal fade text-left" id="product_detail_modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel17"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- product image carousel -->
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    </div>
                    <!-- end product image carouse -->

                    <label class="app-file-label">Information</label>
                    <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Category:</p>
                        <p class="font-weight-bold" id="category_title"></p>
                    </div>
                    <div class="d-flex align-items-center">
                        <p style="margin-right: 20px;" class="text-danger">location:</p>
                        <p class="font-weight-bold" id="location"></p>
                    </div>
                    <input type="hidden" id="location_info">

                    <!-- google map -->
                    <div id="basic-map" class="height-300"></div>
                    <!-- end google map -->

                    <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Serving Time:</p>
                        <p class="font-weight-bold" id="product_time"></p>
                    </div>
                    <div id="product_description">

                    </div>
                    <div>
                        <p style="margin-right: 20px;" class="text-danger">Pricing:</p>
                        <div id="pricing-data">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="day-title-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel150"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Day Title</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="day-title-modal-id">
                    <fieldset class="form-label-group">
                        <input type="text" class="form-control" id="day-title-input"
                            placeholder="Please Enter Day Title">
                        <label for="floating-label1">Title</label>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" data-dismiss="modal" onClick="set_day_title()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Change</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="product-time-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel150" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Product Time</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="product-time-id">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Start Time</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control pickatime" id="start_schedule_time"
                                    placeholder="Select Start Time">
                                <div class="form-control-position">
                                    <i class='bx bx-history'></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 ">
                            <h6>End Time</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control pickatime" id="end_schedule_time"
                                    placeholder="Select End Time">
                                <div class="form-control-position">
                                    <i class='bx bx-history'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" data-dismiss="modal" onClick="set_product_time()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Apply</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="template-itinerary-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel150" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Template Itinerary</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="template_id" name="template_id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Title</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="template_title"
                                    placeholder="Enter Template Itinerary Title" required>
                                <div class="form-control-position">
                                    <i class='bx bx-history'></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" data-dismiss="modal"
                        onClick="save_template_itinerary()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="product-travellers-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel150" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Product Travellers</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="product-travellers-id">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Adult Number</h6>
                            <div class="d-inline-block mb-1 mr-1">
                                <input type="number" class="touchspin" value="0" id="adults_num_modal"
                                    name="adults_num_modal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Child Number</h6>
                            <div class="d-inline-block mb-1 mr-1">
                                <input type="number" class="touchspin" value="0" id="children_num_modal"
                                    name="children_num_modal">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" data-dismiss="modal"
                        onClick="set_product_travellers()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Apply</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="itinerary-margin-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel150" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Itinerary Price Margin</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="itinerary-margin-id">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h6>Type</h6>
                            <ul class="list-unstyled mb-0 mt-1">
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                    <div class="radio">
                                        <input type="radio" name="modal_itinerary_margin_type" id="modal_itinerary_margin_type_percent" checked>
                                        <label for="modal_itinerary_margin_type_percent">Percent</label>
                                    </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2 mb-1">
                                    <fieldset>
                                    <div class="radio">
                                        <input type="radio" name="modal_itinerary_margin_type" id="modal_itinerary_margin_type_amount">
                                        <label for="modal_itinerary_margin_type_amount">Amount</label>
                                    </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Amount</h6>
                            <div class="d-inline-block mb-1 mr-1">
                                <input type="number" class="form-control" id="modal_itinerary_margin_price" name="modal_itinerary_margin_price">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" data-dismiss="modal" onClick="set_price_margin()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Apply</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="product-pricing-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel150" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myModalLabel150">Product Pricing</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary" id="add_new_product_pricing"
                                onclick="new_product_pricing()">Add New</button>
                        </div>
                    </div>
                    <div id="product_pricing_container" class="mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger ml-1" id="set_product_price" onclick="set_product_price()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Apply</span>
                    </button>

					<button type="button" class="btn btn-danger ml-1" id="set_all_product_price" onclick="set_all_product_price()">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Apply All</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="template_detail_modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="template_itinerary_title_caption"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="template_preview_container">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- end modal section -->

    <div class="collapsible collapse-icon accordion-icon-rotate">
        <div class="card collapse-header">
            <div id="headingCollapse7" class="card-header" data-toggle="collapse" role="button" data-target="#collapse7"
                aria-expanded="false" aria-controls="collapse7">
                <span class="collapse-title">
                    <i class='bx bx-help-circle align-middle'></i>
                    <span class="align-middle">Daily Schedule</span>
                </span>
            </div>
            <div id="collapse7" role="tabpanel" aria-labelledby="headingCollapse7" class="collapse">
                <div class="card-content">
                    <div class="card-body">

                        <div class="row" style="height: 100%; overflow-y: auto">
                            <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary_id }}">
                            <div class="col-sm-12 col-md-5">
                                <div class="card daily-schedule" style="height: 650px; overflow-y:auto;">
                                    <div class="card-header d-flex align-items-center justify-content-between" style="border: 0px !important">
                                        <h5>Daily Schedule</h5>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select class="form-control" id="day_select" onChange="day_selection_change()" style="margin-right: 30px;">
                                                <option value="0">All days</option>
                                                <?php 
                                                    $date = date_create($from_date);
                                                    $from_date_select = $from_date;
                                                ?>

                                                @for($i = 0; $i < $days; $i ++)
                                                    <?php $from_date_select = $date->format('Y-m-d');?> <option
                                                    value='{{$i + 1}}'>Day {{$i + 1}} {{$from_date_select}}</option>
                                                    <?php $date = date_add($date, date_interval_create_from_date_string('1 day'));?>
                                                @endfor
                                            </select>
                                            <div class="dropdown">
                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:void(0);" id="itinerary_save"><i class="bx bx-save mr-1"></i> Save</a>
                                                    <a class="dropdown-item" href="javascript:void(0);" id="template_itinerary_save"><i class="bx bx-edit-alt mr-1"></i> Make As Template</a>
                                                    <a class="dropdown-item" href="javascript:void(0);" id="set_task"><i class="bx bx-right-arrow mr-1"></i> Set As Task</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <?php 
                                            $date = date_create($from_date);
                                            $temp_date = $from_date?>
                                            @for($i = 0; $i < $days; $i ++) 
                                            <div class="each-day" id="each_day_container_{{$i}}">
                                                <div class="day-header">
                                                    <div class="day-header-left">
                                                        <?php $from_date = $date->format('Y-m-d');?>
                                                        <div class="day-date" data-pick="{{ $from_date }}">
                                                            {{$from_date}}
                                                        </div>
                                                        <div class="day-title-contain">
                                                            <input type="hidden" id="day-title-val-{{$i}}" value="<?php $timestamp = strtotime($from_date);
                                                            echo date('l', $timestamp)?>">
                                                            <div id="day-title-{{$i}}"><?php $timestamp = strtotime($from_date);
                                                            echo date('l', $timestamp)?></div> &nbsp;
                                                            <div style="padding-top: 3px;"><i
                                                                    class="bx bx-pencil edit-icon"
                                                                    style="color:rgb(210, 77, 83);"
                                                                    onClick="edit_day_title({{$i}})"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="day-body">
                                                    <div class="each-day-products"
                                                        id=<?php echo "each-day-products-" . $i ?>>
                                                        <div class="drag-explain-contain">
                                                            <sapn>Drag a product here to add it to the itinerary</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $date = date_add($date, date_interval_create_from_date_string('1 day'));?>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="card" style="height: 650px;">
                                    <div class="card-header"
                                        style="display: flex; align-items: center; justify-content: space-between; border: 0px !important;">
                                        <h5>Product</h5>
                                        <a href="javascript:void(0)" id="filter_button">
                                            <i class="bx bx-filter-alt"
                                                style="margin-right: 1px;font-size: 25px; color: rgb(210, 77, 83)"
                                                title="Filter Product"></i>
                                        </a>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control" id="search_product"
                                                            placeholder="Enter Product Title">
                                                        <div class="form-control-position">
                                                            <i class="bx bx-search"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3" id="filter_option" style="display: none">
                                                    <h6 style="padding-bottom: 20px;">
                                                        Category
                                                    </h6>

                                                    <fieldset>
                                                        <div class="checkbox checkbox-danger" style="padding-bottom: 7px;">
                                                            <input type="checkbox" id="check_accommodation"
                                                                onChange="filter_change()">
                                                            <label class="colorCheckbox4" style="font-size: 0.9rem;"
                                                                for="check_accommodation">Accommodation</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="checkbox checkbox-danger" style="padding-bottom: 7px;">
                                                            <input type="checkbox" id="check_transport"
                                                                onChange="filter_change()">
                                                            <label class="colorCheckbox4" style="font-size: 0.9rem;"
                                                                for="check_transport">Transport</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="checkbox checkbox-danger" style="padding-bottom: 7px;">
                                                            <input type="checkbox" id="check_activity_attraction"
                                                                onChange="filter_change()">
                                                            <label class="colorCheckbox4" style="font-size: 0.9rem;"
                                                                for="check_activity_attraction">Activies &
                                                                Attraction</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="checkbox checkbox-danger" style="padding-bottom: 7px;">
                                                            <input type="checkbox" id="check_guide"
                                                                onChange="filter_change()">
                                                            <label class="colorCheckbox4" style="font-size: 0.9rem;"
                                                                for="check_guide">Guide</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset>
                                                        <div class="checkbox checkbox-danger" style="padding-bottom: 7px;">
                                                            <input type="checkbox" id="check_other"
                                                                onChange="filter_change()">
                                                            <label class="colorCheckbox4" style="font-size: 0.9rem;"
                                                                for="check_other">Other</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-12" id="product_list_container">
                                                    <ul class="product-list" id="product_list">
                                                    </ul>
                                                    <ul class="pagination custom-pagination" id="product_pagination"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="card" style="height: 650px;">
                                    <div class="card-header" style="border: 0px !important;">
                                        <h5>Template Itinerary</h5>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control"
                                                            id="search_template_itinerary"
                                                            placeholder="Enter Template Itinerary Title">
                                                        <div class="form-control-position">
                                                            <i class="bx bx-search"></i>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="template_itinerary_list_container">
                                                    <ul class="template-list" id="template_itinerary_list"></ul>
                                                    <ul class="pagination" id="template_itinerary_pagination"></ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="collapsible collapse-icon accordion-icon-rotate">
        <div class="card collapse-header">
            <div id="headingCollapse8" class="card-header" data-toggle="collapse" role="button" data-target="#collapse8"
                aria-expanded="false" aria-controls="collapse8">
                <span class="collapse-title">
                    <i class='bx bx-help-circle align-middle'></i>
                    <span>Complete Itinerary</span>
                </span>
            </div>
            <div id="collapse8" role="tabpanel" aria-labelledby="headingCollapse5" class="collapse">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form-horizontal" method="post"
                            action="{{route('admin.itinerary.itinerary_complete_with_budget')}}">
                            @csrf
                            <input type="hidden" name="itinerary_id" id="itinerary_id" value="{{$itinerary_id}}">
                            <div class="row">
                                <div class="col-md-4 col-sm-6" id="budget_div">
                                    <h6>Budget</h6>

                                    @foreach($budget as $key => $val)
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ $key }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ $val }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <h6>Currency</h6>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <div class="controls">
                                            <select class="form-control" id="currency" name="currency" required
                                                data-validation-required-message="This field is required">
                                                @foreach($currency as $item)
                                                <option value="{{$item->id}}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-control-position">
                                            <i class='bx bx-calendar-check'></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" id="currency_converter"
                                            style="margin-top: 26px;">Currency Converter</button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6" id="margin_price_container">
                                    <h6>Profit Price(%)</h6>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <div class="controls">
                                            <input type="number" class="form-control" id="margin_price"
                                                name="margin_price" value="" placeholder="Please enter Profit Price."
                                                min="0" required
                                                data-validation-required-message="This field is required"
                                                aria-invalid="false">
                                        </div>
                                        <div class="form-control-position">
                                            <i class='bx bx-purchase-tag-alt'></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-2 d-flex align-item-center">
                                    <div class="form-group">
                                        <button type="submit" id="save_btn" class="btn btn-primary mr-1"
                                            style="margin-top: 26px;">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Dashboard Analytics end -->
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/extensions/jquery.steps.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>

<script src="{{asset('//maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo')}}"></script>
<script src="{{asset('vendors/js/charts/gmaps.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/dragula.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var msg = <?php if (json_encode(session()->get('msg'))) {
    echo json_encode(session()->get('msg'));
}
?>;
</script>


<script src="{{asset('js/core/libraries/pagination.js')}}"></script>

<script>
var base_url = "{{ url('/admin/itinerary') }}";
var base_path_url = "{{ asset('/') }}";
var budget = <?php echo json_encode($budget) ?>;
var from_date = <?php echo json_encode($temp_date) ?>;
var to_date = <?php echo json_encode($to_date) ?>;
var days = <?php echo json_encode($days) ?>;
var enquiry = <?php echo json_encode($enquiry) ?>;
var itinerary = <?php echo json_encode($itinerary) ?>;
var product = <?php echo json_encode($product) ?>;
var product_pricing = <?php echo json_encode($product_pricing) ?>;
var currency = <?php echo json_encode($currency) ?>;
var currency_list = <?php echo json_encode($currency_list) ?>;
var categoryTag = <?php echo json_encode($categoryTag) ?>;
var categories = <?php echo json_encode($category) ?>;
var product_gallery = <?php echo json_encode($product_gallery) ?>;
var product_description = <?php echo json_encode($product_description) ?>;
var language = <?php echo json_encode($language) ?>;
var days = <?php echo $days ?>;
var daily_schedule_data = <?php echo json_encode($itinerary_schedule_data) ?>;
var template_itinerary_data = <?php echo json_encode($template_itinerary_data) ?>;
</script>

<script src="{{asset('js/scripts/pages/admin/itinerary_basic.js')}}"></script>

<script src="{{asset('js/scripts/pages/admin/itinerary_daily.js')}}"></script>

<script src="{{asset('js/scripts/pages/admin/itinerary_complete.js')}}"></script>
@endsection