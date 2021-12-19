<!DOCTYPE html>
@isset($pageConfigs)
  {!! Helper::updatevendorPageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::vendorClasses();
@endphp

<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
 data-textdirection="{{$configData['direction'] == 'rtl' ? 'rtl' : 'ltr' }}">
  <!-- BEGIN: Head-->

    <head>
    <meta  charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon.ico')}}">
    
    @include('panels.styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
    </head>
    
     <!-- BEGIN: Body-->
      <body class="vertical-layout vertical-menu-modern 2-columns 
      @if($configData['isMenuCollapsed'] == true){{'menu-collapsed'}}@endif 
      @if($configData['theme'] === 'dark'){{'dark-layout'}} @elseif($configData['theme'] === 'semi-dark'){{'semi-dark-layout'}} @else {{'light-layout'}} @endif
      @if($configData['isContentSidebar'] === true) {{'content-left-sidebar'}} @endif @if(isset($configData['navbarType'])){{$configData['navbarType']}}@endif 
      @if(isset($configData['footerType'])) {{$configData['footerType']}} @endif 
      {{$configData['bodyCustomClass']}} 
      @if($configData['isCardShadow'] === false){{'no-card-shadow'}}@endif" 
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

        <!-- BEGIN: Header-->
        @include('panels.vendor')
        <!-- END: Header-->

        <!-- BEGIN: Main Menu-->
        @include('panels.sidebar')
        <!-- END: Main Menu-->

        <!-- BEGIN: Content-->
        <div class="app-content content">
        {{-- Application page structure --}}
        @if($configData['isContentSidebar'] === true)
          <div class="content-area-wrapper">
            <div class="sidebar-left">
              <div class="sidebar">
                @yield('sidebar-content')
              </div>
            </div>
            <div class="content-right">
                <div class="content-overlay"></div>
              <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                  @yield('content')
                </div>
              </div>
            </div>
          </div>
        @else
          <div class="content-overlay"></div>
          <div class="content-wrapper">
            <div class="content-header row">
              @if($configData['pageHeader']=== true && isset($breadcrumbs))
                @include('panels.breadcrumbs')
              @endif
            </div>
            <div class="content-body">
              @yield('content')
            </div>
          </div>
        @endif
        </div>
        
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <!-- BEGIN: Footer-->
          @include('panels.footer')
        <!-- END: Footer-->

        @include('panels.scripts')
      </body>
      <!-- END: Body-->

      <script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
      <script src="{{asset('js/scripts/pages/vendor/message.js')}}"></script>
</html>
