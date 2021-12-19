@extends('layouts.admin')

@section('title','Travel Quoting')

@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
@endsection

@section('custom-horizontal-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/horizontal-custom-menu.css')}}">
@endsection


@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-email.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-invoice.css')}}">

@endsection

@section('content')
<!-- modal -->
<section>
  <input type="hidden" name="account_type_id" id="account_type_id">
  <input type="hidden" name="task_type_id" id="task_type_id">
  <input type="hidden" name="currency_id" id="currency_id">
  <input type="hidden" name="customer_id" id="customer_id">
  <input type="hidden" name="language_id" id="language_id">
  <input type="hidden" name="category_id" id="category_id">
  <input type="hidden" name="category_tag_id" id="category_tag_id">

  <!-- currency -->
  <div class="modal fade text-left" id="currency_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel17">Currency Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>Currency Name</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="currency_name" name="currency_name" class="form-control" placeholder="Currency Name" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onClick="save_currency()" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
          </button>
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
          </button>

        </div>
      </div>
    </div>
  </div>

  <!-- account_type -->
  <div class="modal fade text-left" id="account_type_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel17">Account Type Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>Account Type Name</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="account_type_name" name="account_type_name" class="form-control" placeholder="Account Type Name" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onClick="save_account_type()" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
          </button>
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
          </button>

        </div>
      </div>
    </div>
  </div>

  <!-- language -->
  <div class="modal fade text-left" id="language_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel18">language Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>language Name</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="language_name" name="language_name" class="form-control" placeholder="language Name" required>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>language title</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="language_title" name="language_title" class="form-control" placeholder="language title" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onClick="save_language()" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
          </button>
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
          </button>

        </div>
      </div>
    </div>
  </div>

  <!-- category -->
  <div class="modal fade text-left" id="category_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel18">category Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>category Name</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="category_name" name="category_name" class="form-control" placeholder="category Name" required>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>category Parent</label>
            </div>
            <div class="col-md-6 form-group" >
              <select class="select2 form-control" id="category_parent" name="category_parent" required data-validation-required-message="This title field is required">
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onClick="save_category()" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
          </button>
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
          </button>

        </div>
      </div>
    </div>
  </div>

  <!-- category_tag -->
  <div class="modal fade text-left" id="category_tag_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel18">category_tag Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
        <div class="modal-body">

          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>category tag Name</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="category_tag_title" name="category_tag_title" class="form-control" placeholder="category tag Title" required>
            </div>
          </div>

          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>category tag Value</label>
            </div>
            <div class="col-md-6 form-group" >
              <input type="text" id="category_tag_name" name="category_tag_name" class="form-control" placeholder="category tag Value" required>
            </div>
          </div>

          <div class="row mb-1">
            <div class="col-md-4" style = "padding-top: 10px;">
              <label>category tag Parent</label>
            </div>
            <div class="col-md-6 form-group" >
              <select class="select2 form-control" id="category_tag_parent" name="category_tag_parent" required data-validation-required-message="This title field is required">
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onClick="save_category_tag()" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
          </button>
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
          </button>

        </div>
      </div>
    </div>
  </div>
</section>


<section>

  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Acccount Type </h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end mb-1">
                <button class="btn btn-primary" onclick="detail_account_type(0)">ADD</button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="invoice-list-wrapper">
                  <div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="account_type_table" class="table invoice-data-table dt-responsive nowrap" style = "width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center">NO</th>
                          <th class="text-center">Name</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                       @foreach($account_types as $key => $account_type)
                        <tr>
                          <td></td>
                          <td class="text-center">{{$key + 1}}</td>
                          <td class="text-center">{{$account_type->title}}</td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onClick="detail_account_type({{$account_type->id}})"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onClick="account_type_del({{$account_type->id}})"><i class="bx bx-trash mr-1"></i> delete</a>
                              </div>
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
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Currency</h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end mb-1">
                <button class="btn btn-primary" onclick="detail_currency(0)">ADD</button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="invoice-list-wrapper">
                  <div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="currency_table" class="table invoice-data-table dt-responsive nowrap" style = "width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center">NO</th>
                          <th class="text-center">Name</th>

                          <th class="text-center">Action</th>

                        </tr>
                      </thead>
                      <tbody>

                       @foreach($currencys as $key => $currency)

                        <tr>
                          <td></td>
                          <td class="text-center">{{$key + 1}}</td>

                          <td class="text-center">{{$currency->title}}</td>

                          <td class="text-center">
                            <div class="dropdown">
                              <span class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onClick="detail_currency({{$currency->id}})"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onClick="currency_del({{$currency->id}})"><i class="bx bx-trash mr-1"></i> delete</a>
                              </div>
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
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Language</h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end mb-1">
                <button class="btn btn-primary" onclick="detail_language(0)">ADD</button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="invoice-list-wrapper">
                  <div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="language_table" class="table invoice-data-table dt-responsive nowrap" style = "width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center">NO</th>
                          <th class="text-center">Name</th>
                          <th class="text-center">Title</th>

                          <th class="text-center">Action</th>

                        </tr>
                      </thead>
                      <tbody>

                       @foreach($languages as $key => $language)

                        <tr>
                          <td></td>
                          <td class="text-center">{{$key + 1}}</td>

                          <td class="text-center">{{$language->name}}</td>
                          <td class="text-center">{{$language->title}}</td>

                          <td class="text-center">
                            <div class="dropdown">
                              <span class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onClick="detail_language({{$language->id}})"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onClick="language_del({{$language->id}})"><i class="bx bx-trash mr-1"></i> delete</a>
                              </div>
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
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Category</h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end mb-1">
                <button class="btn btn-primary" onclick="detail_category(0)">ADD</button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="invoice-list-wrapper">
                  <div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="category_table" class="table invoice-data-table dt-responsive nowrap" style = "width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center">NO</th>
                          <th class="text-center">Name</th>

                          <th class="text-center">Action</th>

                        </tr>
                      </thead>
                      <tbody>

                       @foreach($categorys as $key => $category)

                        <tr>
                          <td></td>
                          <td class="text-center">{{$key + 1}}</td>

                          <td class="text-center">{{$category->title}}</td>

                          <td class="text-center">
                            <div class="dropdown">
                              <span class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onClick="detail_category({{$category->id}})"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onClick="category_del({{$category->id}})"><i class="bx bx-trash mr-1"></i> delete</a>
                              </div>
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
    </div>

  </div>
  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Category Tag</h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="col-md-12 d-flex justify-content-end mb-1">
                <button class="btn btn-primary" onclick="detail_category_tag(0)">ADD</button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="invoice-list-wrapper">
                  <div class="action-dropdown-btn d-none">
                    <div class="dropdown invoice-filter-action"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="category_tag_table" class="table invoice-data-table dt-responsive nowrap" style = "width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center">NO</th>
                          <th class="text-center">Name</th>
                          <th class="text-center">Value</th>

                          <th class="text-center">Action</th>

                        </tr>
                      </thead>
                      <tbody>

                       @foreach($category_tags as $key => $category_tag)

                        <tr>
                          <td></td>
                          <td class="text-center">{{$key + 1}}</td>

                          <td class="text-center">{{$category_tag->title}}</td>
                          <td class="text-center">{{$category_tag->value}}</td>


                          <td class="text-center">
                            <div class="dropdown">
                              <span class="bx bx-slider-alt font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)" onClick="detail_category_tag({{$category_tag->id}})"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onClick="category_tag_del({{$category_tag->id}})"><i class="bx bx-trash mr-1"></i> delete</a>
                              </div>
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
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Default Settings</h4>
          <a class="heading-elements-toggle">
            <i class='bx bx-dots-vertical font-medium-3'></i>
          </a>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a data-action="collapse">
                  <i class="bx bx-chevron-down"></i>
                </a>
              </li>
              <li>
                <a data-action="expand">
                  <i class="bx bx-fullscreen"></i>
                </a>
              </li>
              <li>
                <a data-action="reload">
                  <i class="bx bx-revision"></i>
                </a>
              </li>
              <li>
                <a data-action="close">
                  <i class="bx bx-x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">

            <div class="row mb-1">
              <div class="col-md-4" style = "padding-top: 10px;">
                <label>Language</label>
              </div>
              <div class="col-md-6 form-group" >
                <select class="select2 form-control" id="current_language" name="current_language" required data-validation-required-message="This title field is required">
                  <option value="">--- Please select ---</option>

                  @foreach($languages as $key => $language)

                    <option {{$language->default_language == 1 ? 'selected' : ''}} value = "{{$language->id}}">{{$language->title}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-md-4" style = "padding-top: 10px;">
                <label>Currency</label>
              </div>
              <div class="col-md-6 form-group" >
                <select class="select2 form-control" id="current_currency" name="current_currency" required data-validation-required-message="This title field is required">
                  <option value="">--- Please select ---</option>

                  @foreach($currencys as $key => $currency)
                    <option {{$currency->default_currency == 1 ? 'selected' : ''}} value = "{{$currency->id}}">{{$currency->title}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row" style = "justify-content: flex-end;">
              <div class="col-md-2 form-group" >
                <button type="button" onClick="save_default_settings()" class="btn btn-primary ml-1" >
                  <i class="bx bx-check d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Save</span>
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')

<script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/ckeditor/ckeditor.js')}}"></script>

<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
@endsection

@section('page-scripts')
<script>
  var base_url = "{{ url('/admin') }}";
  var msg = <?php if(json_encode(session()->get('msg'))) echo json_encode(session()->get('msg'));  ?>;
</script>
<script src="{{asset('js/scripts/pages/admin/setting.js')}}"></script>
@endsection
