@extends('layouts.vendor')

@section('title','Travel Quoting')

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-chat.css')}}">
<style>
	
    .chat-content .chat-message-wrap {
        float: right !important;
        text-align: right !important;
        margin-bottom: 10px !important;
        max-width: calc(100% - 5rem) !important;
        clear: both !important;
        word-break: break-word !important;
    }

    .chat-content .chat-left .chat-message-wrap {
        text-align: left !important;
        float: left !important;
        color: #727E8C !important;
    }

    .chat-content .chat-body .chat-message-wrap .chat-text {
        padding: 0.75rem 1rem !important;
        color: #FFFFFF !important;
        background: #5A8DEE !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
    }

	.chat-content .chat-left .chat-body .chat-message-wrap .chat-text {
        padding: 0.75rem 1rem !important;
        color: #727E8C !important;
        background: #fafbfb !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
    }

    .chat-content .chat-body .chat-message-wrap .chat-time-text {
        color: #828D99 !important;
        font-size: 0.8rem !important;
        text-align:right!important;
        margin-bottom: 0px!important;
        white-space: nowrap !important;
    }

    .chat-content .chat-body .chat-message-wrap .chat-image {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        color: #FFFFFF !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        height: 200px;
        background-size: cover;
    }

	.chat-content .chat-left .chat-body .chat-message-wrap .chat-image {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        color: #727E8C !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        height: 200px;
        background-size: cover;
    }

    .chat-content .chat-body .chat-message-wrap .chat-file {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        background: #5A8DEE !important;
        color: #FFFFFF !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        background-size: cover;
    }

	.chat-content .chat-left .chat-body .chat-message-wrap .chat-file {
        text-align: right!important;
        padding: 0.75rem 1rem !important;
        clear: both !important;
        background: #fafbfb !important;
        color: #727E8C !important;
        border-radius: 0.267rem !important;
        margin-bottom: 0px!important;
        box-shadow: 0 2px 4px 0 rgba(90, 141, 238, 0.6) !important;
        width: 300px;
        background-size: cover;
    }

    .chat-content .chat-body .chat-message-wrap .chat-file a {
      color: #fff!important;
    }

	.chat-content .chat-left .chat-body .chat-message-wrap .chat-file a {
      color: #727E8C!important;
    }
</style>
@endsection

@section('sidebar-content')
<input type="hidden" id="session_user_id" value="{{ $session_user_id }}">
<input type="hidden" id="session_user_name" value="{{ $session_user_name }}">
<input type="hidden" id="session_user_avatar" value="{{ $session_user_avatar }}">

<input type="hidden" id="staff_id" value="">
<input type="hidden" id="staff_name" value="">
<input type="hidden" id="staff_avatar" value="">

<input type="hidden" id="group_id" value="">
<!-- app chat sidebar start -->
<div class="chat-sidebar card">
  <span class="chat-sidebar-close">
    <i class="bx bx-x"></i>
  </span>
  <div class="chat-sidebar-search">
    <div class="d-flex align-items-center">
      <div class="chat-sidebar-profile-toggle">
        <div class="avatar {{ $session_user_avatar == ''? 'bg-info m-0 mr-50': ''}}">
          @if($session_user_avatar == '')
            @php
              $user_name_arr = explode(' ', $session_user_name);
              $first_name_key = strtoupper(substr($user_name_arr[0], 0, 1));
              $last_name_key = strtoupper(substr($user_name_arr[1], 0, 1));
            @endphp
            <span class="avatar-content">{{ $first_name_key}} {{ $last_name_key }}</span>
          @else
            <img src="{{asset('storage/' . $session_user_avatar )}}" alt="user_avatar" height="36" width="36">
          @endif
        </div>
      </div>
      <fieldset class="form-group position-relative has-icon-left mx-75 mb-0">
        <input type="text" class="form-control round" id="chat-search" placeholder="Search">
        <div class="form-control-position">
          <i class="bx bx-search-alt text-dark"></i>
        </div>
      </fieldset>
    </div>
  </div>
  <div class="chat-sidebar-list-wrapper pt-2">
    <h6 class="px-2 pt-2 pb-25 mb-0">CONTACTS</h6>
    <ul class="chat-sidebar-list" id="contact_list"></ul>
  </div>
</div>{{--  --}}
<!-- app chat sidebar ends -->
@endsection


@section('content')
<!-- app chat overlay -->
<div class="chat-overlay"></div>
<!-- app chat window start -->
<section class="chat-window-wrapper">
  <div class="chat-start">
    <span class="bx bx-message chat-sidebar-toggle chat-start-icon font-large-3 p-3 mb-1"></span>
    <h4 class="d-none d-lg-block py-50 text-bold-500">Select a contact to start a chat!</h4>
    <button class="btn btn-light-primary chat-start-text chat-sidebar-toggle d-block d-lg-none py-50 px-1">Start
      Conversation!</button>
  </div>
  <div class="chat-area d-none">
    <div class="chat-header">
      <header class="d-flex justify-content-between align-items-center border-bottom px-1 py-75">
        <div class="d-flex align-items-center">
          <div class="chat-sidebar-toggle d-block d-lg-none mr-1"><i class="bx bx-menu font-large-1 cursor-pointer"></i>
          </div>
          <div class="avatar chat-profile-toggle m-0 mr-1" id="contact_person_avatar">
            <img src="" alt="avatar" height="36" width="36" />
            <span class="avatar-status-busy"></span>
          </div>
          <h6 class="mb-0" id="contact_person_name"></h6>
        </div>
        <div class="chat-header-icons">
          <span class="chat-icon-favorite">
            <i class="bx bx-star font-medium-5 cursor-pointer"></i>
          </span>
          <span class="dropdown">
            <i class="bx bx-dots-vertical-rounded font-medium-4 ml-25 cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer"
              id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
            </i>
            <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="JavaScript:void(0);"><i class="bx bx-pin mr-25"></i> Pin to top</a>
              <a class="dropdown-item" href="JavaScript:void(0);"><i class="bx bx-trash mr-25"></i> Delete chat</a>
              <a class="dropdown-item" href="JavaScript:void(0);"><i class="bx bx-block mr-25"></i> Block</a>
            </span>
          </span>
        </div>
      </header>
    </div>
    <!-- chat card start -->
    <div class="card chat-wrapper shadow-none">
      <div class="card-content">
        <div class="card-body chat-container">
          <div class="chat-content"></div>
        </div>
      </div>
      <div class="card-footer chat-footer border-top px-2 pt-1 pb-0 mb-1">
        <form class="d-flex align-items-center" onsubmit="widgetMessageSend();" action="javascript:void(0);">
          <span><i class="bx bx-face cursor-pointer"></i></span>
          <span onclick="$('#attach_file').focus().trigger('click');"><i class="bx bx-paperclip ml-75 cursor-pointer"></i></span>
          <input name="attach_file" id="attach_file" type="file" style="display:none" size="2000000" onchange="get_file(event)">
          <input type="text" class="form-control chat-message-send mx-1" placeholder="Type your message here...">
          <button type="submit" class="btn btn-primary glow send d-lg-flex"><i class="bx bx-paper-plane"></i>
            <span class="d-none d-lg-block ml-1">Send</span>
          </button>
        </form>
      </div>
    </div>
    <!-- chat card ends -->
  </div>
</section>
<!-- app chat window ends -->
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/vendor/chat.js')}}"></script>
@endsection
