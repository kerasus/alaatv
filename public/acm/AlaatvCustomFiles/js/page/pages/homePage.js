var initPage = function() {

    function lazyLoad() {
        // Bootstrap 4 carousel lazy load
        window.LazyLoad.carousel([window.imageObserver, window.gtmEecAdvertisementObserver]);
        window.LazyLoad.loadElementByQuerySelector('.dasboardLessons', function (element) {
            $(element).OwlCarouselType2({
                OwlCarousel: {

                    // autoplay:true,
                    // autoplayTimeout:1000,
                    // autoplayHoverPause:true,


                    // animateOut: 'slideOutDown',
                    // animateIn: 'flipInX',

                    // transitionStyle : 'backSlide',


                    // loop: true,
                    // autoplay: true,
                    // slideTransition: 'linear',
                    // autoplayTimeout: 0,
                    // autoplaySpeed: 4000,
                    // autoplayHoverPause: true,


                    stagePadding: 30,
                    center: false,
                    loop: false,
                    lazyLoad:false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        400: {
                            items: 2
                        },
                        600: {
                            items: 3
                        },
                        800: {
                            items: 4
                        },
                        1000: {
                            items: 6
                        }
                    },
                    btnSwfitchEvent: function() {
                        window.imageObserver.observe();
                        window.gtmEecProductObserver.observe();
                    }
                },
                grid: {
                    columnClass: 'col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 gridItem',
                    btnSwfitchEvent: function() {
                        window.imageObserver.observe();
                        window.gtmEecProductObserver.observe();
                    }
                },
                defaultView: 'OwlCarousel', // OwlCarousel or grid
                childCountHideOwlCarousel: 4
            });
        });
    }

    function loadStickeHeader(sections) {
        for (var section in sections) {
            if (sections[section].trim().length > 0) {
                $('.'+sections[section]+'.dasboardLessons .m-portlet__head').sticky({
                    container: '.'+sections[section]+'.dasboardLessons > .col > .m-portlet',
                    topSpacing: $('#m_header').height(),
                    zIndex: 98
                });
            }
        }
    }

    function addEvents() {
        $(document).on('click', '.btnScrollTo', function () {
            var scrollTo = $(this).attr('data-scroll-to');
            $(scrollTo).AnimateScrollTo();
            // $('html,body').animate({scrollTop: $(scrollTo).offset().top - $('#m_header').height()},'slow');

        });
        $(document).on('click', '#m_aside_left_hide_toggle', function () {
            window.LazyLoad.bootstrap4CarouselLoadHeight();
        });
    }

    function init(sections) {
        lazyLoad();
        loadStickeHeader(sections);
        addEvents();
    }

    return {
        init: init
    };
}();


$(document).ready(function () {
    initPage.init(sections);
});
