@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
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
                    <h5 class="card-title" style="color: #FF5B5C">Booking</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li><span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($bookings) }}
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
                                @if(count($bookings) == 0)
                                <h5>Nothing found.</h5>
                                @else
                                <table id="booking_table" class="table invoice-data-table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Itinerary</th>
                                            <th>Title</th>
                                            <th>REF.NO</th>
                                            <th>Issue Date</th>
                                            <th>Due Date</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Tax</th>
                                            <th>Payment Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                        <tr>
                                            <td></td>
                                            <td class="text-bold-500">
                                                {{$booking->get_iti->title}}
                                            </td>
                                            <td class="text-bold-500">
                                                {{$booking->title}}
                                            </td>
                                            <td>
                                                {{$booking->invoice_number}}
                                            </td>
                                            <td class="text-bold-500">
                                                <span>
                                                    @php
                                                    $date=date_create($booking->issue_date);
                                                    echo date_format($date,"Y/m/d");
                                                    @endphp
                                                </span>
                                            </td>
                                            <td class="text-bold-500">
                                                <span>
                                                    @php
                                                    $date=date_create($booking->issue_date);
                                                    echo date_format($date,"Y/m/d");
                                                    @endphp
                                                </span>
                                            </td>
                                            <td class="text-bold-500">
                                                {{ $booking->quantitiy }}
                                            </td>
                                            <td class="text-bold-500">
                                                @php
                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY
                                                );
                                                echo $fmt->formatCurrency($booking->price,
                                                $booking->get_Itinerary())."\n";
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY
                                                );
                                                echo $fmt->formatCurrency($booking->tax,
                                                $booking->get_Itinerary())."\n";
                                                @endphp
                                            </td>
                                            <td>
                                                Stripe Payment Gateway
                                            </td>
                                            <td>
                                                @if($booking->status == 0)
                                                <div class="badge badge-pill badge-approved">Pending</div>
                                                @elseif($booking->status == 1)
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
                                                            href="{{ route('admin.booking.preview', ['booking_id' => $booking->id]) }}"><i
                                                                class="bx bx-edit mr-1"></i> Preview</a>
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
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
var base_url = "{{ url('/admin') }}";
</script>
<script src="{{asset('js/scripts/pages/admin/booking.js')}}"></script>
@endsection