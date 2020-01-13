@extends('app')

@section('page-css')
    <link href="{{ mix('/css/set-show.css') }}" rel="stylesheet">
@endsection

@section('page-head')
    @if(isset($jsonLdArray))
        <!-- JSON-LD markup generated by Google Structured Data Markup Helper. -->
        <script type="application/ld+json">
            {!! json_encode($jsonLdArray , JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
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
                   href="{{ action('Web\ContentController@index') }}">@lang('content.Educational Content Of Alaa')</a>
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
           value='{{route('set.show' , $contentSet->id)}}'>
@endsection

@section('content')

    @include('partials.ads.list', ['id'=>'setShow-TopOfList-mobileShow'])

    <div class="row">

        <div class="col-lg-2 a--desktop-show rightSideAdBanner">
            @include('partials.ads.list', ['id'=>'setShow-TopOfList-desktopShow'])
        </div>
        <div class="col-lg-8 mx-auto">
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__head contentsetListHead">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h1 class="m-portlet__head-text">
                                {{ $contentSet->name }}
                            </h1>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="FavoriteAndOrder">
                            <div class="Order">
                                @if($order == 'desc')
                                    <a href="?order=asc" id="sort-ascending">
                                        <i class="fa fa-sort-amount-down"></i>
                                    </a>
                                @else
                                    <a href="?order=desc" id="sort-descending">
                                        <i class="fa fa-sort-amount-up"></i>
                                    </a>
                                @endif
                            </div>

                            @include('partials.favorite', [
                                'favActionUrl' => route('web.mark.favorite.set', [ 'set' => $contentSet->id ]),
                                'unfavActionUrl' => route('web.mark.unfavorite.set', [ 'set' => $contentSet->id ]),
                                'isFavored' => $isFavored
                            ])


                        </div>
                        <div class="countOfItems">

                            @if($videos->isNotEmpty())
                                فیلم ها : {{$videos->count()}} @if($pamphlets->isNotEmpty())|@endif
                            @endif
                            @if($pamphlets->isNotEmpty())
                                جزوه ها: {{$pamphlets->count()}} @if($articles->isNotEmpty())|@endif
                            @endif
                            @if($articles->isNotEmpty())
                                مقاله ها: {{$articles->count()}}
                            @endif

                        </div>

                    </div>
                </div>
                <div class="m-portlet__body setVideoPamphletTabs">

                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#searchResult_video" data-state="video">
                                @if($videos->count() > 0)
                                    فیلم ها
                                    ({{$videos->count()}})
                                @else
                                    فیلم ندارد
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{($pamphlets->count() === 0) ? 'disabled' : ''}}" data-toggle="tab" href="#searchResult_pamphlet" data-state="pamphlet">
                                @if($pamphlets->count() > 0)
                                    جزوات
                                    ({{$pamphlets->count()}})
                                @else
                                    جزوه ندارد
                                @endif
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="searchResult_video" role="tabpanel">
                            <div class="m-widget5">
                                <div class="setVideo">
                                    @foreach($videos as $video)
                                        <div class="m-widget5__item">
                                            <div class="m-widget5__content">
                                                <a href="{{ route('c.show' , $video ) }}" style="display: inherit">
                                                    <div class="m-widget5__pic  a--full-width" style="display: contents" >
                                                        <img class="img-fluid a--full-width lazy-image" width="453" height="254"  src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="{{$video->thumbnail}}" alt="{{$video->displayName}}" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود یا تماشا فیلم">
                                                    </div>
                                                </a>
                                                <div class="m-widget5__section">
                                                    <h2 class="m-widget5__title m--margin-top-10-mobile">
                                                        <a href="{{ route('c.show' ,$video) }}">{{$video->displayName}}</a>
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
                                                    <button type="button" class="btn m-btn--pill  btn-primary btn-block"  onclick="window.location = '{{route('c.show' , $video)}}';" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود یا تماشا فیلم"> <i class="fa fa-eye"></i> / <i class="fa fa-play"></i> </button>
                                                    {{--                                            @foreach($pamphlets->where('session' , $video->session) as $pamphlet)--}}
                                                    {{--                                                <button type="button" class="btn m-btn--pill  btn-focus btn-block" onclick="window.location = '{{route('c.show' , $pamphlet)}}';" data-container="body" data-toggle="m-tooltip" data-placement="top" title="دانلود جزوه">جزوه</button>--}}
                                                    {{--                                            @endforeach--}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach($articles as $article)
                                        <div class="m-widget5__item">
                                            <div class="m-widget5__content">
                                                <a href="{{ route('c.show' , $article) }}" style="display: inherit">
                                                    <div class="m-widget5__pic  a--full-width" style="display: contents" >
                                                        <img class="img-fluid a--full-width lazy-image" width="453" height="254"  src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="{{$article->thumbnail}}" alt="{{$article->name}}" data-container="body" data-toggle="m-tooltip" data-placement="top" title="خواندن مقاله">
                                                    </div>
                                                </a>
                                                <div class="m-widget5__section">
                                                    <h2 class="m-widget5__title m--margin-top-10-mobile">
                                                        <a href="{{ route('c.show' , $article) }}">{{$article->name}}</a>
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
                                                    <button type="button" class="btn m-btn--pill  btn-primary btn-block"
                                                            onclick="window.location = '{{route('c.show' , $article)}}';"
                                                            data-container="body" data-toggle="m-tooltip"
                                                            data-placement="top" title="خواندن مقاله">خواندن
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane text-center" id="searchResult_pamphlet" role="tabpanel">
                            <div class="setPamphlet m-widget4 text-left">
                                @if($pamphlets->isEmpty())
                                    <h3 class="m--align-center">این درس جزوه ای ندارد</h3>
                                @else
                                    @foreach($videos as $video)
                                        @foreach($pamphlets->where('session' , $video->session) as $pamphlet)
                                            <div class="m-widget4__item">
                                                <div class="m-widget4__img m-widget4__img--icon">
                                                    <a href="{{$pamphlet->file->first()->first()->link}}"
                                                       title="{{$pamphlet->name}}">
                                                        <svg width="50" height="50" viewBox="-79 0 512 512"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="m353.101562 485.515625h-353.101562v-485.515625h273.65625l79.445312 79.449219zm0 0"
                                                                fill="#e3e4d8"/>
                                                            <path d="m273.65625 0v79.449219h79.445312zm0 0"
                                                                  fill="#d0cebd"/>
                                                            <path
                                                                d="m0 353.101562h353.101562v158.898438h-353.101562zm0 0"
                                                                fill="#b53438"/>
                                                            <g fill="#fff">
                                                                <path
                                                                    d="m52.964844 485.515625c-4.871094 0-8.828125-3.953125-8.828125-8.824219v-88.277344c0-4.875 3.957031-8.828124 8.828125-8.828124 4.875 0 8.828125 3.953124 8.828125 8.828124v88.277344c0 4.871094-3.953125 8.824219-8.828125 8.824219zm0 0"/>
                                                                <path
                                                                    d="m300.136719 397.242188h-52.964844c-4.871094 0-8.828125-3.957032-8.828125-8.828126 0-4.875 3.957031-8.828124 8.828125-8.828124h52.964844c4.875 0 8.828125 3.953124 8.828125 8.828124 0 4.871094-3.953125 8.828126-8.828125 8.828126zm0 0"/>
                                                                <path
                                                                    d="m300.136719 441.378906h-52.964844c-4.871094 0-8.828125-3.953125-8.828125-8.828125 0-4.871093 3.957031-8.828125 8.828125-8.828125h52.964844c4.875 0 8.828125 3.957032 8.828125 8.828125 0 4.875-3.953125 8.828125-8.828125 8.828125zm0 0"/>
                                                                <path
                                                                    d="m247.171875 485.515625c-4.871094 0-8.828125-3.953125-8.828125-8.824219v-88.277344c0-4.875 3.957031-8.828124 8.828125-8.828124 4.875 0 8.828125 3.953124 8.828125 8.828124v88.277344c0 4.871094-3.953125 8.824219-8.828125 8.824219zm0 0"/>
                                                            </g>
                                                            <path
                                                                d="m170.203125 95.136719c-.863281.28125-11.695313 15.261719.847656 27.9375 8.351563-18.371094-.464843-28.054688-.847656-27.9375m5.34375 73.523437c-6.296875 21.496094-14.601563 44.703125-23.527344 65.710938 18.378907-7.042969 38.375-13.195313 57.140625-17.546875-11.871094-13.621094-23.738281-30.632813-33.613281-48.164063m65.710937 57.175782c7.167969 5.445312 8.914063 8.199218 13.613282 8.199218 2.054687 0 7.925781-.085937 10.636718-3.828125 1.316407-1.820312 1.828126-2.984375 2.019532-3.59375-1.074219-.574219-2.515625-1.710937-10.335938-1.710937-4.449218 0-10.027344.191406-15.933594.933594m-119.957031 38.601562c-18.804687 10.425781-26.464843 19-27.011719 23.835938-.089843.804687-.328124 2.90625 3.785157 6.011718 1.316406-.414062 8.96875-3.859375 23.226562-29.847656m-23.421875 44.527344c-3.0625 0-6-.980469-8.507812-2.832032-9.15625-6.796874-10.390625-14.347656-9.808594-19.492187 1.597656-14.132813 19.304688-28.945313 52.648438-44.03125 13.230468-28.636719 25.820312-63.921875 33.324218-93.398437-8.773437-18.871094-17.3125-43.351563-11.097656-57.714844 2.179688-5.03125 4.910156-8.894532 9.976562-10.566406 2.011719-.652344 7.078126-1.480469 8.941407-1.480469 4.617187 0 9.050781 5.507812 11.183593 9.089843 3.972657 6.648438 3.992188 14.390626 3.363282 21.859376-.609375 7.253906-1.84375 14.46875-3.265625 21.601562-1.039063 5.242188-2.214844 10.460938-3.46875 15.660156 11.855469 24.175782 28.644531 48.816406 44.746093 65.683594 11.539063-2.054688 21.460938-3.097656 29.546876-3.097656 13.761718 0 22.121093 3.167968 25.519531 9.691406 2.828125 5.402344 1.660156 11.726562-3.433594 18.769531-4.898437 6.769531-11.640625 10.34375-19.523437 10.34375-10.710938 0-23.15625-6.671875-37.050782-19.851562-24.957031 5.15625-54.097656 14.34375-77.65625 24.515625-7.355468 15.410156-14.398437 27.824218-20.964844 36.933594-8.996093 12.5-16.773437 18.316406-24.472656 18.316406"
                                                                fill="#b53438"/>
                                                            <path
                                                                d="m79.449219 450.207031h-26.484375c-4.871094 0-8.828125-3.953125-8.828125-8.828125v-52.964844c0-4.875 3.957031-8.828124 8.828125-8.828124h26.484375c19.472656 0 35.308593 15.835937 35.308593 35.3125 0 19.472656-15.835937 35.308593-35.308593 35.308593zm-17.65625-17.65625h17.65625c9.734375 0 17.652343-7.917969 17.652343-17.652343 0-9.738282-7.917968-17.65625-17.652343-17.65625h-17.65625zm0 0"
                                                                fill="#fff"/>
                                                            <path
                                                                d="m158.898438 485.515625h-8.828126c-4.875 0-8.828124-3.953125-8.828124-8.824219v-88.277344c0-4.875 3.953124-8.828124 8.828124-8.828124h8.828126c29.199218 0 52.964843 23.753906 52.964843 52.964843 0 29.210938-23.765625 52.964844-52.964843 52.964844zm0-17.652344h.085937zm0-70.621093v70.621093c19.472656 0 35.308593-15.839843 35.308593-35.3125 0-19.472656-15.835937-35.308593-35.308593-35.308593zm0 0"
                                                                fill="#fff"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="m-widget4__info">
                                                    <span class="m-widget4__text">
                                                        <a href="{{$pamphlet->file->first()->first()->link}}"
                                                           class="m-link" title="{{$pamphlet->name}}">
                                                            {{$pamphlet->name}}
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="m-widget4__ext">
                                                    <a href="{{$pamphlet->file->first()->first()->link}}"
                                                       class="m-widget4__icon" title="{{$pamphlet->name}}">
                                                        <i class="fa fa-cloud-download-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-2 a--desktop-show"></div>
    </div>
@endsection


@section('page-js')
    <script src="{{ mix('/js/set-show.js') }}" type="text/javascript"></script>
@endsection
