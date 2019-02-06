@extends("app")

@section("headPageLevelStyle")
    <link href="/assets/pages/css/login-4-rtl.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section("headThemeLayoutStyle")

@endsection

@section("bodyClass")
    class=" login"
@endsection

@section("header")
@endsection
@section("sidebar")
@endsection
@section("themePanel")
@endsection
@section("pageBar")
@endsection
@section("content")
    <!-- BEGIN LOGO -->
    <div class="logo">
        {{--<a href="{{action("IndexPageController")}}">--}}
        {{--<img src="/assets/pages/img/logo-big.png" alt="" /> </a>--}}
        <h3 class="bg-font-dark bold">تکمیل ثبت نام</h3>
    </div>

    <!-- END LOGO -->
    <div class="content">
        @include("user.form", ["formID"=>1 , "noteFontColor"=>"bg-font-dark" , "hasHomeButton"=>1])
    </div>
    <!-- BEGIN COPYRIGHT -->
    {{--<div class="copyright" style="direction: ltr;"> 2016 &copy; Alaa </div>--}}
    <!-- END COPYRIGHT -->
@endsection

@section("footer")

@endsection

@section("footerPageLevelPlugin")
    <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
@endsection

@section("footerPageLevelScript")
    <script src="/assets/pages/scripts/login-4.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/ui-modals.min.js" type="text/javascript"></script>
@endsection
