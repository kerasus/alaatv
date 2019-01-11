@extends("app")
@section("pageBar")
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            <li class = "breadcrumb-item">
                <i class = "flaticon-home-2 m--padding-right-5"></i>
                <a class = "m-link" href = "{{action("HomeController@index")}}">@lang('page.Home')</a>
            </li>
            <li class = "breadcrumb-item">
                <i class = "flaticon-photo-camera m--padding-right-5"></i>
                <a class = "m-link" href = "{{ action("ProductController@index") }}">محصولات آموزشی</a>
            </li>
            <li class = "breadcrumb-item active" aria-current = "page">
                <a class = "m-link" href = "#"> {{ $product->name }} </a>
            </li>
        </ol>
    </nav>
@endsection
@section("content")
    @include("systemMessage.flash")
    <div class = "row" id="a_top_section">
        <div class = "col-xl-12">
            <!--begin::Portlet-->
            <div class = "m-portlet m-portlet--mobile">
                <div class = "m-portlet__body">
                    <!--begin::Section-->
                    <div class = "m-section m-section--last">
                        <div class = "m-section__content">
                            <!--begin::Preview-->
                            <div class = "row">
                                <div class = "col-lg-3">
                                    <div class = "">
                                        <img src = "{{ route('image', ['category'=>'4','w'=>'338' , 'h'=>'338' ,  'filename' =>  $product->image ]) }}" alt = "عکس محصول@if(isset($product->name[0])) {{$product->name}} @endif" class = "img-fluid m--marginless"/>
                                    </div>
                                    @if($product->samplePhotos !== null)
                                        <div class="m--space-10"></div>
                                        <h5>نمونه جزوه {{ $product->name }}</h5>
                                        <div class="m-nav-grid">
                                            @foreach ($product->samplePhotos->chunk(3) as $chunk)
                                                <div class="m-nav-grid__row">
                                                    @foreach ($chunk as $samplePhoto)
                                                        <a href="{{ $samplePhoto->url }}"
                                                           target="_blank"
                                                           class="m-nav-grid__item">
                                                            <img src="{{ $samplePhoto->url('100','135') }}"
                                                                 alt="@if(isset($samplePhoto->title[0])) {{$samplePhoto->title}} @else نمونه عکس {{$product->name}} @endif">
                                                            <span class="m-nav-grid__text">{{ $samplePhoto->title ?? $samplePhoto->title }}</span>
                                                            <span class="m-nav-grid__text">{{ $samplePhoto->description ?? $samplePhoto->description }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                                <div class = "col-lg-6">
                                    <div class = "m-demo" data-code-preview = "true" data-code-html = "true" data-code-js = "false">
                                        <div class = "m-demo__preview m--padding-10">
                                            {!! Form::open(['method' => 'POST','action' => ['OrderproductController@store'] ]) !!}
                                            @if($product->attributes->get('information') !== null)
                                                <div class="row">
                                                    @foreach($product->attributes->get('information')->chunk(2) as $key => $chunk)
                                                        <div class="col-lg-6">
                                                            <div class="m-list-search">
                                                                <div class="m-list-search__results">
                                                                    <span class="m-list-search__result-category m-list-search__result-category--first">
                                                                        {{ $key == 0 ? 'ویژگی‌ها' : 'دارای'}}
                                                                    </span>
                                                                    @foreach($chunk as $attribute)
                                                                        <a href="#" class="m-list-search__result-item">
                                                                            <span class="m-list-search__result-item-icon">
                                                                                @if(strcmp($attribute->control , 'checkBox') == 0 )
                                                                                    <i class="la la-check m--font-focus"></i>
                                                                                @else
                                                                                    <i class="flaticon-like m--font-warning"></i>
                                                                                @endif
                                                                            </span>
                                                                            <span class="m-list-search__result-item-text">{{$attribute->title . ': ' . collect($attribute->data)->implode('name',',') }}</span>
                                                                        </a>
                                                                        @if(strcmp($attribute->type,"information") != 0 )
                                                                            @foreach($attribute->data as $data)
                                                                                    <input type = "hidden" value = "{{ $data->id }}" name = "attribute[]">
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class = "m-separator m-separator--space m-separator--dashed"></div>

                                            @if($product->attributes->get('extra') !== null)
                                                <div class="portlet sale-summary">
                                                    <div class="portlet-title">
                                                        <div class="caption font-red sbold">تعیین مشخصات</div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <ul class="list-unstyled">
                                                        @foreach($product->attributes->get('extra') as $attribute)
                                                                <li style="margin: 0% 5% 0% 5%">
                                                                    @include("product.partials.extraSelectCollection")
                                                                    @include("product.partials.extraCheckboxCollection" , ["withExtraCost"])
                                                                </li>
                                                        @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class = "m-separator m-separator--space m-separator--dashed"></div>
                                            @endif

                                                @if(in_array($product->producttype->id ,[Config::get("constants.PRODUCT_TYPE_SELECTABLE")]))

                                                    <ul class = "m-nav m-nav--active-bg" id = "m_nav" role = "tablist">
                                                        @if(isset($product->children) && !empty($product->children))
                                                            @foreach($product->children as $p)
                                                                @include('product.partials.showChildren',['product' => $p,
                                                                 'color' => 1,
                                                                 'colors' => [
                                                                        '1' => 'm-switch--primary',
                                                                        '2' => 'm-switch--warning',
                                                                        '3' => 'm-switch--accent',
                                                                        '4' => 'm-switch--success',
                                                                        '5' => 'm-switch--brand',
                                                                        '6' => 'm-switch--info',
                                                                        '7' => 'm-switch--metal',
                                                                        '8' => 'm-switch--danger'
                                                                    ]
                                                                 ])
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                    <div class = "m-separator m-separator--space m-separator--dashed"></div>
                                                @elseif(in_array($product->producttype->id ,[Config::get("constants.PRODUCT_TYPE_SIMPLE")]))

                                                @elseif(in_array($product->producttype->id, [Config::get("constants.PRODUCT_TYPE_CONFIGURABLE")]))
                                                        @if((isset($selectCollection) && !$selectCollection->isEmpty()) ||
                                                         (isset($groupedCheckboxCollection) && !$groupedCheckboxCollection->isEmpty()))
                                                            <li style="margin: 0% 5% 0% 5%">
                                                                @if(isset($selectCollection))
                                                                    @foreach($selectCollection as $index => $select)

                                                                        <span class="sale-info"> {{ $index }}
                                                                            <i class="fa fa-img-up"></i>
                                                                                                            </span>
                                                                        {!! Form::select('attribute[]',$select,null,['class' => 'form-control attribute']) !!}

                                                                    @endforeach
                                                                @endif
                                                                @if(isset($groupedCheckboxCollection))
                                                                    <div class="input-group">
                                                                        <div class="icheck-list">
                                                                            @foreach($groupedCheckboxCollection as $checkboxArray)
                                                                                @foreach($checkboxArray as $index => $checkbox)
                                                                                    <label>
                                                                                        {!! Form::checkbox('attribute[]', $index, null, ['class' => 'attribute icheck' , 'data-checkbox'=>'icheckbox_square-blue']) !!}
                                                                                        @if(isset($checkbox["index"])) {{$checkbox["index"]}} @endif
                                                                                        @if(isset($checkbox["extraCost"][0]))
                                                                                            (
                                                                                            <span style="@if(isset($checkbox["extraCostWithDiscount"][0])) text-decoration: line-through;  @endif">{{$checkbox["extraCost"]}}</span>
                                                                                            @if(isset($checkbox["extraCostWithDiscount"][0]))
                                                                                                <span class="bg-font-dark"
                                                                                                      style="background: #ff7272;    padding: 0px 5px 0px 5px;">برای شما </span>
                                                                                                <span class="bg-font-dark"
                                                                                                      style="background: #ee5053;    padding: 0px 5px 0px 5px;">{{$checkbox["extraCostWithDiscount"]}}</span>
                                                                                            @endif
                                                                                        @endif
                                                                                    </label>
                                                                                @endforeach
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endif
                                                @endif
                                                {!! Form::hidden('product_id',$product->id) !!}
                                                @if($product->enable)
                                                    <h5 class="m--font-danger">
                                                        <span id="a_product-price">
                                                            {{ $product->priceText }}
                                                        </span>
                                                        <span id = "a_product-discount"></span>
                                                    </h5>

                                                    <a href="#" class="btn btn-primary btn-lg m-btn  m-btn m-btn--icon">
                                                        <span>
                                                            <i class="flaticon-shopping-basket"></i>
                                                            <span>افزودن به سبد خرید</span>
                                                        </span>
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-danger btn-lg m-btn  m-btn m-btn--icon">
                                                            <span>
                                                                <i class="flaticon-shopping-basket"></i>
                                                                <span>این محصول غیر فعال است.</span>
                                                            </span>
                                                    </a>
                                                @endif
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-3">
                                    @if(optional($product->gift)->isNotEmpty())
                                        <div class="portlet sale-summary">
                                            <div class="portlet-title">
                                                <div class="caption font-red sbold"><img
                                                            src="/acm/extra/gift-box.png" width="25"> محصولات
                                                                                                      هدیه
                                                </div>
                                            </div>
                                            <div class="portlet-body" style="padding: 0px">
                                                <ul class="list-unstyled">
                                                    @foreach($product->gift as $gift)
                                                        <li class="text-center bold">
                                                            @if(strlen($gift->url)>0)
                                                                <a target="_blank"
                                                                   href="{{ $gift->url }}">{{ $gift->name }}</a>
                                                            @else
                                                                {{ $gift->name }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($product->introVideo))
                                        <div class="portlet solid light grey-mint">
                                            <div class="portlet-title">
                                                <div class="caption bg-font-dark sbold">کلیپ معرفی</div>
                                            </div>
                                            <div class="portlet-body">
                                                <video controls style="width: 100%">
                                                    <source src="{{$product->introVideo}}" type="video/mp4">
                                                    <span class="bold font-red">مرورگر شما HTML5 را پشتیبانی نمی کند</span>
                                                </video>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!--end::Preview-->
                        </div>
                    </div>
                    <!--end::Section-->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    @if(isset($product->specialDescription))
        <div class = "row">
            {!! $product->specialDescription !!}
        </div>
    @endif
    <div class = "row">
        <div class = "col-xl-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--last m-portlet--head-lg m-portlet--responsive-mobile" id="main_portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-progress">
                        <div class="progress m-progress--sm">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="m-portlet__head-wrapper">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon">
							<i class="flaticon-notepad"></i>
						</span>
                                <h3 class="m-portlet__head-text">
                                    بررسی محصول {{ $product->name }}
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span>
                                        <i class="flaticon-shopping-basket"></i>
                                        <span>افزودن به سبد خرید</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form class="m-form m-form--label-align-left- m-form--state-" id="m_form">
                        <!--begin: Form Body -->
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-xl-8 offset-xl-2">
                                    {!! $product->shortDescription !!}
                                    @if( isset($product->longDescription[0] ) )
                                        <div>
                                            {!!   $product->longDescription !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
