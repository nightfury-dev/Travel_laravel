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
        <h4 class="add_product_title">Add Product</h4>
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
            <input type="hidden" id="general_product_id" name="general_product_id" value="" />
            <div class="row">
              <div class="col-md-6">
                <div class="row">

                  <div class="col-md-12">
                    <h6>Title</h6>
                    <fieldset class="form-group position-relative has-icon-left @error('title') error @enderror">
                      <input type="text" id="title" name="title" value="{{old('title')}}" class="form-control" data-validation-required-message="This field is required" placeholder="Enter Title">
                      <div class="form-control-position">
                          <i class='bx bx-info-circle'></i>
                      </div>
                    </fieldset>
                  </div>

                  <div class="col-md-12">
                    <h6>Category</h6>
                    <fieldset class="form-group position-relative has-icon-left @error('category') error @enderror">
                        <select class="select2 form-control" id="category" name="category" required data-validation-required-message="This field is required">
                            <option value="">Category</option>
                            @foreach($category as $item)
                            <option value="{{$item->id}}" {{old('category')==$item->id?'selected':''}}>{{ $item->title }}</option>
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
                      <input type="text" id="autocomplete" name="autocomplete" class="form-control" data-validation-required-message="This field is required" placeholder="Select Location">
                      <div class="form-control-position">
                          <i class='bx bx-info-circle'></i>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <h6>Check In</h6>
                    <fieldset class="form-group position-relative has-icon-left @error('start_time') error @enderror">
                        <input type="text" id="start_time" name="start_time" value="{{old('start_time')}}" required data-validation-required-message="This field is required" class="form-control pickatime-format" placeholder="Select Start Time">
                        <div class="form-control-position">
                            <i class='bx bx-history'></i>
                        </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6 ">
                    <h6>Check Out</h6>
                    <fieldset class="form-group position-relative has-icon-left @error('end_time') error @enderror">
                        <input type="text" id="end_time" name="end_time" value="{{old('end_time')}}" required data-validation-required-message="This field is required" class="form-control pickatime-format" placeholder="Select End Time">
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
            <input type="hidden" id="description_product_id" name="description_product_id" value="" />
            <div id="description_list">
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
                <button type="submit" class="btn btn-primary mr-1 mb-1">
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
            <div class="col-md-12">
              <h6>Product Image Upload</h6>
            </div>
            <div class="col-md-12">
              <form id="dpz-remove-all-thumb" action="{{ route('vendor.product.gallery.upload') }}" method="POST" enctype="multipart/form-data" class="dropzone dropzone-area">
                @csrf
                <input type="hidden" id="gallery_product_id" name="gallery_product_id" value="" />
                <div class="dz-message">Drop Files Here To Upload</div>
              </form>

            </div>
          </div>
        </div>
        <div class="tab-pane" id="pricing-pan" role="tabpanel" aria-labelledby="pricing-tab">
          <div class="col-md-12">
            <form class="form-horizontal" id="pricing_form" name="pricing_form" method="POST" action="{{ route('vendor.product.pricing') }}" novalidate>
              @csrf
              <input type="hidden" id="price_product_id" name="price_product_id" value="" />
              <div class="row">
                <div class="col-md-2 form-group">
                  <button type="button" id="priceset_add" class="btn btn-danger mr-1 mb-1">
                    <i class="bx bx-plus"></i>
                    <span class="align-middle ml-25">Create</span>
                  </button>
                </div>
                <div class="col-md-10 d-flex justify-content-end form-group">
                  <button type="submit" class="btn btn-primary mr-1 mb-1">
                    <i class="bx bx-save"></i>
                    <span class="align-middle ml-25">Save</span>
                  </button>
                  <a href="{{ route('vendor.product') }}" class="btn btn-light-secondary mr-1 mb-1">
                    <i class="bx bx-reset"></i>
                    <span class="align-middle ml-25">Cancel</span>
                  </a>
                </div>
              </div>

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
                          <option value="{{$item->id}}">{{ $item->title }}</option>
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
                    <h6>Tag Type</h6>
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

            </form>
          </div>
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
      var currency = <?php echo $currency; ?>;

      function back(){
          document.location.href=base_url + '/';
      }
  </script>
  <script src="{{asset('js/scripts/pages/vendor/product_add.js')}}"></script>
@endsection
