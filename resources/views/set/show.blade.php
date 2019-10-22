@extends('app')

@section('page-css')
    <style src="/demo12/assets/demo/demo12/base/style.bundle.rtl.css"></style>
    <style>
        .m-widget5__desc{
            font-size:1.2rem !important;
            text-align: justify;
        }
        .m-widget5__title{
            font-weight: 700 !important;
        }
    </style>
@endsection

@section('pageBar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="fa fa-home m--padding-right-5"></i>
                <a class="m-link" href="{{route('web.index')}}">@lang('page.Home')</a>
            </li>
            <li class="breadcrumb-item">
                <i class="fa fa-video-camera m--padding-right-5"></i>
                <a class="m-link"
                   href="{{ action("Web\ContentController@index") }}">@lang('content.Educational Content Of Alaa')</a>
            </li>
            <li class="breadcrumb-item">
                <i class="fa fa-video-camera m--padding-right-5"></i>
                <a class="m-link" href="#">{{ $contentSet->name }}</a>
            </li>
        </ol>
    </nav>
    <input id="js-var-setId" class="m--hide" type="hidden" value='{{ $contentSet->id }}'>
    <input id="js-var-setName" class="m--hide" type="hidden" value='{{ $contentSet->name }}'>
    <input id="js-var-setUrl" class="m--hide" type="hidden"
           value='{{action("Web\ContentController@show" , $contentSet)}}'>
@endsection

@section('content')
        <div class="col-lg-8 mx-auto">
            <div class="m-portlet m-portlet--full-height ">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h1 class="m-portlet__head-text">
                        {{ $contentSet->name }}
                    </h1>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                    @if($order == 'desc')
                    <li  class="nav-item m-tabs__item ">
                        <a href="?order=asc" id="sort-ascending">
                            <i class="fa fa-arrow-up"></i>
                        </a>
                    </li>
                    @else
                    <li  class="nav-item m-tabs__item ">
                        <a href="?order=desc" id="sort-descending">
                            <i class="fa fa-arrow-down"></i>
                        </a>
                    </li>
                    @endif
                    <li  class="nav-item m-tabs__item font-weight-bold">
                        |
                    </li>

                    @if($videos->isNotEmpty())
                        <li class="nav-item m-tabs__item font-weight-bold">
                                فیلم ها : {{$videos->count()}} @if($pamphlets->isNotEmpty())|@endif
                        </li>
                    @endif
                    @if($pamphlets->isNotEmpty())
                        <li class="nav-item m-tabs__item font-weight-bold">
                                جزوه ها: {{$pamphlets->count()}} @if($articles->isNotEmpty())|@endif
                        </li>
                    @endif
                    @if($articles->isNotEmpty())
                        <li class="nav-item m-tabs__item font-weight-bold">
                                مقاله ها: {{$articles->count()}}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-widget5">
                @foreach($videos as $video)
                    <div class="m-widget5__item">
                        <div class="m-widget5__content">
                            <a href="{{ route('c.show' , ['content'=>$video]) }}" style="display: inherit">
                                <div class="m-widget5__pic  a--full-width" style="display: contents" >
                                    <img class="img-fluid a--full-width lazy-image " width="112" height="63"  src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="{{$video->thumbnail}}" alt="{{$video->displayName}}" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود یا تماشا فیلم">
                                </div>
                            </a>
                            <div class="m-widget5__section">
                                <h2 class="m-widget5__title m--margin-top-10-mobile">
                                    <a href="{{ route('c.show' , ['content'=>$video]) }}">{{$video->displayName}}</a>
                                </h2>
                                <div class="m-widget5__info font-weight-bold">
                                    @if($video->isFree)
                                        <span class="m-badge m-badge--accent m-badge--wide">رایگان</span>
                                    @else
                                        <span class="m-badge m-badge--warning m-badge--wide">ویژه شما</span>
                                    @endif
                                    <span>|</span>
                                        <span class="m-widget5__info-date m--font-info">آخرین به روز رسانی: {{$video->UpdatedAt_Jalali()}}</span>
                                    <span>| آلاء</span>
                                </div>
                                <span class="m-widget5__desc">
                                                    {!! $video->metaDescription !!}...
                                                </span>
                                <div class="m--clearfix"></div>
                            </div>
                        </div>
                        <div class="m-widget5__content">
                            <div>
                                <button type="button" class="btn m-btn--pill  btn-primary btn-block"  onclick="window.location = '{{route('c.show' , ['content'=>$video])}}';" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود یا تماشا فیلم">فیلم</button>
                                @foreach($pamphlets->where('session' , $video->session) as $pamphlet)
                                    <button type="button" class="btn m-btn--pill  btn-focus btn-block" onclick="window.location = '{{route('c.show' , ['content'=>$pamphlet])}}';" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود جزوه">جزوه</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach($articles as $article)
                    <div class="m-widget5__item">
                        <div class="m-widget5__content">
                            <a href="{{ route('c.show' , ['content'=>$article]) }}" style="display: inherit">
                                <div class="m-widget5__pic  a--full-width" style="display: contents" >
                                    <img class="img-fluid a--full-width lazy-image " width="112" height="63"  src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="{{$article->thumbnail}}" alt="{{$article->name}}" data-container="body" data-toggle="m-tooltip" data-placement="top" title="خواندن مقاله">
                                </div>
                            </a>
                            <div class="m-widget5__section">
                                <h2 class="m-widget5__title m--margin-top-10-mobile">
                                    <a href="{{ route('c.show' , ['content'=>$article]) }}">{{$article->name}}</a>
                                </h2>
                                <div class="m-widget5__info font-weight-bold">
                                    @if($article->isFree)
                                        <span class="m-badge m-badge--accent m-badge--wide">رایگان</span>
                                    @else
                                        <span class="m-badge m-badge--warning m-badge--wide">ویژه شما</span>
                                    @endif
                                    <span>|</span>
                                    <span class="m-widget5__info-date m--font-info">آخرین به روز رسانی: {{$article->UpdatedAt_Jalali()}}</span>
                                    <span>| آلاء</span>
                                </div>
                                <span class="m-widget5__desc">
                                         {!! $article->metaDescription !!}...
                                </span>
                                <div class="m--clearfix"></div>
                            </div>
                        </div>
                        <div class="m-widget5__content">
                            <div>
                                <button type="button" class="btn m-btn--pill  btn-primary btn-block"  onclick="window.location = '{{route('c.show' , ['content'=>$article])}}';" data-container="body" data-toggle="m-tooltip" data-placement="top" title="خواندن مقاله">خواندن</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
        </div>
@endsection
