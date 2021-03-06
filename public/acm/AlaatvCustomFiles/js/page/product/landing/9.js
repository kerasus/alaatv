$(document).ready(function () {

    $('.OwlCarouselType2-landing9').each(function(){
        let id = $(this).attr('id');
        $('#'+id).OwlCarouselType2({
            OwlCarousel: {
                center: false,
                loop: false,
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
                        items: 5
                    }
                },
                btnSwfitchEvent: function() {
                    imageObserver.observe();
                    gtmEecProductObserver.observe();
                }
            },
            grid: {
                columnClass: 'col-12 col-sm-6 col-md-2 gridItem',
                btnSwfitchEvent: function() {
                    imageObserver.observe();
                    gtmEecProductObserver.observe();
                }
            },
            defaultView: 'grid', // OwlCarousel or grid
            childCountHideOwlCarousel: 4
        });
    });

    for (let section in sections) {
        $('.'+sections[section]+' .m-portlet__head').sticky({
            container: '.'+sections[section],
            topSpacing: $('#m_header').height(),
            zIndex: 99
        });
    }
});
