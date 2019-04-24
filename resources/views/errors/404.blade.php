@extends("app")

@section("headPageLevelStyle")
    <link href="/assets/pages/css/error-rtl.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section("headThemeLayoutStyle")

@endsection

@section("title")
    <title>Not Found</title>
@endsection
@section("bodyClass")
    class="page-404-full-page"
@endsection

@section("header")
@endsection

@section("themePanel")
@endsection

@section("sidebar")
@endsection

@section("pageBar")
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12 page-404">
            <div class="number m--font-danger"> 404</div>
            <div class="details">
                <h3>ظاهرا شما گم شده اید!</h3>
                <p> صفحه ی مورد نظر شما یافت نشد
                    <br/>
                    <a href = "{{action("Web\IndexPageController")}}"> @lang('page.Home') </a>
                {{--or try the search bar below. </p>--}}
                {{--<form action="#">--}}
                {{--<div class="input-group input-medium">--}}
                {{--<input type="text" class="form-control" placeholder="keyword...">--}}
                {{--<span class="input-group-btn">--}}
                {{--<button type="submit" class="btn red">--}}
                {{--<i class="fa fa-search"></i>--}}
                {{--</button>--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<!-- /input-group -->--}}
                {{--</form>--}}
            </div>
        </div>
    </div>
@endsection

@section("footer")
@endsection

@section("footerThemeLayoutScript")
@endsection
