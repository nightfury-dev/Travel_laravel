@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-analytics.css')}}">

<style>
  @media (min-width: 992px){
    .modal-lg, .modal-xl {
        max-width: 1410px;
    }
  }
  .mr-2, .mx-2 {
      margin-right: 0.5rem !important;
  }
  label{
    font-size: 0.86rem;
  }
</style>
@endsection

@section('content')
<section id="dashboard-analytics">
  <div class="row">
    <!-- Greetings Content Starts -->
    <div class="col-md-4 col-sm-12 dashboard-users">
      <div class="row  ">
        <!-- Statistics Cards Starts -->
        <div class="col-12">
          <div class="row">
            <div class="col-md-6 col-sm-12 dashboard-users-success">
              <div class="card text-center" style="cursor:pointer;" onclick="window.location.href='{{ route('admin.enquiry') }}'">
                <div class="card-content">
                  <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50" style="margin-top: 16px;">
                      <i class="bx bx-help-circle font-medium-5"></i>
                    </div>
                    <div class="text-muted line-ellipsis" style="margin-bottom: 15px;"><h7>Enquiry</h7></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12 dashboard-users-danger">
              <div class="card text-center" style="cursor:pointer;" onclick="window.location.href= '{{ route('admin.crm') }}'">
                <div class="card-content">
                  <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50" style="margin-top: 16px;">
                      <i class="bx bx-user font-medium-5"></i>
                    </div>
                    <div class="text-muted line-ellipsis" style="margin-bottom: 15px;"><h7>CRM</h7></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12 dashboard-users-warning">
              <div class="card text-center" style="cursor:pointer;" onclick="window.location.href='{{ route('admin.setting') }}'">
                <div class="card-content">
                  <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto mb-50" style="margin-top: 16px;">
                      <i class="bx bx-cog font-medium-5"></i>
                    </div>
                    <div class="text-muted line-ellipsis" style="margin-bottom: 15px;"><h7>Setting</h7></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12 dashboard-users-info">
              <div class="card text-center" style="cursor:pointer;" onclick="window.location.href='{{ route('admin.product') }}'">
                <div class="card-content">
                  <div class="card-body py-1">
                    <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto mb-50" style="margin-top: 16px;">
                      <i class="bx bxs-gift font-medium-5"></i>
                    </div>
                    <div class="text-muted line-ellipsis" style="margin-bottom: 15px;"><h7>Product</h7></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Revenue Growth Chart Starts -->
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title">Website Analytics</h4>
          <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div id="analytics-bar-chart"></div>
          </div>
        </div>
      </div>

    </div>
    <!-- Multi Radial Chart Starts -->
    <div class="col-md-4 col-sm-12 dashboard-visit">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title">Visits of 2019</h4>
          <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div id="multi-radial-chart"></div>
            <ul class="list-inline d-flex justify-content-around mb-0">
              <li> <span class="bullet bullet-xs bullet-primary mr-50"></span>Target</li>
              <li> <span class="bullet bullet-xs bullet-danger mr-50"></span>Mart</li>
              <li> <span class="bullet bullet-xs bullet-warning mr-50"></span>Ebay</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>

<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
@endsection

@section('page-scripts')
<script>
  var base_url = "{{ url('/admin') }}";
  var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>

<script src="{{asset('js/scripts/pages/admin/dashboard.js')}}"></script>
@endsection
