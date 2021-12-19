@extends('layouts.customer')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/faq.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/widgets.css')}}">
@endsection

@section('content')

<div class="modal fade text-left" id="send_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form id="send_form" name="send_form" action="{{ route('customer.itinerary.send_message') }}" method="POST">
                @csrf
                <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary_id }}">
                <input type="hidden" id="from_id" name="from_id" value="{{ $from_id }}">
                <input type="hidden" id="to_id" name="to_id" value="{{ $to_id }}">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="6" placeholder="Please Enter New Message." id="message"
                                name="message" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom-file" style="width: 100%;">
                                <input type="file" class="custom-file-input" id="attach_file" name="attach_file">
                                <label class="custom-file-label" for="attach_file">Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Send</span>
                    </button>
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<section id="basic-tabs-components">
    <div class="content-header-left">
        <div class="row breadcrumbs-top">
            <div class="col-md-3" style="padding-top: 10px;">
                <h5 class="content-header-title float-left pr-1 mb-0">@yield('title')</h5>
                <div class="breadcrumb-wrapper col-12">
                    <?php
$breadcrumbs1 = [
    ["link" => "/", "name" => "Home"], ["name" => "Itinerary Contact"],
];
?>
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
            <div class="col-md-6">
                <div class="card bg-transparent box-shadow-0">
                    <div class="card-content">
                        <div class="card-body p-0">
                            <fieldset class="faq-search-width position-relative">
                                <input type="text" class="form-control round form-control-lg shadow pl-2" id="searchbar"
                                    placeholder="Search message...">
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <ul class="nav nav-tabs float-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link btn-danger glow white" href="javascript:void(0)" onclick="new_message();">
                            <i class="bx bx-send align-middle"></i>
                            <span class="align-middle">New Message</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-primary glow white" href="{{ route('customer.itinerary') }}">
                            <i class="bx bx-arrow-left align-middle"></i>
                            <span class="align-middle">Back</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="wrapper-content" id="message_list">
    @include('pages.customer.itinerary.message_search')
</section>

@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
@endsection

@section('page-scripts')
<script src="{{asset('js/scripts/pages/customer/contact.js')}}"></script>
<script>
var base_url = "{{ url('/customer') }}";
var base_path_url = "{{ asset('/') }}";
</script>
@endsection