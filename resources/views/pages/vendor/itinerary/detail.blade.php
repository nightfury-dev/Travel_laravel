@extends('layouts.vendor')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">

@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

@section('content')

<section id="basic-tabs-components">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="row breadcrumbs-top">
            <div class="col-6">
                <h5 class="content-header-title float-left pr-1 mb-0">@yield('title')</h5>
                <div class="breadcrumb-wrapper col-12">
                    <?php
						
                        $name = $task->task_name;
						$breadcrumbs1 = [
							["link" => "/", "name" => "Home"],["name" => $name]
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
            <div class="col-6">
                <ul class="nav nav-tabs float-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link btn-primary glow white" id="account-tab" href="{{route('vendor.itinerary') }}">
                            <i class="bx bx-user align-middle"></i>
                            <span class="align-middle">Back</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="dashboard-analytics">
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header" style="border-left: 5px solid #ffdede">
                    <h5 class="card-title" style="color: #FF5B5C">Task Details</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li>
                                <span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($task_details) }}
                                </span>
                            </li>
                            <li><i class="bx bx-dots-vertical-rounded font-medium-3 align-middle"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="invoice-list-wrapper">
                            <div class="action-dropdown-btn d-none">
                                <div class="dropdown invoice-filter-action"></div>
                            </div>
                            <div class="table-responsive">
                                @if(count($task_details) == 0)
                                <h5>Nothing found.</h5>
                                @else
                                <table id="task_detail_table" class="table invoice-data-table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Product</th>
                                            <th>Title</th>
                                            <th>Season</th>
                                            <th>Date</th>
                                            <th>Price</th>
                                            <th>Margin</th>
                                            <th>Persons</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($task_details as $detail)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <img src="{{ asset('storage/'.$detail->get_product_main_gallery()) }}" alt="product gallery" class="img-thumbnail" style="width:120px; height:80px;">
                                            </td>
                                            <td>{{ $detail->get_product_title() }}</td>
                                            <td>{!! $detail->get_product_seasons() !!}</td>
                                            <td>{{ $detail->get_Datetime() }}</td>
                                            <td>{!! $detail->get_product_prices() !!}</td>
                                            <td>{{ $detail->get_margin() }}</td>
                                            <td>{{ $detail->get_persons() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- datatable ends -->
            </div>
        </div>
    </div>
</section>
<!-- Dashboard Analytics end -->
@endsection

@section('vendor-scripts')

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var base_url = "{{ url('/vendor/itinerary') }}";
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>

<script src="{{asset('js/scripts/pages/vendor/itinerary.js')}}"></script>
@endsection