
<div class="email-detail-header">
    <div class="email-header-left d-flex align-items-center mb-1">
        <span class="go-back mr-50">
            <span id="inbox-menu">
                <i class="bx bx-left-arrow-alt cursor-pointer mr-50"></i>
            </span>
        </span>
        <h5 class="email-detail-title font-weight-normal mb-0">
            {{ $enquiry->title }}
            <input type="hidden" id="enquiry_id" name="enquiry_id" value="{{ $enquiry->id }}">
            <span class="badge badge-light-danger badge-pill ml-1">{{ $enquiry->reference_number}}</span>
        </h5>
    </div>
</div>
<div class="email-scroll-area">
    <div class="row">
        <div class="col-12">
            <div class="collapsible email-detail-head" id="message_area">
                @foreach($messages as $message)
                <div class="card collapse-header" role="tablist" id="message_record_{{ $message->id }}">
                    <div id="headingCollapse{{ $message->id }}" class="card-header d-flex justify-content-between align-items-center" data-toggle="collapse" role="tab" data-target="#collapse{{ $message->id }}" aria-expanded="false" aria-controls="collapse{{ $message->id }}">
                        <div class="collapse-title media">
                            <div class="pr-1">
                                <div class="avatar mr-75">
                                    @if($message->get_From->avatar_path != '')
                                    <img src="{{asset('storage/' . $message->get_From->avatar_path)}}" alt="avtar img holder" width="30" height="30">
                                    @else
                                    <img src="{{asset('images/img/avatar.png')}}" alt="avtar img holder" width="30" height="30">
                                    @endif
                                </div>
                            </div>
                            <div class="media-body mt-25">
                                @if($message->get_From->first_name != '' && $message->get_From->last_name != '')
                                <span class="text-primary">{{ $message->get_From->first_name }} {{ $message->get_From->last_name }}</span>
                                @else
                                <span class="text-primary">{{ $message->get_From->username }}</span>
                                @endif
                                <span class="d-sm-inline d-none"> &lt;{{ $message->get_From->email }}&gt;</span>
                                @if($message->get_To->first_name != '' && $message->get_To->last_name != '')
                                <small class="text-muted d-block">to {{ $message->get_To->first_name }} {{ $message->get_To->last_name }}</small>
                                @else
                                <small class="text-muted d-block">to {{ $message->get_To->username }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="information d-sm-flex d-none align-items-center">
                            <small class="text-muted mr-50">{{ date_format(date_create($message->created_at), "Y/m/d h:i") }}</small>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" id="fisrt-open-submenu"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bx-dots-vertical-rounded mr-0'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left customer-left-menu" aria-labelledby="fisrt-open-submenu">
                                    @if($message->from_id == Auth::user()->id)
                                    <a href="#" class="dropdown-item" onclick="delete_message({{ $message->id }})">
                                        <i class='bx bx-trash'></i>
                                        Delete Message
                                    </a>
                                    @else
                                    <a href="#" class="dropdown-item mail-reply">
                                        <i class='bx bx-share'></i>
                                        Reply
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapse{{ $message->id }}" role="tabpanel" aria-labelledby="headingCollapse{{ $message->id }}" class="collapse">
                        <div class="card-content">
                            <div class="card-body py-1">
                                {!! $message->message !!}
                            </div>
                            @if($message->file != '')
                            <div class="card-footer pt-0 border-top">
                                <label class="sidebar-label">Attached Files</label>
                                <ul class="list-unstyled mb-0">
                                    @php
                                        $file_arr = explode(',', $message->file);
                                    @endphp
                                    @foreach($file_arr as $file)
                                    <li class="cursor-pointer">
                                        <img src="{{asset('images/icon/sketch.png')}}" height="30" alt="sketch.png">
                                        @php 
                                            $file_name_arr = explode(':', $file);
                                        @endphp
                                        <small class="text-muted ml-1 attchement-text">{{ $file_name_arr[1] }}</small>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row px-2 mb-4">
        <div class="col-12 px-0">
            <div class="card shadow-none border rounded">
                <div class="card-body quill-wrapper" id="send_form_wrapper">
                    @if($enquiry->get_staff->first_name != '' && $enquiry->get_staff->last_name != '')
                    <span>Reply to {{ $enquiry->get_staff->first_name }} {{ $enquiry->get_staff->last_name }}</span>
                    @else
                    <span>Reply to {{ $enquiry->get_staff->username }}</span>
                    @endif
                    <div class="snow-container" id="detail-view-quill">
                        <div class="detail-view-editor"></div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="detail-quill-toolbar">
                                <span class="ql-formats mr-50">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-video"></button>
                                </span>
                            </div>
                            <div class="custom-file" style="margin-right: 20px; width: 300px;"> 
                                <input type="file" class="custom-file-input" id="attach_file">
                                <label class="custom-file-label" for="attach_file">Choose file</label>
                            </div>  
                            <button type="button" class="btn btn-danger" style="float" onclick="message_send();">
                                <i class='bx bx-send mr-25'></i>
                                <span class="d-none d-sm-inline"> Send</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>