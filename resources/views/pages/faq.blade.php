@extends('partials.templatePage')

@section('page-css')
    <link href="{{ mix('/css/faq.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('pageBar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="fa fa-home"></i>
                <a href="{{route('web.index')}}">@lang('page.Home')</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                سوالات متداول
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    @include('systemMessage.flash')

    <div class="a--list1">
        @foreach($faqs as $key=>$item)

            <div class="a--list1-item">
                <div class="a--list1-thumbnail text-center">

                    @if(isset($item->video))
                    <video id="video-{{ $key }}"
                           class="video-js vjs-fluid vjs-default-skin vjs-big-play-centered a--full-width"
                           controls
                           preload="none"
                           height="360"
                           width="640"
                           poster="{{$item->photo}}">
                        <source src="{{$item->video}}" type='video/mp4' res="HQ" default label="متوسط"/>
                        <p class="vjs-no-js">@lang('content.javascript is disables! we need it to play a video')</p>
                    </video>
                    @else
                        <svg width="200" height="200" viewBox="1 -72 511.99897 511" xmlns="http://www.w3.org/2000/svg"><path d="m386.683594 76.925781h-33.621094c-68.925781 0-125.316406 56.390625-125.316406 125.316407 0 50.222656 29.949218 93.789062 72.863281 113.734374 2.109375.976563 4.015625 2.339844 5.640625 3.996094l45.679688 46.644532c1.464843 1.5 4.007812.460937 4.007812-1.636719v-31.1875c0-3.445313 2.792969-6.234375 6.238281-6.234375h24.507813c68.921875 0 125.316406-56.394532 125.316406-125.316406 0-68.925782-56.394531-125.316407-125.316406-125.316407zm0 0" fill="#ffc143"/><path d="m511.996094 202.242188c0 54.859374-35.710938 101.769531-85.03125 118.636718 35.148437-22.261718 58.601562-61.503906 58.601562-105.957031 0-34.464844-14.097656-65.796875-36.8125-88.5-22.703125-22.714844-54.035156-36.8125-88.5-36.8125h-33.625c-14.070312 0-27.625 2.34375-40.273437 6.679687 19.347656-12.25 42.238281-19.359374 66.703125-19.359374h33.625c34.464844 0 65.796875 14.097656 88.5 36.8125 22.714844 22.703124 36.8125 54.035156 36.8125 88.5zm0 0" fill="#ffb509"/><path d="m125.316406.5h33.621094c68.925781 0 125.316406 56.390625 125.316406 125.316406 0 50.226563-29.949218 93.789063-72.863281 113.734375-2.109375.980469-4.015625 2.339844-5.640625 4l-45.679688 46.644531c-1.464843 1.496094-4.007812.457032-4.007812-1.636718v-31.1875c0-3.445313-2.792969-6.238282-6.238281-6.238282h-24.507813c-68.921875 0-125.316406-56.390624-125.316406-125.316406 0-68.925781 56.394531-125.316406 125.316406-125.316406zm0 0" fill="#ff5757"/><path d="m284.257812 125.816406c0 25.109375-7.484374 48.554688-20.339843 68.222656-12.851563 19.660157-31.070313 35.539063-52.527344 45.511719-2.113281.980469-4.015625 2.335938-5.640625 4.003907l-45.675781 46.644531c-1.472657 1.492187-4.015625.453125-4.015625-1.636719v-31.195312c0-.265626-.019532-.535157-.050782-.792969l12.75-13.019531c1.628907-1.667969 3.53125-3.023438 5.640626-4.003907 21.460937-9.972656 39.675781-25.851562 52.53125-45.511719 12.855468-19.667968 20.335937-43.113281 20.335937-68.222656 0-68.339844-55.441406-124.359375-123.566406-125.304687.535156-.011719 1.082031-.011719 1.617187-.011719h33.625c68.925782 0 125.316406 56.390625 125.316406 125.316406zm0 0" fill="#ff4343"/><g fill="#5a5a5a"><path d="m315.027344 159.664062h19.007812c4.261719 0 7.71875-3.457031 7.71875-7.71875 0-4.265624-3.457031-7.722656-7.71875-7.722656h-19.007812c-4.261719 0-7.71875 3.457032-7.71875 7.722656 0 4.261719 3.457031 7.71875 7.71875 7.71875zm0 0"/><path d="m409.082031 159.664062c4.265625 0 7.722657-3.457031 7.722657-7.71875 0-4.265624-3.457032-7.722656-7.722657-7.722656h-52.097656c-4.265625 0-7.71875 3.457032-7.71875 7.722656 0 4.261719 3.453125 7.71875 7.71875 7.71875zm0 0"/><path d="m315.027344 196.234375h136.105468c4.261719 0 7.71875-3.457031 7.71875-7.71875 0-4.265625-3.457031-7.71875-7.71875-7.71875h-136.105468c-4.261719 0-7.71875 3.453125-7.71875 7.71875 0 4.261719 3.457031 7.71875 7.71875 7.71875zm0 0"/><path d="m315.027344 223.535156h136.105468c4.261719 0 7.71875-3.453125 7.71875-7.71875 0-4.261718-3.457031-7.71875-7.71875-7.71875h-136.105468c-4.261719 0-7.71875 3.457032-7.71875 7.71875 0 4.265625 3.457031 7.71875 7.71875 7.71875zm0 0"/><path d="m315.027344 250.839844h136.105468c4.261719 0 7.71875-3.457032 7.71875-7.71875 0-4.265625-3.457031-7.71875-7.71875-7.71875h-136.105468c-4.261719 0-7.71875 3.453125-7.71875 7.71875 0 4.261718 3.457031 7.71875 7.71875 7.71875zm0 0"/><path d="m315.027344 277.8125h65.660156c4.265625 0 7.71875-3.453125 7.71875-7.71875 0-4.261719-3.453125-7.71875-7.71875-7.71875h-65.660156c-4.261719 0-7.71875 3.457031-7.71875 7.71875 0 4.265625 3.457031 7.71875 7.71875 7.71875zm0 0"/><path d="m137.707031 169.480469c-6.46875 0-11.703125 5.390625-11.703125 11.859375 0 6.3125 5.082032 11.855468 11.703125 11.855468 6.621094 0 11.855469-5.542968 11.855469-11.855468.003906-6.46875-5.386719-11.859375-11.855469-11.859375zm0 0"/><path d="m155.261719 133.292969c4.160156-3.234375 17.25-13.707031 17.25-28.183594 0-14.472656-13.089844-25.40625-32.804688-25.40625-20.785156 0-30.335937 12.320313-30.335937 20.632813 0 6.007812 5.082031 8.78125 9.242187 8.78125 8.316407 0 4.925781-11.859376 20.632813-11.859376 7.703125 0 13.859375 3.386719 13.859375 10.472657 0 8.316406-8.621094 13.089843-13.703125 17.402343-4.46875 3.847657-10.316406 10.164063-10.316406 23.40625 0 8.007813 2.15625 10.316407 8.46875 10.316407 7.546874 0 9.085937-3.386719 9.085937-6.3125 0-8.007813.152344-12.628907 8.621094-19.25zm0 0"/></g></svg>
                    @endif

                </div>
                <div class="a--list1-content">
                    <h2 class="a--list1-title">{{$item->title}}</h2>
                    <div class="a--list1-info"></div>
                    <div class="a--list1-desc" style="text-align:justify">{!! pureHTML($item->body) !!}</div>
                </div>
                <div class="a--list1-action"></div>
            </div>

        @endforeach
    </div>

@endsection

@section('page-js')
    <script>
        var videosId = [
            @foreach($faqs as $key=>$item)
                @if(isset($item->video))
                    'video-{{ $key }}',
                @endif
            @endforeach
        ];
    </script>
    <script src="{{ mix('/js/faq.js') }}" defer></script>
@endsection

