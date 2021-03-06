@extends('partials.templatePage')

@section('page-css')
    <link href="{{ mix('/css/admin-all.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('pageBar')
    <nav aria-label = "breadcrumb">
        <ol class = "breadcrumb">
            <li class = "breadcrumb-item">
                <i class = "fa fa-home m--padding-right-5"></i>
                <a class = "m-link" href = "{{route('web.index')}}">@lang('page.Home')</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a class="m-link" href="#">پنل بات ها</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    {{--Ajax modal loaded after inserting content--}}
    <div id = "ajax-modal" class = "modal fade" tabindex = "-1"></div>
    {{--Ajax modal for panel startup --}}

    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        چک کردن سفارش های قسطی شده
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'GET' , 'url'=>route('web.bots') , 'target'=>'_blank']) !!}
            <input type="hidden" name="checkghesdi" value="1">
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <label class = "control-label">از تاریخ</label>
                        <input id = "checkGhesdiSinceDate" type = "text" class = "form-control">
                        <input name = "since" id = "checkGhesdiSinceDateAlt" type = "text" class = "form-control d-none">
                    </div>
                    <div class = "col">
                        <label class = "control-label">تا تاریخ</label>
                        <input id = "checkGhesdiTillDate" type = "text" class = "form-control">
                        <input name = "till" id = "checkGhesdiTillDateAlt" type = "text" class = "form-control d-none">
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class = "btn btn-info">چک کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        چک کردن سفارش های که آیتم پاک شده
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'GET' , 'url'=>route('web.bots') , 'target'=>'_blank']) !!}
            <input type="hidden" name="checkorderproducts" value="1">
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <label class = "control-label">از تاریخ</label>
                        <input id = "checkOrderproductsSinceDate" type = "text" class = "form-control">
                        <input name = "since" id = "checkOrderproductsSinceDateAlt" type = "text" class = "form-control d-none">
                    </div>
                    <div class = "col">
                        <label class = "control-label">تا تاریخ</label>
                        <input id = "checkOrderproductsTillDate" type = "text" class = "form-control">
                        <input name = "till" id = "checkOrderproductsTillDateAlt" type = "text" class = "form-control d-none">
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class = "btn btn-info">چک کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        چک کردن تراکنش ها
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'GET' , 'url'=>route('web.bots') , 'target'=>'_blank']) !!}
            <input type="hidden" name="checktransactions" value="1">
            <button type="submit" class = "btn btn-info">چک کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        تایید تراکنش زرین پال
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.verifyZarinpal') , 'target'=>'_blank']) !!}
            <input type="text" name="authority" value="" placeholder="شماره اتوریتی" dir="ltr">
            <input type="text" name="cost" value="" placeholder="مبلغ(تومان)" dir="ltr">
            <button type="submit" class = "btn btn-info">تایید کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        درست کردن تامبنیل ها
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.fixthumbnails') , 'target'=>'_blank']) !!}
            <input type="text" name="set" value="" placeholder="شماره ست" dir="ltr">
            <button type="submit" class = "btn btn-info">درست کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        درست کردن دکمه افزودن به سبد کلیپ معرفی
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.introContentTags') , 'target'=>'_blank']) !!}
                <input type="text" name="product" value="" placeholder="شماره محصول" dir="ltr">
                <input type="text" name="contents" value="" placeholder="شماره کانتنتها جدا شده با کاما" dir="ltr">
                <button type="submit" class = "btn btn-info">درست کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        درست کردن تگ ها
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.fixtag') , 'target'=>'_blank']) !!}
                <div class = "form-group">
                    <div class = "row">
                        <div class = "col">
                            <input type="text" name="contentset_id" value="" placeholder="شماره ست" dir="ltr">
                            {!! Form::select('contenttype_id',$contenttypes,null) !!}
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col">
                            <input type="text" name="tags" value="" class="form-control" data-role="tagsinput" placeholder="تگها" >
                        </div>
                    </div>
                </div>
                <button type="submit" class = "btn btn-info">درست کن</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        بستن سفارش ها
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.closeOrders') , 'target'=>'_blank']) !!}
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <input type="checkbox" name="specificProducts"  >
                        <label>تمام سفارش ها</label>
                    </div>
                </div>
            </div>
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <input type="text" name="products" value="" id="closeOrdersProducts" class="form-control" data-role="tagsinput" placeholder="آیدی محصولات مورد نظر" >
                    </div>
                </div>
            </div>
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <label class = "control-label">تا تاریخ</label>
                        <input id = "removeOrdesTillDate" type = "text" class = "form-control">
                        <input name = "tillDate" id = "removeOrdesTillDateAlt" type = "text" class = "form-control d-none">
                        <input name="tillTime" id="removeOrdesTillTime"   type = "text" class = "form-control">
                    </div>
                </div>
            </div>

            <button type="submit" class = "btn btn-info">سفارش ها را ببند</button>
            {!! Form::close() !!}
        </div>
    </div>
    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="fa fa-list-ul"></i>
                            </span>
                    <h3 class="m-portlet__head-text">
                        تولید انبوه کپن تصادفی نوع کلی
                    </h3>
                </div>
            </div>
        </div>
        <div class = "m-portlet__body">
            {!! Form::open(['method'=>'POST' , 'url'=>route('web.bot.massive.random.coupon') , 'target'=>'_blank']) !!}
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <label class = "control-label">معتبر تا</label>
                        <input id = "couponValidUntil" type = "text" class = "form-control">
                        <input name = "validUntil" id = "couponValidUntilAlt" type = "text" class = "form-control d-none">
                    </div>
                </div>
            </div>
            <div class = "form-group">
                <div class = "row">
                    <div class = "col">
                        <input type="text" name="name" value="" placeholder="نام کد تخفیف" >
                    </div>
                    <div class = "col">
                        <input type="text" name="discount" value="" placeholder="درصد تخفیف" dir="ltr">
                    </div>
                    <div class = "col">
                        <input type="text" name="codePrefix" value="" placeholder="حرف اول کد(در صورت وجود)">
                    </div>
                    <div class = "col">
                        <input type="text" name="number" value="" placeholder="تعداد کدها" dir="ltr">
                    </div>
                    <div class = "col">
                        <input type="text" name="usageLimit" value="" placeholder="تعداد مجاز استفاده هر کد" dir="ltr">
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class = "btn btn-info">تولید کن</button>
            {!! Form::close() !!}
        </div>
    </div>
{{--    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">--}}
{{--        <div class="m-portlet__head">--}}
{{--            <div class="m-portlet__head-caption">--}}
{{--                <div class="m-portlet__head-title">--}}
{{--                            <span class="m-portlet__head-icon">--}}
{{--                                <i class="fa fa-list-ul"></i>--}}
{{--                            </span>--}}
{{--                    <h3 class="m-portlet__head-text">--}}
{{--                        بات کیف پول--}}
{{--                    </h3>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class = "m-portlet__body">--}}
{{--            {!! Form::open(['method'=>'POST' , 'action'=>'Web\BotsController@walletBot' , 'target'=>'_blank' ]) !!}--}}
{{--            <label for = "userGroup" class = "control-label">--}}
{{--                گروه کاربران:--}}
{{--            </label>--}}
{{--            <select name = "userGroup">--}}
{{--                <option value = "0">انتخاب کنید</option>--}}
{{--                <option value = "1">5+1 و یک اردو و طلایی خریده اند</option>--}}
{{--                <option value = "2">5+1 و یک اردو خریده اند اما طلایی نه</option>--}}
{{--                <option value = "3">5+1 خریده اند و دیگر هیچی نخریده اند</option>--}}
{{--                <option value = "4">هیچی نخریده اند</option>--}}
{{--                <option value = "5">از محصولات دیگر خریده اند</option>--}}
{{--                <option value = "6">همایش 1+5 و طلایی خریده اند و اردو ندارند</option>--}}
{{--                <option value = "7">همایش طلایی را خریده اند</option>--}}
{{--            </select>--}}
{{--            <label for = "userGroup" class = "control-label">--}}
{{--                <input type = "checkbox" name = "giveGift" value = "1">--}}
{{--                اعتبار اهداء کن--}}
{{--            </label>--}}
{{--            <input type = "text" name = "giftCost" value = "" placeholder = "مبلغ اهدایی">--}}
{{--            <input type = "submit" value = "انجام بده">--}}
{{--            {!! Form::close() !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class = "m-portlet m-portlet--mobile m-portlet--body-progress-">--}}
{{--        <div class="m-portlet__head">--}}
{{--            <div class="m-portlet__head-caption">--}}
{{--                <div class="m-portlet__head-title">--}}
{{--                            <span class="m-portlet__head-icon">--}}
{{--                                <i class="fa fa-list-ul"></i>--}}
{{--                            </span>--}}
{{--                    <h3 class="m-portlet__head-text">--}}
{{--                        بات اکسل--}}
{{--                    </h3>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class = "m-portlet__body">--}}
{{--            {!! Form::open(['files'=>'true' , 'method'=>'POST' , 'action'=>'Web\BotsController@excelBot' , 'target'=>'_blank' ]) !!}--}}
{{--            <input type = "file" name = "file">--}}
{{--            <input type = "submit" value = "انجام بده">--}}
{{--            {!! Form::close() !!}--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection

@section('page-js')
    <script src="{{ mix('/js/admin-all.js') }}" type="text/javascript"></script>
    <script>
        $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput({
            tagClass: 'm-badge m-badge--info m-badge--wide m-badge--rounded'
        });

        $("#checkGhesdiSinceDate").persianDatepicker({
            altField: '#checkGhesdiSinceDateAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#checkGhesdiTillDate").persianDatepicker({
            altField: '#checkGhesdiTillDateAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#checkOrderproductsSinceDate").persianDatepicker({
            altField: '#checkOrderproductsSinceDateAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#checkOrderproductsTillDate").persianDatepicker({
            altField: '#checkOrderproductsTillDateAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#couponValidUntil").persianDatepicker({
            altField: '#couponValidUntilAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#removeOrdesTillDate").persianDatepicker({
            altField: '#removeOrdesTillDateAlt',
            altFormat: "YYYY MM DD",
            observer: true,
            format: 'YYYY/MM/DD',
            altFieldFormatter: function (unixDate) {
                var d = new Date(unixDate).toISOString();
                d = d.substring(0, d.indexOf('T'));
                return d;
            }
        });

        $("#removeOrdesTillTime").inputmask("hh:mm", {
            placeholder: "",
            clearMaskOnLostFocus: true
        });

    </script>
@endsection
