@extends('partials.templatePage')

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
                            <div class="Order text-center">
                                @if($order == 'desc')
                                    <a href="?order=asc" id="sort-ascending">
                                        <i class="fa fa-sort-amount-down"></i>
                                    </a>
                                @else
                                    <a href="?order=desc" id="sort-descending">
                                        <i class="fa fa-sort-amount-up"></i>
                                    </a>
                                @endif

                                    <a class="m--margin-left-10" href="javascript:" id="btnShowSectionView">
                                        <i class="fa fa-cubes"></i>
                                    </a>

                                    <a class="m--margin-left-10" href="javascript:" id="btnShowListView">
                                        <i class="fa fa-list-ul"></i>
                                    </a>

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
                                @if($videos->isNotEmpty())
                                    فیلم ها
                                    ({{$videos->count()}})
                                @else
                                    فیلم ندارد
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{($pamphlets->isEmpty()) ? 'disabled' : ''}}" data-toggle="tab" href="#searchResult_pamphlet" data-state="pamphlet">
                                @if($pamphlets->isNotEmpty())
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
                            <div class="a--list1 setVideo">
                                <div class="m-accordion m-accordion--default m-accordion--toggle-arrow" id="m_accordion_video" role="tablist"></div>
                            </div>
                        </div>
                        <div class="tab-pane text-center" id="searchResult_pamphlet" role="tabpanel">
                            <div class="a--list2 setPamphlet">
                                <div class="m-accordion m-accordion--default m-accordion--toggle-arrow" id="m_accordion_pamphlet" role="tablist"></div>
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
    <script type="text/javascript">
        var videos = [
                @foreach($videos as $videoItemKey => $video)
                    {
                        section: {
                            id : '{{optional($video->section)->id}}',
                            name : '{{optional($video->section)->name}}',
                        },
                        order: '{{$video->order}}',
                        link: '{{ route('c.show' , $video ) }}',
                        photo: '{{$video->thumbnail}}',
                        name: '{{$video->displayName}}',
                        isFree: {{$video->isFree}},
                        updatedAtJalali: '{{$video->UpdatedAt_Jalali()}}',
                        metaDescription: '{{$video->metaDescription}}',
                    },
                @endforeach
            ],
            pamphlets = [
                @foreach($pamphlets as $pamphletItemKey => $pamphlet)
                    {
                        section: {
                            id : '{{optional($pamphlet->section)->id}}',
                            name : '{{optional($pamphlet->section)->name}}',
                        },
                        order:'{{$pamphlet->order}}',
                        link: '{{(isset($pamphlet->file))?$pamphlet->file->first()->first()->link:$pamphlet->url}}',
                        name: '{{$pamphlet->name}}',
                        shouldPurchase: '{{$pamphlet->shouldPurchase ?? 0}}'
                    },
                @endforeach
            ];
    </script>
    <script src="{{ mix('/js/set-show.js') }}" type="text/javascript"></script>
@endsection
