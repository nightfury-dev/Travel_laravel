@extends('layouts.customer')

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
						$breadcrumbs1 = [
							["link" => "/", "name" => "Home"],["name" => 'Itinerary Task']
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
        </div>
    </div>
</section>

<!-- Dashboard Analytics Start -->

<section id="dashboard-analytics">
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header" style="border-left: 5px solid #ffdede">
                    <h5 class="card-title" style="color: #FF5B5C">Itinerary</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li><span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($itineraries) }}
                                </span></li>
                            <li><i class="bx bx-dots-vertical-rounded font-medium-3 align-middle"></i></li>
                        </ul>
                    </div>
                </div>
                <!-- datatable start -->
                <div class="card-content">
                    <div class="card-body">

                        <div class="invoice-list-wrapper">
                            <div class="action-dropdown-btn d-none">
                                <div class="dropdown invoice-filter-action"></div>
                            </div>
                            <div class="table-responsive">
                                @if(count($itineraries) == 0)
                                <h5>Nothing found.</h5>
                                @else
                                <table id="itinerary_table" class="table invoice-data-table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Customer</th>
                                        <th>REF.NO</th>
                                        <th>Budget</th>
                                        <th>Margin(%)</th>
                                        <th>Currency</th>
                                        <th>Duration</th>
                                        <th>Persons</th>
                                        <th>Rooms</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($itineraries as $itinerary)
                                    <tr>
                                        <td></td>
                                        <td class="text-bold-500">
                                        {{$itinerary->title}}
                                        </td>
                                        <td>
                                        {{$itinerary->get_customer()}}
                                        </td>
                                        <td class="text-bold-500">
                                        <span>{{$itinerary->reference_number}}</span>
                                        </td>
                                        <td class="text-bold-500">
                                        <span>{{$itinerary->budget}}</span>
                                        </td>
                                        <td class="text-bold-500">
                                        @if($itinerary->margin_price == 0)
                                            ----
                                        @else
                                            {{ $itinerary->margin_price }}(%)
                                        @endif
                                        </td>
                                        <td class="text-bold-500">
                                        @if($itinerary->currency == '0')
                                            ---
                                        @else
                                            {{$itinerary->currency}}
                                        @endif
                                        </td>
                                        <td>
                                        {{$itinerary->from_date}} - {{$itinerary->to_date}}
                                        </td>
                                        <td>
                                        adult({{ $itinerary->adult_number}}people), children({{ $itinerary->children_number}}people)
                                        </td>
                                        <td>
                                        {{$itinerary->single_count + $itinerary->double_count + $itinerary->twin_count + $itinerary->triple_count + $itinerary->family_count}} rooms
                                        </td>
                                        <td>
                                            @if($itinerary->status == 2)
                                            <div class="badge badge-pill badge-approved">Pending</div>
                                            @elseif($itinerary->status == 3)
                                            <div class="badge badge-pill badge-approved">Completed</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($itinerary->status == 2)
                                                    <a class="dropdown-item" href="{{ route('customer.itinerary.messages', ['id' => $itinerary->id]) }}"><i class="bx bx-message mr-1"></i> messages</a>
                                                    <a class="dropdown-item" href="{{ route('customer.itinerary.customer_info', ['id' => $itinerary->id]) }}"><i class="bx bx-edit-alt mr-1"></i> Send Customer Detail</a>
                                                    <a class="dropdown-item" href="{{ route('customer.itinerary.invoice', ['id' => $itinerary->id]) }}"><i class="bx bx-dollar mr-1"></i> Invoice</a>
                                                    @else
                                                    <a class="dropdown-item" href="{{ route('customer.itinerary.preview_itinerary', ['id' => $itinerary->id]) }}"><i class="bx bx-film mr-1"></i> Preview Itinerary</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
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
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var base_url = "{{ url('/customer/itinerary') }}";
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>

<script src="{{asset('js/scripts/pages/customer/itinerary.js')}}"></script>
@endsection