<div class="collapsible">
    @foreach($contacts as $contact)
    <div class="card collapse-header" id="collapse_header_{{ $contact->id }}">
        <div id="contact_header_{{ $loop->index }}" class="card-header" data-toggle="collapse" role="button"
            data-target="#contact_content_{{ $loop->index }}" aria-expanded="false"
            aria-controls="contact_content_{{ $loop->index }}">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75">
                        @if($contact->get_From->avatar_path != '')
                        <img src="{{asset('storage/' . $contact->get_From->avatar_path)}}" alt="avtar img holder"
                            width="30" height="30">
                        @else
                        <img src="{{asset('images/img/avatar.png')}}" alt="avtar img holder" width="30" height="30">
                        @endif
                    </div>
                    <div class="media-body mt-25">
                        @if($contact->get_From->first_name != '' && $contact->get_From->last_name != '')
                        <span class="text-primary">{{ $contact->get_From->first_name }}
                            {{ $contact->get_From->last_name }}</span>
                        @else
                        <span class="text-primary">{{ $contact->get_From->username }}</span>
                        @endif
                        <span class="d-sm-inline d-none"> &lt;{{ $contact->get_From->email }}&gt;</span>
                        @if($contact->get_To->first_name != '' && $contact->get_To->last_name != '')
                        <small class="text-muted d-block">to {{ $contact->get_To->first_name }}
                            {{ $contact->get_To->last_name }}</small>
                        @else
                        <small class="text-muted d-block">to {{ $contact->get_To->username }}</small>
                        @endif
                    </div>
                </div>
                <div class="information d-sm-flex d-none align-items-center">
                    <small
                        class="text-muted mr-50">{{ date_format(date_create($contact->created_at), "Y/m/d h:i") }}</small>
                    <div>
                        @if($contact->from_id == Auth::user()->id)
                        <a href="javascript:void(0)" class="delete_message" data-id="{{ $contact->id }}"
                            style="color: #777;">
                            <i class='bx bx-trash'></i>
                        </a>
                        @else
                        <a href="javascript:void(0)" class="mail-reply" style="color: #777;">
                            <i class='bx bx-share'></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="contact_content_{{ $loop->index }}" role="tabpanel" aria-labelledby="contact_header_{{ $loop->index }}"
            class="collapse">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">{!! $contact->message !!}</div>
                        @if($contact->file != '')
                        <div class="col-md-12 mt-2">
                            <label class="sidebar-label">Attached Files</label>
                            <ul class="list-unstyled mb-0">
                                @php
                                $file_arr = explode(',', $contact->file);
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
        </div>
    </div>
    @endforeach
</div>
<div class="d-flex align-items-center justify-content-end mt-3">
    {{ $contacts->appends(array('task_id' => $task->id))->onEachSide(5)->links() }}
</div>