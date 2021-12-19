@extends('layouts.customer')

@section('title','Travel Quoting')

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
                            href="{{route('customer.itinerary.invoice', array('id' => $itinerary_id ))}}">
                            <i class="bx bx-user align-middle"></i>
                            <span class="align-middle">Back</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="invoice-view-wrapper">
    <div class="row">
        <!-- invoice view page -->
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card invoice-print-area">
                <div class="card-content">
                    <div class="card-body pb-0 mx-25">
                        <!-- header section -->
                        <div class="row">
                            <div class="col-xl-4 col-md-12">
                                <span class="invoice-number mr-50">Invoice Number</span>
                                <span>{{ $itinerary_invoice->invoice_number }}</span>
                            </div>
                            <div class="col-xl-8 col-md-12">
                                <div class="d-flex align-items-center justify-content-xl-end flex-wrap">
                                    <div class="mr-3">
                                        <small class="text-muted">Date Issue:</small>
                                        <span>{{ $itinerary_invoice->issue_date }}</span>
                                    </div>
                                    <div>
                                        <small class="text-muted">Date Due:</small>
                                        <span>{{ $itinerary_invoice->due_date }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- logo and title -->
                        <div class="row my-3">
                            <div class="col-6">
                                <h4 class="text-primary">Invoice</h4>
                                <span>{{ $itinerary_invoice->title }}</span>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <!-- product details table-->
                    <div class="invoice-product-details table-responsive mx-md-25">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col" class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $itinerary_invoice->price }}({{ $itinerary->get_currency->title }})</td>
                                    <td>{{ $itinerary_invoice->quantitiy}}</td>
                                    <td>{{ $itinerary_invoice->tax }}({{ $itinerary->get_currency->title }})</td>
                                    <td>{{ $itinerary_invoice->payment_type == 1? 'Stripe': ''}}</td>
                                    <td class="text-primary text-right font-weight-bold">
                                        {{ $itinerary_invoice->price + $itinerary_invoice->tax }}({{ $itinerary->get_currency->title }})
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- invoice subtotal -->
                    <div class="card-body pt-0 mx-25">
                        <hr>
                        <div class="row">
                            <div class="col-4 col-sm-6 mt-75">
                                <p>Thanks for your business.</p>
                            </div>
                            <div class="col-8 col-sm-6 d-flex justify-content-end mt-75">
                                <div class="invoice-subtotal">
                                    <div class="invoice-calc d-flex justify-content-between">
                                        <span class="invoice-title">Total Amount</span>
                                        <span
                                            class="invoice-value">{{ $itinerary_invoice->price + $itinerary_invoice->tax }}({{ $itinerary->get_currency->title }})</span>
                                    </div>
                                    <div class="invoice-calc d-flex justify-content-between">
                                        <span class="invoice-title">Tax</span>
                                        <span
                                            class="invoice-value">{{ $itinerary_invoice->tax }}({{ $itinerary->get_currency->title }})</span>
                                    </div>
                                    <div class="invoice-calc d-flex justify-content-between">
                                        <span class="invoice-title">Currency</span>
                                        <span class="invoice-value">{{ $itinerary->get_currency->title }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="invoice-action-btn ml-2">
                                <form name="pay_form" method="post"
                                    action="{{ route('customer.itinerary.invoice_buy') }}">
                                    @csrf
                                    <input type="hidden" id="itinerary_id" name="itinerary_id"
                                        value="{{ $itinerary_id }}">
                                    <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $invoice_id }}">
                                    <input type="hidden" id="total_amount" name="total_amount"
                                        value="{{ $itinerary_invoice->price + $itinerary_invoice->tax }}">
                                    <input type="hidden" id="currency" name="currency"
                                        value="{{ $itinerary->get_currency->title }}">
                                    <button type='submit' class="btn btn-primary btn-block invoice-send-btn">
                                        <i class="bx bx-dollar"></i>
                                        <span>Pay Now</span>
                                    </button>
                                </form>
                            </div>
                            <div class="invoice-action-btn ml-2">
                                <a href="{{ route('customer.itinerary') }}" class="btn btn-success btn-block">
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
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script>
var base_url = "{{ url('/customer/itinerary') }}";
</script>
<script src="{{asset('js/scripts/pages/customer/invoice.js')}}"></script>
@endsection