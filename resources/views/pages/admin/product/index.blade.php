@extends('layouts.admin')

@section('title', 'Product')

@section('vendor-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/bootstrap-treeview.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-custom-menu.css')}}">
@endsection

@section('page-styles')
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-file-manager.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/ext-component-treeview.css')}}">

  <style>
    #category-tree .list-group .list-group-item {
      display: flex!important;
      align-items: center!important;
    }

    #category-tree .list-group .list-group-item .node-icon {
      display: none!important;
    }

    #category-tree .list-group .list-group-item .check-icon {
      margin-right: 10px!important;
    }

    .p-act {
      /* border-radius: 4px!important;
      border: 1px solid lightgray!important;
      box-shadow: 0 0 5px!important; */
    }

    .p-act .act-image {
        position: relative;
        width: 100%;
        height: 140px;
        object-fit: cover;
        background-size: cover !important;
        background-position: 50%;
        background-repeat: no-repeat;
        overflow: hidden;
        margin-right: 20px;
        background-color: #e9e9e9;
        box-shadow: 0 0 12px rgba(0,0,0,.03);
        -webkit-transition: all .2s ease;
        transition: all .2s ease;
    }

    .app-file-sidebar-info {
      width: 700px!important;
      right: -10%!important;
    }

    .file-manager-application .content-area-wrapper .sidebar .app-file-sidebar-info.show {
      right: 35%!important;
    }
    
    @media(max-width: 996px) {
      .app-file-sidebar-info {
        width: 376px!important;
        right: 0!important
      } 
    }

    @media(max-width: 396px) {
      .app-file-sidebar-info {
        display: none;
      }
    }
  </style>
@endsection

@section('sidebar-content')
  <input type="hidden" id="alert" value="{{ Session::get('alert') }}">
  <div class="app-file-sidebar sidebar-content d-flex">
    <!-- App File sidebar - Left section Starts -->
    <div class="app-file-sidebar-left">
      <!-- sidebar close icon starts -->
      <span class="app-file-sidebar-close"><i class="bx bx-x"></i></span>
      <!-- sidebar close icon ends -->
      <div class="form-group add-new-file text-center">
        <a href="{{ route('admin.product.add', ['flag' => 'accommodation']) }}" class="btn btn-primary btn-block glow my-2 add-file-btn text-capitalize">
          <i class="bx bx-plus"></i>Add Product
        </a>
      </div>
      <div class="app-file-sidebar-content">
        <!-- App File Left Sidebar - Labels Content Starts -->
        <label class="app-file-label">Category</label>
        <div id="category-tree"></div>
        <!-- App File Left Sidebar - Labels Content Ends -->
      </div>
    </div>
  </div>
  <!-- App File sidebar - Right section Starts -->
  <div class="app-file-sidebar-info"></div>
  <!-- App File sidebar - Right section Ends -->
@endsection

@section('content')
    
  <!-- File Manager app overlay -->
  <div class="app-file-overlay"></div>
  <div class="app-file-area">
    <!-- File App Content Area -->
    <!-- App File Header Starts -->
    <div class="app-file-header">
      <!-- Header search bar starts -->
      <div class="app-file-header-search flex-grow-1">
        <div class="sidebar-toggle d-block d-lg-none">
          <i class="bx bx-menu"></i>
        </div>
        <fieldset class="form-group position-relative has-icon-left m-0">
          <input type="text" class="form-control border-0 shadow-none" id="product_search" placeholder="Search products">
          <div class="form-control-position">
            <i class="bx bx-search"></i>
          </div>
        </fieldset>
      </div>
      <!-- Header search bar Ends -->
    </div>
    <!-- App File Header Ends -->

    <!-- App File Content Starts -->
    <div class="app-file-content p-2">
      <h5 id="product_window_header">All Products</h5>
      <label class="app-file-label"></label>
      <div id="product_list">
        @include('pages.admin.product.search')
      </div>
    </div>
  </div>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/extensions/bootstrap-treeview.min.js')}}"></script>
<!-- <script src="{{asset('//maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo')}}"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1jKOFLhfQoZD3xJISSPnSW9-4SyYPpjY" defer></script>
<script src="{{asset('vendors/js/charts/gmaps.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
    var base_url = "{{ url('/admin') }}";
    var tree_data = <?php echo $tree_data; ?>;
</script>
<script src="{{asset('js/scripts/pages/admin/product.js')}}"></script>
@endsection
