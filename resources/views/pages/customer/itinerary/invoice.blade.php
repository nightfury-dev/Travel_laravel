@extends('layouts.customer')

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
                        $name = "Invoice";
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
                        <a class="nav-link btn-primary glow white" id="account-tab"
                            href="{{route('customer.itinerary')}}">
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
                    <h5 class="card-title" style="color: #FF5B5C">Invoice</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li><span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($itinerary_invoice) }}
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
                                @if(count($itinerary_invoice) == 0)
                                <h5>Nothing found.</h5>
                                @else
                                <table id="invoice_table" class="table invoice-data-table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
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
                                        @foreach($itinerary_invoice as $invoice)
                                        <tr>
                                            <td></td>
                                            <td class="text-bold-500">
                                                {{$invoice->title}}
                                            </td>
                                            <td>
                                                {{$invoice->invoice_number}}
                                            </td>
                                            <td class="text-bold-500">
                                                <span>
                                                    @php
                                                    $date=date_create($invoice->issue_date);
                                                    echo date_format($date,"Y/m/d");
                                                    @endphp
                                                </span>
                                            </td>
                                            <td class="text-bold-500">
                                                <span>
                                                    @php
                                                    $date=date_create($invoice->issue_date);
                                                    echo date_format($date,"Y/m/d");
                                                    @endphp
                                                </span>
                                            </td>
                                            <td class="text-bold-500">
                                                {{ $invoice->quantitiy }}
                                            </td>
                                            <td class="text-bold-500">
                                                @php
                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY );
                                                echo $fmt->formatCurrency($invoice->price,
                                                $invoice->get_Itinerary())."\n";
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                $fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY );
                                                echo $fmt->formatCurrency($invoice->tax,
                                                $invoice->get_Itinerary())."\n";
                                                @endphp
                                            </td>
                                            <td>
                                                Stripe Payment Gateway
                                            </td>
                                            <td>
                                                @if($invoice->status == 0)
                                                <div class="badge badge-pill badge-approved">Pending</div>
                                                @elseif($invoice->status == 1)
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
                                                            href="{{ route('customer.itinerary.invoice_detail', ['invoice_id' => $invoice->id]) }}"><i
                                                                class="bx bx-dollar mr-1"></i> Pay</a>
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

<script src="{{asset('js/scripts/pages/customer/invoice.js')}}"></script>
@endsection