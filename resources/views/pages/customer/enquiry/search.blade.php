<div class="email-action">
    <div class="action-left d-flex align-items-center">
        <div class="checkbox checkbox-shadow checkbox-sm selectAll mr-50">
            <input type="checkbox" id="checkboxsmall">
            <label for="checkboxsmall"></label>
        </div>
        <ul class="list-inline m-0 d-flex">
            <li class="list-inline-item">
                <a href="{{ route('customer.enquiry.create') }}" class="btn btn-icon action-icon">
                    <i class="bx bx-plus"></i>
                </a>
            </li>
            <li class="list-inline-item" id="delete_enquiry">
                <button type="button" class="btn btn-icon action-icon">
                    <i class="bx bx-trash"></i>
                </button>
            </li>
            <li class="list-inline-item">
                <div class="dropdown">
                    <button type="button" class="btn btn-icon dropdown-toggle action-icon" id="tag"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-badge"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="tag">
                        <a href="javascript:void(0)" onclick="filter_by_status(-1)"
                            class="dropdown-item align-items-center">
                            <span class="bullet bullet-info bullet-sm"></span>
                            <span>All Enquiry</span>
                        </a>
                        <a href="javascript:void(0)" onclick="filter_by_status(2)"
                            class="dropdown-item align-items-center">
                            <span class="bullet bullet-success bullet-sm"></span>
                            <span>Completed Enquiry</span>
                        </a>
                        <a href="javascript:void(0)" onclick="filter_by_status(1)"
                            class="dropdown-item align-items-center">
                            <span class="bullet bullet-warning bullet-sm"></span>
                            <span>Setted as Itinerary</span>
                        </a>
                        <a href="javascript:void(0)" onclick="filter_by_status(0)"
                            class="dropdown-item align-items-center">
                            <span class="bullet bullet-danger bullet-sm"></span>
                            <span>Unsetted as Itinerary</span>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="action-right d-flex flex-grow-1 align-items-center justify-content-around">
        <div class="email-fixed-search flex-grow-1">
            <fieldset class="form-group position-relative has-icon-left m-0">
                <input type="text" class="form-control" id="email-search" placeholder="Search Enquiry"
                    value="{{ $search_string }}">
                <div class="form-control-position">
                    <i class="bx bx-search"></i>
                </div>
            </fieldset>
        </div>
        <div id="enquiry_pagination" class="enquiry_pagination" style="float: right;">
            {{ $enquiries->onEachSide(5)->links() }}
        </div>
    </div>
</div>

<div class="email-user-list list-group">
    @if($total_enquiry_count > 0)
    <ul class="users-list-wrapper media-list">
        @foreach($enquiries as $enquiry)
        <li class="media">
            <div class="user-action">
                <div class="checkbox-con mr-25">
                    <div class="checkbox checkbox-shadow checkbox-sm">
                        <input type="checkbox" id="checkboxsmall{{ $loop->index }}" data-id="{{ $enquiry->id }}">
                        <label for="checkboxsmall{{ $loop->index }}"></label>
                    </div>
                </div>
            </div>
            <div class="pr-50">
                <div class="avatar">
                    @if($enquiry->get_account->avatar_path != '')
                    <img src="{{asset('storage/' . $enquiry->get_account->avatar_path)}}" alt="avtar img holder">
                    @else
                    <img src="{{asset('images/img/avatar.png')}}" alt="avtar img holder">
                    @endif
                </div>
            </div>
            <div class="media-body" data-id="{{ $enquiry->id }}" data-message="{{ $enquiry->id }}">
                <div class="user-details">
                    <div class="mail-items">
                        <span
                            class="list-group-item-text text-truncate">{{ $enquiry->title }}({{ $enquiry->reference_number }})</span>
                    </div>
                    <div class="mail-meta-item">
                        <span class="float-right">
                            <span class="mail-date">{{ date_format(date_create($enquiry->created_at), "Y/m/d") }}</span>
                        </span>
                    </div>
                </div>
                <div class="mail-message">
                    <p class="list-group-item-text truncate mb-0">
                        {{ $enquiry->note }}
                    </p>
                    <div class="mail-meta-item d-flex align-items-center justiy-content-between"
                        style="margin-right: 30px;">
                        <span class="float-right">
                            @if($enquiry->is_created_itinerary == 0)
                            <span class="bullet bullet-danger bullet-sm"></span>
                            @elseif($enquiry->is_created_itinerary == 1)
                            <span class="bullet bullet-warning bullet-sm"></span>
                            @else
                            <span class="bullet bullet-success bullet-sm"></span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="dropdown enquiry-tool" style="margin-left: 30px;">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class='bx bx-dots-vertical-rounded mr-0'></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('customer.enquiry.edit', ['enquiry_id' => $enquiry->id]) }}"
                        class="dropdown-item">
                        <i class='bx bx-edit mr-1'></i>
                        Edit
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="delete_enquiry({{ $enquiry->id }})">
                        <i class='bx bx-trash mr-1'></i>
                        Delete
                    </a>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    @else
    <div class="no-results" style="display:block;">
        <i class="bx bx-error-circle font-large-2"></i>
        <h5>No Items Found</h5>
    </div>
    @endif
</div>