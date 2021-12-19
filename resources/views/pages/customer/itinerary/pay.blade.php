@extends('layouts.customer')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
@endsection

@section('custom-horizontal-style')
@endsection

@section('page-styles')
@endsection

@section('content')
<!-- app invoice View Page -->
<form name='invoice_form' action="{{ route('customer.itinerary.invoice_pay') }}" method="post">
    @csrf
    <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary_id }}">
    <section class="invoice-edit-wrapper">
        <div class="row">
            <!-- invoice view page -->
            <div class="col-xl-12 col-md-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <h4 class="text-primary">Card Number</h4>
                                    <input autocomplete='off' class='form-control card-number' size='20' type='text'
                                        name="card_no">
                                    @error('card_no')
                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-primary">CVV</h4>
                                    <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311'
                                        size='4' type='text' name="cvvNumber">
                                    @error('cvvNumber')
                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-primary">Expiration Month</h4>
                                    <input class='form-control card-expiry-month' placeholder='MM' size='4' type='text'
                                        name="ccExpiryMonth">
                                    @error('ccExpiryMonth')
                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-primary">Expiration Month</h4>
                                    <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'
                                        name="ccExpiryYear">
                                    @error('ccExpiryYear')
                                    <div style="color: red; font-size: 12px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class='row mb-2'>
                                <div class="col-md-6">
                                    <h4 class="text-primary">Amount</h4>
                                    <input class='form-control' type='text' name="amount" id="amount" readonly
                                        value='{{ $total_amount }}'>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-primary">Currency</h4>
                                    <input class='form-control' size='20' type='text' name="currency" id="currency"
                                        readonly value='{{ $currency }}'>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="invoice-action-btn mb-1 ml-2">
                                            <button type="submit" class="btn btn-primary btn-block invoice-send-btn">
                                                <i class="bx bx-send"></i>
                                                <span>Buy</span>
                                            </button>
                                        </div>
                                    </div>
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
@endsection

@section('page-scripts')
@endsection