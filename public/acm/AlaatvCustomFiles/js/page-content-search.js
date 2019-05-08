var Alaasearch = function () {

    var productAjaxLock = 0;
    var videoAjaxLock = 0;
    var setAjaxLock = 0;
    var pamphletAjaxLock = 0;

    function getProductCarouselItem(data) {
        let widgetActionLink = data.url;
        let widgetActionName = 'مشاهده و خرید';
        let widgetPic = data.photo;
        let widgetTitle = data.name;
        let price = data.price;
        let priceHtml = '';
        if (price.base !== price.final) {
            priceHtml =
                '                                <span class = "m-badge m-badge--danger m-badge--wide m-badge--rounded a--productPrice">\n' +
                '                                    <span class = "m-badge m-badge--warning a--productRealPrice">' + price.base.toLocaleString('fa') + '</span>\n' +
                '                                    ' + price.final.toLocaleString('fa') + ' تومان \n' +
                '                                    <span class = "m-badge m-badge--info a--productDiscount">' + ((1 - (price.final / price.base)) * 100) + '%</span>\n' +
                '                                </span>';
        } else {
            priceHtml =
                '                                <span class = "m-widget6__text m--align-right m--font-boldest m--font-primary">\n' +
                '                                    ' + price.final.toLocaleString('fa') + ' تومان \n' +
                '                                </span>';
        }
        return '<div class = "item">\n' +
            '    <!--begin:: Widgets/Blog-->\n' +
            '    <div class = "m-portlet m-portlet--bordered-semi m-portlet--rounded-force">\n' +
            '        <div class = "m-portlet__head m-portlet__head--fit">\n' +
            '            <div class = "m-portlet__head-caption">\n' +
            '                <div class = "m-portlet__head-action">\n' +
            '                    <a href="' + widgetActionLink + '" class = "btn btn-sm m-btn--pill btn-brand">' + widgetActionName + '</a>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '        <div class = "m-portlet__body">\n' +
            '            <div class = "m-widget19">\n' +
            '                <div class = "m-widget19__pic m-portlet-fit--top m-portlet-fit--sides" >\n' +
            '                    <img src = "' + widgetPic + '" alt = "' + widgetTitle + '"/>\n' +
            '                    <div class = "m-widget19__shadow"></div>\n' +
            '                </div>\n' +
            '                <div class = "m-widget19__content">\n' +
            '                    <div class="owl-carousel-fileTitle">\n' +
            '                        <a href = "' + widgetActionLink + '" class = "m-link">\n' +
            '                            <h6>\n' +
            '                                <span class="m-badge m-badge--info m-badge--dot"></span> ' + widgetTitle + '\n' +
            '                            </h6>\n' +
            '                        </a>\n' +
            '                    </div>\n' +
            '                    <div class = "m-widget19__header">\n' +
            '                        <div class = "m-widget19__info">\n' +
            priceHtml +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <!--end:: Widgets/Blog-->\n' +
            '</div>';
    }

    function getVideoCarouselItem(data) {

        let widgetActionLink = data.url;
        let widgetActionName = 'پخش / دانلود';
        let widgetPic = (typeof (data.photo) === 'undefined' || data.photo == null) ? data.thumbnail : data.photo;
        let widgetTitle = data.name;
        let widgetAuthor = {
            photo: (typeof (data.author.photo) === 'undefined' || data.author.photo == null) ? null : data.author.photo,
            name: data.author.firstName,
            full_name: data.author.full_name
        };

        var widgetLink = data.url;
        return '<div class = \"item\"> \
    <!--begin:: Widgets/Blog--> \
    <div class = \"m-portlet m-portlet--bordered-semi m-portlet--rounded-force\"> \
        <div class = \"m-portlet__head m-portlet__head--fit\"> \
            <div class = \"m-portlet__head-caption\"> \
                <div class = \"m-portlet__head-action\"> \
                    <a href=\"' + widgetActionLink + '\" class = \"btn btn-sm m-btn--pill btn-brand\">' + widgetActionName + '</a> \
                </div> \
            </div> \
        </div> \
        <div class = \"m-portlet__body\"> \
            <div class = \"m-widget19\"> \
                <div class = \"m-widget19__pic m-portlet-fit--top m-portlet-fit--sides\" > \
                    <img src = \"' + widgetPic + '\" alt = \" ' + widgetTitle + '\"/> \
                    <div class = \"m-widget19__shadow\"></div> \
                </div> \
                <div class = \"m-widget19__content\"> \n' +
            '                    <div class="owl-carousel-fileTitle">\n' +
            '                        <a href = "' + widgetActionLink + '" class = "m-link">\n' +
            '                            <h6>\n' +
            '                                <span class="m-badge m-badge--info m-badge--dot"></span> ' + widgetTitle + '\n' +
            '                            </h6>\n' +
            '                        </a>\n' +
            '                    </div>\
                    <div class = \"m-widget19__header\"> \
                        <div class = \"m-widget19__user-img\"> \
                            <img class = \"m-widget19__img\" src = \" ' + widgetAuthor.photo + '\" alt = \"' + widgetAuthor.name + '\"> \
                        </div> \
                        <div class = \"m-widget19__info\"> \
                            <span class = \"m-widget19__username\"> \
                            ' + widgetAuthor.full_name + ' \
                            </span> \
                            <br> \
                            <span class = \"m-widget19__time\"> \
                            موسسه غیرتجاری آلاء \
                            </span> \
                        </div> \
                    </div> \
                </div> \
                <!--<div class = \"m-widget19__action\"> \
                    <a href = \"' + widgetLink + '\" class = \"btn m-btn--pill    btn-outline-warning m-btn m-btn--outline-2x \">نمایش فیلم های این دوره</a> \
                </div> --> \
            </div> \
        </div> \
    </div> \
    <!--end:: Widgets/Blog--> \
</div>';
    }

    function getSetCarouselItem(data) {

        let widgetActionLink = data.url;
        let widgetActionName = 'نمایش این دوره';
        let widgetPic = (typeof (data.photo) === 'undefined' || data.photo == null) ? data.thumbnail : data.photo;
        let widgetTitle = data.name;
        let widgetAuthor = {
            photo : data.author.photo,
            name: data.author.firstName,
            full_name: data.author.full_name
        };

        var widgetCount = data.contents_count;
        var widgetLink = data.url;
        return '<div class = \"item\"> \
    <!--begin:: Widgets/Blog--> \
    <div class = \"m-portlet m-portlet--bordered-semi m-portlet--rounded-force\"> \
        <div class = \"m-portlet__head m-portlet__head--fit\"> \
            <div class = \"m-portlet__head-caption\"> \
                <div class = \"m-portlet__head-action\"> \
                    <a href=\"' + widgetActionLink +'\" class = \"btn btn-sm m-btn--pill btn-brand\">' + widgetActionName + '</a> \
                </div> \
            </div> \
        </div> \
        <div class = \"m-portlet__body\"> \
            <div class = \"m-widget19\"> \
                <div class = \"m-widget19__pic m-portlet-fit--top m-portlet-fit--sides\" > \
                    <img src = \"'+ widgetPic +'\" alt = \" ' + widgetTitle +'\"/> \
                    <div class = \"m-widget19__shadow\"></div> \
                </div> \
                <div class = \"m-widget19__content\"> \n' +
            '                    <div class="owl-carousel-fileTitle">\n' +
            '                        <a href = "' + widgetActionLink + '" class = "m-link">\n' +
            '                            <h6>\n' +
            '                                <span class="m-badge m-badge--info m-badge--dot"></span> ' + widgetTitle + '\n' +
            '                            </h6>\n' +
            '                        </a>\n' +
            '                    </div>\
                    <div class = \"m-widget19__header\"> \
                        <div class = \"m-widget19__user-img\"> \
                            <img class = \"m-widget19__img\" src = \" ' + widgetAuthor.photo + '\" alt = \"' + widgetAuthor.name + '\"> \
                        </div> \
                        <div class = \"m-widget19__info\"> \
                            <span class = \"m-widget19__username\"> \
                            ' + widgetAuthor.full_name + ' \
                            </span> \
                            <br> \
                            <span class = \"m-widget19__time\"> \
                            موسسه غیرتجاری آلاء \
                            </span> \
                        </div> \
                        <div class = \"m-widget19__stats\"> \
                            <span class = \"m-widget19__number m--font-brand\"> \
                            ' + widgetCount + ' \
                            </span> \
                            <span class = \"m-widget19__comment\"> \
                            محتوا  \
                            </span> \
                        </div> \
                    </div> \
                </div> \
                <!--<div class = \"m-widget19__action\"> \
                    <a href = \"' + widgetLink +'\" class = \"btn m-btn--pill    btn-outline-warning m-btn m-btn--outline-2x \">نمایش فیلم های این دوره</a> \
                </div> --> \
            </div> \
        </div> \
    </div> \
    <!--end:: Widgets/Blog--> \
</div>';
    }

    function getSetPamphletItem(data) {

        let widgetActionLink = data.url;
        let widgetTitle = data.name;
        let widgetThumbnail = data.thumbnail;
        let widgetAuthor = {
            photo: (data.author!==null) ? data.author.photo : '',
            name: (data.author!==null) ? data.author.firstName : '',
            full_name: (data.author!==null) ? data.author.full_name : ''
        };
        if (widgetThumbnail !== null && widgetThumbnail.length !== 0) {
            widgetThumbnail =
                '                                                    <div class="m-widget4__img m-widget4__img--pic">\n' +
                '                                                        <img src="{{ $content->thumbnail }}" alt="' + widgetTitle + '">\n' +
                '                                                    </div>\n';
        } else {
            widgetThumbnail = '';
        }
        return '\n' +
            '                                            <div class="m-widget4__item m--padding-top-5 m--padding-bottom-5">\n' +
            '                                                <div class="m-widget4__img m-widget4__img--pic">\n' +
            '                                                    <img src="' + widgetAuthor.photo + '" alt="">\n' +
            '                                                </div>\n' +
            '                                                <div class="m-widget4__info">\n' +
            '                                                        <span class="m-widget4__title">\n' +
            '                                                            <a href="' + widgetActionLink + '" class="m-link">\n' +
            '                                                                ' + widgetTitle + '\n' +
            '                                                            </a>\n' +
            '                                                        </span>\n' +
            '                                                    <br>\n' +
            '                                                    <span class="m-widget4__sub">\n' +
            '                                                            <a href="' + widgetActionLink + '" class="m-link">\n' +
            '                                                                ' + widgetAuthor.full_name + '\n' +
            '                                                            </a>\n' +
            '                                                        </span>\n' +
            '                                                </div>\n' +
            widgetThumbnail +
            '                                            </div>';
    }

    function makeWidgetFromJsonResponse(data, type) {
        switch (type) {
            case 'product':
                return getProductCarouselItem(data);
            case 'video':
                return getVideoCarouselItem(data);
            case 'set':
                return getSetCarouselItem(data);
            case 'pamphlet':
                return getSetPamphletItem(data);
            case 'article':
                return getSetPamphletItem(data);
        }
    }

    function addContentToVerticalWidget(vw, data, type) {
        $.each(data, function (index, value) {
            vw.append(makeWidgetFromJsonResponse(value, type));
        });
    }
    function addContentToOwl(owl, data, type) {
        $.each(data, function (index, value) {
            owl.trigger('add.owl.carousel',
                [
                    // jQuery('<div class="owl-item">' + makeWidgetFromJsonResponse(value) + '</div>');
                    jQuery(makeWidgetFromJsonResponse(value, type))
                ]
            );
        });
        owl.trigger('refresh.owl.carousel');
    }
    function ajaxSetup() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            }
        });
    }
    function loadData(owl , action,type,callback) {
        ajaxSetup();

        var fetchMoreData = $.ajax({
                type: "GET",
                url: action,
                accept: "application/json; charset=utf-8",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                statusCode: {
                    200:function (response) {
                        removeLoadingItem(owl, type);

                        if (type === 'product' || type === 'video' || type === 'set') {
                            addContentToOwl(owl, response.result[type].data, type);
                        } else if (type === 'pamphlet' || type === 'article') {
                            addContentToVerticalWidget(owl, response.result[type].data, type);
                        }

                        // responseMessage = response.responseText;
                        callback(response.result[type].next_page_url);
                    },
                    403: function (response) {
                        // responseMessage = response.responseJSON.message;
                    },
                    404: function (response) {
                    },
                    422: function (response) {
                    },
                    429: function (response) {
                    },
                    //The status for when there is error php code
                    500: function (response) {
                        removeLoadingItem(owl, type);
                    }
                }
            }
        );
    }

    function lockAjax(type) {
        switch (type) {
            case 'product':
                productAjaxLock = 1;
                break;
            case 'video':
                videoAjaxLock = 1;
                break;
            case 'set':
                setAjaxLock = 1;
                break;
            case 'pamphlet':
                pamphletAjaxLock = 1;
                break;
            case 'article':
                pamphletAjaxLock = 1;
                break;
        }
    }
    function unLockAjax(type) {
        switch (type) {
            case 'product':
                productAjaxLock = 0;
                break;
            case 'video':
                videoAjaxLock = 0;
                break;
            case 'set':
                setAjaxLock = 0;
                break;
            case 'pamphlet':
                pamphletAjaxLock = 0;
                break;
            case 'article':
                pamphletAjaxLock = 0;
                break;
        }
    }

    function load(event, nextPageUrl, owl, owlType, callback) {


        if (owlType === 'product' || owlType === 'video' || owlType === 'set' || owlType === 'pamphlet' || owlType === 'article') {
            var perPage = typeof (owl.data("per-page")) === "number" ? owl.data("per-page") : 6;

            // console.log('per page:' + perPage);
            // console.log('nextPageUrl:' + nextPageUrl);
            // console.log('event.property.name',event.property.name);
            // console.log('event.property.value',event.property.value);
            // console.log('event.relatedTarget.items().length - perPage',event.relatedTarget.items().length - perPage);
            if (nextPageUrl !== null && nextPageUrl.length !== 0
                && event.namespace && event.property.name === 'position'
                && event.property.value >= event.relatedTarget.items().length - perPage) {
                lockAjax(owlType);
                addLoadingItem(owl, owlType);
                // load, add and update
                // console.log("next page Url: " + nextPageUrl);
                loadData(owl, nextPageUrl, owlType, callback);
                // console.log([currentPage,lastPage,nextPage]);
            }
        } else if (owlType === 'pamphlet' || owlType === 'article') {
            lockAjax(owlType);
            addLoadingItem(owl, owlType);
            loadData(owl, nextPageUrl, owlType, callback);
        }

    }

    function loadProductFromJson(data) {
        addContentToOwl($('#product-carousel.owl-carousel'), data.data, 'product');
        $('#owl--js-var-next-page-product-carousel-url').val(decodeURI(data.next_page_url));
    }

    function initProduct(data) {
        loadProductFromJson(data);
        $('#product-carousel.owl-carousel').on('change.owl.carousel', function (event) {
            let owlType = 'product';
            let nextPageUrl = $('#owl--js-var-next-page-product-carousel-url');
            let owl = $(this);
            // console.log("productAjaxLock:" + productAjaxLock);
            if (!productAjaxLock && nextPageUrl.val() !== "null") {
                load(event, nextPageUrl.val(), owl, owlType, function (newPageUrl) {
                    // console.log("PRE:" + $('#owl--js-var-next-page-product-carousel-url').val());
                    if (newPageUrl === null) {
                        newPageUrl = '';
                    }
                    $('#owl--js-var-next-page-product-carousel-url').val(decodeURI(newPageUrl));
                    // console.log("NEW:" + $('#owl--js-var-next-page-product-carousel-url').val());
                    unLockAjax(owlType);
                    // console.log("productAjaxLock:" + productAjaxLock);
                });
            }
        });
    }

    function loadVideoFromJson(data) {
        if (data === null) {
            return false;
        }
        addContentToOwl($('#video-carousel.owl-carousel'), data.data, 'video');
        $('#owl--js-var-next-page-video-url').val(decodeURI(data.next_page_url));
    }

    function initVideo(data) {
        loadVideoFromJson(data);
        $('#video-carousel.owl-carousel').on('change.owl.carousel', function(event) {
            var owlType = "video";
            var nextPageUrl = $('#owl--js-var-next-page-video-url');
            var owl = $(this);

            if( !videoAjaxLock && nextPageUrl.val() !== "null") {

                load(event, nextPageUrl.val(), owl, owlType,function (newPageUrl) {
                    // console.log("PRE:" + nextPageUrl.val());
                    $('#owl--js-var-next-page-video-url').val(decodeURI(newPageUrl));
                    // console.log("NEW:" + nextPageUrl.val());
                    unLockAjax(owlType);
                });
            }
        });
    }

    function loadSetFromJson(data) {
        addContentToOwl($('#set-carousel.owl-carousel'), data.data, 'video');
        $('#owl--js-var-next-page-set-url').val(decodeURI(data.next_page_url));
    }

    function initSet(data) {
        loadSetFromJson(data);
        $('#set-carousel.owl-carousel').on('change.owl.carousel', function(event) {
            var owlType = "set";
            var nextPageUrl = $('#owl--js-var-next-page-set-url');
            var owl = $(this);
            // console.log("nextPageUrl:"+nextPageUrl.val());
            if( !setAjaxLock && nextPageUrl.val() !== "null") {
                // console.log("se Ajax is not lock!");
                load(event, nextPageUrl.val(), owl, owlType,function (newPageUrl) {
                    // console.log("PRE:" + nextPageUrl.val());
                    $('#owl--js-var-next-page-set-url').val(decodeURI(newPageUrl));
                    // console.log("NEW:" + nextPageUrl.val());
                    unLockAjax(owlType);
                });
            }
        });
    }

    function loadPamphletFromJson(data) {
        if (data === null) {
            return false;
        }
        $('#pamphlet-vertical-widget').find('.pamphlet-lastITemSensor').remove();
        addContentToVerticalWidget($('#pamphlet-vertical-widget'), data.data, 'pamphlet');
        $('#vertical-widget--js-var-next-page-pamphlet-url').val(decodeURI(data.next_page_url));
        $('#pamphlet-vertical-widget').append('<div class="pamphlet-lastITemSensor"></div>');
    }

    function initPamphlet(data) {
        loadPamphletFromJson(data);

        $(window).scroll(function () {
            if (isScrolledIntoView($('.pamphlet-lastITemSensor'))) {

                let vwType = 'pamphlet';
                let nextPageUrl = $('#vertical-widget--js-var-next-page-pamphlet-url');
                let vw = $('#pamphlet-vertical-widget');

                if (!pamphletAjaxLock && nextPageUrl.val() !== "null") {
                    load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
                        $('#vertical-widget--js-var-next-page-pamphlet-url').val(decodeURI(newPageUrl));
                        unLockAjax(vwType);
                    });
                }

            }
        });
    }

    function loadArticleFromJson(data) {
        if (data === null) {
            return false;
        }
        $('#pamphlet-vertical-widget').find('.article-lastITemSensor').remove();
        addContentToVerticalWidget($('#article-vertical-widget'), data.data, 'article');
        $('#vertical-widget--js-var-next-page-article-url').val(decodeURI(data.next_page_url));
        $('#pamphlet-vertical-widget').append('<div class="article-lastITemSensor"></div>');
    }

    function initArticle(data) {
        loadArticleFromJson(data);

        $(window).scroll(function () {
            if (isScrolledIntoView($('.article-lastITemSensor'))) {

                let vwType = 'pamphlet';
                let nextPageUrl = $('#vertical-widget--js-var-next-page-article-url');
                let vw = $('#article-vertical-widget');

                if (!pamphletAjaxLock && nextPageUrl.val() !== "null") {
                    load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
                        $('#vertical-widget--js-var-next-page-article-url').val(decodeURI(newPageUrl));
                        unLockAjax(vwType);
                    });
                }

            }
        });
    }

    function loadAjaxContent(contentData) {
        let hasPamphletOrArticle = false;
        let hasItem = false;
        if (typeof contentData.product !== 'undefined' && contentData.product !== null && contentData.product.total>0) {
            initProduct(contentData.product);
            $('#product-carousel-warper').fadeIn();
            hasItem = true;
        } else {
            $('#product-carousel-warper').fadeOut();
        }
        if (typeof contentData.video !== 'undefined' && contentData.video !== null && contentData.video.total>0) {
            initVideo(contentData.video);
            $('#video-carousel-warper').fadeIn();
            hasItem = true;
        } else {
            $('#video-carousel-warper').fadeOut();
        }
        if (typeof contentData.set !== 'undefined' && contentData.set !== null && contentData.set.total>0) {
            initSet(contentData.set);
            $('#set-carousel-warper').fadeIn();
            hasItem = true;
        } else {
            $('#set-carousel-warper').fadeOut();
        }
        if (typeof contentData.pamphlet !== 'undefined' && contentData.pamphlet !== null && contentData.pamphlet.total>0) {
            initPamphlet(contentData.pamphlet);
            // $('#pamphlet-vertical-tabpanel').fadeIn();
            $('#pamphlet-vertical-tab').fadeIn();
            $('#pamphlet-vertical-tab a').trigger('click');
            hasPamphletOrArticle = true;
            hasItem = true;
        } else {
            // $('#pamphlet-vertical-tabpanel').fadeOut();
            $('#pamphlet-vertical-tab').fadeOut();
        }
        if (typeof contentData.article !== 'undefined' && contentData.article !== null && contentData.article.data.length>0) {
            initArticle(contentData.article);
            // $('#article-vertical-tabpanel').fadeIn();
            $('#article-vertical-tab').fadeIn();
            $('#article-vertical-tab a').trigger('click');
            hasPamphletOrArticle = true;
            hasItem = true;
        } else {
            // $('#article-vertical-tabpanel').fadeOut();
            $('#article-vertical-tab').fadeOut();
        }

        if (hasPamphletOrArticle) {
            $('.ProductAndSetAndVideoWraper').removeClass('col').addClass('col-12 col-md-9');
            $('.PamphletAndArticleWraper').removeClass('d-none');
        } else {
            $('.ProductAndSetAndVideoWraper').removeClass('col-12 col-md-9').addClass('col');
            $('.PamphletAndArticleWraper').removeClass('d-none').addClass('d-none');
        }

        if (!hasItem) {
            $('.notFoundMessage').fadeIn();
        } else {
            $('.notFoundMessage').fadeOut();
        }
    }

    function addLoadingItem(owl, owlType) {
        if (owlType === 'product' || owlType === 'video' || owlType === 'set') {
            let loadingHtml = '<div class="a--owlCarouselLoading"><div style="width: 30px; display: inline-block;" class="m-loader m-loader--primary m-loader--lg"></div></div>';
            owl.trigger('add.owl.carousel',
                [
                    jQuery(loadingHtml)
                ]
            );
        } else if (owlType === 'pamphlet' || owlType === 'article') {
            owl.append('<div class="a--vw-Loading"><div style="width: 30px; display: inline-block;" class="m-loader m-loader--primary m-loader--lg"></div></div>');
        }
    }

    function removeLoadingItem(owl, owlType) {
        if (owlType === 'product' || owlType === 'video' || owlType === 'set') {
            let lastIndex = owl.find('.owl-item').length;
            owl.trigger('remove.owl.carousel', [lastIndex - 1])
                .trigger('refresh.owl.carousel');
        } else if (owlType === 'pamphlet' || owlType === 'article') {
            owl.find('.a--vw-Loading').remove();
        }
    }

    function isScrolledIntoView(elem) {
        if (elem.length === 0) {
            return false;
        }
        let docViewTop = $(window).scrollTop();
        let docViewBottom = docViewTop + $(window).height();
        let elemTop = $(elem).offset().top;
        let elemBottom = elemTop + $(elem).height();
        return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom) && (elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    function clearOwlcarousel(owl) {
        let length = owl.find('.item').length;
        while (length > 0) {
            owl.trigger('remove.owl.carousel', 0)
                .trigger('refresh.owl.carousel');
            length = owl.find('.item').length;
        }
    }

    return {
        init: function (contentData) {
            loadAjaxContent(contentData);
        },
        clearFields: function () {
            clearOwlcarousel($('#product-carousel-warper .a--owl-carousel-type-1'));
            clearOwlcarousel($('#set-carousel-warper .a--owl-carousel-type-1'));
            clearOwlcarousel($('#video-carousel-warper .a--owl-carousel-type-1'));
            $('#pamphlet-vertical-widget').html('');
            $('#article-vertical-widget').html('');
        },
    }
}();

var MultiLevelSearch = function () {

    let selectorItems = [];
    let selectorId = '';

    function getSelectorItems(MultiLevelSearchSelectorId) {
        selectorId = MultiLevelSearchSelectorId;
        selectorItems = $('#' + selectorId + ' .selectorItem');
        return selectorItems;
    }

    function getSelectorItem(selectorIndex) {
        return $('#' + selectorId + ' .selectorItem[data-select-order="' + selectorIndex + '"]');
    }

    function getSelectorSubitems(selectorIndex) {
        return $('#' + selectorId + ' .selectorItem[data-select-order="' + selectorIndex + '"] .subItem');
    }

    function showSelectorItem(selectorIndex) {

        let selectorItem = getSelectorItem(selectorIndex);

        if (selectorItem.length === 0) {
            refreshNavbar(selectorIndex);
            return false;
        }

        selectorItems.fadeOut(0);

        let title = selectorItem.data('select-title');
        let showType = selectorItem.data('select-display');
        if (typeof showType === 'undefined') {
            if (getSelectorSubitems(selectorIndex).length > 10) {
                showType = 'select2';
            } else {
                showType = 'grid';
            }
            selectorItem.attr('data-select-display', showType);
        }

        if (showType === 'grid') {
            if (selectorItem.find('.selectorItemTitle').length > 0) {
                selectorItem.find('.selectorItemTitle').html(title);
            } else {
                selectorItem.prepend('<div class="col-12 selectorItemTitle">' + title + '</div>');
            }
            if (selectorItem.length > 0) {
                selectorItem.fadeIn();
            }
        } else if (showType === 'select2') {
            let selectorSubitems = getSelectorSubitems(selectorIndex);
            selectorSubitems.fadeOut(0);
            let select2Html = '';
            let select2Id = 'MultiLevelSearch-select2-' + selectorId + '-' + selectorIndex;

            if (selectorItem.find('.selectorItemTitle').length > 0) {
                selectorItem.find('.selectorItemTitle').html(title);
            } else {
                selectorItem.prepend('<div class="col-12 selectorItemTitle">' + title + '</div>');
            }

            for (let index in selectorSubitems) {
                if (!isNaN(index)) {
                    let selected = '';
                    if ($(selectorSubitems[index]).attr('selected') === 'selected') {
                        selected = 'selected="selected"';
                    }
                    select2Html += '<option value="' + selectorSubitems[index].innerHTML + '" '+selected+'>' + selectorSubitems[index].innerHTML + '</option>';
                }
            }
            if (selectorItem.find('.form-control.select2').length > 0) {
                let oldSelect2 = $('#' + select2Id);
                if (!oldSelect2.data('select2')) {
                    oldSelect2.select2('destroy');
                }
                selectorItem.find('.select2warper').remove();
                selectorItem.append('<div class="col-12 col-sm-9 col-md-5 col-lg-4 select2warper"><div><select class="form-control select2" id="' + select2Id + '">' + select2Html + '</select></div></div>');
                $('#' + select2Id)
                    .select2({closeOnSelect: true})
                    .on('select2:select', function (event) {
                        $('.a--multi-level-search select').select2("close");
                    })
                    .on('select2:close', function (event) {
                        $('.a--multi-level-search select').select2("close");
                    });
                selectorItem.fadeIn();
            } else {
                selectorItem.append('<div class="col-12 col-sm-9 col-md-5 col-lg-4 select2warper"><div><select class="form-control select2" id="' + select2Id + '">' + select2Html + '</select></div></div>');
                $('#' + select2Id)
                    .select2({closeOnSelect: true})
                    .on('select2:select', function (event) {
                        $('.a--multi-level-search select').select2('destroy');
                        $('.a--multi-level-search select').select2({closeOnSelect: true});
                        $('.a--multi-level-search select').select2("close");
                        $('.a--multi-level-search select').select2("close");
                    })
                    .on('select2:close', function (event) {
                        $('.a--multi-level-search select').select2('destroy');
                        $('.a--multi-level-search select').select2({closeOnSelect: true});
                        $('.a--multi-level-search select').select2("close");
                        $('.a--multi-level-search select').select2("close");
                    });
                selectorItem.fadeIn();
            }
        }
        refreshNavbar(selectorIndex);
    }

    function setValueOfSelector(selectorIndex, value) {
        getSelectorItem(selectorIndex).attr('data-select-value', value);
    }

    function getValueOfSelector(selectorIndex) {
        let value = getSelectorItem(selectorIndex).attr('data-select-value');
        return (typeof value !== 'undefined') ? value : null;
    }

    function getActiveStepOrder() {
        if ($('#' + selectorId + ' .selectorItem[data-select-active="true"]').length > 0) {
            return parseInt($('#' + selectorId + ' .selectorItem[data-select-active="true"]').data('select-order'));
        } else {
            return 0;
        }
    }

    function setActiveStep(selectorIndex) {
        $('#' + selectorId + ' .selectorItem').attr('data-select-active', 'false');
        getSelectorItem(selectorIndex).attr('data-select-active', 'true');
    }

    function getMaxOrder() {
        let maxOrder = 0;
        for (let index in selectorItems) {
            if (!isNaN(index)) {
                let order = parseInt($(selectorItems[index]).data('select-order'));
                if (maxOrder < order) {
                    maxOrder = order;
                }
            }
        }
        return maxOrder;
    }

    function refreshNavbar(selectorIndex) {
        let filterNavigationWarper = $('#' + selectorId + ' .filterNavigationWarper');
        filterNavigationWarper.html('');

        selectorItems = $('#' + selectorId + ' .selectorItem');
        for (let index in selectorItems) {
            if (!isNaN(index)) {

                let title = $(selectorItems[index]).data('select-title');
                let order = $(selectorItems[index]).data('select-order');
                let activeString = 'deactive';
                if (parseInt(order) < parseInt(selectorIndex)) {
                    activeString = 'active';
                } else if (parseInt(order) === parseInt(selectorIndex)) {
                    activeString = 'current';
                } else {
                    setValueOfSelector(order, '');
                }

                let selectedText = '';
                if (activeString === 'deactive') {
                    $(selectorItems[index]).attr('data-select-value', '');
                }
                selectedText = getValueOfSelector(order);

                // filterNavigationWarper.append('<div class="col ' + activeString + ' filterNavigationStep" data-select-order="' + order + '"><div class="filterStepText">' + title + '</div><div class="filterStepSelectedText">'+selectedText+'</div></div>');

                if (selectedText === null || selectedText.trim().length === 0) {
                    selectedText = '(' + 'انتخاب ' + title + ')';
                }
                filterNavigationWarper.append('<li class="filterNavigationStep ' + activeString + '" data-select-order="' + order + '">' + selectedText + '</li>');
            }
        }
    }

    function refreshNavbar1(selectorIndex) {
        let filterNavigationWarper = $('#' + selectorId + ' .filterNavigationWarper');
        filterNavigationWarper.html('');

        selectorItems = $('#' + selectorId + ' .selectorItem');
        for (let index in selectorItems) {
            if (!isNaN(index)) {

                let title = $(selectorItems[index]).data('select-title');
                let order = $(selectorItems[index]).data('select-order');
                let activeString = 'deactive';
                if (parseInt(order) < parseInt(selectorIndex)) {
                    activeString = 'active';
                } else if (parseInt(order) === parseInt(selectorIndex)) {
                    activeString = 'current';
                } else {
                    setValueOfSelector(order, '');
                }

                let selectedText = '';
                if (activeString === 'deactive') {
                    $(selectorItems[index]).attr('data-select-value', '');
                }
                selectedText = getValueOfSelector(order);

                // filterNavigationWarper.append('<div class="col ' + activeString + ' filterNavigationStep" data-select-order="' + order + '"><div class="filterStepText">' + title + '</div><div class="filterStepSelectedText">'+selectedText+'</div></div>');

                if (selectedText === null || selectedText.trim().length === 0) {
                    selectedText = '(' + title + ')';
                }
                filterNavigationWarper.append('<div class="col ' + activeString + ' filterNavigationStep" data-select-order="' + order + '"><div class="filterStepText">' + selectedText + '</div></div>');
            }
        }
    }

    function onChangeFilter(selectorOrder, initOptions) {
        if (
            typeof (initOptions.selector) !== 'undefined' &&
            typeof (initOptions.selector[selectorOrder]) !== 'undefined' &&
            typeof (initOptions.selector[selectorOrder].ajax) !== 'undefined' &&
            initOptions.selector[selectorOrder].ajax !== null) {
            // ajax load ...
        } else {
            showSelectorItem(parseInt(selectorOrder) + 1);
            if (getMaxOrder() >= (parseInt(selectorOrder) + 1)) {
                console.log('if order: ', (parseInt(selectorOrder) + 1));
                setActiveStep(parseInt(selectorOrder) + 1);
            }
        }
    }

    function onFilterNavCliked(selectorOrder, initOptions) {
        if (
            typeof (initOptions.selector) !== 'undefined' &&
            typeof (initOptions.selector[selectorOrder]) !== 'undefined' &&
            typeof (initOptions.selector[selectorOrder].ajax) !== 'undefined' &&
            initOptions.selector[selectorOrder].ajax !== null) {
            // ajax load ...
        } else {
            if (selectorOrder < getActiveStepOrder()) {
                showSelectorItem(parseInt(selectorOrder));
                setActiveStep(selectorOrder);
            }
        }
    }

    function getSelectedData() {
        let data = [];
        selectorItems = $('#' + selectorId + ' .selectorItem');
        for (let index in selectorItems) {
            if (!isNaN(index)) {
                let title = $(selectorItems[index]).data('select-title');
                let order = $(selectorItems[index]).data('select-order');
                let selectedText = (typeof ($(selectorItems[index]).attr('data-select-value')) !== 'undefined') ? $(selectorItems[index]).attr('data-select-value') : null;
                data.push({
                    title: title,
                    order: order,
                    selectedText: selectedText
                });
            }
        }
        return data;
    }

    return {
        init: function (initOptions, onChangeCallback, beforeChangeFilterCallback) {
            let multiSelector = $('#' + initOptions.selectorId);
            multiSelector.fadeOut(0);
            getSelectorItems(initOptions.selectorId);
            showSelectorItem(getActiveStepOrder());
            $(document).on('click', '#' + initOptions.selectorId + ' .selectorItem .subItem', function () {
                let parent = $(this).parents('.selectorItem');
                let selectorOrder = parent.data('select-order');
                parent.attr('data-select-value', $(this).html());
                let data = {
                    selectorOrder: selectorOrder,
                    selectorType: 'grid'
                };
                beforeChangeFilterCallback(data);
                onChangeFilter(selectorOrder, initOptions);
                onChangeCallback(data);
            });
            $(document).on('change', '#' + initOptions.selectorId + ' .selectorItem .select2', function () {
                let parent = $(this).parents('.selectorItem');
                let selectorOrder = parent.data('select-order');
                parent.attr('data-select-value', $(this).select2('data')[0].text.trim());
                // parent.attr('data-select-value', $(this).find(':selected').text.trim());
                let data = {
                    selectorOrder: selectorOrder,
                    selectorType: 'select2'
                };
                beforeChangeFilterCallback(data);
                onChangeFilter(selectorOrder, initOptions);
                onChangeCallback(data);
            });
            $(document).on('click', '#' + initOptions.selectorId + ' .filterNavigationStep', function () {
                // let selectorOrder = $(this).parents('.filterNavigationStep').data('select-order');
                let selectorOrder = $(this).data('select-order');
                onFilterNavCliked(selectorOrder, initOptions);
                onChangeCallback('click on filterNavigationStep');
            });
            multiSelector.fadeIn();
        },
        getSelectedData: function () {
            return getSelectedData();
        }
    };
}();

var CustomInitMultiLevelSearch = function () {

    let filterData = {
        "lessonTeacher": {
            "همه_دروس": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "آقاجانی",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_آقاجانی"
                },
                {
                    "lastName": "آقاجانی",
                    "firstName": "رضا",
                    "value": "رضا_آقاجانی"
                },
                {
                    "lastName": "آهویی",
                    "firstName": "محسن",
                    "value": "محسن_آهویی"
                },
                {
                    "lastName": "ارشی",
                    "firstName": "",
                    "value": "ارشی"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "محمد علی",
                    "value": "محمد_علی_امینی_راد"
                },
                {
                    "lastName": "بهمند",
                    "firstName": "یاشار",
                    "value": "یاشار_بهمند"
                },
                {
                    "lastName": "تاج بخش",
                    "firstName": "عمار",
                    "value": "عمار_تاج_بخش"
                },
                {
                    "lastName": "تفتی",
                    "firstName": "مهدی",
                    "value": "مهدی_تفتی"
                },
                {
                    "lastName": "ثابتی",
                    "firstName": "محمد صادق",
                    "value": "محمد_صادق_ثابتی"
                },
                {
                    "lastName": "جعفری",
                    "firstName": "ابوالفضل",
                    "value": "ابوالفضل_جعفری"
                },
                {
                    "lastName": "جعفری",
                    "firstName": "",
                    "value": "جعفری"
                },
                {
                    "lastName": "جعفری نژاد",
                    "firstName": "مصطفی",
                    "value": "مصطفی_جعفری_نژاد"
                },
                {
                    "lastName": "جلادتی",
                    "firstName": "مهدی",
                    "value": "مهدی_جلادتی"
                },
                {
                    "lastName": "جلالی",
                    "firstName": "سید حسام الدین",
                    "value": "سید_حسام_الدین_جلالی"
                },
                {
                    "lastName": "جهانبخش",
                    "firstName": "",
                    "value": "جهانبخش"
                },
                {
                    "lastName": "حاجی سلیمانی",
                    "firstName": "روح الله",
                    "value": "روح_الله_حاجی_سلیمانی"
                },
                {
                    "lastName": "حدادی",
                    "firstName": "مسعود",
                    "value": "مسعود_حدادی"
                },
                {
                    "lastName": "حسین انوشه",
                    "firstName": "محمد",
                    "value": "محمد_حسین_انوشه"
                },
                {
                    "lastName": "حسین خانی",
                    "firstName": "میثم",
                    "value": "میثم__حسین_خانی"
                },
                {
                    "lastName": "حسین شکیباییان",
                    "firstName": "محمد",
                    "value": "محمد_حسین_شکیباییان"
                },
                {
                    "lastName": "حسینی فرد",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_حسینی_فرد"
                },
                {
                    "lastName": "حشمتی",
                    "firstName": "ناصر",
                    "value": "ناصر_حشمتی"
                },
                {
                    "lastName": "داداشی",
                    "firstName": "فرشید",
                    "value": "فرشید_داداشی"
                },
                {
                    "lastName": "درویش",
                    "firstName": "",
                    "value": "درویش"
                },
                {
                    "lastName": "راستی بروجنی",
                    "firstName": "عباس",
                    "value": "عباس_راستی_بروجنی"
                },
                {
                    "lastName": "راوش",
                    "firstName": "داریوش",
                    "value": "داریوش_راوش"
                },
                {
                    "lastName": "رحیمی",
                    "firstName": "شهروز",
                    "value": "شهروز_رحیمی"
                },
                {
                    "lastName": "رحیمی",
                    "firstName": "پوریا",
                    "value": "پوریا_رحیمی"
                },
                {
                    "lastName": "رمضانی",
                    "firstName": "علیرضا",
                    "value": "علیرضا_رمضانی"
                },
                {
                    "lastName": "رنجبرزاده",
                    "firstName": "جعفر",
                    "value": "جعفر_رنجبرزاده"
                },
                {
                    "lastName": "زاهدی",
                    "firstName": "امید",
                    "value": "امید_زاهدی"
                },
                {
                    "lastName": "سبطی",
                    "firstName": "هامون",
                    "value": "هامون_سبطی"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "شاه محمدی",
                    "firstName": "",
                    "value": "شاه_محمدی"
                },
                {
                    "lastName": "شهریان",
                    "firstName": "محسن",
                    "value": "محسن_شهریان"
                },
                {
                    "lastName": "صادقی",
                    "firstName": "محمد",
                    "value": "محمد_صادقی"
                },
                {
                    "lastName": "صدری",
                    "firstName": "علی",
                    "value": "علی_صدری"
                },
                {
                    "lastName": "صنیعی طهرانی",
                    "firstName": "مهدی",
                    "value": "مهدی_صنیعی_طهرانی"
                },
                {
                    "lastName": "طلوعی",
                    "firstName": "پیمان",
                    "value": "پیمان_طلوعی"
                },
                {
                    "lastName": "عزتی",
                    "firstName": "علی اکبر",
                    "value": "علی_اکبر_عزتی"
                },
                {
                    "lastName": "علیمرادی",
                    "firstName": "پدرام",
                    "value": "پدرام_علیمرادی"
                },
                {
                    "lastName": "فدایی فرد",
                    "firstName": "حمید",
                    "value": "حمید_فدایی_فرد"
                },
                {
                    "lastName": "فراهانی",
                    "firstName": "کیاوش",
                    "value": "کیاوش_فراهانی"
                },
                {
                    "lastName": "مؤذنی پور",
                    "firstName": "بهمن",
                    "value": "بهمن_مؤذنی_پور"
                },
                {
                    "lastName": "محمد زاده",
                    "firstName": "خسرو",
                    "value": "خسرو_محمد_زاده"
                },
                {
                    "lastName": "مرادی",
                    "firstName": "عبدالرضا",
                    "value": "عبدالرضا_مرادی"
                },
                {
                    "lastName": "مرصعی",
                    "firstName": "حسن",
                    "value": "حسن_مرصعی"
                },
                {
                    "lastName": "معینی",
                    "firstName": "سروش",
                    "value": "سروش_معینی"
                },
                {
                    "lastName": "معینی",
                    "firstName": "محسن",
                    "value": "محسن_معینی"
                },
                {
                    "lastName": "مقصودی",
                    "firstName": "محمدرضا",
                    "value": "محمدرضا_مقصودی"
                },
                {
                    "lastName": "موقاری",
                    "firstName": "جلال",
                    "value": "جلال_موقاری"
                },
                {
                    "lastName": "نادریان",
                    "firstName": "",
                    "value": "نادریان"
                },
                {
                    "lastName": "ناصح زاده",
                    "firstName": "میلاد",
                    "value": "میلاد_ناصح_زاده"
                },
                {
                    "lastName": "ناصر شریعت",
                    "firstName": "مهدی",
                    "value": "مهدی_ناصر_شریعت"
                },
                {
                    "lastName": "نایب کبیر",
                    "firstName": "جواد",
                    "value": "جواد_نایب_کبیر"
                },
                {
                    "lastName": "نباخته",
                    "firstName": "محمدامین",
                    "value": "محمدامین_نباخته"
                },
                {
                    "lastName": "نصیری",
                    "firstName": "سیروس",
                    "value": "سیروس_نصیری"
                },
                {
                    "lastName": "پازوکی",
                    "firstName": "محمد",
                    "value": "محمد_پازوکی"
                },
                {
                    "lastName": "پویان نظر",
                    "firstName": "حامد",
                    "value": "حامد_پویان_نظر"
                },
                {
                    "lastName": "کازرانیان",
                    "firstName": "",
                    "value": "کازرانیان"
                },
                {
                    "lastName": "کاظمی",
                    "firstName": "کاظم",
                    "value": "کاظم_کاظمی"
                },
                {
                    "lastName": "کبریایی",
                    "firstName": "وحید",
                    "value": "وحید_کبریایی"
                },
                {
                    "lastName": "کرد",
                    "firstName": "حسین",
                    "value": "حسین_کرد"
                }
            ],
            "دیفرانسیل": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "ثابتی",
                    "firstName": "محمد صادق",
                    "value": "محمد_صادق_ثابتی"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "شهریان",
                    "firstName": "محسن",
                    "value": "محسن_شهریان"
                },
                {
                    "lastName": "نصیری",
                    "firstName": "سیروس",
                    "value": "سیروس_نصیری"
                }
            ],
            "گسسته": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "مؤذنی پور",
                    "firstName": "بهمن",
                    "value": "بهمن_مؤذنی_پور"
                },
                {
                    "lastName": "محمدی",
                    "firstName": "شاه",
                    "value": "شاه_محمدی"
                },
                {
                    "lastName": "معینی",
                    "firstName": "سروش",
                    "value": "سروش_معینی"
                }
            ],
            "تحلیلی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "ثابتی",
                    "firstName": "محمد صادق",
                    "value": "محمد_صادق_ثابتی"
                },
                {
                    "lastName": "حسینی فرد",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_حسینی_فرد"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                }
            ],
            "هندسه_پایه": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "حسینی فرد",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_حسینی_فرد"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "مرصعی",
                    "firstName": "حسن",
                    "value": "حسن_مرصعی"
                },
                {
                    "lastName": "کبریایی",
                    "firstName": "وحید",
                    "value": "وحید_کبریایی"
                }
            ],
            "حسابان": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "ثابتی",
                    "firstName": "محمد صادق",
                    "value": "محمد_صادق_ثابتی"
                },
                {
                    "lastName": "رحیمی",
                    "firstName": "شهروز",
                    "value": "شهروز_رحیمی"
                },
                {
                    "lastName": "مقصودی",
                    "firstName": "محمدرضا",
                    "value": "محمدرضا_مقصودی"
                }
            ],
            "جبر_و_احتمال": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "کرد",
                    "firstName": "حسین",
                    "value": "حسین_کرد"
                }
            ],
            "ریاضی_پایه": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "شهریان",
                    "firstName": "محسن",
                    "value": "محسن_شهریان"
                },
                {
                    "lastName": "مقصودی",
                    "firstName": "محمدرضا",
                    "value": "محمدرضا_مقصودی"
                },
                {
                    "lastName": "نایب کبیر",
                    "firstName": "جواد",
                    "value": "جواد_نایب_کبیر"
                }
            ],
            "ریاضی_تجربی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                },
                {
                    "lastName": "حسینی فرد",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_حسینی_فرد"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "صدری",
                    "firstName": "علی",
                    "value": "علی_صدری"
                },
                {
                    "lastName": "نباخته",
                    "firstName": "محمدامین",
                    "value": "محمدامین_نباخته"
                }
            ],
            "ریاضی_انسانی": {
                "0": {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                "2": {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                },
                "1": {
                    "lastName": "محمد زاده",
                    "firstName": "خسرو",
                    "value": "خسرو_محمد_زاده"
                }
            },
            "عربی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "آهویی",
                    "firstName": "محسن",
                    "value": "محسن_آهویی"
                },
                {
                    "lastName": "تاج بخش",
                    "firstName": "عمار",
                    "value": "عمار_تاج_بخش"
                },
                {
                    "lastName": "جلادتی",
                    "firstName": "مهدی",
                    "value": "مهدی_جلادتی"
                },
                {
                    "lastName": "حشمتی",
                    "firstName": "ناصر",
                    "value": "ناصر_حشمتی"
                },
                {
                    "lastName": "رنجبرزاده",
                    "firstName": "جعفر",
                    "value": "جعفر_رنجبرزاده"
                },
                {
                    "lastName": "علیمرادی",
                    "firstName": "پدرام",
                    "value": "پدرام_علیمرادی"
                },
                {
                    "lastName": "ناصح زاده",
                    "firstName": "میلاد",
                    "value": "میلاد_ناصح_زاده"
                },
                {
                    "lastName": "ناصر شریعت",
                    "firstName": "مهدی",
                    "value": "مهدی_ناصر_شریعت"
                }
            ],
            "شیمی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "آقاجانی",
                    "firstName": "محمد رضا",
                    "value": "محمد_رضا_آقاجانی"
                },
                {
                    "lastName": "انوشه",
                    "firstName": "محمد حسین",
                    "value": "محمد_حسین_انوشه"
                },
                {
                    "lastName": "جعفری",
                    "firstName": "",
                    "value": "جعفری"
                },
                {
                    "lastName": "حاجی سلیمانی",
                    "firstName": "روح الله ",
                    "value": "روح_الله_حاجی_سلیمانی"
                },
                {
                    "lastName": "شکیباییان",
                    "firstName": "محمد حسین ",
                    "value": "محمد_حسین_شکیباییان"
                },
                {
                    "lastName": "طهرانی",
                    "firstName": "مهدی صنیعی",
                    "value": "مهدی_صنیعی_طهرانی"
                },
                {
                    "lastName": "معینی",
                    "firstName": "محسن ",
                    "value": "محسن_معینی"
                },
                {
                    "lastName": "پویان نظر",
                    "firstName": "حامد ",
                    "value": "حامد_پویان_نظر"
                }
            ],
            "فیزیک": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "جهانبخش",
                    "firstName": "",
                    "value": "جهانبخش"
                },
                {
                    "lastName": "داداشی",
                    "firstName": "فرشید",
                    "value": "فرشید_داداشی"
                },
                {
                    "lastName": "رمضانی",
                    "firstName": "علیرضا",
                    "value": "علیرضا_رمضانی"
                },
                {
                    "lastName": "طلوعی",
                    "firstName": "پیمان",
                    "value": "پیمان_طلوعی"
                },
                {
                    "lastName": "فدایی فرد",
                    "firstName": "حمید",
                    "value": "حمید_فدایی_فرد"
                },
                {
                    "lastName": "نادریان",
                    "firstName": "",
                    "value": "نادریان"
                },
                {
                    "lastName": "کازرانیان",
                    "firstName": "",
                    "value": "کازرانیان"
                }
            ],
            "زبان_انگلیسی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "درویش",
                    "firstName": "",
                    "value": "درویش"
                },
                {
                    "lastName": "عزتی",
                    "firstName": "علی اکبر",
                    "value": "علی_اکبر_عزتی"
                },
                {
                    "lastName": "فراهانی",
                    "firstName": "کیاوش",
                    "value": "کیاوش_فراهانی"
                }
            ],
            "دین_و_زندگی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "تفتی",
                    "firstName": "مهدی",
                    "value": "مهدی_تفتی"
                },
                {
                    "lastName": "رنجبرزاده",
                    "firstName": "جعفر",
                    "value": "جعفر_رنجبرزاده"
                }
            ],
            "زبان_و_ادبیات_فارسی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "خانی حسین",
                    "firstName": "میثم",
                    "value": "میثم__حسین_خانی"
                },
                {
                    "lastName": "راوش",
                    "firstName": "داریوش",
                    "value": "داریوش_راوش"
                },
                {
                    "lastName": "سبطی",
                    "firstName": "هامون",
                    "value": "هامون_سبطی"
                },
                {
                    "lastName": "صادقی",
                    "firstName": "محمد",
                    "value": "محمد_صادقی"
                },
                {
                    "lastName": "مرادی",
                    "firstName": "عبدالرضا",
                    "value": "عبدالرضا_مرادی"
                },
                {
                    "lastName": "کاظمی",
                    "firstName": "کاظم",
                    "value": "کاظم_کاظمی"
                }
            ],
            "آمار_و_مدلسازی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                },
                {
                    "lastName": "شامیزاده",
                    "firstName": "رضا",
                    "value": "رضا_شامیزاده"
                },
                {
                    "lastName": "کبریایی",
                    "firstName": "وحید",
                    "value": "وحید_کبریایی"
                }
            ],
            "زیست_شناسی": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "ارشی",
                    "firstName": "",
                    "value": "ارشی"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "محمد علی",
                    "value": "محمد_علی_امینی_راد"
                },
                {
                    "lastName": "جعفری",
                    "firstName": "ابوالفضل",
                    "value": "ابوالفضل_جعفری"
                },
                {
                    "lastName": "حدادی",
                    "firstName": "مسعود",
                    "value": "مسعود_حدادی"
                },
                {
                    "lastName": "راستی بروجنی",
                    "firstName": "عباس",
                    "value": "عباس_راستی_بروجنی"
                },
                {
                    "lastName": "رحیمی",
                    "firstName": "پوریا",
                    "value": "پوریا_رحیمی"
                },
                {
                    "lastName": "موقاری",
                    "firstName": "جلال",
                    "value": "جلال_موقاری"
                },
                {
                    "lastName": "پازوکی",
                    "firstName": "محمد",
                    "value": "محمد_پازوکی"
                }
            ],
            "ریاضی_و_آمار": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "مهدی",
                    "value": "مهدی_امینی_راد"
                }
            ],
            "منطق": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "آقاجانی",
                    "firstName": "رضا",
                    "value": "رضا_آقاجانی"
                },
                {
                    "lastName": "الدین جلالی",
                    "firstName": "سید حسام",
                    "value": "سید_حسام_الدین_جلالی"
                }
            ],
            "المپیاد_فیزیک": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "جعفری نژاد",
                    "firstName": "مصطفی",
                    "value": "مصطفی_جعفری_نژاد"
                }
            ],
            "المپیاد_نجوم": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "بهمند",
                    "firstName": "یاشار",
                    "value": "یاشار_بهمند"
                }
            ],
            "مشاوره": [
                {
                    "lastName": "همه دبیرها",
                    "firstName": "",
                    "value": "همه_دبیرها"
                },
                {
                    "lastName": "امینی راد",
                    "firstName": "محمد علی",
                    "value": "محمد_علی_امینی_راد"
                },
                {
                    "lastName": "زاهدی",
                    "firstName": "امید",
                    "value": "امید_زاهدی"
                }
            ]
        },
        "allLessons": [
            {
                "value": "همه_دروس",
                "index": "همه دروس"
            },
            {
                "value": "آمار_و_مدلسازی",
                "index": "آمار و مدلسازی"
            },
            {
                "value": "اخلاق",
                "index": "اخلاق"
            },
            {
                "value": "المپیاد_فیزیک",
                "index": "المپیاد فیزیک"
            },
            {
                "value": "المپیاد_نجوم",
                "index": "المپیاد نجوم"
            },
            {
                "value": "تحلیلی",
                "index": "تحلیلی"
            },
            {
                "value": "جبر_و_احتمال",
                "index": "جبر و احتمال"
            },
            {
                "value": "حسابان",
                "index": "حسابان"
            },
            {
                "value": "دیفرانسیل",
                "index": "دیفرانسیل"
            },
            {
                "value": "دین_و_زندگی",
                "index": "دین و زندگی"
            },
            {
                "value": "ریاضی_انسانی",
                "index": "ریاضی انسانی"
            },
            {
                "value": "ریاضی_تجربی",
                "index": "ریاضی تجربی"
            },
            {
                "value": "ریاضی_و_آمار",
                "index": "ریاضی و آمار"
            },
            {
                "value": "ریاضی_پایه",
                "index": "ریاضی پایه"
            },
            {
                "value": "زبان_انگلیسی",
                "index": "زبان انگلیسی"
            },
            {
                "value": "زبان_و_ادبیات_فارسی",
                "index": "زبان و ادبیات فارسی"
            },
            {
                "value": "زیست_شناسی",
                "index": "زیست شناسی"
            },
            {
                "value": "شیمی",
                "index": "شیمی"
            },
            {
                "value": "عربی",
                "index": "عربی"
            },
            {
                "value": "فیزیک",
                "index": "فیزیک"
            },
            {
                "value": "مشاوره",
                "index": "مشاوره"
            },
            {
                "value": "منطق",
                "index": "منطق"
            },
            {
                "value": "هندسه_پایه",
                "index": "هندسه پایه"
            },
            {
                "value": "گسسته",
                "index": "گسسته"
            }
        ],
        "riaziLessons": [
            {
                "value": "همه_دروس",
                "index": "همه دروس"
            },
            {
                "value": "آمار_و_مدلسازی",
                "index": "آمار و مدلسازی"
            },
            {
                "value": "المپیاد_فیزیک",
                "index": "المپیاد فیزیک"
            },
            {
                "value": "المپیاد_نجوم",
                "index": "المپیاد نجوم"
            },
            {
                "value": "تحلیلی",
                "index": "تحلیلی"
            },
            {
                "value": "جبر_و_احتمال",
                "index": "جبر و احتمال"
            },
            {
                "value": "حسابان",
                "index": "حسابان"
            },
            {
                "value": "دیفرانسیل",
                "index": "دیفرانسیل"
            },
            {
                "value": "دین_و_زندگی",
                "index": "دین و زندگی"
            },
            {
                "value": "ریاضی_پایه",
                "index": "ریاضی پایه"
            },
            {
                "value": "زبان_انگلیسی",
                "index": "زبان انگلیسی"
            },
            {
                "value": "زبان_و_ادبیات_فارسی",
                "index": "زبان و ادبیات فارسی"
            },
            {
                "value": "شیمی",
                "index": "شیمی"
            },
            {
                "value": "عربی",
                "index": "عربی"
            },
            {
                "value": "فیزیک",
                "index": "فیزیک"
            },
            {
                "value": "مشاوره",
                "index": "مشاوره"
            },
            {
                "value": "هندسه_پایه",
                "index": "هندسه پایه"
            },
            {
                "value": "گسسته",
                "index": "گسسته"
            }
        ],
        "tajrobiLessons": [
            {
                "value": "همه_دروس",
                "index": "همه دروس"
            },
            {
                "value": "آمار_و_مدلسازی",
                "index": "آمار و مدلسازی"
            },
            {
                "value": "المپیاد_فیزیک",
                "index": "المپیاد فیزیک"
            },
            {
                "value": "المپیاد_نجوم",
                "index": "المپیاد نجوم"
            },
            {
                "value": "دین_و_زندگی",
                "index": "دین و زندگی"
            },
            {
                "value": "ریاضی_تجربی",
                "index": "ریاضی تجربی"
            },
            {
                "value": "ریاضی_پایه",
                "index": "ریاضی پایه"
            },
            {
                "value": "زبان_انگلیسی",
                "index": "زبان انگلیسی"
            },
            {
                "value": "زبان_و_ادبیات_فارسی",
                "index": "زبان و ادبیات فارسی"
            },
            {
                "value": "زیست_شناسی",
                "index": "زیست شناسی"
            },
            {
                "value": "شیمی",
                "index": "شیمی"
            },
            {
                "value": "عربی",
                "index": "عربی"
            },
            {
                "value": "فیزیک",
                "index": "فیزیک"
            },
            {
                "value": "مشاوره",
                "index": "مشاوره"
            },
            {
                "value": "هندسه_پایه",
                "index": "هندسه پایه"
            }
        ],
        "ensaniLessons": [
            {
                "value": "همه_دروس",
                "index": "همه دروس"
            },
            {
                "value": "آمار_و_مدلسازی",
                "index": "آمار و مدلسازی"
            },
            {
                "value": "اخلاق",
                "index": "اخلاق"
            },
            {
                "value": "دین_و_زندگی",
                "index": "دین و زندگی"
            },
            {
                "value": "ریاضی_انسانی",
                "index": "ریاضی انسانی"
            },
            {
                "value": "ریاضی_و_آمار",
                "index": "ریاضی و آمار"
            },
            {
                "value": "زبان_انگلیسی",
                "index": "زبان انگلیسی"
            },
            {
                "value": "زبان_و_ادبیات_فارسی",
                "index": "زبان و ادبیات فارسی"
            },
            {
                "value": "عربی",
                "index": "عربی"
            },
            {
                "value": "مشاوره",
                "index": "مشاوره"
            },
            {
                "value": "منطق",
                "index": "منطق"
            }
        ],
        "major": [
            {
                name: 'همه رشته ها',
                value: 'همه_رشته_ها',
                lessonKey: 'allLessons'
            },
            {
                name: 'رشته ریاضی',
                value: 'رشته_ریاضی',
                lessonKey: 'riaziLessons'
            },
            {
                name: 'رشته تجربی',
                value: 'رشته_تجربی',
                lessonKey: 'tajrobiLessons'
            },
            {
                name: 'رشته انسانی',
                value: 'رشته_انسانی',
                lessonKey: 'ensaniLessons'
            }
        ],
        "maghta": [
            {
                name: 'همه مقاطع',
                value: 'همه_مقاطع'
            },
            {
                name: 'دهم',
                value: 'دهم'
            },
            {
                name: 'یازدهم',
                value: 'یازدهم'
            },
            {
                name: 'کنکور',
                value: 'کنکور'
            },
            {
                name: 'اول دبیرستان',
                value: 'اول_دبیرستان'
            },
            {
                name: 'دوم دبیرستان',
                value: 'دوم_دبیرستان'
            },
            {
                name: 'سوم دبیرستان',
                value: 'سوم_دبیرستان'
            },
            {
                name: 'چهارم دبیرستان',
                value: 'چهارم_دبیرستان'
            },
            {
                name: 'المپیاد',
                value: 'المپیاد'
            }
        ]
    };

    let tags = null;

    let selectedVlues = {
        maghta: filterData.maghta[0],
        major: filterData.major[0],
        lesson: filterData.allLessons[0],
        teacher: filterData.lessonTeacher.همه_دروس[0].value
    };

    let emptyFields = [
        filterData.lessonTeacher.همه_دروس[0].value,
        filterData.allLessons[0].value,
        filterData.major[0].value,
        filterData.maghta[0].value
    ];

    function addUnderLine(string) {
        return string.replace(/ /g, '_');
    }

    function removeUnderLine(string) {
        return string.replace(/_/g, ' ');
    }

    function getTags() {
        url = new URL(window.location.href);
        tags = url.searchParams.getAll("tags[]");
        return tags;
    }

    function activeFilter(filterClass) {
        $('.selectorItem').attr('data-select-active', 'false');
        $('.selectorItem.'+filterClass).attr('data-select-active', 'true');
    }

    function setSelectedMaghtaFromTags() {
        let selectedVal = filterData.maghta[0];
        let existInTags = false;
        tags = getTags();
        for (let tagsIndex in tags) {
            for (let majorIndex in filterData.maghta) {
                let item = filterData.maghta[majorIndex];
                if (item.value === tags[tagsIndex]) {
                    selectedVal = item;
                    activeFilter('majorSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.maghta = selectedVal;
        if (existInTags) {
            let name = removeUnderLine(selectedVal.value);
            $('.selectorItem.maghtaSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedMajorFromTags() {
        let selectedVal = filterData.major[0];
        let existInTags = false;
        tags = getTags();
        for (let tagsIndex in tags) {
            for (let majorIndex in filterData.major) {
                let value = filterData.major[majorIndex].value;
                if (value === tags[tagsIndex]) {
                    selectedVal = filterData.major[majorIndex];
                    activeFilter('lessonSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.major = selectedVal;
        if (existInTags) {
            let name = removeUnderLine(selectedVal.value);
            $('.selectorItem.majorSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedLessonFromTags() {
        let selectedMajor = selectedVlues.major;
        let selectedLesson = filterData[selectedMajor.lessonKey][0];
        let existInTags = false;
        tags = getTags();
        for (let tagsIndex in tags) {
            for (let lessonIndex in filterData[selectedMajor.lessonKey]) {
                let item = filterData[selectedMajor.lessonKey][lessonIndex];
                if (item.value === tags[tagsIndex]) {
                    selectedLesson = item;
                    activeFilter('teacherSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.lesson = selectedLesson;
        if (existInTags) {
            let name = removeUnderLine(selectedLesson.index);
            $('.selectorItem.lessonSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedTeacherFromTags() {
        let selectedLesson = selectedVlues.lesson.value;
        let selectedTeacher = filterData.lessonTeacher[selectedLesson][0];
        let existInTags = false;
        tags = getTags();
        for (let tagsIndex in tags) {
            for (let teacherIndex in filterData.lessonTeacher[selectedLesson]) {
                let item = filterData.lessonTeacher[selectedLesson][teacherIndex];
                if (item.value === tags[tagsIndex]) {
                    selectedTeacher = item;
                    activeFilter('teacherSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.teacher = selectedTeacher;
        if (existInTags) {
            let name = removeUnderLine(selectedTeacher.value);
            $('.selectorItem.teacherSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function initMaghta() {
        let selectedVlue = setSelectedMaghtaFromTags();
        $('.maghtaSelector').find('.subItem').remove();
        for (let index in filterData.maghta) {
            let name = filterData.maghta[index].value.replace('_', ' ');
            if (selectedVlue === name) {
                $('.maghtaSelector').append('<div class="col subItem" selected="selected">'+name+'</div>');
            } else {
                $('.maghtaSelector').append('<div class="col subItem">'+name+'</div>');
            }
        }
        setSelectedMaghtaFromTags();
    }

    function initMajor() {
        let selectedVlue = setSelectedMajorFromTags();
        $('.majorSelector').find('.subItem').remove();
        for (let index in filterData.major) {
            let name = filterData.major[index].value.replace('_', ' ');
            if (selectedVlue === name) {
                $('.majorSelector').append('<div class="col subItem" selected="selected">'+name+'</div>');
            } else {
                $('.majorSelector').append('<div class="col subItem">'+name+'</div>');
            }
        }
    }

    function initLessons() {
        let selectedVlue = setSelectedLessonFromTags();
        let major = selectedVlues.major.lessonKey;
        $('.lessonSelector').find('.subItem').remove();
        for (let index in filterData[major]) {
            let name = filterData[major][index].value.replace('_', ' ');
            name = removeUnderLine(name);
            if (selectedVlue === name) {
                $('.lessonSelector').append('<div class="col subItem" selected="selected">'+name+'</div>');
            } else {
                $('.lessonSelector').append('<div class="col subItem">'+name+'</div>');
            }
        }
    }

    function initTeacher() {
        let selectedVlue = setSelectedTeacherFromTags();
        let lesson = selectedVlues.lesson.value;
        $('.teacherSelector').find('.subItem').remove();
        for (let index in filterData.lessonTeacher[lesson]) {
            let name = filterData.lessonTeacher[lesson][index].value.replace('_', ' ');
            name = removeUnderLine(name);
            if (selectedVlue === name) {
                $('.teacherSelector').append('<div class="col subItem" selected="selected">'+name+'</div>');
            } else {
                $('.teacherSelector').append('<div class="col subItem">'+name+'</div>');
            }
        }
    }

    function checkEmptyField(string) {
        for (let index in emptyFields) {
            let item = emptyFields[index];
            if (addUnderLine(item) === addUnderLine(string)) {
                return '';
            }
        }
        return addUnderLine(string);
    }

    return {
        initFilters: function () {
            initMaghta();
            initMajor();
            initLessons();
            initTeacher();
        },
        checkEmptyField: function (string) {
            return checkEmptyField(string);
        },
        addUnderLine: function (string) {
            return addUnderLine(string);
        },
        removeUnderLine: function (string) {
            return removeUnderLine(string);
        }
    };
}();

var GetAjaxData = function () {

    function refreshTags() {
        $('.pageTags .m-list-badge__items').find('.m-list-badge__item').remove();

        let searchFilterData = MultiLevelSearch.getSelectedData();

        let url = document.location.href.split('?')[0];
        let tagsValue = '';
        for (let index in searchFilterData) {
            let selectedText = searchFilterData[index].selectedText;
            selectedText = CustomInitMultiLevelSearch.checkEmptyField(selectedText);
            if (typeof selectedText !== 'undefined' && selectedText !== null && selectedText !== '') {
                tagsValue += '&tags[]=' + selectedText;
                $('.pageTags .m-list-badge__items').append(
                    '<span class="m-list-badge__item m-list-badge__item--focus m--padding-10 m--margin-top-5 m--block-inline  tag_0">\n' +
                    '    <a class="m-link m--font-light" href="'+url+'?tags[]='+CustomInitMultiLevelSearch.addUnderLine(selectedText)+'">'+CustomInitMultiLevelSearch.removeUnderLine(selectedText)+'</a>\n' +
                    '</span>');
            }
        }
        if (tagsValue !== '') {
            tagsValue = tagsValue.substr(1);
        }

        url += '?' + tagsValue;

        history.pushState('data to be passed', 'Title of the page', url);
        // The above will add a new entry to the history so you can press Back button to go to the previous state.
        // To change the URL in place without adding a new entry to history use
        // history.replaceState('data to be passed', 'Title of the page', '');
    }

    function runWaiting() {
        Alaasearch.clearFields();
        $('.notFoundMessage').fadeOut();
        $('#product-carousel-warper').fadeIn();
        $('#video-carousel-warper').fadeIn();
        $('#set-carousel-warper').fadeIn();
        $('#pamphlet-vertical-tabpanel').fadeIn();
        $('#article-vertical-tabpanel').fadeIn();
        $('#pamphlet-vertical-tab').fadeIn();
        $('#article-vertical-tab').fadeIn();
        $('.ProductAndSetAndVideoWraper').removeClass('col').addClass('col-12 col-md-9');
        $('.PamphletAndArticleWraper').removeClass('d-none');
        mApp.block('#product-carousel-warper, #set-carousel-warper, #video-carousel-warper, #pamphlet-vertical-widget, #article-vertical-widget', {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "کمی صبر کنید..."
        });
    }
    function stopWaiting() {
        mApp.unblock('#product-carousel-warper, #set-carousel-warper, #video-carousel-warper, #pamphlet-vertical-widget, #article-vertical-widget');
    }

    function getNewDataBaseOnTags() {

        runWaiting();

        $.ajax({
            type: 'GET',
            url: document.location.href,
            data: {},
            dataType: 'json',
            success: function (data) {
                if (typeof data === 'undefined' || data.error) {

                    let message = '';
                    if (typeof data !== 'undefined') {
                        message = data.error.message;
                    }

                    toastr.error('خطای سیستمی رخ داده است.' + '<br>' + message);


                } else {
                    Alaasearch.init(data.result);
                }
                stopWaiting();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let message = '';
                // if (jqXHR.status === 403) {
                //     message = 'کد وارد شده اشتباه است.';
                // } else if (jqXHR.status === 422) {
                //     message = 'کد را وارد نکرده اید.';
                // } else {
                //     message = 'خطای سیستمی رخ داده است.';
                // }

                message = 'خطای سیستمی رخ داده است.';
                toastr.error(message);
                stopWaiting();
            }
        });
    }

    return {
        refreshTags: function () {
            refreshTags();
        },
        getNewDataBaseOnTags: function () {
            getNewDataBaseOnTags();
        }
    };
}();


jQuery(document).ready(function () {

    Alaasearch.init(contentData);

    CustomInitMultiLevelSearch.initFilters();

    MultiLevelSearch.init({
        selectorId: 'contentSearchFilter'
    }, function (data) {
        GetAjaxData.refreshTags();
        GetAjaxData.getNewDataBaseOnTags();
    },  function (data) {
        if (data.selectorOrder < 3) {
            GetAjaxData.refreshTags();
            CustomInitMultiLevelSearch.initFilters();
        }

        $('.a--multi-level-search select').select2('destroy');
        $('.a--multi-level-search select').select2({closeOnSelect: true});
        $('.a--multi-level-search select').select2("close");
        $('.a--multi-level-search select').select2("close");
    });

});