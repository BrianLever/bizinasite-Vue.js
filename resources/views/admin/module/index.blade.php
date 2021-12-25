@extends('layouts.master')

@section('title', 'Module Management')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Module Management</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tab_btn_area text-center">
        <div class="show_checked pt3 font-size16 font-weight-bold">
            Module Limit: <span class="module_limit">0</span>, Featured Modules: <span class="fmodule_limit">0</span>
        </div>
    </div>
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#my" href="#/my"> My Modules  (<span class="my_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#canceled" href="#/canceled"> Recently Canceled Modules  (<span class="canceled_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#all" href="#/all"> All Modules  (<span class="all_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50 area-active" id="my_area">
        <div class="m-portlet__body">
            <div class="text-center mt-4"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="canceled_area">
        <div class="m-portlet__body">
            <div class="text-center mt-4"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <div class="text-center mt-4"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/js/admin/module/index.js')}}"></script>
@endsection
