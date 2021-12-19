{{-- navabar  --}}
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu
@if(isset($configData['navbarType'])){{$configData['navbarClass']}} @endif"
    data-bgcolor="@if(isset($configData['navbarBgColor'])){{$configData['navbarBgColor']}}@endif">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a
                                class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                    class="ficon bx bx-menu"></i></a></li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="flag-icon flag-icon-{{ $languages[0]->title }}"></i><span
                                class="selected-language">{{ $languages[0]->name }}</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                            @foreach($languages as $lg)
                            <a class="dropdown-item" href="#" data-language="{{ $lg->title }}">
                                <i class="flag-icon flag-icon-{{ $lg->title}} mr-50"></i> {{ $lg->name }}
                            </a>
                            @endforeach
                        </div>
                    </li>
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                            <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
                            <span class="badge badge-pill badge-danger badge-up" id="message_alarm_count"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header px-1 py-75 d-flex justify-content-between">
                                    <span class="notification-title" id="message_alarm_title"></span>
                                    <span class="text-bold-400 cursor-pointer" id="mark_allmessage">Mark all as
                                        read</span>
                                </div>
                            </li>
                            <li class="scrollable-container media-list" id="message_alarm_list">
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name">{{Auth::user()->username}}</span>
                            </div>
                            @if(Auth::user()->avatar_path)
                            <span><img class="round" src="{{asset('storage/' . Auth::user()->avatar_path)}}"
                                    alt="avatar" height="40" width="40"></span>
                            @else
                            <span><img class="round" src="{{asset('images/img/avatar.png')}}" alt="avatar" height="40"
                                    width="40"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="{{ route('vendor.profile') }}">
                                <i class="bx bx-user mr-50"></i> Profile
                            </a>
                            <form id="logout-form" name="logout-form" action="{{ route('logout') }}" method="post"
                                style="display:none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off mr-50"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>