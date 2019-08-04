var Alaasearch = function () {

    var productAjaxLock = 0;
    var videoAjaxLock = 0;
    var setAjaxLock = 0;
    var articleAjaxLock = 0;
    var pamphletAjaxLock = 0;

    function getProductCarouselItem(data, itemKey) {
        var widgetActionLink = data.url;
        var widgetActionName = '<i class="flaticon-bag"></i>' + ' / ' + '<i class="fa fa-eye"></i>';
        var widgetPic = data.photo;
        var widgetTitle = data.name;
        var price = data.price;
        var discount = Math.round((1 - (price.final / price.base)) * 100);
        var discountRibbon = '';
        var countOfExistingProductInCarousel = $('#product-carousel.owl-carousel').find('.item').length;
        var gtmEecProductId = data.id;
        var gtmEecProductName = data.name;
        var gtmEecProductCategory = '-';
        var gtmEecProductVariant = '-';
        var gtmEecProductPosition = countOfExistingProductInCarousel;
        var priceHtml = '<span class="m-badge m-badge--danger m-badge--wide m-badge--rounded a--productPrice">';
        if (price.base !== price.final) {
            priceHtml += '    <span class="m-badge m-badge--warning a--productRealPrice">' + price.base.toLocaleString('fa') + '</span>\n';
            priceHtml += '    <span class="m-badge m-badge--info a--productDiscount">' + discount + '%</span>\n';
            discountRibbon = '\n' +
                '        <div class="ribbon">\n' +
                '            <span>\n' +
                '                <div class="glow">&nbsp;</div>\n' +
                '                '+ discount +'%\n' +
                '                <span>تخفیف</span>\n' +
                '            </span>\n' +
                '        </div>';
        }
        priceHtml += '    ' + price.final.toLocaleString('fa') + ' تومان \n';
        priceHtml += '</span>';

        return '' +
            '<div class="item carousel a--block-item a--block-type-product a--gtm-eec-product"\n' +
            '     data-position="'+itemKey+'"\n' +
            '     data-gtm-eec-product-id="'+gtmEecProductId+'"\n' +
            '     data-gtm-eec-product-name="'+gtmEecProductName+'"\n' +
            '     data-gtm-eec-product-price="'+priceToStringWithTwoDecimal(price.final)+'"\n' +
            '     data-gtm-eec-product-brand="آلاء"\n' +
            '     data-gtm-eec-product-category="-"\n' +
            '     data-gtm-eec-product-variant="-"\n' +
            '     data-gtm-eec-product-position="'+itemKey+'"\n' +
            '     data-gtm-eec-product-list="محصولات صفحه سرچ">'+
                discountRibbon +
            '    <div class="a--block-imageWrapper">\n' +
            '        <a href="' + widgetActionLink + '"\n' +
            '           class="a--block-imageWrapper-image a--gtm-eec-product-click"\n' +
            '           data-gtm-eec-product-id="'+gtmEecProductId+'"\n' +
            '           data-gtm-eec-product-name="'+gtmEecProductName+'"\n' +
            '           data-gtm-eec-product-price="'+priceToStringWithTwoDecimal(price.final)+'"\n' +
            '           data-gtm-eec-product-brand="آلاء"\n' +
            '           data-gtm-eec-product-category="-"\n' +
            '           data-gtm-eec-product-variant="-"\n' +
            '           data-gtm-eec-product-position="'+itemKey+'"\n' +
            '           data-gtm-eec-product-list="محصولات صفحه سرچ">\n' +
            '            <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="'+widgetPic+'" alt="'+gtmEecProductName+'" class="a--block-image lazy-image" width="400" height="400" />\n' +
            '        </a>\n' +
            '    </div>\n' +
            '    <div class="a--block-infoWrapper">\n' +
            '        <div class="a--block-titleWrapper">\n' +
            '            <a href="' + widgetActionLink + '"\n' +
            '               class="m-link a--owl-carousel-type-2-item-subtitle a--gtm-eec-product-click"\n' +
            '               data-gtm-eec-product-id="'+gtmEecProductId+'"\n' +
            '               data-gtm-eec-product-name="'+gtmEecProductName+'"\n' +
            '               data-gtm-eec-product-price="'+priceToStringWithTwoDecimal(price.final)+'"\n' +
            '               data-gtm-eec-product-brand="آلاء"\n' +
            '               data-gtm-eec-product-category="-"\n' +
            '               data-gtm-eec-product-variant="-"\n' +
            '               data-gtm-eec-product-position="'+itemKey+'"\n' +
            '               data-gtm-eec-product-list="محصولات صفحه سرچ">\n' +
            '                '+widgetTitle+'\n' +
            '            </a>\n' +
            '        </div>\n' +
            '        <div class="a--block-detailesWrapper">\n' +
                        priceHtml +
            '        </div>\n' +
            '    </div>\n' +
            '</div>';
    }

    function priceToStringWithTwoDecimal(price) {
        return parseFloat((Math.round(price * 100) / 100).toString()).toFixed(2);
    }

    function getVideoCarouselItem(data) {
        let inputData = {
            widgetPic: (typeof (data.photo) === 'undefined' || data.photo == null) ? data.thumbnail + '?w=444&h=250' : data.photo + '?w=444&h=250',
            widgetTitle: data.name,
            widgetAuthor: {
                photo: (typeof (data.author.photo) === 'undefined' || data.author.photo == null) ? null : data.author.photo,
                name: data.author.firstName,
                full_name: data.author.full_name
            },
            widgetCount: false,
            widgetLink: data.url
        };

        return getCommonCarouselItem(inputData);
    }

    function getSetCarouselItem(data) {
        let inputData = {
            widgetPic: (typeof (data.photo) === 'undefined' || data.photo == null) ? data.thumbnail + '?w=253&h=142' : data.photo + '?w=253&h=142',
            widgetTitle: data.name,
            widgetAuthor: {
                photo : data.author.photo,
                name: data.author.firstName,
                full_name: data.author.full_name
            },
            widgetCount: data.contents_count,
            widgetLink: data.url
        };


        let widgetPic = inputData.widgetPic,
            widgetTitle = inputData.widgetTitle,
            widgetAuthor = inputData.widgetAuthor,
            widgetCount = inputData.widgetCount,
            widgetLink = inputData.widgetLink;

        return '' +
            '<div class="item carousel a--block-item a--block-type-set">\n' +
            '    <div class="a--block-imageWrapper">\n' +
            '        \n' +
            '        <div class="a--block-detailesWrapper">\n' +
            '    \n' +
            '            <div class="a--block-set-count">\n' +
            '                <span class="a--block-set-count-number">'+widgetCount+'</span>\n' +
            '                <br>\n' +
            '                <span class="a--block-set-count-title">محتوا</span>\n' +
            '                <br>\n' +
            '                <a href="'+widgetLink+'" class="a--block-set-count-icon">\n' +
            '                    <i class="fa fa-bars"></i>\n' +
            '                </a>\n' +
            '            </div>\n' +
            '            \n' +
            '            <div class="a--block-set-author-pic d-none">\n' +
            '                <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" class="m-widget19__img lazy-image" data-src="'+widgetAuthor.photo+'" alt="'+widgetAuthor.full_name+'" width="40" height="40">\n' +
            '            </div>\n' +
            '            \n' +
            '    \n' +
            '        </div>\n' +
            '        \n' +
            '        <a href="'+widgetLink+'" class="a--block-imageWrapper-image">\n' +
            '            <img src="https://cdn.alaatv.com/loder.jpg?w=16&h=9" data-src="'+widgetPic+'" alt="'+widgetTitle+'" class="a--block-image lazy-image" width="453" height="254" />\n' +
            '        </a>\n' +
            '    </div>\n' +
            '    \n' +
            '    <div class="a--block-infoWrapper">\n' +
            '        \n' +
            '        <div class="a--block-titleWrapper">\n' +
            '            <a href="'+widgetLink+'" class="m-link">\n' +
            '                <h6>\n' +
            '                    <span class="m-badge m-badge--info m-badge--dot"></span>\n' +
            '                    '+widgetTitle+'\n' +
            '                </h6>\n' +
            '            </a>\n' +
            '        </div>\n' +
            '        \n' +
            '    </div>\n' +
            '    \n' +
            '</div>';
    }

    function getCommonCarouselItem(data) {

        let widgetPic = data.widgetPic,
            widgetTitle = data.widgetTitle,
            widgetAuthor = data.widgetAuthor,
            widgetCount = data.widgetCount,
            widgetLink = data.widgetLink,
            widgetAuthorFullameHtml = '';
        if (widgetAuthor.full_name.trim().length > 0) {
            widgetAuthorFullameHtml =
                '        <div class="a--block-detailesWrapper">\n' +
                '            <div class="a--block-set-author-name">\n' +
                '                <span class="a--block-set-author-name-title">' +
                '                    <span class="m-badge m-badge--info m-badge--wide m-badge--rounded">\n' +
                '                        '+widgetAuthor.full_name+'\n' +
                '                    </span>' +
                '                </span>\n' +
                '            </div>\n' +
                '        </div>\n';
            }

        return '' +
            '<div class="item carousel a--block-item a--block-type-set">\n' +
            '    <div class="a--block-imageWrapper">\n' +
            '        <a href="'+widgetLink+'" class="btn btn-sm m-btn--pill btn-brand btnViewMore">\n' +
            '            <i class="fa fa-play"></i> / <i class="fa fa-cloud-download-alt"></i>\n' +
            '        </a>\n' +
            '        <a href="'+widgetLink+'" class="a--block-imageWrapper-image">\n' +
            '            <img src="https://cdn.alaatv.com/loder.jpg?w=16&h=9" data-src="'+widgetPic+'" alt="'+widgetTitle+'" class="a--block-image lazy-image" width="253" height="142" />\n' +
            '        </a>\n' +
            '    </div>\n' +
            '    <div class="a--block-infoWrapper">\n' +
            '        <div class="a--block-titleWrapper">\n' +
            '            <a href="'+widgetLink+'" class="m-link">\n' +
            '                <h6>\n' +
            '                    <span class="m-badge m-badge--info m-badge--dot"></span>\n' +
            '                    '+widgetTitle+'\n' +
            '                </h6>\n' +
            '            </a>\n' +
            '        </div>\n' +
            widgetAuthorFullameHtml +
            '    </div>\n' +
            '</div>';
    }

    function getPamphletItem(data) {

        var widgetActionLink = data.url;
        var widgetTitle = data.name;
        var widgetThumbnail = data.thumbnail;
        var widgetAuthorPhoto = '';
        var widgetAuthor = {
            photo: (data.author!==null) ? data.author.photo : '',
            name: (data.author!==null) ? data.author.firstName : '',
            full_name: (data.author!==null) ? data.author.full_name : ''
        };
        if (
            typeof widgetThumbnail !== 'undefined' &&
            widgetThumbnail !== null &&
            widgetThumbnail.length !== 0
        ) {
            widgetThumbnail =
                '<div class="m-widget4__img m-widget4__img--pic">\n' +
                '    <img src="' + widgetThumbnail + '" alt="' + widgetTitle + '">\n' +
                '</div>\n';
        } else {
            widgetThumbnail = '';
        }
        if (
            typeof widgetAuthor.photo !== 'undefined' &&
            widgetAuthor.photo !== null &&
            widgetAuthor.photo.length !== 0
        ) {
            widgetAuthorPhoto =
                '    <div class="m-widget4__img m-widget4__img--pic">\n' +
                '        <img src="' + widgetAuthor.photo + '" alt="">\n' +
                '    </div>\n';
        } else {
            widgetAuthorPhoto = '';
        }
        return '\n' +
            '<div class="m-widget4__item m--padding-top-5 m--padding-bottom-5">\n' +
            widgetAuthorPhoto +
            '    <div class="m-widget4__info">\n' +
            '            <span class="m-widget4__title">\n' +
            '                <a href="' + widgetActionLink + '" class="m-link">\n' +
            '                    ' + widgetTitle + '\n' +
            '                </a>\n' +
            '            </span>\n' +
            '        <br>\n' +
            '        <span class="m-widget4__sub">\n' +
            '                <a href="' + widgetActionLink + '" class="m-link">\n' +
            '                    ' + widgetAuthor.full_name + '\n' +
            '                </a>\n' +
            '            </span>\n' +
            '    </div>\n' +
            widgetThumbnail +
            '</div>';
    }

    function makeWidgetFromJsonResponse(data, type, itemKey) {
        switch (type) {
            case 'product':
                return getProductCarouselItem(data, itemKey);
            case 'video':
                return getVideoCarouselItem(data);
            case 'set':
                return getSetCarouselItem(data);
            case 'pamphlet':
                return getPamphletItem(data);
            case 'article':
                return getPamphletItem(data);
        }
    }

    function addContentToVerticalWidget(vw, data, type) {
        $.each(data, function (index, value) {
            vw.append(makeWidgetFromJsonResponse(value, type, index));
        });
    }
    function addContentToOwl(owl, data, type) {
        $.each(data, function (index, value) {
            owl.trigger('add.owl.carousel',
                [
                    // jQuery('<div class="owl-item">' + makeWidgetFromJsonResponse(value) + '</div>');
                    jQuery(makeWidgetFromJsonResponse(value, type, index))
                ]
            );
        });
        owl.trigger('refresh.owl.carousel');
    }
    function ajaxSetup() {
        $.ajaxSetup({
            cache: false,
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            }
        });
    }
    function loadData(owl , action,type,callback) {
        ajaxSetup();

        $.ajax({
                type: "GET",
                url: action,
                accept: "application/json; charset=utf-8",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                statusCode: {
                    200: function (response) {
                        removeLoadingItem(owl, type);

                        if (type === 'product' || type === 'video' || type === 'set') {
                            addContentToOwl(owl, response.result[type].data, type);
                        } else if (type === 'pamphlet') {
                            loadPamphletFromJson(response.result[type]);
                        } else if (type === 'article') {
                            loadArticleFromJson(response.result[type]);
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
                    500: function () {
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
                articleAjaxLock = 1;
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
                articleAjaxLock = 0;
                break;
        }
    }

    function load(event, nextPageUrl, owl, owlType, callback) {

        if (owlType === 'product' || owlType === 'video' || owlType === 'set') {
            var perPage = typeof (owl.data("per-page")) === "number" ? owl.data("per-page") : 6;

            if (
                nextPageUrl !== null && nextPageUrl.length !== 0 &&
                (
                    (
                        event.namespace && event.property.name === 'position' &&
                        event.property.value >= event.relatedTarget.items().length - perPage
                    ) ||
                    (
                        event === '5moreProduct'
                    )
                )
            ) {
                lockAjax(owlType);
                addLoadingItem(owl, owlType);
                // load, add and update
                loadData(owl, nextPageUrl, owlType, callback);
            }
        } else if (owlType === 'pamphlet' || owlType === 'article') {

            if (
                nextPageUrl !== null && nextPageUrl.length !== 0
            ) {
                lockAjax(owlType);
                addLoadingItem(owl, owlType);
                loadData(owl, nextPageUrl, owlType, callback);
            }
        }

    }

    function loadProductFromJson(data) {
        addContentToOwl($('#product-carousel.owl-carousel'), data.data, 'product');
        $('#owl--js-var-next-page-product-carousel-url').val(decodeURI(data.next_page_url));
    }

    function initProduct(data, isInit) {
        loadProductFromJson(data);
        if (isInit) {
            load5moreProductInInit();
        }

        $('#product-carousel.owl-carousel').on('change.owl.carousel', function (event) {
            var owlType = 'product';
            var nextPageUrl = $('#owl--js-var-next-page-product-carousel-url');
            var owl = $(this);
            if (!productAjaxLock && nextPageUrl.val() !== "null") {
                load(event, nextPageUrl.val(), owl, owlType, function (newPageUrl) {
                    if (newPageUrl === null) {
                        newPageUrl = '';
                    }
                    $('#owl--js-var-next-page-product-carousel-url').val(decodeURI(newPageUrl));
                    unLockAjax(owlType);
                });
            }
        });
    }

    function load5moreProductInInit() {
        var owlType = 'product';
        var nextPageUrl = $('#owl--js-var-next-page-product-carousel-url');
        var owl = $('#product-carousel.owl-carousel');
        if (!productAjaxLock && nextPageUrl.val() !== "null") {
            load('5moreProduct', nextPageUrl.val(), owl, owlType, function (newPageUrl) {
                if (newPageUrl === null) {
                    newPageUrl = '';
                }
                $('#owl--js-var-next-page-product-carousel-url').val(decodeURI(newPageUrl));
                unLockAjax(owlType);
                gtmEecProductObserver.observe();
                imageObserver.observe();
            });
        }
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
            var owlType="video";
            var nextPageUrl = $('#owl--js-var-next-page-video-url');
            var owl = $(this);

            if( !videoAjaxLock && nextPageUrl.val() !== "null") {

                load(event, nextPageUrl.val(), owl, owlType,function (newPageUrl) {
                    $('#owl--js-var-next-page-video-url').val(decodeURI(newPageUrl));
                    unLockAjax(owlType);
                });
            }
        });
    }

    function loadSetFromJson(data) {
        addContentToOwl($('#set-carousel.owl-carousel'), data.data, 'set');
        $('#owl--js-var-next-page-set-url').val(decodeURI(data.next_page_url));
    }

    function initSet(data) {
        loadSetFromJson(data);
        $('#set-carousel.owl-carousel').on('change.owl.carousel', function(event) {
            var owlType="set";
            var nextPageUrl = $('#owl--js-var-next-page-set-url');
            var owl = $(this);
            if( !setAjaxLock && nextPageUrl.val() !== "null") {
                load(event, nextPageUrl.val(), owl, owlType,function (newPageUrl) {
                    $('#owl--js-var-next-page-set-url').val(decodeURI(newPageUrl));
                    unLockAjax(owlType);
                });
            }
        });
    }

    function loadPamphletFromJson(data) {
        if (data === null) {
            return false;
        }

        var pamphletverticalWidget = '#pamphlet-vertical-widget';

        // $('.pamphlet-lastITemSensor').remove();
        $('.pamphlet-wraperShowMore').remove();
        addContentToVerticalWidget($(pamphletverticalWidget), data.data, 'pamphlet');
        // $(pamphletverticalWidget).append('<div class="pamphlet-lastITemSensor"></div>');
        if (data.next_page_url !== null) {
            $(pamphletverticalWidget).append('<div class="pamphlet-wraperShowMore text-center"><button class="btn m-btn--pill m-btn--air btn-primary pamphlet-btnShowMore animated infinite heartBeat">نمایش بیشتر</button></div>');
        }
        $('#vertical-widget--js-var-next-page-pamphlet-url').val(decodeURI(data.next_page_url));
    }

    function initPamphlet(data) {
        loadPamphletFromJson(data);

        $(document).on('click', '.pamphlet-btnShowMore', function () {
            var pamphletBtnShowMore = '.pamphlet-btnShowMore';
            $(pamphletBtnShowMore).fadeOut();
            var vwType = 'pamphlet';
            var nextPageUrl = $('#vertical-widget--js-var-next-page-pamphlet-url');
            var vw = $('#pamphlet-vertical-widget');

            if (!pamphletAjaxLock && nextPageUrl.val() !== "null") {
                load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
                    $('#vertical-widget--js-var-next-page-pamphlet-url').val(decodeURI(newPageUrl));
                    unLockAjax(vwType);
                    $(pamphletBtnShowMore).fadeOut(0).fadeIn();
                });
            }
        });

        // $(window).scroll(function () {
        //     if (isScrolledIntoView($('.pamphlet-lastITemSensor'))) {
        //
        //         var vwType = 'pamphlet';
        //         var nextPageUrl = $('#vertical-widget--js-var-next-page-pamphlet-url');
        //         var vw = $('#pamphlet-vertical-widget');
        //
        //         if (!pamphletAjaxLock && nextPageUrl.val() !== "null") {
        //             load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
        //                 $('#vertical-widget--js-var-next-page-pamphlet-url').val(decodeURI(newPageUrl));
        //                 unLockAjax(vwType);
        //             });
        //         }
        //
        //     }
        // });
    }

    function loadArticleFromJson(data) {
        if (data === null) {
            return false;
        }
        var articleVerticalWidget = '#article-vertical-widget';
        // $('#pamphlet-vertical-widget').find('.article-lastITemSensor').remove();
        $('.article-wraperShowMore').remove();
        addContentToVerticalWidget($(articleVerticalWidget), data.data, 'article');
        if (data.next_page_url !== null) {
            $(articleVerticalWidget).append('<div class="article-wraperShowMore text-center"><button class="btn m-btn--pill m-btn--air btn-primary article-btnShowMore animated infinite heartBeat">نمایش بیشتر</button></div>');
        }
        $('#vertical-widget--js-var-next-page-article-url').val(decodeURI(data.next_page_url));
        // $('#pamphlet-vertical-widget').append('<div class="article-lastITemSensor"></div>');
    }

    function initArticle(data) {
        loadArticleFromJson(data);


        $(document).on('click', '.article-btnShowMore', function () {
            $('.article-btnShowMore').fadeOut();
            var vwType = 'pamphlet';
            var nextPageUrl = $('#vertical-widget--js-var-next-page-article-url');
            var vw = $('#article-vertical-widget');
            if (!articleAjaxLock && nextPageUrl.val() !== "null") {
                load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
                    $('#vertical-widget--js-var-next-page-article-url').val(decodeURI(newPageUrl));
                    unLockAjax(vwType);
                    $('.article-btnShowMore').fadeOut(0).fadeIn();
                });
            }
        });

        // $(window).scroll(function () {
        //     if (isScrolledIntoView($('.article-lastITemSensor'))) {
        //
        //         var vwType = 'pamphlet';
        //         var nextPageUrl = $('#vertical-widget--js-var-next-page-article-url');
        //         var vw = $('#article-vertical-widget');
        //
        //         if (!articleAjaxLock && nextPageUrl.val() !== "null") {
        //             load(event, nextPageUrl.val(), vw, vwType, function (newPageUrl) {
        //                 $('#vertical-widget--js-var-next-page-article-url').val(decodeURI(newPageUrl));
        //                 unLockAjax(vwType);
        //             });
        //         }
        //
        //     }
        // });
    }

    function loadAjaxContent(contentData, isInit) {

        var hasPamphletOrArticle = false;
        var hasItem = false;
        var hasPamphlet = false;
        if (typeof contentData.product !== 'undefined' && contentData.product !== null && contentData.product.total>0) {
            initProduct(contentData.product, isInit);
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
            hasPamphlet = true;
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
            if (!hasPamphlet) {
                $('#article-vertical-tab a').trigger('click');
            }
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

        var contentSearchFilter = '#contentSearchFilter';
        $(contentSearchFilter).removeClass('lockActiveStep');
        if (!hasItem) {
            $('.notFoundMessage').fadeIn();
            var contentSearchFilterSelectorItem = '#contentSearchFilter .selectorItem[data-select-active="true"]';
            if (
                typeof $(contentSearchFilterSelectorItem).attr('data-select-order') !== 'undefined' &&
                parseInt($(contentSearchFilterSelectorItem).attr('data-select-order')) !== 4
            ) {
                $(contentSearchFilter).addClass('lockActiveStep');
            }
        } else {
            $('.notFoundMessage').fadeOut();
        }


        gtmEecProductObserver.observe();
        imageObserver.observe();
    }

    function addLoadingItem(owl, owlType) {
        if (owlType === 'product' || owlType === 'video' || owlType === 'set') {
            var loadingHtml = '<div class="a--owlCarouselLoading"><div style="width: 30px; display: inline-block;" class="m-loader m-loader--primary m-loader--lg"></div></div>';
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
            var lastIndex = owl.find('.owl-item').length;
            owl.trigger('remove.owl.carousel', [lastIndex - 1])
                .trigger('refresh.owl.carousel');
        } else if (owlType === 'pamphlet' || owlType === 'article') {
            owl.find('.a--vw-Loading').remove();
        }
    }

    // function isScrolledIntoView(elem) {
    //     if (elem.length === 0) {
    //         return false;
    //     }
    //     var docViewTop = $(window).scrollTop();
    //     var docViewBottom = docViewTop + $(window).height();
    //     var elemTop = $(elem).offset().top;
    //     var elemBottom = elemTop + $(elem).height();
    //     return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom) && (elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    // }

    function clearOwlcarousel(owl) {
        var length = owl.find('.item').length;
        while (length > 0) {
            owl.trigger('remove.owl.carousel', 0)
                .trigger('refresh.owl.carousel');
            length = owl.find('.item').length;
        }
    }

    return {
        init: function (contentData) {
            loadAjaxContent(contentData, true);
        },
        loadData: function (contentData) {
            loadAjaxContent(contentData, true);
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

var CustomInitMultiLevelSearch = function () {

    var filterData = null;
    var tags = null;
    var tagsFromController = null;

    var selectedVlues = null;

    var emptyFields = null;

    function addUnderLine(string) {
        if (typeof string === 'undefined' || string === null) {
            return '';
        }
        return string.replace(/ /g, '_');
    }

    function removeUnderLine(string) {
        return string.replace(/_/g, ' ');
    }

    function getTags() {
        // var url = new URL(window.location.href);
        // var tags = url.searchParams.getAll("tags[]");
        var tags = getUrlParams(window.location.href, /(tags\[[0-9]*\])/g);
        var tagValue = [];
        for (var index in tags) {
            tagValue.push(tags[index].value);
        }
        return tagValue;
    }

    function getUrlParams(url, param) {
        var res = url.match(/(\?|\&)([^=]+)\=([^&]+)/g);
        var params = [];
        for (var index in res) {
            var tagItem = res[index];
            var paramValue = tagItem.replace(/(\?|\&)([^=]+)\=/g, '');
            var paramName = tagItem.replace('='+paramValue, '').replace('?', '').replace('&', '');
            if(typeof param !== 'undefined' && paramName.search(param) !== -1) {
                params.push({
                    name: paramName,
                    value: paramValue
                });
            } else {
                params.push({
                    name: paramName,
                    value: paramValue
                });
            }
        }
        return params;
    }

    function activeFilter(filterClass) {
        $('.selectorItem').attr('data-select-active', 'false');
        $('.selectorItem.'+filterClass).attr('data-select-active', 'true');
    }

    function getTagsFromControllerOrUrl() {
        var tags = [];
        if (tagsFromController !== null && typeof tagsFromController !== 'undefined') {
            tags = tagsFromController;
        } else {
            tags = getTags();
        }
        return tags;
    }

    function setSelectedNezamFromTags() {
        var selectedVal = filterData.nezam[1];
        var existInTags = false;

        var tags = getTagsFromControllerOrUrl();

        for (var tagsIndex in tags) {
            for (var nezamIndex in filterData.nezam) {
                var item = filterData.nezam[nezamIndex];
                if (item.value === decodeURI(tags[tagsIndex])) {
                    selectedVal = item;
                    activeFilter('maghtaSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.nezam = selectedVal;
        if (existInTags) {
            var name = removeUnderLine(selectedVal.value);
            $('.selectorItem.nezamSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedMaghtaFromTags() {

        var selectedNezam = selectedVlues.nezam;
        var selectedMaghta = filterData[selectedNezam.maghtaKey][0];

        var existInTags = false;
        var tags = getTagsFromControllerOrUrl();

        for (var tagsIndex in tags) {
            for (var maghtaIndex in filterData[selectedNezam.maghtaKey]) {
                var item = filterData[selectedNezam.maghtaKey][maghtaIndex];
                if (item.value === decodeURI(tags[tagsIndex])) {
                    selectedMaghta = item;
                    activeFilter('majorSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues[selectedNezam.maghtaKey] = selectedMaghta;
        if (existInTags) {
            var name = removeUnderLine(selectedMaghta.value);
            $('.selectorItem.maghtaSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedMajorFromTags() {
        var selectedVal = filterData.major[0];
        var existInTags = false;

        var tags = getTagsFromControllerOrUrl();

        for (var tagsIndex in tags) {
            for (var majorIndex in filterData.major) {
                var value = filterData.major[majorIndex].value;
                if (value === decodeURI(tags[tagsIndex])) {
                    selectedVal = filterData.major[majorIndex];
                    activeFilter('lessonSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.major = selectedVal;
        if (existInTags) {
            var name = removeUnderLine(selectedVal.value);
            $('.selectorItem.majorSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedLessonFromTags() {
        var selectedMajor = selectedVlues.major;
        var selectedLesson = filterData[selectedMajor.lessonKey][0];
        var existInTags = false;

        var tags = getTagsFromControllerOrUrl();

        for (var tagsIndex in tags) {
            for (var lessonIndex in filterData[selectedMajor.lessonKey]) {
                var item = filterData[selectedMajor.lessonKey][lessonIndex];
                if (item.value === decodeURI(tags[tagsIndex])) {
                    selectedLesson = item;
                    activeFilter('teacherSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.lesson = selectedLesson;
        if (existInTags) {
            var name = removeUnderLine(selectedLesson.index);
            $('.selectorItem.lessonSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function setSelectedTeacherFromTags() {
        var selectedLesson = selectedVlues.lesson.value;
        var selectedTeacher = filterData.lessonTeacher[selectedLesson][0];
        var existInTags = false;

        var tags = getTagsFromControllerOrUrl();

        for (var tagsIndex in tags) {
            for (var teacherIndex in filterData.lessonTeacher[selectedLesson]) {
                var item = filterData.lessonTeacher[selectedLesson][teacherIndex];
                if (item.value === decodeURI(tags[tagsIndex])) {
                    selectedTeacher = item;
                    activeFilter('teacherSelector');
                    existInTags = true;
                    break;
                }
            }
        }
        selectedVlues.teacher = selectedTeacher;
        if (existInTags) {
            var name = removeUnderLine(selectedTeacher.value);
            $('.selectorItem.teacherSelector').attr('data-select-value', name);
            return name;
        } else {
            return false;
        }
    }

    function initSelectorItem(selectorClass, selectedValue, filterDataArray) {
        $(selectorClass).find('.subItem').remove();
        appendSubItems(filterDataArray, selectorClass, selectedValue);
        fadeOutSubItemsIfDisplayTypeIsSelect2(selectorClass, filterDataArray, selectedValue);
    }

    function initNezam() {
        var selectorClass = '.nezamSelector';
        var selectedValue = setSelectedNezamFromTags();
        var filterDataArray = filterData.nezam;
        initSelectorItem(selectorClass, selectedValue, filterDataArray);
        setSelectedNezamFromTags();
    }

    function initMaghta() {
        var selectorClass = '.maghtaSelector';
        var selectedValue = setSelectedMaghtaFromTags();
        var maghta = selectedVlues.nezam.maghtaKey;
        var filterDataArray = filterData[maghta];
        initSelectorItem(selectorClass, selectedValue, filterDataArray);
        setSelectedMaghtaFromTags();
    }

    function initMajor() {
        var selectorClass = '.majorSelector';
        var selectedValue = setSelectedMajorFromTags();
        var filterDataArray = filterData.major;
        initSelectorItem(selectorClass, selectedValue, filterDataArray);
    }

    function initLessons() {
        var selectorClass = '.lessonSelector';
        var selectedValue = setSelectedLessonFromTags();
        var major = selectedVlues.major.lessonKey;
        var filterDataArray = filterData[major];
        initSelectorItem(selectorClass, selectedValue, filterDataArray);
    }

    function initTeacher() {
        var selectorClass = '.teacherSelector';
        var selectedValue = setSelectedTeacherFromTags();
        var lesson = selectedVlues.lesson.value;
        var filterDataArray = filterData.lessonTeacher[lesson];
        initSelectorItem(selectorClass, selectedValue, filterDataArray);
    }

    function fadeOutSubItemsIfDisplayTypeIsSelect2(selectorClass, filterDataArray, selectedValue) {
        var showType = getDisaplayType(selectorClass);
        if (showType === 'select2') {
            var selectorOrder = $(selectorClass).attr('data-select-order');
            if (selectedValue === false) {
                if ($('.filterNavigationStep[data-select-order="'+selectorOrder+'"]').hasClass('current')) {
                    selectedValue = null;
                } else {
                    selectedValue = $(selectorClass).attr('data-select-value');
                }
            }
            $(selectorClass).find('.subItem').fadeOut(0);
            $(selectorClass).find('.form-control.select2').empty();
            for (var index in filterDataArray) {
                var name = filterDataArray[index].value;
                name = removeUnderLine(name);
                if (selectedValue === name) {
                    $(selectorClass).find('.form-control.select2').append("<option value='"+name+"' selected>"+name+"</option>");
                } else {
                    $(selectorClass).find('.form-control.select2').append("<option value='"+name+"'>"+name+"</option>");
                }
            }
        } else if (showType === 'grid') {
            $(selectorClass).find('.subItem').fadeIn(0);
        }
    }

    function appendSubItems(filterDataArray, selectorClass, selectedValue) {
        for (var index in filterDataArray) {
            var name = filterDataArray[index].value;
            name = removeUnderLine(name);
            if (selectedValue === name) {
                $(selectorClass).append('<div class="col subItem" selected="selected">'+name+'</div>');
            } else {
                $(selectorClass).append('<div class="col subItem">'+name+'</div>');
            }
        }
    }

    function checkEmptyField(string) {
        for (var index in emptyFields) {
            var item = emptyFields[index];
            if (addUnderLine(item) === addUnderLine(string)) {
                return '';
            }
        }
        return addUnderLine(string);
    }

    function getDisaplayType(selectorClass) {
        var showType = $(selectorClass).data('select-display');
        if (typeof showType === 'undefined') {
            showType = 'grid';
        }
        return showType;
    }

    function initSelectedValues() {
        selectedVlues = {
            nezam: filterData.nezam[1],
            maghta: filterData.maghtaJadid[0],
            major: filterData.major[0],
            lesson: filterData.allLessons[0],
            teacher: filterData.lessonTeacher.همه_دروس[0].value
        };
    }

    function initEmptyFields() {
        emptyFields = [
            filterData.lessonTeacher.همه_دروس[0].value,
            filterData.allLessons[0].value,
            filterData.major[0].value,
            filterData.maghtaGhadim[0].value,
            filterData.maghtaJadid[0].value
        ];
    }

    return {
        initFilters: function (contentSearchFilterData, inputTags) {
            filterData = contentSearchFilterData;
            tagsFromController = inputTags;
            initSelectedValues();
            initNezam();
            initMaghta();
            initMajor();
            initLessons();
            initTeacher();
            tagsFromController = null;
        },
        checkEmptyField: function (contentSearchFilterData, string) {
            initEmptyFields();
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

    function refreshTags(contentSearchFilterData) {
        var pageTagsListBadge = '.pageTags .m-list-badge__items';
        $(pageTagsListBadge).find('.m-list-badge__item').remove();

        var searchFilterData = MultiLevelSearch.getSelectedData();
        var url = document.location.href.split('?')[0];
        var tagsValue = '';
        for (var index in searchFilterData) {
            var selectedText = searchFilterData[index].selectedText;
            selectedText = CustomInitMultiLevelSearch.checkEmptyField(contentSearchFilterData, selectedText);
            if (typeof selectedText !== 'undefined' && selectedText !== null && selectedText !== '') {
                tagsValue += '&tags[]=' + selectedText;
                $(pageTagsListBadge).append(
                    '<span class="m-list-badge__item m-list-badge__item--focus m--padding-10 m--margin-top-5 m--block-inline  tag_0">\n' +
                    '    <a class="m-link m--font-light" href="'+url+'?tags[]='+CustomInitMultiLevelSearch.addUnderLine(selectedText)+'">'+CustomInitMultiLevelSearch.removeUnderLine(selectedText)+'</a>\n' +
                    '</span>');
            }
        }
        if (tagsValue !== '') {
            tagsValue = tagsValue.substr(1);
        }
        url += '?' + tagsValue;

        // window.history.pushState('data to be passed', 'Title of the page', url);
        // The above will add a new entry to the history so you can press Back button to go to the previous state.
        // To change the URL in place without adding a new entry to history use
        history.replaceState('data to be passed', 'Title of the page', url);

        return tagsValue;
    }

    function runWaiting() {
        Alaasearch.clearFields();
        $('.notFoundMessage').fadeOut();
        $('#product-carousel-warper').fadeIn();
        $('#video-carousel-warper').fadeIn();
        $('#set-carousel-warper').fadeIn();
        // $('#pamphlet-vertical-tabpanel').fadeIn();
        $('#pamphlet-vertical-tabpanel').removeClass('d-none');
        // $('#article-vertical-tabpanel').fadeIn();
        $('#article-vertical-tabpanel').removeClass('d-none');
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

    function getNewDataBaseOnTags(contentSearchFilterData) {

        runWaiting();

        // document.location.href

        var tagsValue = refreshTags(contentSearchFilterData);


        var originUrl = document.location.origin;
        var pathnameUrl = document.location.pathname;

        $.ajax({
            type: 'GET',
            // url: document.location.href,
            url: originUrl+pathnameUrl+'?'+tagsValue,
            data: {},
            dataType: 'json',
            success: function (data) {
                if (typeof data === 'undefined' || data.error) {

                    var message = '';
                    if (typeof data !== 'undefined') {
                        message = data.error.message;
                    }

                    toastr.error('خطای سیستمی رخ داده است.' + '<br>' + message);


                } else {
                    Alaasearch.loadData(data.result);
                }
                stopWaiting();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = '';
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
        refreshTags: function (contentSearchFilterData) {
            refreshTags(contentSearchFilterData);
        },
        getNewDataBaseOnTags: function (contentSearchFilterData) {
            getNewDataBaseOnTags(contentSearchFilterData);
        }
    };
}();

// var GtmEecImpression = function () {
//
//     function view() {
//         var countOfExistingProductInCarousel = $('#product-carousel.owl-carousel').find('.owl-item').length;
//         if (countOfExistingProductInCarousel <= 5) {
//             return;
//         }
//         var gtmEecImpressions = [];
//         $('#product-carousel.owl-carousel').find('.owl-item.active .m-widget19__content .a--gtm-eec-product-click').each(function (index, value) {
//             gtmEecImpressions.push({
//                 id:       $(this).data('gtm-eec-product-id'),      // (String) The SKU of the product. Example: 'P12345'
//                 name:     $(this).data('gtm-eec-product-name'),    // (String) The name of the product. Example: 'T-Shirt'
//                 price:    $(this).data('gtm-eec-product-price'),
//                 brand:    $(this).data('gtm-eec-product-brand'),   // (String) The brand name of the product. Example: 'NIKE'
//                 category: $(this).data('gtm-eec-product-category'),// (String) Product category of the item. Can have maximum five levels of hierarchy. Example: 'clothes/shirts/t-shirts'
//                 variant:  $(this).data('gtm-eec-product-variant'), // (String) What variant of the main product this is. Example: 'Large'
//                 list:     $(this).data('gtm-eec-product-list'),
//                 position: $(this).data('gtm-eec-product-position'),// (Integer) The position of the impression that was clicked. Example: 1
//             });
//         });
//         GAEE.impressionView(gtmEecImpressions);
//     }
//
//     return {
//         view: function () {
//             view();
//         }
//     };
// }();

$('.notFoundMessage').fadeOut(0);

jQuery(document).ready(function () {

    $.ajaxSetup({ cache: false });

    var owl = jQuery('.a--owl-carousel-type-1');
    owl.each(function () {
        var itemId = $(this).attr('id');
        var responsive = {
            0:{
                items:1,
            },
            400:{
                items:2,
            },
            600:{
                items:4,
            },
            800:{
                items:5,
            },
            1190:{
                items:4
            },
            1400:{
                items:5
            }
        };
        var config = {
            stagePadding: 0,
            loop: false,
            rtl:true,
            nav: true,
            dots: false,
            margin:10,
            mouseDrag: true,
            touchDrag: true,
            pullDrag: true,
            lazyLoad:false,
            responsiveClass:true,
            responsive: responsive
        };
        if (itemId === 'product-carousel') {
            function slideChanged1(event) {
                gtmEecProductObserver.observe();
                imageObserver.observe();
            }
            responsive = {
                0:{
                    items:1,
                },
                400:{
                    items:2,
                },
                600:{
                    items:6,
                },
                800:{
                    items:8,
                },
                1190:{
                    items:6
                },
                1400:{
                    items:8
                }
            };
            config.onTranslated = slideChanged1;
            config.responsive = responsive;
            config.lazyLoad = true;
        } else {
            function slideChanged2(event) {
                imageObserver.observe();
            }
            config.onTranslated = slideChanged2;
        }
        $(this).owlCarousel(config);
    });


    Alaasearch.init(contentData);

    CustomInitMultiLevelSearch.initFilters(contentSearchFilterData, tags);

    MultiLevelSearch.init({
        selectorId: 'contentSearchFilter'
    }, function () {
        GetAjaxData.refreshTags(contentSearchFilterData);
        CustomInitMultiLevelSearch.initFilters(contentSearchFilterData);
        GetAjaxData.getNewDataBaseOnTags(contentSearchFilterData);
    },  function () {
        GetAjaxData.refreshTags(contentSearchFilterData);
        CustomInitMultiLevelSearch.initFilters(contentSearchFilterData);
    });

});