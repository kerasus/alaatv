@permission((Config::get('constants.EDIT_SLIDESHOW_ACCESS')))@extends('app' , ['pageName'=> 'admin'])

@section('page-css')
    <link href = "/acm/AlaatvCustomFiles/components/alaa_old/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel = "stylesheet" type = "text/css"/>
    <link href = "/acm/AlaatvCustomFiles/components/alaa_old/css/profile-rtl.css" rel = "stylesheet" type = "text/css"/>
@endsection

@section('pageBar')
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            <li class = "breadcrumb-item">
                <i class = "flaticon-home-2 m--padding-right-5"></i>
                <a class = "m-link" href = "{{action("Web\IndexPageController")}}">@lang('page.Home')</a>
            </li>
            <li class = "breadcrumb-item" aria-current = "page">
                <a class = "m-link" href = "{{action("Web\AdminController@adminSlideShow")}}">پنل مدیریتی اسلاید شو صفحه اصلی</a>
            </li>
            <li class = "breadcrumb-item active" aria-current = "page">
                <a class = "m-link" href = "#">اصلاح اطلاعات اسلاید</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class = "row">
        <div class = "col-md-3"></div>
        <div class = "col-md-6 ">
        @include("systemMessage.flash")
        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
                <div class = "m-portlet__head">
                    <div class = "m-portlet__head-caption">
                        <div class = "m-portlet__head-title">
                            <h3 class = "m-portlet__head-text">
                                اصلاح اطلاعات
                                <a href = "{{action("Web\ProductController@show" , $slide)}}">{{$slide->name}}</a>
                            </h3>
                        </div>
                    </div>
                    <div class = "m-portlet__head-tools">
                        <a class = "btn m-btn--air btn-primary" href = "@if(isset($previousUrl)) {{$previousUrl}}  @endif">
                            بازگشت
                            <i class = "fa fa-angle-left"></i>
                        </a>
                    </div>
                </div>
                <div class = "m-portlet__body">
                    {!! Form::model($slide,['files'=>true,'method' => 'PUT','action' => ['Web\SlideShowController@update' , $slide], 'class'=>'form-horizontal']) !!}
                    @include('slideShow.form')
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
        <div class = "col-md-3"></div>
    </div>
@endsection

@section('page-js')
    <script src = "/acm/AlaatvCustomFiles/components/alaa_old/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type = "text/javascript"></script>
    <script src = "/acm/AlaatvCustomFiles/components/alaa_old/plugins/bootstrap-select/js/bootstrap-select.min.js" type = "text/javascript"></script>
    <script src = "/acm/AlaatvCustomFiles/components/alaa_old/plugins/jquery-multi-select/js/jquery.multi-select.js" type = "text/javascript"></script>
    <script src = "/acm/AlaatvCustomFiles/components/alaa_old/plugins/select2/js/select2.full.min.js" type = "text/javascript"></script>
    <script src = "/acm/AlaatvCustomFiles/components/alaa_old/scripts/components-multi-select.min.js" type = "text/javascript"></script>
@endsection

@endpermission
