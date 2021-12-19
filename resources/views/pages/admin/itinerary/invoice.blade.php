@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

@section('content')
<!-- app invoice View Page -->
<form name='invoice_form' action="{{ route('admin.itinerary.send_invoice') }}" method="post">
    @csrf
    <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary_id }}">
    <section class="invoice-edit-wrapper">
        <div class="row">
            <!-- invoice view page -->
            <div class="col-xl-12 col-md-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pb-0 mx-25">
                            <!-- header section -->
                            <div class="row mx-0">
                                <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                                    <h6 class="invoice-number mr-75">Invoice</h6>
                                </div>
                                <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                                    <div
                                        class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted mr-75">Date Issue: </small>
                                            <fieldset>
                                                <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0"
                                                    id="issue_date" name="issue_date" placeholder="Enter Issue Date"
                                                    value="">
                                                @error('issue_date')
                                                <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                                @enderror
                                            </fieldset>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted mr-75">Date Due: </small>
                                            <fieldset class="justify-content-end">
                                                <input type="text" class="form-control pickadate mb-50 mb-sm-0"
                                                    id="due_date" name="due_date" placeholder="Enter Due Date" value="">
                                                @error('due_date')
                                                <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                                @enderror
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- logo and title -->
                            <div class="row my-2 py-50">
                                <div class="col-sm-6 col-12 order-2 order-sm-1">
                                    <h4 class="text-primary">Invoice</h4>
                                    <input type="text" class="form-control" placeholder="Enter Title" id="title"
                                        name="title">
                                    @error('title')
                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="card-body pt-50">
                            <!-- product details table-->
                            <div class="invoice-product-details ">
                                <div data-repeater-list="group-a">
                                    <div data-repeater-item>
                                        @php
                                        $price = intval($itinerary->budget);
                                        $margin = intVal($itinerary->margin_price);

                                        $real_price = floor($price + $price * $margin/100);
                                        $currency = $itinerary->get_currency->title
                                        @endphp
                                        <div class="row mb-50">
                                            <div class="col-3 col-md-3 invoice-item-title">Price({{ $currency }})</div>
                                            <div class="col-3 col-md-3 invoice-item-title">Qty</div>
                                            <div class="col-3 col-md-3 invoice-item-title">Tax({{ $currency }})</div>
                                            <div class="col-3 col-md-3 invoice-item-title">Payment Method</div>
                                        </div>
                                        <div class="invoice-item border rounded mb-1">
                                            <div class="invoice-item-filed row pt-1 px-1">
                                                <div class="col-md-3 col-12 form-group">

                                                    <input type="text" class="form-control" id="price" name="price"
                                                        value="{{ $real_price }}" readonly>
                                                </div>
                                                <div class="col-md-3 col-12 form-group">
                                                    <input type="text" class="form-control" id="quantitiy"
                                                        name="quantitiy" value="1" readonly>
                                                </div>
                                                <div class="col-md-3 col-12 form-group">
                                                    <input type="number" min="0" step="1" class="form-control" id="tax"
                                                        name="tax" value="0" required>
                                                    @error('tax')
                                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 col-12 form-group">
                                                    <fieldset>
                                                        <select name="payment_method" id="payment_method"
                                                            class="form-control">
                                                            <option value="1">Stripe</option>
                                                        </select>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- invoice subtotal -->
                            <hr>
                            <div class="invoice-subtotal pt-50">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 offset-lg-8">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                <span class="invoice-subtotal-title">Total Amount</span>
                                                <h6 class="invoice-subtotal-value mb-0" id="amount_description"></h6>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                <span class="invoice-subtotal-title">Tax</span>
                                                <h6 class="invoice-subtotal-value mb-0" id="tax_description"></h6>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                <span class="invoice-subtotal-title">Currency</span>
                                                <h6 class="invoice-subtotal-value mb-0">{{ $currency }}</h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="invoice-action-btn mb-1 ml-2">
                                    <button type="submit" class="btn btn-primary btn-block invoice-send-btn">
                                        <i class="bx bx-send"></i>
                                        <span>Send Invoice</span>
                                    </button>
                                </div>
                                <div class="invoice-action-btn mb-1 ml-2">
                                    <a href="{{ route('admin.itinerary') }}" class="btn btn-success btn-block">
                                        <i class='bx bx-arrow-back'></i>
                                        <span>Back</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
var base_url = "{{ url('/admin') }}";
</script>
<script src="{{asset('js/scripts/pages/admin/itinerary_invoice.js')}}"></script>
@endsection