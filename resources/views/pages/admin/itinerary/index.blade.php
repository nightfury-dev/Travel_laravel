@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css"
    href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

@section('content')
<section id="basic-tabs-components">
    <div class="row">
        <div class="col-md-12">
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
                <div class="card-content">
                    <div class="card-body">
                        <div class="invoice-list-wrapper">
                            <div class="action-dropdown-btn d-none"></div>
                            <div class="table-responsive">
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
                                                adult({{ $itinerary->adult_number}}people),
                                                children({{ $itinerary->children_number}}people)
                                            </td>
                                            <td>
                                                {{$itinerary->single_count + $itinerary->double_count + $itinerary->twin_count + $itinerary->triple_count + $itinerary->family_count}}
                                                rooms
                                            </td>
                                            <td>
                                                @if($itinerary->status == 0)
                                                <div class="badge badge-pill badge-danger">Draft</div>
                                                @elseif($itinerary->status == 1)
                                                <div class="badge badge-pill badge-warning">Pending</div>
                                                @elseif($itinerary->status == 2)
                                                <div class="badge badge-pill badge-approved">Approved</div>
                                                @elseif($itinerary->status == 3)
                                                <div class="badge badge-pill badge-approved">Completed</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.build', ['id' => $itinerary->id, 'type' => 1]) }}"><i
                                                                class="bx bx-edit-alt mr-1"></i> Edit</a>
                                                        @if($itinerary->status == 0)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.task', ['itinerary_id' => $itinerary->id, 'type' => 1]) }}"><i
                                                                class="bx bx-info-circle mr-1"></i> Tasks</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            onClick="itinerary_del({{$itinerary->id}})"><i
                                                                class="bx bx-trash mr-1"></i> Delete</a>
                                                        @elseif($itinerary->status == 1)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.task', ['itinerary_id' => $itinerary->id, 'type' => 1]) }}"><i
                                                                class="bx bx-info-circle mr-1"></i> Tasks</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            onClick="itinerary_del({{$itinerary->id}})"><i
                                                                class="bx bx-trash mr-1"></i> Delete</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.confirm', ['itinerary_id' => $itinerary->id]) }}"><i
                                                                class="bx bx-info-circle mr-1"></i> Confirm</a>
                                                        @elseif($itinerary->status == 2)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.send_itinerary', array('itinerary_id' => $itinerary->id)) }}"><i
                                                                class="bx bxs-right-arrow-square mr-1"></i>Send
                                                            Itinerary</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.contact', array('itinerary_id' => $itinerary->id)) }}"><i
                                                                class="bx bxs-message mr-1"></i>Contact</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.invoice', array('itinerary_id' => $itinerary->id)) }}"><i
                                                                class="bx bx-dollar mr-1"></i>Invoice</a>
                                                        @elseif($itinerary->status == 3)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.itinerary.preview_itinerary', array('itinerary_id' => $itinerary->id)) }}"><i
                                                                class="bx bx-film mr-1"></i>Review Itinerary</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
var base_url = "{{ url('/admin') }}";
</script>
<script src="{{asset('js/scripts/pages/admin/itinerary.js')}}"></script>
@endsection