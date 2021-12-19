@extends('layouts.vendor')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css"
    href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
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
                    <h5 class="card-title" style="color: #FF5B5C">Itinerary Task</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li><span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($task_list) }}
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
                                @if(count($task_list) == 0)
                                <h5>Nothing found.</h5>
                                @else
                                <table id="task_table" class="table invoice-data-table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Task Name</th>
                                            <th>Staff</th>
                                            <th>Itinerary</th>
                                            <th>Traveller</th>
                                            <th>Total Budget</th>
                                            <th>Date</th>
                                            <th>Priority</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($task_list as $task)
                                        <tr>
                                            <td></td>
                                            <td>{{$task->task_name}}</td>
                                            <td>{{$task->get_assigned_to()}}</td>
                                            <td>{{$task->get_itinerary->title}}</td>
                                            <td>adult({{ $task->get_itinerary->adult_number}}people),
                                                children({{ $task->get_itinerary->children_number}}people)</td>
                                            <td>
                                                @php
                                                if($task->get_itinerary->budget == ''){
                                                $real_budget = "UnKnown";
                                                }
                                                else {
                                                $real_budget = $task->get_itinerary->budget +
                                                $task->get_itinerary->budget * ($task->get_itinerary->margin_price/100);
                                                }
                                                @endphp
                                                {{$real_budget}}{{$task->get_itinerary->get_currency?'(' . $task->get_itinerary->get_currency->title . ')' : ''}}
                                            </td>
                                            <td>{{$task->start_date}} ~ {{$task->end_date}}</td>
                                            <td>
                                                @if($task->get_priority() == 1)
                                                <div class="badge badge-pill badge-danger">High</div>
                                                @elseif($task->get_priority() == 2)
                                                <div class="badge badge-pill badge-light-danger">Medium</div>
                                                @else
                                                <div>Low</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item"
                                                            href="{{ route('vendor.itinerary.contact', ['task_id' => $task->id]) }}">
                                                            <i class="bx bx-edit-alt mr-1"></i> contact
                                                        </a>

                                                        <a class="dropdown-item"
                                                            href="{{ route('vendor.itinerary.detail', ['task_id' => $task->id]) }}">
                                                            <i class="bx bx-edit-alt mr-1"></i> detail
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('vendor.itinerary.confirm', ['task_id' => $task->id]) }}">
                                                            <i class="bx bx-check mr-1"></i> Confirm
                                                        </a>

                                                        <a class="dropdown-item"
                                                            href="{{ route('vendor.itinerary.decline', ['task_id' => $task->id]) }}">
                                                            <i class="bx bx-x mr-1"></i> Decline
                                                        </a>
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
var base_url = "{{ url('/vendor/itinerary') }}";
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>

<script src="{{asset('js/scripts/pages/vendor/itinerary.js')}}"></script>
@endsection