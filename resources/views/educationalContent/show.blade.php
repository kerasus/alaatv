@extends("app")

@section("css")
    <link rel="stylesheet" href="{{ mix('/css/all.css') }}">
    <link href="/videojs/video-js.min.css" rel="stylesheet">
    <link href="/videojs/video.js-quality/quality-selector.css" rel="stylesheet">
    <style>
        @media screen and (max-width: 480px) {
            .google-docs {
                height: 350px;
            }
        }
        .mt-element-list {
            background-color: white;
        }

    </style>
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="/videojs/ie8-1.1.2/dist/videojs-ie8.min.js"></script>
    <style>
        .video-js .vjs-title-bar {
            background: rgba(0, 0, 0, 0.5);
            color: white;

            /*
              By default, do not show the title bar.
            */
            display: none;
            font-size: 2em;
            padding: .5em;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        /*
          Only show the title bar after playback has begun (so as not to hide
          the big play button) and only when paused or when the user is
          interacting with the player.
        */
        .video-js.vjs-paused.vjs-has-started .vjs-title-bar,
        .video-js.vjs-user-active.vjs-has-started .vjs-title-bar{
            display: block;
        }
    </style>
@endsection

@section("pageBar")

@endsection
@section("bodyClass")
    class = "page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed page-md"
@endsection

@section("pageBar")
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="{{action("HomeController@index")}}">خانه</a>
                <i class="fa fa-angle-left"></i>
            </li>
            <li>
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                <a href="{{action("EducationalContentController@search")}}">محتوای آموزشی</a>
                <i class="fa fa-angle-left"></i>
            </li>
            <li>
                <span>نمایش @if(isset($rootContentType->displayName[0])){{$rootContentType->displayName}}@endif</span>
            </li>
        </ul>
    </div>
@endsection

@section("content")
    @if(isset($educationalContent->template))
        @if($educationalContent->template->name == "video1")
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                {{ isset($educationalContentDisplayName) ? $educationalContentDisplayName : '' }}
                            </div>
                        </div>
                        <div class="portlet-body  text-justify">
                            <div class="row col-md-7">
                                <video  id="video-{{$educationalContent->id}}"
                                        class="video-js vjs-default-skin vjs-big-play-centered"
                                        preload="metadata"
                                        height='360px'
                                        width="640px"
                                        poster='@if(isset($files["thumbnail"])){{$files["thumbnail"]}}@endif'
                                        data-setup='{ "fluid" : true ,"loop": "true", "playbackRates" : [1, 1.5, 2] }'
                                        controls >

                                @foreach($files["videoSource"] as $source)
                                        <source label="{{ $source["caption"] }}" src="{{ $source["src"] }}" type='video/mp4'>
                                    @endforeach
                                    <p class="vjs-no-js">جهت پخش آنلاین فیلم، ابتدا مطمئن شوید که جاوا اسکریپت در مرور
                                        گر شما فعال است و از آخرین نسخه ی مرورگر استفاده می کنید.</p>
                                </video>
                            </div>
                            <div class="row">
                                    <hr>
                                    <div class="col-md-7">
                                        <div class="caption"> <i class="fa fa-comment-o" aria-hidden="true"></i> </div>
                                        @if(isset($educationalContent->description[0]))
                                            <div class="scroller" style="max-height:400px ; " data-rail-visible="1" data-rail-color="black" data-handle-color="#a1b2bd">
                                                {!! $educationalContent->description !!}
                                            </div>
                                        @else
                                            به زودی ...
                                        @endif
                                    </div>

                                    <div class="col-md-5">
                                        @if(isset($educationalContent->author_id))

                                                <ul class="list-unstyled">
                                                    <li><i class="fa fa-user"></i>{{$author}}</li>&nbsp
                                                    @if(isset($contentSetName))
                                                    <li><i class="fa fa-tv"></i>{{$contentSetName}}</li>&nbsp;
                                                    @endif
                                                    @if($userCanSeeCounter)
                                                     <li>
                                                            <i class="fa fa-eye"></i>
                                                            {{$productSeenCount}}
                                                     </li>
                                                    @endif
                                                </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <hr>
                                    <div class="col-md-12">
                                        @if(!empty($tags))
                                            @include("partials.search.tagLabel" , ["tags"=>$tags])
                                        @endif
                                    </div>


                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        لینک های دانلود
                                    </div>
                                </div>
                                <div class="portlet-body text-justify">
                                    <p>
                                    پیشنهاد می کنیم برای دانلود، از نرم افزار Internet Download Manager در ویندوز و یا ADM در اندروید و یا wget در لینوکس استفاده بفرمایید.
                                    </p>
                                    <p>
                                        جهت دانلود روی یکی از دکمه های زیر کلیک کنید:
                                    </p>
                                    <div class="row">


                                        @foreach($files["videoSource"] as $key => $source)
                                            <div class="col-md-4">
                                                <a href="{{$source["src"]}}?download=1" class="btn red margin-bottom-5" style="width: 250px;">
                                                    فایل {{$source["caption"]}}{{ (isset($source["size"] )  && strlen($source["size"])  > 0 )?"(".$source["size"]. ")":""  }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="portlet light ">--}}
                        {{--<div class="portlet-title">--}}
                            {{--<div class="caption">--}}
                                {{--<i class="fa fa-handshake-o" aria-hidden="true"></i>--}}
                           {{--بخش تبلیغات--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="portlet-body  text-justify">--}}

                            {{--<div id="yektanet-pos-1"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="row">
                <div class="col-md-8 margin-bottom-15">
                    @if(isset($contentsWithSameSet) && $contentsWithSameSet->whereIn("type" , "video" )->isNotEmpty())
                    <div class="mt-element-list">
                        <div class="mt-list-head list-news ext-1 font-white bg-yellow-crusta">
                            <div class="list-head-title-container">
                                <h3 class="list-title">
                                    جلسات دیگر
                                    @if(isset($contentSetName))
                                        {{ $contentSetName }}
                                    @endif
                                </h3>
                            </div>
                            <div class="list-count pull-right bg-yellow-saffron"></div>
                        </div>
                        <div class="mt-list-container list-news ext-1" id="otherSessions">
                            <div id="playListScroller" class="scroller" style="min-height: 50px; max-height:950px" data-always-visible="1" data-rail-visible="1"
                                 data-rail-color="red" data-handle-color="green">
                                <ul>
                                    @foreach($contentsWithSameSet->whereIn("type" , "video" ) as $item)
                                        <li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif " id="playlistItem_{{$item["content"]->id}}">
                                                <div class="list-icon-container">
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}" >
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                </div>
                                                <div class="list-thumb">
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}" >
                                                        <img alt="{{$item["content"]->name}}"
                                                             src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>
                                                    </a>
                                                </div>
                                                <div class="list-datetime bold uppercase font-yellow-casablanca" >
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}" >
                                                        {{($item["content"]->getDisplayName())}}
                                                    </a>
                                                </div>
                                                <div class="list-item-content">
                                                    <h3 class="uppercase bold">
                                                        <a href="javascript:;">&nbsp;</a>
                                                    </h3>
                                                </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="col-md-4 margin-bottom-15">
                    @if(isset($adItems) )
                        <div class="portlet light margin-top-10">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    نمونه همایش های طلایی 97
                                </div>
                            </div>

                            <div class="portlet-body">
                                @include("educationalContent.partials.adSideBar" , ["items" => $adItems])
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <style>
                .mt-list-item{
                    min-height: 150px;
                }
                .list-thumb {
                    padding-left: 10px;
                    width: 220px !important;
                    height: 110px !important;
                }
            </style>
            @if(isset($contentsWithSameSet) && $contentsWithSameSet->whereIn("type" , "pamphlet" )->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                                    جزوات این درس
                                </div>
                            </div>
                            <div class="portlet-body text-justify">
                                <div class="m-grid m-grid-demo">
                                    @foreach($contentsWithSameSet->whereIn("type" , "pamphlet" )->chunk(5) as $chunk)
                                        <div class="m-grid-row">
                                            @foreach($chunk as $item)
                                                <div class="m-grid-col m-grid-col-middle m-grid-col-center">

                                                    <img width="80" alt="{{$item["content"]->name}}" src="{{( ( isset($item["thumbnail"]) && ( strlen($item["thumbnail"]) > 0 ) ) ? $item["thumbnail"] : '/img/extra/orange-pdf-icon-32.png' )}}"/>
                                                    <br/>
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}">
                                                        <i class="fa fa-angle-left"></i>
                                                        {{$item["content"]->name}}
                                                    </a>

                                                </div>
                                                {{--<li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif ">--}}
                                                {{--<div class="list-icon-container">--}}
                                                {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                                                {{--<i class="fa fa-angle-left"></i>--}}
                                                {{--</a>--}}
                                                {{--</div>--}}
                                                {{--<div class="list-thumb">--}}
                                                {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                                                {{--<img alt="{{$item["content"]->name}}"--}}
                                                {{--src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>--}}
                                                {{--</a>--}}
                                                {{--</div>--}}
                                                {{--<div class="list-datetime bold uppercase font-yellow-casablanca"> {{$item["content"]->name}} </div>--}}
                                                {{--<div class="list-item-content">--}}
                                                {{--<h3 class="uppercase bold">--}}
                                                {{--<a href="javascript:;">&nbsp;</a>--}}
                                                {{--</h3>--}}
                                                {{--</div>--}}
                                                {{--</li>--}}
                                            @endforeach
                                        </div>

                                    @endforeach
                                    <style>
                                        .m-grid.m-grid-demo .m-grid-col{
                                            border: none !important;
                                            min-height: 150px !important;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                        {{--<div class="mt-element-list">--}}
                        {{--<div class="mt-list-head list-news ext-1 font-white bg-blue">--}}
                        {{--<div class="list-head-title-container">--}}
                        {{--<h3 class="list-title">جزوات مرتبط</h3>--}}
                        {{--</div>--}}
                        {{--<div class="list-count pull-right bg-blue-chambray"></div>--}}
                        {{--</div>--}}
                        {{--<div class="mt-list-container list-news ext-2">--}}
                        {{--<div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible="1"--}}
                        {{--data-rail-color="red" data-handle-color="green">--}}
                        {{--<ul>--}}
                        {{--@foreach($contentsWithSameSet->whereIn("type" , "pamphlet" ) as $item)--}}
                        {{--<li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif ">--}}
                        {{--<div class="list-icon-container">--}}
                        {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                        {{--<i class="fa fa-angle-left"></i>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="list-thumb">--}}
                        {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                        {{--<img alt="{{$item["content"]->name}}"--}}
                        {{--src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="list-datetime bold uppercase font-yellow-casablanca"> {{$item["content"]->name}} </div>--}}
                        {{--<div class="list-item-content">--}}
                        {{--<h3 class="uppercase bold">--}}
                        {{--<a href="javascript:;">&nbsp;</a>--}}
                        {{--</h3>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--@endforeach--}}

                        {{--</ul>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            @endif
        @elseif($educationalContent->template->name == "pamphlet1" )
            <div class="row">
                <div class="col-md-8">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    {{isset($educationalContentDisplayName) ? $educationalContentDisplayName : "" }}
                            </div>
                            <div class="actions">
                                @if($files->count() == 1)
                                    <a target="_blank"
                                       href="{{action("HomeController@download" , ["fileName"=>$files->first()->uuid ])}}"
                                       class="btn btn-circle green btn-outline btn-sm"><i class="fa fa-download"></i>
                                        دانلود </a>
                                @else
                                    <div class="btn-group">
                                        <button class="btn btn-circle green btn-outline btn-sm" data-toggle="dropdown"
                                                aria-expanded="true">دانلود
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($files as $file)
                                                <li>
                                                    <a target="_blank"
                                                       href="{{action("HomeController@download" , ["fileName"=>$file->uuid ])}}">
                                                        فایل {{$file->pivot->caption}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="portlet-body">
                                @if(isset($fileToShow) && $fileToShow->getExtention() === "pdf")
                                    <iframe class="google-docs"
                                            src='https://docs.google.com/viewer?url={{$fileToShow->getUrl()}}&embedded=true'
                                            width='100%' height='760' style='border: none;'></iframe>
                                @endif
                            <div class="row">
                                <div class="col-md-12">
                                    @if(!empty($tags))
                                        <hr>
                                        @include("partials.search.tagLabel" , ["tags"=>$tags])
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-comment-o" aria-hidden="true"></i>
                                    درباره فایل
                            </div>
                        </div>
                        <div class="portlet-body text-justify">
                            <div class="scroller" style="height:200px" data-rail-visible="1" data-rail-color="black"
                                 data-handle-color="#a1b2bd">
                                @if(isset($educationalContent->description[0]))
                                    {!! $educationalContent->description !!}
                                @else
                                    به زودی ...
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                    <div class="col-md-4 margin-bottom-15">
                        @if(isset($contentsWithSameSet) && $contentsWithSameSet->whereIn("type" , "video" )->isNotEmpty())
                            <div class="mt-element-list">
                            <div class="mt-list-head list-news ext-1 font-white bg-yellow-crusta">
                                <div class="list-head-title-container">
                                    <h3 class="list-title">فیلم های درس</h3>
                                </div>
                                <div class="list-count pull-right bg-yellow-saffron"></div>
                            </div>
                            <div class="mt-list-container list-news ext-2" id="otherSessions">
                                <div id="playListScroller" class="scroller" style="min-height: 50px; max-height:500px" data-always-visible="1" data-rail-visible="1"
                                     data-rail-color="red" data-handle-color="green">
                                    <ul>

                                        @foreach($contentsWithSameSet->whereIn("type" , "video" ) as $item)
                                            <li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif " id="playlistItem_{{$item["content"]->id}}">
                                                <div class="list-icon-container">
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}">
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                </div>
                                                <div class="list-thumb">
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}">
                                                        <img alt="{{$item["content"]->name}}"
                                                             src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>
                                                    </a>
                                                </div>
                                                <div class="list-datetime bold uppercase font-yellow-casablanca"> {{($item["content"]->getDisplayName())}} </div>
                                                <div class="list-item-content">
                                                    <h3 class="uppercase bold">
                                                        <a href="javascript:;">&nbsp;</a>
                                                    </h3>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($adItems) )
                            <div class="portlet light margin-top-10">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        نمونه همایش های طلایی 97
                                    </div>
                                </div>

                                <div class="portlet-body">
                                    @include("educationalContent.partials.adSideBar" , ["items" => $adItems])
                                </div>
                            </div>
                        @endif
                    </div>
                {{--<div class="col-md-4">--}}
                    {{--@if($contentsWithSameType->isNotEmpty())--}}
                        {{--<div class="mt-element-list">--}}
                            {{--<div class="mt-list-head list-simple ext-1 font-white bg-green-sharp">--}}
                                {{--<div class="list-head-title-container">--}}
                                    {{--<div class="list-date">Nov 8, 2015</div>--}}
                                    {{--<h3 class="list-title">@if(isset($rootContentType->displayName[0])){{$rootContentType->displayName}}@endif--}}
                                        {{--های @if(isset($childContentType->displayName[0])){{$childContentType->displayName}}@endif--}}
                                        {{--دیگر</h3>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="mt-list-container list-simple ext-1">--}}
                                {{--<ul>--}}
                                    {{--@foreach($contentsWithSameType as $content)--}}
                                        {{--<li class="mt-list-item">--}}
                                            {{--<div class="list-icon-container">--}}
                                                {{--<i class="fa fa-file-pdf-o" aria-hidden="true"></i>--}}
                                            {{--</div>--}}
                                            {{--<div class="list-datetime"> @if($content->grades->isNotEmpty()){{$content->grades->first()->displayName}}@endif</div>--}}
                                            {{--<div class="list-item-content">--}}
                                                {{--<h5 class="uppercase">--}}
                                                    {{--<a href="{{action("EducationalContentController@show" , $content)}}">{{$content->getDisplayName()}}</a>--}}
                                                {{--</h5>--}}
                                            {{--</div>--}}
                                        {{--</li>--}}
                                    {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                {{--</div>--}}
            </div>
            @if(isset($contentsWithSameSet) && $contentsWithSameSet->whereIn("type" , "pamphlet" )->where("content.id" , "<>",$educationalContent->id)->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                                    جزوات دیگر
                                </div>
                            </div>
                            <div class="portlet-body text-justify">
                                <div class="m-grid m-grid-demo">
                                    @foreach($contentsWithSameSet->whereIn("type" , "pamphlet" )->chunk(5) as $chunk)
                                        <div class="m-grid-row">
                                            @foreach($chunk as $item)
                                                <div class="m-grid-col m-grid-col-middle m-grid-col-center">

                                                    <img width="80" alt="{{$item["content"]->name}}" src="{{( ( isset($item["thumbnail"]) && ( strlen($item["thumbnail"]) > 0 ) ) ? $item["thumbnail"] : '/img/extra/orange-pdf-icon-32.png' )}}"/>
                                                    <br/>
                                                    <a href="{{action("EducationalContentController@show" , $item["content"])}}">
                                                        <i class="fa fa-angle-left"></i>
                                                        {{$item["content"]->name}}
                                                    </a>

                                                </div>
                                                {{--<li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif ">--}}
                                                {{--<div class="list-icon-container">--}}
                                                {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                                                {{--<i class="fa fa-angle-left"></i>--}}
                                                {{--</a>--}}
                                                {{--</div>--}}
                                                {{--<div class="list-thumb">--}}
                                                {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                                                {{--<img alt="{{$item["content"]->name}}"--}}
                                                {{--src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>--}}
                                                {{--</a>--}}
                                                {{--</div>--}}
                                                {{--<div class="list-datetime bold uppercase font-yellow-casablanca"> {{$item["content"]->name}} </div>--}}
                                                {{--<div class="list-item-content">--}}
                                                {{--<h3 class="uppercase bold">--}}
                                                {{--<a href="javascript:;">&nbsp;</a>--}}
                                                {{--</h3>--}}
                                                {{--</div>--}}
                                                {{--</li>--}}
                                            @endforeach
                                        </div>

                                    @endforeach
                                    <style>
                                        .m-grid.m-grid-demo .m-grid-col{
                                            border: none !important;
                                            min-height: 150px !important;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                        {{--<div class="mt-element-list">--}}
                        {{--<div class="mt-list-head list-news ext-1 font-white bg-blue">--}}
                        {{--<div class="list-head-title-container">--}}
                        {{--<h3 class="list-title">جزوات مرتبط</h3>--}}
                        {{--</div>--}}
                        {{--<div class="list-count pull-right bg-blue-chambray"></div>--}}
                        {{--</div>--}}
                        {{--<div class="mt-list-container list-news ext-2">--}}
                        {{--<div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible="1"--}}
                        {{--data-rail-color="red" data-handle-color="green">--}}
                        {{--<ul>--}}
                        {{--@foreach($contentsWithSameSet->whereIn("type" , "pamphlet" ) as $item)--}}
                        {{--<li class="mt-list-item @if($item["content"]->id == $educationalContent->id) bg-grey-mint @endif ">--}}
                        {{--<div class="list-icon-container">--}}
                        {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                        {{--<i class="fa fa-angle-left"></i>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="list-thumb">--}}
                        {{--<a href="{{action("EducationalContentController@show" , $item["content"])}}">--}}
                        {{--<img alt="{{$item["content"]->name}}"--}}
                        {{--src="{{(isset($item["thumbnail"]))?$item["thumbnail"]:''}}"/>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                        {{--<div class="list-datetime bold uppercase font-yellow-casablanca"> {{$item["content"]->name}} </div>--}}
                        {{--<div class="list-item-content">--}}
                        {{--<h3 class="uppercase bold">--}}
                        {{--<a href="javascript:;">&nbsp;</a>--}}
                        {{--</h3>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--@endforeach--}}

                        {{--</ul>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            @endif
        @elseif($educationalContent->template->name == "article1")
            <div class="row">
                <div class="col-md-8">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    {{$educationalContent->name}}
                            </div>
                        </div>
                        <div class="portlet-body">
                                {!! $educationalContent->context !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(!empty($tags))
                                            <hr>
                                            @include("partials.search.tagLabel" , ["tags"=>$tags])
                                        @endif
                                    </div>
                                </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-comment-o" aria-hidden="true"></i>
                                    درباره مقاله
                            </div>
                        </div>
                        <div class="portlet-body text-justify">
                            <div class="scroller" style="height:200px" data-rail-visible="1" data-rail-color="black"
                                 data-handle-color="#a1b2bd">
                                @if(isset($educationalContent->description[0])) {!! $educationalContent->description !!} @endif
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    {{--@if($contentsWithSameType->isNotEmpty())--}}
                        {{--<div class="mt-element-list">--}}
                            {{--<div class="mt-list-head list-simple ext-1 font-white bg-green-sharp">--}}
                                {{--<div class="list-head-title-container">--}}
                                    {{--<div class="list-date">Nov 8, 2015</div>--}}
                                    {{--<h3 class="list-title">@if(isset($rootContentType->displayName[0])){{$rootContentType->displayName}}@endif--}}
                                        {{--های @if(isset($childContentType->displayName[0])){{$childContentType->displayName}}@endif--}}
                                        {{--دیگر</h3>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="mt-list-container list-simple ext-1">--}}
                                {{--<ul>--}}
                                    {{--@foreach($contentsWithSameType as $content)--}}
                                        {{--<li class="mt-list-item">--}}
                                            {{--<div class="list-icon-container">--}}
                                                {{--<i class="fa fa-file-pdf-o" aria-hidden="true"></i>--}}
                                            {{--</div>--}}
                                            {{--<div class="list-datetime"> @if($content->grades->isNotEmpty()){{$content->grades->first()->displayName}}@endif</div>--}}
                                            {{--<div class="list-item-content">--}}
                                                {{--<h5 class="uppercase">--}}
                                                    {{--<a href="{{action("EducationalContentController@show" , $content)}}">{{$content->getDisplayName()}}</a>--}}
                                                {{--</h5>--}}
                                            {{--</div>--}}
                                        {{--</li>--}}
                                    {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                </div>
            </div>
        @endif
    @else
        قالب محتوا تنظیم نشده است
    @endif
@endsection

@section("footerPageLevelPlugin")
@endsection

@section("footerPageLevelScript")
@endsection

@section("extraJS")
    {{--//v7.2--}}
    <script src="/videojs/video.min.js"></script>
    <script src="/videojs/video.js-quality/silvermine-videojs-quality-selector.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function (){
            var container = $("#playListScroller"),
                scrollTo = $("#playlistItem_"+"{{$educationalContent->id}}");
            container.scrollTop(
                scrollTo.offset().top - container.offset().top + container.scrollTop() - 100
            );
            $("#otherSessions").find(".slimScrollBar").css("top" , scrollTo.offset().top +"px");
        });
    </script>
    <script>
        $(document).ready(function(){
            console.log( "ready!" );
            //
            // Get the Component base class from Video.js
            var Component = videojs.getComponent('Component');

            // The videojs.extend function is used to assist with inheritance. In
            // an ES6 environment, `class TitleBar extends Component` would work
            // identically.
            var TitleBar = videojs.extend(Component, {

                // The constructor of a component receives two arguments: the
                // player it will be associated with and an object of options.
                constructor: function(player, options) {

                    // It is important to invoke the superclass before anything else,
                    // to get all the features of components out of the box!
                    Component.apply(this, arguments);

                    // If a `text` option was passed in, update the text content of
                    // the component.
                    if (options.text) {
                        this.updateTextContent(options.text);
                    }
                },

                // The `createEl` function of a component creates its DOM element.
                createEl: function() {
                    return videojs.createEl('div', {

                        // Prefixing classes of elements within a player with "vjs-"
                        // is a convention used in Video.js.
                        className: 'vjs-title-bar'
                    });
                },

                // This function could be called at any time to update the text
                // contents of the component.
                updateTextContent: function(text) {

                    // If no text was provided, default to "Title Unknown"
                    if (typeof text !== 'string') {
                        text = 'Title Unknown';
                    }

                    // Use Video.js utility DOM methods to manipulate the content
                    // of the component's element.
                    videojs.emptyEl(this.el());
                    videojs.appendContent(this.el(), text);
                }
            });

            // Register the component with Video.js, so it can be used in players.
            videojs.registerComponent('TitleBar', TitleBar);

            var player = videojs('video-{{$educationalContent->id}}');
            player.controlBar.addChild('QualitySelector');
            // Create a player.
            // Add the TitleBar as a child of the player and provide it some text
            // in its options.
            player.addChild('TitleBar', {text: '{{ isset($educationalContentDisplayName) ? $educationalContentDisplayName : "" }}'});

        });
    </script>
@endsection