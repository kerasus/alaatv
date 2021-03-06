@extends('partials.templatePage')

@section('page-css')
    <link href="{{ mix('/css/page-landing5.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
                <div class="m-portlet__head m-portlet__head--fit-">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light d-none">
                                ...
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools"></div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget27 m-portlet-fit--sides">

                        <div class="m-widget27__pic">
                            <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1"
                                 data-src="https://cdn.alaatv.com/upload/landingNezameGhadim-gold-fest.png"
                                 class="lazy-image" alt="" width="944" height="407">
                            <h3 class="m-widget27__title m--font-light">

                            </h3>
                        </div>
                        <div class="m-widget27__container">
                            <div class="container-fluid m--padding-right-40 m--padding-left-40">


                                <div class="row">
                                    <div class="col text-center m--margin-bottom-15">
                                        <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">
                                            <button type="button" class="m-btn btn btn-danger btnShowRiazi">ریاضی</button>
                                            <button type="button" class="m-btn btn btn-info btnShowTajrobi">تجربی</button>
                                            <button type="button" class="m-btn btn btn-success btnShowEnsani">انسانی</button>
                                            <button type="button" class="m-btn btn btn-warning btnAllMajor">همه رشته ها</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">

                                        <div class="row justify-content-center">


                                            @foreach($products as $key=>$product)


                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 m--padding-left-5 m--padding-right-5 m--margin-top-5 a--imageWithCaption {{ $product['type']  }}">
                                                    <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="{{ $product['image']  }}" alt="{{ $product['name']  }}" class="img-thumbnail lazy-image" width="400" height="400">
                                                    <a href="{{ $product['link']  }}"
                                                       class="a--gtm-eec-product a--gtm-eec-product-click"
                                                       data-gtm-eec-product-id="{{ $product['id'] }}"
                                                       data-gtm-eec-product-name="{{ $product['name'] }}"
                                                       data-gtm-eec-product-price="{{ number_format($product['price']['final'], 2, '.', '') }}"
                                                       data-gtm-eec-product-brand="آلاء"
                                                       data-gtm-eec-product-category="-"
                                                       data-gtm-eec-product-variant="-"
                                                       data-gtm-eec-product-position="{{ $key }}"
                                                       data-gtm-eec-product-list="لندینگ5-همایش های نظام قدیم">
                                                        <div class="a--imageCaptionWarper">
                                                            <div class="a--imageCaptionContent">
                                                                <div class="a--imageCaptionTitle">
                                                                    {{ $product['name']  }}
                                                                </div>
                                                                <div class="a--imageCaptionDescription">
                                                                    @if($product['type']=='tajrobi')
                                                                        <span class="m-badge m-badge--info m-badge--wide"><span class="m--font-light">تجربی</span></span>
                                                                    @elseif($product['type']=='riazi')
                                                                        <span class="m-badge m-badge--danger m-badge--wide"><span class="m--font-light">ریاضی</span></span>
                                                                    @elseif($product['type']=='ensani')
                                                                        <span class="m-badge m-badge--success m-badge--wide"><span class="m--font-light">انسانی</span></span>
                                                                    @else
                                                                        <span class="m-badge m-badge--warning m-badge--wide">
                                                                            <span class="m--font-light">
                                                                                @if(strpos($product['type'], 'tajrobi')!==false)
                                                                                    تجربی
                                                                                @endif
                                                                                @if(strpos($product['type'], 'riazi')!==false)
                                                                                    ریاضی
                                                                                @endif
                                                                                @if(strpos($product['type'], 'ensani')!==false)
                                                                                    انسانی
                                                                                @endif
                                                                            </span>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-5 d-flex justify-content-center">
                                    <div class="col-md-10 text-center heading-section ftco-animate">
                                        <h2 class="h2">دانلود نمونه فیلم همایش</h2>
                                        <div>
                                            <a href="{{action("Web\ContentController@show",7884)}}">
                                                <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1"
                                                     data-src="https://cdn.alaatv.com/upload/landingNezameGhadim-sampleVideo.png"
                                                     class="downloadVideoImage a--full-width lazy-image"
                                                     width="563"
                                                     height="342" />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-md-10 text-center heading-section heading-section-white ftco-animate">
                                        <h3>با ماندگاری مطالب در ذهنتان، استرس را فراموش کنید!</h3>

                                        <p>
                                            چند ماه مانده به کنکور؛ دوران گیجی دانش‌آموزان است: آن‌هایی که زیاد خوانده‌اند دیوانه‌وار بیشتر و بیشتر می‌خوانند و آن‌هایی که کمتر خوانده‌اند پناهشان می‌شود جزوات متعدد دم کنکور! اما چاره این سرگیجه چیست؟

                                            با بررسی دلیل موفقیت برترین‌های کنکور در سال‌های متوالی یک نکته مهم در برطرف‌شدن این استرس نهفته است: مرور در ماه‌های آخر!
                                        </p>


                                    </div>
                                </div>

                                <div class="row lastSection">
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 lastSectionItem">
                                        <div class="icon">
                                            <i class="fa fa-lightbulb"></i>
                                        </div>
                                        <div class="text">
                                            <h3>مفهومی</h3>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 lastSectionItem">
                                        <div class="icon">
                                            <i class="fa fa-road"></i>
                                        </div>
                                        <div class="text">
                                            <h3>سبقت</h3>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 lastSectionItem">
                                        <div class="icon">
                                            <i class="fa fa-edit"></i>
                                        </div>
                                        <div class="text">
                                            <h3>جزوه</h3>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 lastSectionItem">
                                        <div class="icon">
                                            <i class="fa fa-lightbulb"></i>
                                        </div>
                                        <div class="text">
                                            <h3>تحلیل</h3>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ mix('/js/page-landing5.js') }}"></script>
@endsection
