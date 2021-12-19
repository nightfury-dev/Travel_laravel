@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-menu.css')}}">
@endsection

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">
@endsection

@section('content')
<div class="modal fade text-left" id="change_password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel150" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
        <form method="post" action="{{ route('admin.crm.password') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-dark white">
                    <span class="modal-title" id="myModalLabel150">Change Password</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id_pwd" id="user_id_pwd">
                    
                    <fieldset class="form-group">
                        <h6>Change Password</h6>
                        <input type="password" class="form-control" id="change_password" name="change_password" required data-validation-required-message="The password field is required" minlength="6">
                    </fieldset>
                    
                    <fieldset class="form-group">
                        <h6>Change Password Confirm</h6>
                        <input type="password" class="form-control" id="change_password_confirm" name="change_password_confirm" required data-validation-match-match="change_password" data-validation-required-message="The Confirm password field is required" minlength="6">
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Change</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<section id="basic-tabs-components">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="border-left: 5px solid #ffdede">
                    <h5 class="card-title" style="color: #FF5B5C">Users</h5>
                    <div class="heading-elements">
                        <ul class="list-inline">
                            <li><span class="badge badge-pill badge-light-danger">
                                    Total Count {{ count($users) }}
                                </span></li>
                            <li><i class="bx bx-dots-vertical-rounded font-medium-3 align-middle"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="invoice-list-wrapper">
                            <div class="action-dropdown-btn d-none">
                                <div class="dropdown invoice-filter-action" id="invoice-filter-action-user">
                                    <a href="{{ route('admin.crm.create') }}" class="btn btn-primary mr-1 glow d-flex align-items-center align-left"><span><i class="bx bx-user" style="margin-bottom: 5px"></i></span>&nbsp; New</a>
                                </div>
                                <div class="dropdown invoice-options" id="invoice-options-user">
                                    <button id="export_csv_user" class="btn btn-primary mr-1 glow d-flex align-items-center align-left"><span><i class="bx bx-table" style="margin-bottom: 5px"></i></span>&nbsp; Export CSV</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table invoice-data-table dt-responsive nowrap" id="table-user">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>UserName</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Phone</th>
                                            <th>Country</th>
                                            <th>Account Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    @if($user->avatar_path == '')
                                                        <img class="rounded-circle mr-1" src="{{asset('images/img/avatar.png')}}"  style="width:40px; height:40px;" alt="card">
                                                    @else
                                                        <img class="rounded-circle mr-1" src="{{asset('storage/' . $user->avatar_path)}}"  style="width:40px; height:40px;" alt="card">
                                                    @endif
                                                    {{$user->username}}
                                                </td>
                                                <td>
                                                    {{$user->first_name.' '.$user->last_name}}
                                                </td>
                                                <td>
                                                    {{$user->main_email}}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="cursor-pointer" id="change_pwd_btn" onClick="onPasswordBtnClick({{$user->id}})">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    {{$user->main_office_phone}}
                                                </td>
                                                <td>
                                                    {{$user->main_country}}
                                                </td>
                                                <td>
                                                    @if(isset($user->get_account_type->title))
                                                        {{$user->get_account_type->title}}
                                                    @endif
                                                </td>
                                                <td>       
                                                    <div class="invoice-action">
                                                        <a title="Edit" href="{{ route('admin.crm.edit', ['user_id' => $user->id]) }}" id="customer_edit" class="invoice-action-edit cursor-pointer mr-1">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <a title="Delete" href="javascript:void(0)" onClick="account_del({{$user->id}})" class="invoice-action-view">
                                                            <i class="bx bx-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('vendor-scripts')
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
@endsection

@section('page-scripts')
<script>
    var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
    var base_url = "{{ url('/admin') }}";
</script>
<script src="{{asset('js/scripts/pages/admin/crm.js')}}"></script>
@endsection