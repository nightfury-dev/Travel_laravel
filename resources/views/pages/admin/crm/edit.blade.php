@extends('layouts.admin')

@section('title','CRM')

@section('vendor-styles')

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/forms/validation/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.Jcrop.min.css')}}">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<style type="text/css">
.nounderline, .violet{
    color: #7c4dff !important;
}
.btn-dark {
    background-color: #7c4dff !important;
    border-color: #7c4dff !important;
}
.btn-dark .file-upload {
    padding: 10px 0px;
    position: absolute;
    left: 0;
    opacity: 0;
    cursor: pointer;
}
.profile-img img{
    width: 200px;
    height: 200px;
    border-radius: 50%;
}   
</style>
@endsection
@section('content')

<div class="modal" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Crop Image And Upload</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="resizer"></div>
                <button class="btn rotate float-lef" data-deg="90" > 
                <i class="fas fa-undo"></i></button>
                <button class="btn rotate float-right" data-deg="-90" > 
                <i class="fas fa-redo"></i></button>
                <hr>
                <button class="btn btn-block btn-dark" id="upload" > 
                Crop And Upload</button>
            </div>
        </div>
    </div>
</div>

<section id="dashboard-analytics">
    <form class="form-horizontal" method="post" action="{{ route('admin.crm.update') }}" novalidate>
        @csrf
        <div class="card">
            <div class="card-header" style="padding-bottom: 0;">
                <h3>Edit Account</h3>
            </div> 
            <hr>
            <input type="hidden" name="avatar_path" id="avatar_path" value="">
            <input type="hidden" name="account_id" id="account_id" value="{{$account->id}}">
            <div class="card-content">
                <div class="card-body">
                    <div class="media mb-2">
                        <a class="mr-2" href="javascript:void(0)">
                            @if($account->avatar_path)
                            <img src="{{asset('storage/' . $account->avatar_path)}}" id="profile-pic" alt="users avatar"
                                class="users-avatar-shadow rounded-circle" height="64" width="64">
                            @else
                            <img src="{{asset('images/img/avatar.png')}}" id="profile-pic" alt="users avatar"
                                class="users-avatar-shadow rounded-circle" height="64" width="64">
                            @endif
                        </a>
                        <div class="btn btn-dark" style="margin-top: 15px;">
                            <input type="file" class="file-upload" id="file-upload" name="profile_picture" accept="image/*"> Upload Change Photo
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="controls">
                                    <label>Username</label>
                                    <input type="text" class="form-control" placeholder="Username"
                                        data-validation-required-message="This username field is required" value="{{$account->username}}"
                                        name="username" id="username" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Account Type</label>
                                <select class="form-control" name="account_type" id="account_type" required>
                                    @foreach($account_type as $type)
                                        @if($account->account_type == $type->id)
                                            <option value="{{ $type->id }}" selected>{{ $type->title }}</option>
                                        @else
                                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">                                
                            <div class="form-group">
                                <div class="controls">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" placeholder="First Name" required 
                                        data-validation-required-message="This first name field is required" value="{{$account->first_name}}"
                                        name="first_name" id="first_name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">                                
                            <div class="form-group">
                                <div class="controls">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" placeholder="Last Name" required 
                                        data-validation-required-message="This last name field is required" value="{{$account->last_name}}"
                                        name="last_name" id="last_name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6" id="company_div">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" placeholder="Company name" name="company_name" id="company_name" value="{{$account->billing_company_name}}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6" id="category_div">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="category" id="category" required>
                                    <option value="1" {{ $account->category==1? 'selected': ''}}>Accommodation</option>
                                    <option value="2" {{ $account->category==2? 'selected': ''}}>Transport</option>
                                    <option value="3" {{ $account->category==3? 'selected': ''}}>Activites and Attraction</option>
                                    <option value="4" {{ $account->category==4? 'selected': ''}}>Guide</option>
                                    <option value="5" {{ $account->category==5? 'selected': ''}}>Transport</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6 style="font-weight: 500; padding-bottom: 20px; padding-top: 20px;" >Main Information</h6>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6 style="font-weight: 500; padding-bottom: 20px; padding-top: 20px;">Company Information</h6>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <h6>Location</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="main_location" placeholder="- Location -">
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Location</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="billing_location" placeholder="- Location -">
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>


                        <div class="col-md-6 col-sm-12">
                            <h6>Postal Code(ZIP)</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="main_postal_code" name="main_postal_code" placeholder="- Postal Code -" value="{{$account->main_postal_code}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Postal Code(ZIP)</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" placeholder="- Postal Code -" value="{{$account->billing_postal_code}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <h6>State/Region</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="main_region_state" name="main_region_state" placeholder="- Region/State -" value="{{$account->main_region_state}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>State/Region</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="billing_region_state" name="billing_region_state" placeholder="- Region/State -" value="{{$account->billing_region_state}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-map"></i>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <h6>Country</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="main_country" name="main_country" placeholder="- Country -" value="{{$account->main_country}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-flag-alt"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Country</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="billing_country" name="billing_country" placeholder="- Country -" value="{{$account->billing_country}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-flag-alt"></i>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <h6>City</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="main_city" name="main_city" placeholder="- City -" value="{{$account->main_city}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-city"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>City</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="- City -" value="{{$account->billing_city}}" readonly>
                                <div class="form-control-position">
                                    <i class="bx bxs-city"></i>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <h6>Street Number</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <div class="controls">
                                    <input type="text" class="form-control" id="main_street_number" name="main_street_number" placeholder="Street Number" value="{{$account->main_street_number}}" readonly>
                                </div>
                                <div class="form-control-position">
                                    <i class="bx bx-street-view"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Street Number</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <div class="controls">
                                    <input type="text" class="form-control" id="billing_street_number" name="billing_street_number" placeholder="Street Number" value="{{$account->billing_street_number}}" readonly>
                                </div>
                                <div class="form-control-position">
                                    <i class="bx bx-street-view"></i>
                                </div>
                            </fieldset>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <h6>Street Address</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <div class="controls">
                                    <input type="text" class="form-control" id="main_street_address" name="main_street_address" value="{{$account->main_street_address}}" placeholder="Street Address" readonly>
                                </div>
                                <div class="form-control-position">
                                    <i class="bx bx-street-view"></i>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h6>Street Address</h6>
                            <fieldset class="form-group position-relative has-icon-left">
                                <div class="controls">
                                    <input type="text" class="form-control" id="billing_street_address" name="billing_street_address" value="{{$account->billing_street_address}}" placeholder="Street Address" readonly>
                                </div>
                                <div class="form-control-position">
                                    <i class="bx bx-street-view"></i>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="controls">
                                    <label>Office Phone</label>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <div class="controls">
                                            <input type="text" class="form-control" id="main_office_phone" name="main_office_phone" required 
                                        data-validation-required-message="This Phone field is required" placeholder="Office Phone" value="{{$account->main_office_phone}}">
                                        </div>
                                        <div class="form-control-position">
                                            <i class="bx bx-mobile"></i>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Office Phone</label>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <div class="controls">
                                        <input type="text" class="form-control" id="billing_office_phone" name="billing_office_phone" value="{{$account->billing_office_phone}}"
                                            placeholder="Office Phone">
                                    </div>
                                    <div class="form-control-position">
                                        <i class="bx bx-mobile"></i>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="controls">
                                    <label>Main Email</label>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <div class="controls">
                                            <input type="email" class="form-control" id="main_email" name="main_email" required 
                                        data-validation-required-message="This Email field is required" value="{{$account->main_email}}" placeholder="Main Email">
                                        </div>
                                        <div class="form-control-position">
                                            <i class="bx bx-mail-send"></i>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Billing Email</label>
                                <fieldset class="form-group position-relative has-icon-left">
                                    <div class="controls">
                                        <input type="email" class="form-control" id="billing_email" name="billing_email" value="{{$account->billing_email}}" placeholder="Billing Email">
                                    </div>
                                    <div class="form-control-position">
                                        <i class="bx bx-mail-send"></i>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                            <button type="submit" class="btn btn-primary glow mr-1 mb-1">Update Account</button>
                            <a href="{{ route('admin.crm') }}" class="btn btn-light mr-1 mb-1">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
  </section>
  <!-- Dashboard Analytics end -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('js/scripts/jquery.Jcrop.min.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1jKOFLhfQoZD3xJISSPnSW9-4SyYPpjY&callback=initAutocomplete&libraries=places&v=weekly" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" 
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" 
    crossorigin="anonymous"></script>
<!-- Boostrap 4 -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
@endsection

@section('page-scripts')
<script>
    var account = <?php echo json_encode($account)?>;
    var base_url = "{{ url('/admin/crm') }}";
</script>
<script src="{{asset('js/scripts/pages/admin/crm.js')}}"></script>
@endsection
