@extends('layouts.vendor')

@section('title','Product')

@section('vendor-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/ui/prism.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/file-uploaders/dropzone.min.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">

  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/animate/animate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
@endsection

@section('page-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/ext-component-treeview.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/file-uploaders/dropzone.css')}}">
  <style>
    .add_product_title {
      margin: 5px 0;
      font-size: 20px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      margin-left: 25px;
    }
    .file-manager-application .content-area-wrapper .content-right {
      width: 100%;
    }
  </style>
@endsection

@section('sidebar-content')
  @if ($errors->any())
        @foreach ($errors->all() as $error)
            <input type="hidden" class="error-message" value="{{ $error }}">
        @endforeach
  @endif

  <input type="hidden" id="alert" value="{{ Session::get('alert') }}">
@endsection

@section('content')
  <!-- File Manager app overlay -->
  <div class="app-file-overlay"></div>
  <div class="app-file-area">
    <!-- File App Content Area -->
    <div class="app-file-header">
      <!-- Header search bar starts -->
      <div class="flex-grow-1">
        <div class="sidebar-toggle d-block d-lg-none">
          <i class="bx bx-menu"></i>
        </div>
        <h4 class="add_product_title">Edit Product</h4>
      </div>
      <!-- Header search bar Ends -->
      <div class="app-file-header-icons">
        <div class="fonticon-wrap d-inline mx-sm-1 align-middle">
          <a href="{{ route('vendor.product') }}">
            <i class="livicon-evo cursor-pointer" data-options="name: hand-left.svg; size: 24px; style: lines; strokeColor:#596778; duration:0.85;"></i>
          </a>
        </div>
      </div>
    </div>
    <!-- App File Content Starts -->
    <div class="app-file-content p-2">
      <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general-pan" role="tab" aria-controls="general-pan" aria-selected="true">
            <i class="bx bx-detail align-middle"></i>
            <span class="align-middle">General</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="translation-tab" data-toggle="tab" href="#translation-pan" role="tab" aria-controls="translation-pan" aria-selected="false">
            <i class="bx bx-flag align-middle"></i>
            <span class="align-middle">Description</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="image-tab" data-toggle="tab" href="#image-pan" role="tab" aria-controls="image-pan" aria-selected="false">
            <i class="bx bx-image align-middle"></i>
            <span class="align-middle">Images</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pricing-tab" data-toggle="tab" href="#pricing-pan" role="tab" aria-controls="pricing-pan" aria-selected="false">
            <i class="bx bx-dollar-circle align-middle"></i>
            <span class="align-middle">Pricing</span>
          </a>
        </li>
      </ul>
      <div class="tab-content pt-1">
        <div class="tab-pane active" id="general-pan" role="tabpanel" aria-labelledby="general-tab">
          <form class="form-horizontal" action="{{ route('vendor.product.save') }}" id="general-form" name="general-form" method="POST"  novalidate>
            @csrf
            <input type="hidden" id="general_product_id" name="general_product_id" value="{{ $product->id }}" />
            <div class="row">
              <div class="col-md-6">
                <div class="row">

                  <div class="col-md-12">
                    <h6>Title</h6>
                    <fieldset class="form-group position-relative">
                      <input type="text" id="title" name="title" value="{{ $product->title }}" class="form-control" data-validation-required-message="This field is required" placeholder="Enter Title">
                      <div class="form-control-position">
                          <i class='bx bx-info-circle'></i>
                      </div>
                    </fieldset>
                  </div>

                  <div class="col-md-12">
                    <h6>Category</h6>
                    <fieldset class="form-group position-relative has-icon-left">
                        <select class="select2 form-control" id="category" name="category" required data-validation-required-message="This field is required">
                            @foreach($category as $item)
                            <option value="{{$item->id}}" {{ $product->category == $item->id? 'selected' : '' }}>{{ $item->title }}</option>
                            @endforeach
                        </select>
                        <div class="form-control-position">
                          <i class='bx bx-search'></i>
                        </div>
                    </fieldset>
                  </div>

                  <div class="col-md-12">
                    <h6>Location</h6>

                    <input type="hidden" id="postal_code" name="zip" required=""/>
                    <input type="hidden" id="country" name="country" required=""/>
                    <input type="hidden" id="locality" name="city" required="">
                    <input type="hidden" id="administrative_area_level_1" name="state" required=""/>
                    <input type="hidden" name="position" id="position">
                    <input type="hidden" id="street_number" name="street_number" required=""/>
                    <input type="hidden" id="route" name="street_address" required=""/>

                    <fieldset class="form-group position-relative has-icon-left @error('location') error @enderror">
                      <input type="text" id="autocomplete" name="autocomplete" class="form-control" data-validation-required-message="This field is required" placeholder="Select Location" value="{{ $product->city }} {{ $product->street_address }}">
                      <div class="form-control-position">
                          <i class='bx bx-info-circle'></i>
                      </div>
                    </fieldset>
                  </div>

                  <div class="col-md-6">
                    <h6>Check In</h6>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" id="start_time" name="start_time" value="{{ $product->start_time }}" required data-validation-required-message="This field is required" class="form-control pickatime-format" placeholder="Select Start Time">
                        <div class="form-control-position">
                            <i class='bx bx-history'></i>
                        </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6 ">
                    <h6>Check Out</h6>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" id="end_time" name="end_time" value="{{ $product->end_time }}" required data-validation-required-message="This field is required" class="form-control pickatime-format" placeholder="Select End Time">
                        <div class="form-control-position">
                            <i class='bx bx-history'></i>
                        </div>
                    </fieldset>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h6>Map</h6>
                <div id="basic-map" style="border: 1px solid #eee; height: 275px;"></div>
              </div>
              <div class="col-md-12 d-flex justify-content-end" style="margin-top: 20px;">
                <button type="submit" id="general_submit" class="btn btn-primary mr-1 mb-1">
                  <i class="bx bx-save"></i>
                  <span class="align-middle ml-25">Save</span>
                </button>
                <a href="{{ route('vendor.product') }}" class="btn btn-light-secondary mr-1 mb-1">
                  <i class="bx bx-reset"></i>
                  <span class="align-middle ml-25">Cancel</span>
                </a>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane" id="translation-pan" role="tabpanel" aria-labelledby="translation-tab">
          <form class="form-horizontal form" id="description_form" name="description_form" method="POST" action="{{ route('vendor.product.description') }}" novalidate>
            @csrf
            <input type="hidden" id="description_product_id" name="description_product_id" value="{{ $product->id }}" />
            <div id="description_list">
              @if($product->getDescription->count() != 0)
                @foreach($product->getDescription as $description)
                @if($loop->index == 0)
                <div class="row" id="description_wrapper_{{ $description->language }}">
                  <div class="col-md-12 d-flex align-items-center justify-content-between">
                    <h6 class="d-flex align-items-center">
                      <span>Description</span>
                      <div class="avatar mr-1 avatar-lg">
                        <img src="{{ asset('images/flags/'. $description->language .'.png')}}" alt="avtar img holder" class="flag">
                      </div>
                    </h6>
                  </div>
                  <div class="col-md-12">
                    <input type="hidden" id="descriptionID_{{$description->language}}" name="group['{{$description->language}}']['descriptionID']" value="{{$description->id}}" />
                    <input type="hidden" id="language_{{$description->language}}" name="group['{{$description->language}}']['language']" class="description_language" value="{{$description->language}}" />
                    <div class="form-group">
                      <textarea id="description_{{$description->language}}" name="group['{{$description->language}}']['description']"  cols="30" rows="10" class="form-control">
                        {{ $description->description }}
                      </textarea>
                    </div>
                  </div>
                </div>
                @else
                <div class="row" id="description_wrapper_{{ $description->language }}">
                  <div class="col-md-12 d-flex align-items-center justify-content-between">
                    <h6 class="d-flex align-items-center">
                      <span>Description</span>
                      <div class="avatar mr-1 avatar-lg">
                        <img src="{{ asset('images/flags/'. $description->language .'.png')}}" alt="avtar img holder" class="flag">
                      </div>
                    </h6>
                    <button class="btn btn-danger text-nowrap px-1" type="button" onclick="delete_description('{{ $description->language }}')">
                      <i class="bx bx-x"></i>
                      Delete
                    </button>
                  </div>
                  <div class="col-md-12">
                    <input type="hidden" id="descriptionID_{{$description->language}}" name="group['{{$description->language}}']['descriptionID']" value="{{$description->id}}" />
                    <input type="hidden" id="language_{{$description->language}}" name="group['{{$description->language}}']['language']" class="description_language" value="{{$description->language}}" />
                    <div class="form-group">
                      <textarea id="description_{{$description->language}}" name="group['{{$description->language}}']['description']"  cols="30" rows="10" class="form-control">
                        {{ $description->description }}
                      </textarea>
                    </div>
                  </div>
                </div>
                @endif
                @endforeach
              @else
              <div class="row" id="description_wrapper_gb">
                <div class="col-md-12 d-flex align-items-center justify-content-between">
                  <h6 class="d-flex align-items-center">
                    <span>Description</span>
                    <div class="avatar mr-1 avatar-lg">
                      <img src="{{ asset('images/flags/gb.png')}}" alt="avtar img holder" class="flag">
                    </div>
                  </h6>
                </div>
                <div class="col-md-12">
                  <input type="hidden" id="descriptionID_gb" name="group['gb']['descriptionID']" value="" />
                  <input type="hidden" id="language_gb" name="group['gb']['language']" value="gb" />
                  <div class="form-group">
                    <textarea id="description_gb" name="group['gb']['description']"  cols="30" rows="10" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              @endif
            </div>
            <hr>
            <div class="row">
              <div class="col-md-3">
                <h6>Language</h6>
                <fieldset class="form-group position-relative has-icon-left">
                  <select class="select2 form-control" id="alllanguage_list" name="language_list">
                      @foreach($language as $item)
                        <option value="{{$item->title}}">{{ $item->title }}</option>
                      @endforeach
                  </select>
                    <div class="form-control-position">
                        <i class='bx bx-history'></i>
                    </div>
                </fieldset>
              </div>
              <div class="col-md-3 form-group" style="padding-top: 25px;">
                <button type="button" id="description_add" data-repeater-create class="btn btn-primary mr-1 mb-1">
                  <i class="bx bx-plus"></i>
                  <span class="align-middle ml-25">Add</span>
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 d-flex justify-content-end" style="margin-top: 30px;">
                <button type="type" id="description_submit" class="btn btn-primary mr-1 mb-1">
                  <i class="bx bx-save"></i>
                  <span class="align-middle ml-25">Save</span>
                </button>
                <a href="{{ route('vendor.product') }}" class="btn btn-light-secondary mr-1 mb-1">
                  <i class="bx bx-reset"></i>
                  <span class="align-middle ml-25">Cancel</span>
                </a>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane" id="image-pan" role="tabpanel" aria-labelledby="translation-tab">
          <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-between">
              <h6>Product Image Upload</h6>
            </div>
            <div class="col-md-12">

              <form id="dpz-remove-all-thumb" action="{{ route('vendor.product.gallery.upload') }}" method="POST" enctype="multipart/form-data" class="dropzone dropzone-area">
                @csrf
                <input type="hidden" id="gallery_product_id" name="gallery_product_id" value="{{ $product->id }}" />
                <div class="dz-message">Drop Files Here To Upload</div>
              </form>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="pricing-pan" role="tabpanel" aria-labelledby="pricing-tab">
          <div class="col-md-12">
            <form class="form-horizontal" id="pricing_form" name="pricing_form" method="POST" action="{{ route('vendor.product.pricing') }}" novalidate>
              @csrf
              <input type="hidden" id="price_product_id" name="price_product_id" value="{{ $product->id }}" />

              <div class="row">
                <div class="col-md-2 form-group">
                  <button type="button" id="priceset_add" class="btn btn-danger mr-1 mb-1">
                    <i class="bx bx-plus"></i>
                    <span class="align-middle ml-25">Create</span>
                  </button>
                </div>
                <div class="col-md-10 d-flex justify-content-end form-group">
                  <button type="button" id="pricing_form_save" class="btn btn-primary mr-1 mb-1">
                    <i class="bx bx-save"></i>
                    <span class="align-middle ml-25">Save</span>
                  </button>
                  <a href="{{ route('vendor.product') }}" class="btn btn-light-secondary mr-1 mb-1">
                    <i class="bx bx-reset"></i>
                    <span class="align-middle ml-25">Cancel</span>
                  </a>
                </div>
              </div>


              @if(count($pricing_data) != 0)
                <div id="priceset_list">
                @for($j=count($pricing_data); $j>0; $j--)
                    <div class="row mb-1" id="pricingset_{{ $j }}" style="border: 1px solid #ccc; padding-top: 20px;">
                      <div class="col-md-12"><h6>Season</h6></div>
                      <div class="col-md-3">
                        @php
                          $duration = $pricing_data[$j-1]['duration'];
                          $duration = explode(' ~ ', $duration);
                        @endphp
                        <fieldset class="form-group position-relative has-icon-left">
                          <input type="text" class="form-control pickadate" placeholder="Select From Date" id="fromdate_{{ $j }}" name="fromdate[]" value="{{ $duration[0] }}"  onchange="fromdate_trigger(this, {{ $j }})">
                          <div class="form-control-position">
                            <i class='bx bx-calendar-check'></i>
                          </div>
                        </fieldset>
                      </div>
                      <div class="col-md-3">
                        <fieldset class="form-group position-relative has-icon-left">
                          <input type="text" class="form-control pickadate" placeholder="Select To Date" id="todate_{{ $j }}" name="todate[]" value="{{ $duration[1] }}" onchange="todate_trigger(this, {{ $j }})">
                          <div class="form-control-position">
                            <i class='bx bx-calendar-check'></i>
                          </div>
                        </fieldset>
                      </div>
                      <div class="col-md-3">
                        <fieldset class="form-group position-relative has-icon-left">
                          <select class="select2 form-control" id="currency_{{ $j }}" name="currency[]">
                            <option value="">Currency</option>
                            @foreach($currency as $item)
                              <option value="{{$item->id}}" {{ $item->id == $pricing_data[$j-1]['currency']? 'selected': '' }}>{{ $item->title }}</option>
                            @endforeach
                          </select>
                          <div class="form-control-position">
                            <i class='bx bx-search'></i>
                          </div>
                        </fieldset>
                      </div>
                      <div class="col-md-3 form-group d-flex align-items-center justify-content-start">
                        <button type="button" id="copyset_{{ $j }}" class="btn btn-outline-primary mr-1 mb-1" onclick="copypriceset({{ $j }})">
                          <i class="bx bx-copy"></i>
                          <span class="align-middle ml-25">Copy</span>
                        </button>
                        <button type="button" id="deleteset_{{ $j }}" class="btn btn-outline-danger mr-1 mb-1" onclick="deleteset({{ $j }})">
                          <i class="bx bx-trash"></i>
                          <span class="align-middle ml-25">Delete</span>
                        </button>
                      </div>

                      <div class="col-md-12" id="blackoutdatelist_{{ $j }}">
                        <h6>Blackout Date</h6>
                        @php
                          $blackout = $pricing_data[$j-1]['blackout'];
                          $blackout = explode(', ', $blackout);
                          $blackout_msg = $pricing_data[$j-1]['blackout_msg'];
                          $blackout_msg = explode(', ', $blackout_msg);
                        @endphp
                        @for($i=1; $i<=count($blackout); $i++)
                        <div class="row" id="blackoutdaterecord_{{ $j }}_{{ $i }}">
                          <div class="col-md-3 form-group">
                            <fieldset class="form-group position-relative has-icon-left">
                              <input type="text" class="form-control daterange" placeholder="Select BlackOut Date" id="blackoutdate_{{ $j }}_{{ $i }}" name="blackoutdate[{{$j-1}}][]" value="{{ $blackout[$i-1] }}">
                              <div class="form-control-position">
                                <i class='bx bx-calendar-check'></i>
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-6">
                            <fieldset class="form-group position-relative has-icon-left">
                              <input type="text" class="form-control" id="blackoutmsg_{{ $j }}_{{ $i }}" name="blackoutmsg[{{$j-1}}][]" value="{{ $blackout_msg[$i-1] }}">
                              <div class="form-control-position">
                                <i class='bx bx-info-circle'></i>
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-3 form-group">
                            <button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteblackoutdate_{{ $j }}_{{ $i }}" onclick="deleteblackoutdate({{ $j }}, {{ $i }})">
                              <i class="bx bx-trash"></i>
                            </button>
                          </div>
                        </div>
                        @endfor
                      </div>
                      <div class="col-md-6 form-group">
                        <button type="button" id="addblackoutdate_{{ $j }}" class="btn btn-outline-primary mr-1 mb-1" onclick="addblackoutdate({{ $j }})">
                          <i class="bx bx-plus"></i>
                          <span class="align-middle ml-25">Add</span>
                        </button>
                      </div>

                      <div class="col-md-12" id="pricelist_{{ $j }}">
                        <h6>Category Tag</h6>
                        @php
                          $pricing_sub_data =$pricing_data[$j-1]['pricing_data'];
                        @endphp

                        @for($i=1; $i<=count($pricing_sub_data); $i++)
                        <div class="row" id="pricinglist_{{ $j }}_{{ $i }}">
                          <input type="hidden" id="priceID_{{ $j }}_{{ $i }}" name="priceID[{{$j-1}}][]" value="{{ $pricing_sub_data[$i-1]['id'] }}" />
                          <div class="col-md-3">
                            <fieldset class="form-group position-relative has-icon-left">
                              <select class="select2 form-control" id="tag_{{ $j }}_{{ $i }}" name="tag[{{ $j-1 }}][]">
                                @foreach($category_tag as $item)
                                  <option value="{{$item->id}}" {{ $item->id == $pricing_sub_data[$i-1]['tag']? 'selected': '' }}>{{ $item->title }}</option>
                                @endforeach
                              </select>
                              <div class="form-control-position">
                                <i class='bx bx-search'></i>
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-3">
                            <fieldset class="form-group position-relative has-icon-left">
                              <input type="text" class="form-control" id="description_{{ $j }}_{{ $i }}" name="description[{{ $j-1 }}][]" value="{{ $pricing_sub_data[$i-1]['description'] }}" placeholder="Description">
                              <div class="form-control-position">
                                <i class='bx bx-info-circle'></i>
                              </div>
                            </fieldset>
                          </div>
                          <div class="col-md-3">
                            <fieldset class="form-group position-relative has-icon-left">
                              <input type="number" id="price_{{ $j }}_{{ $i }}" name="price[{{ $j-1 }}][]" class="form-control" value="{{ $pricing_sub_data[$i-1]['price'] }}" data-bts-step="0.5" data-bts-decimals="2" data-bts-prefix="$" placeholder="Price" min="0">
                            </fieldset>
                          </div>
                          <div class="col-md-3 form-group">
                            <button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteprice_{{ $j }}_{{ $i }}" onclick="deleteprice({{ $j }}, {{ $i }})">
                              <i class="bx bx-trash"></i>
                            </button>
                          </div>
                        </div>
                        @endfor
                      </div>
                      <div class="col-md-6 form-group">
                        <button type="button" id="addtype_{{ $j }}" class="btn btn-outline-primary mr-1 mb-1" onclick="addpricetype({{ $j }})">
                          <i class="bx bx-plus"></i>
                          <span class="align-middle ml-25">Add</span>
                        </button>
                      </div>
                    </div>
                @endfor
                </div>
              @else
                <div id="priceset_list">
                  <div class="row mb-1" id="pricingset_1" style="border: 1px solid #ccc; padding-top: 20px;">
                    <div class="col-md-12"><h6>Season</h6></div>
                    <div class="col-md-3">
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control pickadate" placeholder="Select From Date" id="fromdate_1" name="fromdate[]" value="" onchange="fromdate_trigger(this, 1)">
                        <div class="form-control-position">
                          <i class='bx bx-calendar-check'></i>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-3">
                      <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control pickadate" placeholder="Select To Date" id="todate_1" name="todate[]" value="" onchange="todate_trigger(this, 1)">
                        <div class="form-control-position">
                          <i class='bx bx-calendar-check'></i>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-3">
                      <fieldset class="form-group position-relative has-icon-left">
                        <select class="select2 form-control" id="currency_1" name="currency[]">
                          <option value="">Currency</option>
                          @foreach($currency as $item)
                            <option {{$item->default_currency == '1' ? 'selected' : '' }} value="{{$item->id}}">{{ $item->title }}</option>
                          @endforeach
                        </select>
                        <div class="form-control-position">
                          <i class='bx bx-search'></i>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-3 form-group d-flex align-items-center justify-content-start">
                      <button type="button" id="copyset_1" class="btn btn-outline-primary mr-1 mb-1" onclick="copypriceset(1)">
                        <i class="bx bx-copy"></i>
                        <span class="align-middle ml-25">Copy</span>
                      </button>

                      <button type="button" id="deleteset_1" class="btn btn-outline-danger mr-1 mb-1" onclick="deleteset(1)">
                        <i class="bx bx-trash"></i>
                        <span class="align-middle ml-25">Delete</span>
                      </button>
                    </div>

                    <div class="col-md-12" id="blackoutdatelist_1">
                      <h6>Blackout Date</h6>
                    </div>
                    <div class="col-md-6 form-group">
                      <button type="button" id="addblackoutdate_1" class="btn btn-outline-primary mr-1 mb-1" onclick="addblackoutdate(1)">
                        <i class="bx bx-plus"></i>
                        <span class="align-middle ml-25">Add</span>
                      </button>
                    </div>
                    <div class="col-md-12" id="pricelist_1">
                      <h6>Category Tag</h6>
                      <div class="row" id="pricinglist_1_1">
                        <input type="hidden" id="priceID_1_1" name="priceID[0][]" value="" />
                        <div class="col-md-3">
                          <fieldset class="form-group position-relative has-icon-left">
                            <select class="select2 form-control" id="tag_1_1" name="tag[0][]">
                              @foreach($category_tag as $item)
                                <option value="{{$item->id}}">{{ $item->title }}</option>
                              @endforeach
                            </select>
                            <div class="form-control-position">
                              <i class='bx bx-search'></i>
                            </div>
                          </fieldset>
                        </div>
                        <div class="col-md-3">
                          <fieldset class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control" id="description_1_1" name="description[0][]" value="" placeholder="Description">
                            <div class="form-control-position">
                              <i class='bx bx-info-circle'></i>
                            </div>
                          </fieldset>
                        </div>
                        <div class="col-md-3">
                          <fieldset class="form-group position-relative has-icon-left">
                            <input type="number" id="price_1_1" name="price[0][]" class="form-control" value="" data-bts-step="0.5" data-bts-decimals="2" data-bts-prefix="$" placeholder="Price" min="0">
                          </fieldset>
                        </div>
                        <div class="col-md-3 form-group">
                          <button type="button" class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1" id="deleteprice_1_1" onclick="deleteprice(1, 1)">
                            <i class="bx bx-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 form-group">
                      <button type="button" id="addtype_1" class="btn btn-outline-primary mr-1 mb-1" onclick="addpricetype(1)">
                        <i class="bx bx-plus"></i>
                        <span class="align-middle ml-25">Add</span>
                      </button>
                    </div>
                  </div>
                </div>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-bs-container">
    <div class="toast-position">
      <div class="toast" id="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
        <div class="toast-header">
          <i class="bx bx-bell"></i>
          <span class="mr-auto toast-title" id="toast_title">Bootstrap</span>
          <button type="button" class=" close" data-dismiss="toast" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
{{-- page styles --}}

@section('vendor-scripts')

  <script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
  <script src="{{asset('vendors/js/forms/validation/jquery.validate.min.js')}}"></script>

  <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>

  <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
  <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>

  <script src="{{asset('vendors/js/extensions/dropzone.min.js')}}"></script>
  <script src="{{asset('vendors/js/ui/prism.min.js')}}"></script>

  <script src="{{asset('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1jKOFLhfQoZD3xJISSPnSW9-4SyYPpjY&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
  <script src="{{asset('vendors/js/charts/gmaps.min.js')}}"></script>

  <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>

  <script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>
  <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
  <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

@endsection

@section('page-scripts')
  <script>
      var base_url = "{{ url('/vendor') }}";
      var base_path_url = "{{ asset('/') }}";

      var tag = <?php echo $category_tag; ?>;
      var description_list = <?php echo $product->getDescription; ?>;
      var image_gallery = <?php echo $product->getGallery; ?>;
      var currency = <?php echo $currency; ?>;
      var product = <?php echo $product; ?>;
      var pricing_server_data = <?php echo json_encode($pricing_data); ?>;
      var default_currency_id = <?php echo Helper::getDefaultCurrency() ?>;
      
      function back(){
          document.location.href=base_url + '/';
      }
  </script>
  <script src="{{asset('js/scripts/pages/vendor/product_edit.js')}}"></script>
@endsection
