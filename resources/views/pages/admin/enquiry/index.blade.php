@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-email.css')}}">
<style>
.email-application .content-area-wrapper .content-right {
    width: 100%;
}

.email-application .content-area-wrapper .content-right .email-app-details.show {
    width: 100%
}

.enquiry-tool .dropdown-toggle:after {
    display: none;
}

.pagination .page-item .page-link {
    text-align: center;
    width: 38px;
    height: 38px;
}

.pagination {
    margin-bottom: 0;
}

.email-application .content-area-wrapper .content-right .email-app-list-wrapper .email-app-list .email-action .action-left ul li i {
    top: 3px;
}

.customer-left-menu {
    top: 30px !important;
    left: -175px !important;
}
</style>
@endsection

@section('content')
<div class="email-app-area">
    <div class="email-app-list-wrapper">
        <div class="email-app-list" id="enquiry_section">
            @include('pages.admin.enquiry.search')
        </div>
    </div>

    <div class="email-app-details"></div>
</div>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection

@section('page-scripts')
<script>
var base_url = "{{ url('/admin') }}";
var assets_url = "{{ asset('/') }}";
var link_id = "{{ $link_id }}";
var message_id = "{{ $message_id }}";
</script>
<script src="{{asset('js/scripts/pages/admin/enquiry.js')}}"></script>
@endsection