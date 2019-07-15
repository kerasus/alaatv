<link rel="dns-prefetch" href="//alaatv.com">
<link rel="dns-prefetch" href="//cdn.alaatv.com">
<link rel="dns-prefetch" href="//www.googletagmanager.com">
<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//app.najva.com">

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- begin::seo meta tags -->
{!! SEO::generate(true) !!}
<!-- end:: seo meta tags -->

{{--<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>--}}
<script>
    // // Web font
    // WebFont.load({
    //     google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
    //     active: function () {
    //         sessionStorage.fonts = true;
    //     }
    // });
    // csrf token
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
</script>

<!--begin::Global Theme Styles -->
<link href="{{ mix('/css/all.css') }}" rel="stylesheet" type="text/css" defer/>
<!--end::Global Theme Styles -->

@yield('page-css')

@if(isset($wSetting->site->favicon))
    <link rel="shortcut icon" href="https://cdn.alaatv.com/upload/favicon2_20190508061941_20190512113140.ico"/>
@endif

@if(config('gtm.GTM'))
    @include('partials.gtm-head')
@endif
