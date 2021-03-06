@extends('partials.templatePage' , ['pageName'=>$pageName])

@section('page-preload-css')
    <link rel="preload" href="{{ mix('/css/page-homePage.css') }}" as="style" />
@endsection

@section('page-css')
    <link href="{{ mix('/css/page-homePage.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    @if($slideBlock->banners->count() > 0)
        @include('block.partials.main', [
            'block'=>$slideBlock,
             'positionOfSlideShow'=>'صفحه اصلی',
            'marginBottom'=>'25'
        ])
    @endif

    <div class="m--clearfix"></div>
    <div class="row">
        <div class="col">
            <!--begin:: Widgets/Stats-->
            <div class="m-portlet homePageNavigation">
                <div class="m-portlet__body  m-portlet__body--no-padding homePageNavigation-row">
                    <div class="row m-row--no-padding m-row--col-separator-xl shopNavItems">
                        <div class="col-6 col-md-3 m--bg-warning shopNavItem">
                            <a target="_self" class="btnScrollTo" data-scroll-to=".konkoor1" href="#konkoor1">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            کنکور نظام قدیم
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                        <div class="col-6 col-md-3 m--bg-accent shopNavItem">
                            <a target="_self" class="btnScrollTo" data-scroll-to=".konkoor2" href="#konkoor2">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            کنکور نظام جدید
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                        <div class="col-6 col-md-3 m--bg-success shopNavItem">
                            <a target="_self" class="btnScrollTo" data-scroll-to=".yazdahom" href="#yazdahom">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            پایه یازدهم
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                        <div class="col-6 col-md-3 m--bg-info shopNavItem">
                            <a target="_self" class="btnScrollTo" data-scroll-to=".dahom" href="#dahom">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            پایه دهم
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body  m-portlet__body--no-padding homePageNavigation-row">
                    <div class="row m-row--no-padding m-row--col-separator-xl shopNavItems">
                        <div class="col-12 col-md-6 m--bg-info shopNavItem">
                            <a target="_self" href="{{ route('web.landing.5') }}">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            همایش های دانلودی نظام قدیم
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                        <div class="col-12 col-md-6 m--bg-accent shopNavItem">
                            <a target="_self" href="{{ route('web.landing.8') }}">
                                <!--begin::Total Profit-->
                                <div class="m-widget24 text-center">
                                    <div class="m-widget24__item">
                                        <h2 class="m-widget24__title">
                                            همایش های دانلودی نظام جدید
                                        </h2>
                                    </div>
                                </div>
                                <!--end::Total Profit-->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Stats-->
        </div>
    </div>

    @foreach($blocks as $block)
        @if($block->class === 'konkoor1')
{{--            <div class="row">--}}
{{--                <div class="col-12 col-md-6 text-center m--margin-bottom-5">--}}
{{--                    <a href="{{ route('web.landing.5') }}"--}}
{{--                       class="a--gtm-eec-advertisement a--gtm-eec-advertisement-click"--}}
{{--                            @include('partials.gtm-eec.promotion', ['id'=>'homepage-downloadi1', 'name'=>'همایش های دانلودی آلاء', 'creative'=>'قبل از بلاک کنکور نظام قدیم', 'position'=>'سمت راست', ])--}}
{{--                       data-gtm-eec-promotion-id="homepage-downloadi1"--}}
{{--                       data-gtm-eec-promotion-name="همایش های دانلودی آلاء"--}}
{{--                       data-gtm-eec-promotion-creative="قبل از بلاک کنکور نظام قدیم"--}}
{{--                       data-gtm-eec-promotion-position="سمت راست">--}}
{{--                        <img data-src="{{ asset('/acm/extra/ads/gif/728-180(2).gif') }}" alt="همایش های دانلودی آلاء" class="a--full-width lazy-image">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-md-6 text-center m--margin-bottom-5">--}}
{{--                    <a href="{{ route('web.landing.8') }}"--}}
{{--                       class="a--gtm-eec-advertisement a--gtm-eec-advertisement-click"--}}
{{--                       data-gtm-eec-promotion-id="homepage-ghorekeshi1"--}}
{{--                       data-gtm-eec-promotion-name="قرعه کشی گوشی"--}}
{{--                       data-gtm-eec-promotion-creative="قبل از بلاک کنکور نظام قدیم"--}}
{{--                       data-gtm-eec-promotion-position="سمت چپ">--}}
{{--                        <img data-src="{{ asset('/acm/extra/ads/gif/728-180.gif') }}" alt="قرعه کشی گوشی" class="a--full-width lazy-image">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
        @elseif($block->class === 'dahom')
{{--            <div class="row">--}}
{{--                <div class="col-12 col-md-6 text-center m--margin-bottom-5">--}}
{{--                    <a href="{{ route('web.landing.8') }}"--}}
{{--                       class="a--gtm-eec-advertisement a--gtm-eec-advertisement-click"--}}
{{--                       data-gtm-eec-promotion-id="homepage-ghorekeshi1"--}}
{{--                       data-gtm-eec-promotion-name="قرعه کشی گوشی"--}}
{{--                       data-gtm-eec-promotion-creative="قبل از بلاک پایه دهم"--}}
{{--                       data-gtm-eec-promotion-position="سمت راست">--}}
{{--                        <img data-src="{{ asset('/acm/extra/ads/gif/728-180.gif') }}" alt="قرعه کشی گوشی" class="a--full-width lazy-image">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-md-6 text-center m--margin-bottom-5">--}}
{{--                    <a href="{{ route('web.landing.5') }}"--}}
{{--                       class="a--gtm-eec-advertisement a--gtm-eec-advertisement-click"--}}
{{--                       data-gtm-eec-promotion-id="homepage-downloadi1"--}}
{{--                       data-gtm-eec-promotion-name="همایش های دانلودی آلاء"--}}
{{--                       data-gtm-eec-promotion-creative="قبل از بلاک پایه دهم"--}}
{{--                       data-gtm-eec-promotion-position="سمت چپ">--}}
{{--                        <img data-src="{{ asset('/acm/extra/ads/gif/728-180(2).gif') }}" alt="همایش های دانلودی آلاء" class="a--full-width lazy-image">--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
        @endif
        @if($block->banners->count() === 0)
            @include('block.partials.main', [
                'blockCustomClass'=>$block->class.' a--content-carousel-1 dasboardLessons',
                'blockCustomId'=>'sectionId-'.$block->class,
                'blockType'=>(isset($block->sets) && $block->sets->count()>0)?'set':(isset($block->products) && $block->products->count()>0?'product':'content'),
                'blockUrlDisable'=>false,
                'btnLoadMore'=>true
            ])
        @endif
        {{--            @foreach($section["ads"] as $image => $link)--}}
        {{--                @include('partials.bannerAds', ['img'=>$image , 'link'=>$link])--}}
        {{--            @endforeach--}}
    @endforeach
{{--    <div class="row">--}}
{{--        <div class="col text-center m--margin-bottom-5">--}}
{{--            <a href="{{ route('web.landing.8') }}"--}}
{{--               class="a--gtm-eec-advertisement a--gtm-eec-advertisement-click"--}}
{{--               data-gtm-eec-promotion-id="homepage-ghorekeshi2"--}}
{{--               data-gtm-eec-promotion-name="قرعه کشی گوشی"--}}
{{--               data-gtm-eec-promotion-creative="پایین همه بلاک ها"--}}
{{--               data-gtm-eec-promotion-position="وسط">--}}
{{--                <img data-src="{{ asset('/acm/extra/ads/gif/970-90(1).gif') }}" alt="همایش های دانلودی آلاء" class="a--full-width lazy-image">--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    @include('partials.certificates')--}}

@endsection

@section('page-js')
    <script>
        var sections = [
        @foreach($blocks as $block)
            '{{ $block->class }}',
        @endforeach
        ];
        var gtmEecImpressions = [
            @foreach($blocks as $block)
                @foreach($block->products as $productKey=>$product)
                {
                    id: '{{ $product->id }}',
                    name: '{{ $product->name }}',
                    category: '-',
                    list: '{{ $block->title }}',
                    position: '{{ $productKey }}'
                },
                @endforeach
            @endforeach
        ];
        var gtmEecPromotions = [
            @foreach($slideBlock->banners as $slideKey=>$slide)
                {
                    id: '{{ $slide->id }}',
                    name: '{{ $slide->title }}',
                    creative: 'اسلایدر صفحه اصلی',
                    position: '{{ $slideKey }}'
                },
            @endforeach
        ];
    </script>


    <script src="{{ mix('/js/page-homePage.js') }}" async ></script>
@endsection

@section('page-preload-js')
    <link rel="preload" href="{{ mix('/js/page-homePage.js') }}" as="script">
@endsection
