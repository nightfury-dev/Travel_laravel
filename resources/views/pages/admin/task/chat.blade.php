@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/dragula.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-custom-menu.css')}}">
@endsection

@section('page-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/widgets.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/ext-component-treeview.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/file-uploaders/dropzone.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
  <style>
    .add_product_title {
      margin: 5px 0;
      font-size: 20px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      margin-left: 25px;
    }

    .widget-chat .widget-chat-container {
      height: 600px;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap {
        float: right !important;
        text-align: right !important;
        margin-bottom: 10px !important;
        max-width: calc(100% - 5rem) !important;
        clear: both !important;
        word-break: break-word !important;
    }

    .widget-chat .chat-content .chat-left .chat-message-wrap {
        text-align: left !important;
        float: left !important;
        color: #727E8C !important;
        background-color: #fafbfb !important;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap .chat-text {
        padding: 0.75rem 1rem !important;
        color: #FFFFFF !important;
        background: #5A8DEE !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
    }

    .widget-chat .chat-content .chat-left .chat-body .chat-message-wrap .chat-text {
        padding: 0.75rem 1rem !important;
        color: #727E8C !important;
        background: #fafbfb !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap .chat-time-text {
        color: #828D99 !important;
        font-size: 0.8rem !important;
        text-align:right!important;
        margin-bottom: 0px!important;
        white-space: nowrap !important;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap .chat-image {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        color: #FFFFFF !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        height: 200px;
        background-size: cover;
    }

    .widget-chat .chat-content .chat-left .chat-body .chat-message-wrap .chat-image {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        color: #727E8C !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        height: 200px;
        background-size: cover;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap .chat-file {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        background: #5A8DEE !important;
        color: #FFFFFF !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        background-size: cover;
    }

    .widget-chat .chat-content .chat-left .chat-body .chat-message-wrap .chat-file {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        background: #fafbfb !important;
        color: #727E8C !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        background-size: cover;
    }

    .widget-chat .chat-content .chat-body .chat-message-wrap .chat-file a {
      color: #fff!important;
    }

    .widget-chat .chat-content .chat-left .chat-body .chat-message-wrap .chat-file a {
      color: #727E8C!important;
    }

  </style>
@endsection

@section('content')
<!-- Widgets Advance start -->
<input type="hidden" id="session_user_id" value="">
<input type="hidden" id="session_user_name" value="">
<input type="hidden" id="session_user_avatar" value="">

<input type="hidden" id="task_type" value="{{ $task->task_type }}">


<section id="widgets-advance">
  <div class="row">
    <div class="col-md-12">
    <div class="collapsible collapse-icon accordion-icon-rotate">
      <div class="card collapse-header">
        <div id="headingCollapse6" class="card-header" data-toggle="collapse" role="button" data-target="#collapse6"  aria-expanded="false" aria-controls="collapse6">
          <span class="collapse-title">
            <i class='bx bx-help-circle align-middle'></i>
              <span class="align-middle">
              Itinerary Information | Ref.No:
              <?php
                $str = $itinerary->reference_number;
                echo $str;
              ?>
              </span>
            </span>
        </div>

        <div id="collapse6" role="tabpanel" aria-labelledby="headingCollapse6" class="collapse">
          <div class="card-content">
            <div class="card-body">
              <form class="form-horizontal" novalidate>
                @csrf
                <input type="hidden" name="enquiry_id" id = "enquiry_id" value="{{ $itinerary->enquiry_id }}">

                <input type="hidden" name="itinerary_id" id="itinerary_id" value="{{ $itinerary->id }}">

                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <h6>Itinerary Title</h6>
                    <fieldset class="form-group position-relative has-icon-left">
                        <div class="controls">
                            <input type="text" class="form-control" id="title" name="title" value="{{ $itinerary->title }}" placeholder="Itinerary Title" required data-validation-required-message="This Title field is required" aria-invalid="false" readonly>
                        </div>
                        <div class="form-control-position">
                            <i class="bx bx-purchase-tag-alt"></i>
                        </div>
                    </fieldset>
                  </div>

                  <div class="col-md-2 col-sm-2">
                    <h6>Pick up duration</h6>
                    <fieldset class="form-group position-relative has-icon-left">
                        <?php

                          $from_date = $itinerary->from_date;
                          $from_date= date_create($from_date);
                          $from_date = date_format($from_date,"m/d/Y");

                          $to_date = $itinerary->to_date;
                          $to_date = date_create($to_date);
                          $to_date = date_format($to_date,"m/d/Y");

                          $duration = $from_date . ' - ' . $to_date;
                        ?>
                        <input type="text" class="form-control showdropdowns" placeholder="Select duration" id="duration" name="duration" value="{{ $duration }}" required data-validation-required-message="This Duration field is required" aria-invalid="false" readonly>
                        <div class="form-control-position">
                            <i class='bx bx-calendar-check'></i>
                        </div>
                    </fieldset>
                  </div>
                  <div class="col-md-2 col-sm-2">
                      <h6>Adults Number:</h6>
                      <div class="d-inline-block mb-1 mr-1">
                          <input type="number" class="touchspin" value="{{ $itinerary->adult_number }}" id="adults_num" name="adults_num" required data-validation-required-message="This Title field is required" aria-invalid="false" readonly>
                      </div>
                  </div>
                  <div class="col-md-2 col-sm-2">
                      <h6>Children Number:</h6>
                      <div class="d-inline-block mb-1 mr-1">
                          <input type="number" class="touchspin" value="{{ $itinerary->children_number}}" id="children_num" name="children_num" readonly>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2 col-sm-2">
                    <h6>Single Rooms:</h6>
                    <div class="d-inline-block mb-1 mr-1">
                      <input type="number" class="touchspin" value="{{ $itinerary->single_count }}" id="single_room" name="single_room" readonly>
                    </div>
                  </div>
                  <div class="col-2 col-sm-2">
                    <h6>Double Rooms:</h6>
                    <div class="d-inline-block mb-1 mr-1">
                      <input type="number" class="touchspin" value="{{ $itinerary->double_count }}" id="double_room" name="double_room" readonly>
                    </div>
                  </div>
                  <div class="col-2 col-sm-2">
                    <h6>Twin Rooms:</h6>
                    <div class="d-inline-block mb-1 mr-1">
                      <input type="number" class="touchspin" value="{{ $itinerary->twin_count }}" id="twin_room" name="twin_room" readonly>
                    </div>
                  </div>
                  <div class="col-2 col-sm-2">
                    <h6>Triple Rooms:</h6>
                    <div class="d-inline-block mb-1 mr-1">
                      <input type="number" class="touchspin" value="{{ $itinerary->triple_count }}" id="triple_room" name="triple_room" readonly>
                    </div>
                  </div>
                  <div class="col-2 col-sm-2">
                    <h6>Family Rooms:</h6>
                    <div class="d-inline-block mb-1 mr-1">
                      <input type="number" class="touchspin" value="{{ $itinerary->family_count }}" id="family_room" name="family_room" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12">
                    <h6>Note:</h6>
                    <textarea class="form-control" rows="6" name="note1" id="note1" readOnly>
                      {{ $itinerary->note }}
                    </textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
    </div>
  </div>

  <div class="row">
    <!-- Services Starts -->
    <div class="col-xl-6 col-md-6 col-sm-12 earnings-card" id="widget-earnings">
      <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
          <h5 class="card-title">Tasks </h5>
        </div>
        <div class="card-content">
          <div class="card-body py-1">
            <!-- services swiper starts -->
            <div class="widget-earnings-swiper swiper-container p-1">
              <div class="swiper-wrapper">
                @foreach($confirm_tasks as $confirm_task)
                <div class="swiper-slide rounded swiper-shadow py-75 px-2 d-flex align-items-center" id="service_{{$confirm_task->id}}" data-task-id="{{$confirm_task->task_id}}" data-user-id="{{$confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->id}}" data-user-name="{{$confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->first_name}} {{$confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->last_name}}"  data-user-avatar="{{$confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->avatar_path}}">
                  <i class="bx bx-pyramid mr-50 font-large-1"></i>
                  <div class="swiper-text">{{$confirm_task->get_ItineraryDaily()->get_product()->title}}
                    <p class="mb-0 font-small-2 font-weight-normal">{{$confirm_task->get_time()}}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <!-- services swiper ends -->
            @foreach($confirm_tasks as $confirm_task)
            <div class="wrapper-content" data-earnings="service_{{$confirm_task->id}}">
              @php
                  $product_gallery = $product_gallery_model->where('product_id', $confirm_task->product_id)->first();
                  $path = $product_gallery?$product_gallery->path:'';

                  $product = $confirm_task->get_ItineraryDaily()->get_product();
                  
                  $product_prices = $confirm_task->get_product_prices();
                  $product_seasons = $confirm_task->get_product_seasons();
                  $product_tags = $confirm_task->get_product_tags();
                  $product_currencies = $confirm_task->get_product_currencies();
              @endphp

              <div class="row">
                <div class = "col-md-8">
                  <div class="d-flex align-items-center mt-75">
                    <p style="margin-right: 20px;" class="text-danger">Category:</p>
                    <p class="font-weight-bold" id="category_title">{{ $product->get_category()->title?$product->get_category()->title:''  }}</p>
                  </div>
                  <div class="d-flex align-items-center">
                    <p style="margin-right: 20px;" class="text-danger">location:</p>
                    <p class="font-weight-bold" id="location">{{ $product->country?$product->country:'' }} {{  $product->city?$product->city:'' }}</p>
                  </div>
                </div>
                <div class = "col-md-4 mt-75" style="color: green; text-align: right;">
                  <button type="button" onClick="confirm_check({{$confirm_task->id}})" class="btn btn-danger confirm_btn" {{ $confirm_task->status == 1 ? "disabled" : ""}}> confirm </button>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="d-flex align-items-center mt-75">
                      <p style="margin-right: 20px;" class="text-danger">Travelers:</p>
                      <p class="font-weight-bold" id="category_title">adults: {{ $confirm_task->get_ItineraryDaily()->adults_num}}</p>
                      <p style="margin-left: 30px;" class="font-weight-bold" id="category_title">childs: {{ $confirm_task->get_ItineraryDaily()->children_num}}</p>
                  </div>

                  @for ($i=0; $i<count($product_prices); $i++)
                    <div class="d-flex align-items-center mt-75">
                      <p style="margin-right: 20px;" class="text-danger">Service Type:</p>
                      <p class="font-weight-bold" id="category_title">{{ $product_tags[$i]}}</p>

                      <p style="margin-left: 30px;" class="font-weight-bold" id="season_duration">{{ $product_seasons[$i]}}</p>
                      <p style="margin-left: 30px;" class="font-weight-bold" id="category_price">{{ $product_prices[$i]}}({{ $product_currencies[$i] }})</p>
                    </div>
                  @endfor

                  <div class="d-flex align-items-center mt-75">
                    <p style="margin-right: 20px;" class="text-danger">Margin Price:</p>
                    <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->itinerary_margin_price  }}(%)</p>
                  </div>

                  <hr />
                  <p style="margin-right: 20px;" class="text-danger">Supplier Information</p>
                  <div class="row">
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Name:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->first_name . '.' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->last_name }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Company Name:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_company_name }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Location:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_country . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_city . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_street_address . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_street_number }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Company Location:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_country . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_city . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_street_address . ' ' . $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_street_number }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Email:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_email }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Company Email:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->billing_email }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Phone Number:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_phone_number }}</p>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="d-flex align-items-center mt-75">
                        <p style="margin-right: 20px;" class="text-danger">Company Phone Number:</p>
                        <p class="font-weight-bold" id="category_title">{{ $confirm_task->get_ItineraryDaily()->get_product()->get_supplier()->main_phone_number }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>    
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <!-- Earnings Widget Swiper Ends -->

    <!-- chat Widget Starts -->
    @if($task->task_type == 1)
      <div class="col-xl-6 col-md-6 col-sm-12 widget-chat-card">
        <div class="widget-chat widget-chat-messages">
          <div class="card">
            <div class="card-header border-bottom p-0">
              <div class="media m-75">
                <a class="media-left" href="JavaScript:void(0);">
                  <div class="avatar mr-75">
                    <img src="" id="chat_supplier_avatar" alt="avtar images" width="32"height="32">
                    <span class="avatar-status-online"></span>
                  </div>
                </a>
                <div class="media-body">
                  <h6 class="media-heading mb-0 pt-25"><span id="chat_supplier_name"></span></h6>
                  <span class="text-muted font-small-3">Active</span>
                </div>
              </div>
            </div>
            <div class="card-body widget-chat-scroll">
              <textarea class="form-control" rows="10" name="call" id="call"></textarea>
            </div>
            <div class="card-footer border-top p-1">
              <form class="d-flex align-items-center" action="javascript:void(0);">
                <input type="hidden" id="confirm_id" value="">
                <input type="hidden" id="task_id" value="">
                <input type="hidden" id="supplier_id" value="">
                <input type="hidden" id="supplier_name" value="">
                <input type="hidden" id="supplier_avatar" value="">
                <button type="button" onclick="savecallcontent()" class="btn btn-primary glow"><i class="bx bx-save"></i>Save</button>
              </form>
            </div>
          </div>
        </div>
      </div>          
    @elseif($task->task_type == 2)
      <div class="col-xl-6 col-md-6 col-sm-12 widget-chat-card">
        <div class="widget-chat widget-chat-messages">
          <div class="card">
            <div class="card-header border-bottom p-0">
              <div class="media m-75">
                <a class="media-left" href="JavaScript:void(0);">
                  <div class="avatar mr-75">
                    <img src="" id="chat_supplier_avatar" alt="avtar images" width="32"height="32">
                    <span class="avatar-status-online"></span>
                  </div>
                </a>
                <div class="media-body">
                  <h6 class="media-heading mb-0 pt-25"><span id="chat_supplier_name"></span></h6>
                  <span class="text-muted font-small-3">Active</span>
                </div>
              </div>
            </div>
            <div class="card-body widget-chat-container widget-chat-scroll">
              <div class="chat-content"></div>
            </div>
            <div class="card-footer border-top p-1">
              <form class="d-flex align-items-center" onsubmit="widgetMessageSend();" action="javascript:void(0);">
                
                <input type="hidden" id="confirm_id" value="">
                <input type="hidden" id="task_id" value="">
                <input type="hidden" id="supplier_id" value="">
                <input type="hidden" id="supplier_name" value="">
                <input type="hidden" id="supplier_avatar" value="">

                <input name="attach_file" id="attach_file" type="file" style="display:none" size="2000000" onchange="get_file(event)">
                <span><i class="bx bx-face cursor-pointer"></i></span>
                <span onclick="$('#attach_file').focus().trigger('click');"><i class="bx bx-paperclip ml-75 cursor-pointer"></i></span>
                <input type="text" class="form-control widget-chat-message mx-75" placeholder="Type here...">
                <button type="submit" class="btn btn-primary glow"><i class="bx bx-paper-plane"></i></button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @else

    @endif
    <!-- chat Widget Ends -->
  </div>
</section>
<!-- Widgets Advance End -->
@endsection
{{-- page styles --}}

@section('vendor-scripts')

<script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/dragula.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>


@endsection

@section('page-scripts')
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('js/scripts/pages/admin/contact.js')}}"></script>

  <script>
      var base_url = "{{ url('/admin') }}";
      var base_path_url = "{{ asset('/') }}";

      function back(){
          document.location.href=base_url + '/';
      }
  </script>
@endsection
