@extends('partials.templatePage')

@section('page-css')
    <link href="{{ mix('/css/page-live.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section("pageBar")
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            <li class = "breadcrumb-item">
                <i class = "flaticon-home-2 m--padding-right-5"></i>
                <a class = "m-link" href = "{{route('web.index')}}">@lang('page.Home')</a>
            </li>
            <li class = "breadcrumb-item active" aria-current = "page">
                <a class = "m-link" href = "#"> پخش زنده</a>
            </li>
        </ol>
    </nav>
@endsection

@section("content")

    @if(isset($live) && $live === true)
        <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon"></span>
                                    <h3 class="m-portlet__head-text">
                                        پخش زنده  @if(isset($title)) {{$title}} @endif
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                            </div>
                        </div>
                        <div class="m-portlet__body">

                            <div class="a--video-wraper">
                                <video id="video-0"
                                       class="video-js vjs-fluid vjs-default-skin vjs-big-play-centered" controls
                                       preload="auto" height='360' width="640" poster='{{ (isset($poster))?$poster:'' }}'>
                                    <source src="{{ (isset($xMpegURL))?$xMpegURL:'' }}" type="application/x-mpegURL" res="x-mpegURL" label="x-mpegURL">
                                    <source src="{{ (isset($dashXml))?$dashXml:'' }}" type="application/dash+xml" res="dash+xml" label="dash+xml">
                                    <p class="vjs-no-js">@lang('content.javascript is disables! we need it to play a video')</p>
                                </video>
                            </div>
                            <div class="m--clearfix"></div>

                        </div>
                    </div>
                </div>
            </div>

        @permission((config('constants.LIVE_STOP_ACCESS')))
        <div class="row">
            <div class="col text-center">
                <button type="button" class="btn m-btn--pill m-btn--air m-btn btn-info" id="btnStopLive">
                    <i class="fa fa-stop"></i>
                    توقف
                </button>
            </div>
        </div>
        @endpermission

    @else


        @permission((config('constants.LIVE_PLAY_ACCESS')))
        <div class="row">
            <div class="col text-center">
                <input type="text" name="title" placeholder="عنوان" id="startLiveTitle">
                <button type="button" class="btn m-btn--pill m-btn--air m-btn btn-info mx-auto" id="btnPlayLive">
                    <i class="fa fa-play"></i>
                    پخش
                </button>
            </div>
        </div>
        @endpermission

        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            <strong class="m--font-danger"  >
                اطلاعیه
            </strong>
            <br>
            تعداد کسانی که از پخش زنده ریاضیات آقای شامیزاده صبح ها استفاده می کردند خیلی زیاد شد
            <br>
            این تعداد استفاده کننده حدود 60 میلیون تومان هزینه مازاد (علاوه بر هزینه های فعلی) برای آلاء داره ، لذا با اجازه تا اطلاع ثانوی ما لایو رو قطع کردیم. ولی فیلم های ضبط شده اش رو سریع تدوین می کنیم و قرار می دیم.
            <br>
            تفاوت کلاس کنکور امسال آلاء صفر تا صد آلاء دو چیز بود:
            <br>
            1. دانش آموز سر کلاس است که وقتی سوال می پرسه ممکنه سوال شما باشه.
            <br>
            2. لایو بود
            <br>
            از اینجا به بعد خاصیت شماره 1 رو همچنان خواهیم داشت ولی پخش لایوش رو به خاطر مشکل مالی مجبوریم تا اطلاع ثانوی قطع کنیم.
            <br>
            ببخشید به امید روزی که آلاء از لحاظ مالی به قدری توانمند بشه که بتونه هر خدمتی رو رایگان تقدیم موفقیت شما بکنه.
            <br>
            سهراب ابوذرخانی فرد ، مدیر عامل آلاء
            <br><br>
            <div class="row">
                <div class="col-md-4 mx-auto">
                    <a class="btn btn-brand a--full-width" href="{{route('set.show' , 566)}}">دانلود فیلم های هندسه آقای شامیزاده</a>
                    <br>
                    <a class="btn btn-focus a--full-width" href="{{route('set.show' , 572)}}">دانلود فیلم های گسسته آقای شامیزاده</a>
                    <br>
                    <a class="btn btn-primary a--full-width" href="{{route('set.show' , 576)}}">دانلود فیلم های ریاضی تجربی آقای شامیزاده</a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col">

            <div class="m-divider">
                <span></span>
                <span class="m-badge m-badge--info m-badge--wide m-badge--rounded">
                    <h5 class="display-5">
                        برنامه پخش زنده
                    </h5>
                </span>
                <span></span>
            </div>

            <div id="a--fullcalendar"></div>
        </div>
    </div>

@endsection

@section('page-js')
    <script type="text/javascript">
        var contentDisplayName = '{{(isset($title)?$title:'')}}';
        var contentUrl = '{{ asset('/live') }}';
        var playLiveAjaxUrl = '{{ $playLiveAjaxUrl }}';
        var stopLiveAjaxUrl = '{{ $stopLiveAjaxUrl }}';
        var liveData = @if(isset($schedule)) {!! $schedule !!} @else [] @endif;
    </script>
    <script src="{{mix('/js/page-live.js')}}" type="text/javascript"></script>
@endsection

