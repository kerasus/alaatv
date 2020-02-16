@extends('partials.templatePage')

@section('page-css')
    <link href="{{ mix('/css/page-error.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="errorPage">
        <div class="background">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:rgba(255, 255, 255, 0);display:block;z-index:1;position:relative" preserveAspectRatio="xMidYMid" viewBox="0 0 1366 500">
                <g transform="">
                    <linearGradient id="lg-0.6590519274487807" x1="0" x2="1" y1="0" y2="0">
                        <stop stop-color="#ff9000" offset="0"></stop>
                        <stop stop-color="#ffd500" offset="1"></stop>
                    </linearGradient>
                    <path d="" fill="url(#lg-0.6590519274487807)" opacity="0.4">
                        <animate attributeName="d" dur="10s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="0s" values="M0 0M 0 347.7732950970103Q 136.6 437.227949444993 273.2 432.849741242714T 546.4 348.96283580918714T 819.6 415.2076367017273T 1092.8 435.63522142940144T 1366 341.8141943700737L 1366 77.5325633430104Q 1229.4 100.66712108396636 1092.8 99.15501964321078T 819.6 66.08677970719972T 546.4 137.2724205087282T 273.2 92.27776653327274T 0 129.7643571048099Z;M0 0M 0 420.2154711771481Q 136.6 454.0995364186637 273.2 453.24013324779366T 546.4 449.34447139865983T 819.6 372.80208920167513T 1092.8 416.61331182401716T 1366 395.9156234764151L 1366 69.47668480073793Q 1229.4 119.75418694563004 1092.8 118.26363573146881T 819.6 141.04730143934682T 546.4 107.9110239071538T 273.2 132.04910445494147T 0 132.28390550016837Z;M0 0M 0 348.69948008770444Q 136.6 384.45205217382 273.2 375.31547648427T 546.4 348.10077032346555T 819.6 401.5574074816818T 1092.8 425.060768787716T 1366 439.5145468469471L 1366 130.87117987638186Q 1229.4 133.92768518977564 1092.8 127.81547497921473T 819.6 127.16286691790576T 546.4 79.9991299281821T 273.2 128.20596330352913T 0 110.51964224298905Z;M0 0M 0 347.7732950970103Q 136.6 437.227949444993 273.2 432.849741242714T 546.4 348.96283580918714T 819.6 415.2076367017273T 1092.8 435.63522142940144T 1366 341.8141943700737L 1366 77.5325633430104Q 1229.4 100.66712108396636 1092.8 99.15501964321078T 819.6 66.08677970719972T 546.4 137.2724205087282T 273.2 92.27776653327274T 0 129.7643571048099Z"></animate>
                    </path>
                    <path d="" fill="url(#lg-0.6590519274487807)" opacity="0.4">
                        <animate attributeName="d" dur="10s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-2.5s" values="M0 0M 0 394.7402934031769Q 136.6 373.9160492767795 273.2 364.0079701094311T 546.4 381.72430239261814T 819.6 454.4478062516156T 1092.8 340.1491372295196T 1366 395.81381408875427L 1366 47.11969121435533Q 1229.4 147.75375498107263 1092.8 138.91357998269342T 819.6 75.44098344505136T 546.4 106.35006061216228T 273.2 155.9847404514329T 0 124.68180917307825Z;M0 0M 0 423.07348454810995Q 136.6 415.585322442454 273.2 412.56170779331154T 546.4 357.81124315983726T 819.6 373.4114331981339T 1092.8 459.0254777139826T 1366 408.9487068102851L 1366 68.21491426595927Q 1229.4 66.50559420704859 1092.8 60.843938816337214T 819.6 126.34850718185466T 546.4 126.27275067677157T 273.2 99.60943959601158T 0 80.28200964691345Z;M0 0M 0 375.9592438844488Q 136.6 431.43677135333047 273.2 424.5148085504289T 546.4 428.4200595359878T 819.6 377.8388803177115T 1092.8 443.030823037064T 1366 401.1159654090932L 1366 107.24942228511983Q 1229.4 123.98759600717788 1092.8 121.62302545678685T 819.6 53.90812125686679T 546.4 103.96313875725284T 273.2 116.3767575070531T 0 95.47417782863388Z;M0 0M 0 394.7402934031769Q 136.6 373.9160492767795 273.2 364.0079701094311T 546.4 381.72430239261814T 819.6 454.4478062516156T 1092.8 340.1491372295196T 1366 395.81381408875427L 1366 47.11969121435533Q 1229.4 147.75375498107263 1092.8 138.91357998269342T 819.6 75.44098344505136T 546.4 106.35006061216228T 273.2 155.9847404514329T 0 124.68180917307825Z"></animate>
                    </path
                    ><path d="" fill="url(#lg-0.6590519274487807)" opacity="0.4">
                        <animate attributeName="d" dur="10s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-5s" values="M0 0M 0 343.8021145586359Q 136.6 415.71337462269577 273.2 407.32201922333945T 546.4 447.36058099880614T 819.6 429.7050297309979T 1092.8 402.878526061506T 1366 400.38316796827485L 1366 66.28098976601362Q 1229.4 64.45286829798056 1092.8 58.03718795675201T 819.6 139.35289917334381T 546.4 152.3226246583509T 273.2 103.09761509275867T 0 106.7670545361552Z;M0 0M 0 341.4546118802898Q 136.6 399.483264490298 273.2 391.22447799914005T 546.4 397.1591674120151T 819.6 363.20051374755815T 1092.8 454.25430474831694T 1366 452.1913050978269L 1366 135.75511616494873Q 1229.4 78.7174241831697 1092.8 77.82361331172243T 819.6 101.36885655125349T 546.4 134.22520678698612T 273.2 97.52717069216581T 0 75.94994346953919Z;M0 0M 0 362.16355347853914Q 136.6 368.98801457664825 273.2 362.7723128602787T 546.4 392.19472057123664T 819.6 403.25840964392125T 1092.8 454.91964982338686T 1366 353.9230368867409L 1366 141.2831467533717Q 1229.4 79.51369775123555 1092.8 70.37253136924153T 819.6 91.89646365259878T 546.4 121.05760441004611T 273.2 119.12695025996456T 0 103.62615570595474Z;M0 0M 0 343.8021145586359Q 136.6 415.71337462269577 273.2 407.32201922333945T 546.4 447.36058099880614T 819.6 429.7050297309979T 1092.8 402.878526061506T 1366 400.38316796827485L 1366 66.28098976601362Q 1229.4 64.45286829798056 1092.8 58.03718795675201T 819.6 139.35289917334381T 546.4 152.3226246583509T 273.2 103.09761509275867T 0 106.7670545361552Z"></animate>
                    </path>
                    <path d="" fill="url(#lg-0.6590519274487807)" opacity="0.4">
                        <animate attributeName="d" dur="10s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-7.5s" values="M0 0M 0 357.3683936815636Q 136.6 445.54793492398755 273.2 445.36920794384116T 546.4 426.9505960466196T 819.6 376.4805694797567T 1092.8 365.26700249344736T 1366 404.84593629333574L 1366 70.75898721375435Q 1229.4 67.4782969002158 1092.8 66.71987963692717T 819.6 70.74442242682855T 546.4 148.79839094610213T 273.2 124.42285440822245T 0 128.04932132868967Z;M0 0M 0 348.81738498281345Q 136.6 382.5270746702683 273.2 381.6555139983295T 546.4 374.7357831452226T 819.6 403.63814628526205T 1092.8 398.6852475493217T 1366 427.42823255466794L 1366 139.09766866273182Q 1229.4 52.924159580060135 1092.8 52.08632479411804T 819.6 40.85831215505493T 546.4 70.79030453007624T 273.2 157.79696991315717T 0 104.96384277448382Z;M0 0M 0 391.26210501648825Q 136.6 383.28357449258954 273.2 378.55109881636355T 546.4 394.6913383078772T 819.6 381.79865228908955T 1092.8 340.54010106330026T 1366 398.5338620842431L 1366 110.31876747877851Q 1229.4 160.29222737943434 1092.8 159.828441221998T 819.6 106.17101063245781T 546.4 151.79980618771629T 273.2 68.65147956888947T 0 53.52229869075259Z;M0 0M 0 357.3683936815636Q 136.6 445.54793492398755 273.2 445.36920794384116T 546.4 426.9505960466196T 819.6 376.4805694797567T 1092.8 365.26700249344736T 1366 404.84593629333574L 1366 70.75898721375435Q 1229.4 67.4782969002158 1092.8 66.71987963692717T 819.6 70.74442242682855T 546.4 148.79839094610213T 273.2 124.42285440822245T 0 128.04932132868967Z"></animate>
                    </path>
                </g>
            </svg>
        </div>
        <div class="message">

            <div class = "col-md-12 page-500">
                <div class = "number m--font-danger"> 500</div>
                <div class = "details">
                    <h3>با عرض پوزش خطایی غیر منتظره رخ داده است!</h3>
                    <p>
{{--                        تیم ما در حال برطرف کردن این خطا می باشند. لطفا چند دقیقه دیگر دوباره امتحان کنید.--}}
                        <br/>
                        <a href = "{{route('web.index')}}"> @lang('page.Home') </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ mix('/js/page-error.js') }}"></script>
@endsection
