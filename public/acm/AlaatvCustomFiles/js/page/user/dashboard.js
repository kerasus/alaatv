var LoadContentSet = function () {

    let lockLoadNextPage = false;

    function loadNewData(url) {
        showSelectedProduct(url);
        loadList($('#searchResult_video .searchResult .listType'), '', 'video');
        loadList($('#searchResult_pamphlet .a--list2'), '', 'pamphlet');
        ajax(url, loadNewContentSetList);
    }

    function loadNextPage(type) {
        ajax(getNextPageUrl(type), appendToContentSetList);
    }

    function showTabPage(type) {
        if (type === 'video') {
            openVideoTabPage();
        } else {
            openPamohletTabPage();
        }
    }

    function loadNewContentSetList(data) {
        var listHtmlData = createListHtmlData(data);
        handleVideoAndPamphletListForNewContentSet($('#searchResult_video .searchResult .listType'), listHtmlData.video, 'video');
        handleVideoAndPamphletListForNewContentSet($('#searchResult_pamphlet .a--list2'), listHtmlData.pamphlet, 'pamphlet');
        imageObserver.observe();
    }

    function handleVideoAndPamphletListForNewContentSet($list, listHtmlData, type) {
        loadList($list, listHtmlData, type);
        checkShowLoadMoreBtn(type);
        checkNoContent(type);
    }

    function loadList($list, data, type) {
        $list.html(data);
    }

    function appendToContentSetList(data) {
        var listHtmlData = createListHtmlData(data);
        appendList($('#searchResult_video .searchResult .listType'), listHtmlData.video, 'video');
        appendList($('#searchResult_pamphlet .a--list2'), listHtmlData.pamphlet, 'pamphlet');
        imageObserver.observe();
    }

    function appendList($list, data, type) {
        $list.append(data);
        checkShowLoadMoreBtn(type);
    }

    function checkShowLoadMoreBtn(type) {
        var nextPageUrl = getNextPageUrl(type);
        if (nextPageUrl.trim().length > 0) {
            $('.btnLoadMore[data-content-type="' + type + '"]').fadeIn(0);
        } else {
            $('.btnLoadMore[data-content-type="' + type + '"]').fadeOut(0);
        }
    }

    function checkNoContent(type) {
        if (type==='video') {
            checkNoVideo();
        } else if (type==='pamphlet') {
            checkNoPamphlet();
        }
    }

    function checkNoVideo() {
        if ($('#searchResult_video .searchResult .listType .item').length > 0) {
            showVideoTabPage();
            hideNoVideoMessage();
        } else {
            hideVideoTabPage();
            $('.noVideoMessage').fadeIn();
        }
    }

    function checkNoPamphlet() {
        if ($('#searchResult_pamphlet .a--list2 .a--list2-item').length > 0) {
            showPamohletTabPage();
            hideNoPamphleMessage();
        } else {
            hidePamohletTabPage();
            $('.noPamphletMessage').fadeIn();
        }
    }

    function hideNoVideoMessage() {
        $('.noVideoMessage').fadeOut(0);
    }

    function hideNoPamphleMessage() {
        $('.noPamphletMessage').fadeOut(0);
    }

    function createListHtmlData(data) {
        return {
            video: (typeof data.video === 'undefined') ? null : createVideoListHtmlData(data.video),
            pamphlet: (typeof data.pamphlet === 'undefined') ? null : createPamphletListHtmlData(data.pamphlet)
        };
    }

    function ajax(contentUrl, callback) {

        if (lockLoadNextPage || contentUrl.trim().length === 0) {
            return false;
        }

        showLoading();

        lockLoadNextPage = true;

        $.ajax({
            type: 'GET',
            url: contentUrl,
            data: {},
            dataType: 'json',
            success: function (data) {
                lockLoadNextPage = false;
                if (data.error) {
                } else {
                    callback(data.result);
                }
                hideLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                lockLoadNextPage = false;
                hideLoading();
            }
        });
    }

    function createVideoListHtmlData(data) {
        if (typeof data === 'undefined' || data === null) {
            setNextPageUrlVideo('');
            return '';
        }
        setNextPageUrlVideo(data.next_page_url);
        var htmlData = '',
            dataLength = data.data.length;
        for (var i = 0; i < dataLength; i++) {
            var item = data.data[i];
            htmlData += getVideoItem({
                src: item.thumbnail,
                title: item.name,
                link: item.url,
                setName: (typeof item.set !== 'undefined') ? item.set.name : '',
                lastUpdate: item.updated_at,
                order: item.order,
                file: item.file,
            });
        }
        return htmlData;
    }

    function createPamphletListHtmlData(data) {
        if (typeof data === 'undefined' || data === null) {
            setNextPageUrlPamphlet('');
            return '';
        }
        setNextPageUrlPamphlet(data.next_page_url);
        var htmlData = '',
            dataLength = data.data.length;
        for (var i = 0; i < dataLength; i++) {
            var item = data.data[i];
            htmlData += getPamphletItem({
                title: item.name,
                link: item.url,
                setName: (typeof item.set !== 'undefined') ? item.set.name : '',
                lastUpdate: item.updated_at,
                order: item.order,
                fileLink: (typeof item.file !== 'undefined') ? item.file.pamphlet[0].link : '',
            });
        }
        return htmlData;
    }

    function getVideoItem(data) {

        var videos = data.file.video,
            videosLength = videos.length,
            groupBtn = '<div class="btn-group m-btn-group" role="group">\n';
        for (var i = 0; i < videosLength; i++) {
            var link = videos[i].link,
                title = videos[i].caption;
            if (title==='کیفیت بالا') {
                groupBtn += '<a href="' + link + '?download=1"><button type="button" class="btn btn-success"><i class="fa fa-cloud-download-alt m--margin-right-5"></i> ' + title + '</button></a>';
            }
        }
        groupBtn += '<a href="'+data.link+'"><button type="button" class="btn btn-success"><i class="fa fa-ellipsis-h m--margin-right-5"></i> بیشتر</button></a>';
        groupBtn += '</div>\n';

        return '' +
            '<div class="item ">\n' +
            '    <div class="pic">\n' +
            '        <a href="' + data.link + '" class="d-block">\n' +
            '            <img src="https://cdn.alaatv.com/loder.jpg?w=1&h=1" data-src="' + data.src + '" alt="' + data.title + '" class="a--full-width lazy-image videoImage" width="253" height="142">\n' +
            '        </a>\n' +
            '    </div>\n' +
            '    <div class="content">\n' +
            '        <div class="title">\n' +
            '            <h2>\n' +
            '                <a href="' + data.link + '" class="m-link">\n' +
            '                    ' + data.title +
            '                </a>\n' +
            '            </h2>\n' +
            '        </div>\n' +
            '        <div class="detailes">\n' +
            '            <div class="videoDetaileWrapper">\n' +
            '                <div class="setName">\n' +
            '                    <span>\n' +
            '                        <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" class="style-scope yt-icon">\n' +
            '                            <path d="M3.67 8.67h14V11h-14V8.67zm0-4.67h14v2.33h-14V4zm0 9.33H13v2.34H3.67v-2.34zm11.66 0v7l5.84-3.5-5.84-3.5z" class="style-scope yt-icon"></path>\n' +
            '                        </svg>\n' +
            '                    </span>\n' +
            '                    <span> از دوره </span>\n' +
            '                    <span>' + data.setName + '</span>\n' +
            '                </div>\n' +
            '                <div class="updateTime">\n' +
            '                    <i class="fa fa-calendar-alt m--margin-right-5"></i>\n' +
            '                    <span>تاریخ بروزرسانی: </span>\n' +
            '                    <span>' + data.lastUpdate + '</span>\n' +
            '                </div>\n' +
            '                <div class="videoOrder">\n' +
            '                    <div class="videoOrder-title">جلسه</div>\n' +
            '                    <div class="videoOrder-number">' + data.order + '</div>\n' +
            '                    <div class="videoOrder-om"> اُم </div>\n' +
            '                </div>\n' +
            '            </div>\n' +
            groupBtn +
            '        </div>\n' +
            '    </div>\n' +
            '    <div class="itemHover"></div>\n' +
            '</div>';
    }

    function getPamphletItem(data) {
        return Alist2.getItem({
            link: data.fileLink,
            title: data.title,
            class: '',
            attr: '',
            info: '',
            desc: '',
            action:
                '        <a href="' + data.fileLink + '" class="downloadPamphletIcon">\n' +
                '            <i class="fa fa-cloud-download-alt"></i>\n' +
                '        </a>\n',
            img:
                '        <svg width="50" height="50" viewBox="-79 0 512 512" xmlns="http://www.w3.org/2000/svg">' +
                '            <path d="m353.101562 485.515625h-353.101562v-485.515625h273.65625l79.445312 79.449219zm0 0" fill="#e3e4d8"/>' +
                '            <path d="m273.65625 0v79.449219h79.445312zm0 0" fill="#d0cebd"/>' +
                '            <path d="m0 353.101562h353.101562v158.898438h-353.101562zm0 0" fill="#b53438"/>' +
                '            <g fill="#fff">' +
                '                <path d="m52.964844 485.515625c-4.871094 0-8.828125-3.953125-8.828125-8.824219v-88.277344c0-4.875 3.957031-8.828124 8.828125-8.828124 4.875 0 8.828125 3.953124 8.828125 8.828124v88.277344c0 4.871094-3.953125 8.824219-8.828125 8.824219zm0 0"/>' +
                '                <path d="m300.136719 397.242188h-52.964844c-4.871094 0-8.828125-3.957032-8.828125-8.828126 0-4.875 3.957031-8.828124 8.828125-8.828124h52.964844c4.875 0 8.828125 3.953124 8.828125 8.828124 0 4.871094-3.953125 8.828126-8.828125 8.828126zm0 0"/>' +
                '                <path d="m300.136719 441.378906h-52.964844c-4.871094 0-8.828125-3.953125-8.828125-8.828125 0-4.871093 3.957031-8.828125 8.828125-8.828125h52.964844c4.875 0 8.828125 3.957032 8.828125 8.828125 0 4.875-3.953125 8.828125-8.828125 8.828125zm0 0"/>' +
                '                <path d="m247.171875 485.515625c-4.871094 0-8.828125-3.953125-8.828125-8.824219v-88.277344c0-4.875 3.957031-8.828124 8.828125-8.828124 4.875 0 8.828125 3.953124 8.828125 8.828124v88.277344c0 4.871094-3.953125 8.824219-8.828125 8.824219zm0 0"/>' +
                '            </g>' +
                '            <path d="m170.203125 95.136719c-.863281.28125-11.695313 15.261719.847656 27.9375 8.351563-18.371094-.464843-28.054688-.847656-27.9375m5.34375 73.523437c-6.296875 21.496094-14.601563 44.703125-23.527344 65.710938 18.378907-7.042969 38.375-13.195313 57.140625-17.546875-11.871094-13.621094-23.738281-30.632813-33.613281-48.164063m65.710937 57.175782c7.167969 5.445312 8.914063 8.199218 13.613282 8.199218 2.054687 0 7.925781-.085937 10.636718-3.828125 1.316407-1.820312 1.828126-2.984375 2.019532-3.59375-1.074219-.574219-2.515625-1.710937-10.335938-1.710937-4.449218 0-10.027344.191406-15.933594.933594m-119.957031 38.601562c-18.804687 10.425781-26.464843 19-27.011719 23.835938-.089843.804687-.328124 2.90625 3.785157 6.011718 1.316406-.414062 8.96875-3.859375 23.226562-29.847656m-23.421875 44.527344c-3.0625 0-6-.980469-8.507812-2.832032-9.15625-6.796874-10.390625-14.347656-9.808594-19.492187 1.597656-14.132813 19.304688-28.945313 52.648438-44.03125 13.230468-28.636719 25.820312-63.921875 33.324218-93.398437-8.773437-18.871094-17.3125-43.351563-11.097656-57.714844 2.179688-5.03125 4.910156-8.894532 9.976562-10.566406 2.011719-.652344 7.078126-1.480469 8.941407-1.480469 4.617187 0 9.050781 5.507812 11.183593 9.089843 3.972657 6.648438 3.992188 14.390626 3.363282 21.859376-.609375 7.253906-1.84375 14.46875-3.265625 21.601562-1.039063 5.242188-2.214844 10.460938-3.46875 15.660156 11.855469 24.175782 28.644531 48.816406 44.746093 65.683594 11.539063-2.054688 21.460938-3.097656 29.546876-3.097656 13.761718 0 22.121093 3.167968 25.519531 9.691406 2.828125 5.402344 1.660156 11.726562-3.433594 18.769531-4.898437 6.769531-11.640625 10.34375-19.523437 10.34375-10.710938 0-23.15625-6.671875-37.050782-19.851562-24.957031 5.15625-54.097656 14.34375-77.65625 24.515625-7.355468 15.410156-14.398437 27.824218-20.964844 36.933594-8.996093 12.5-16.773437 18.316406-24.472656 18.316406" fill="#b53438"/>' +
                '            <path d="m79.449219 450.207031h-26.484375c-4.871094 0-8.828125-3.953125-8.828125-8.828125v-52.964844c0-4.875 3.957031-8.828124 8.828125-8.828124h26.484375c19.472656 0 35.308593 15.835937 35.308593 35.3125 0 19.472656-15.835937 35.308593-35.308593 35.308593zm-17.65625-17.65625h17.65625c9.734375 0 17.652343-7.917969 17.652343-17.652343 0-9.738282-7.917968-17.65625-17.652343-17.65625h-17.65625zm0 0" fill="#fff"/>' +
                '            <path d="m158.898438 485.515625h-8.828126c-4.875 0-8.828124-3.953125-8.828124-8.824219v-88.277344c0-4.875 3.953124-8.828124 8.828124-8.828124h8.828126c29.199218 0 52.964843 23.753906 52.964843 52.964843 0 29.210938-23.765625 52.964844-52.964843 52.964844zm0-17.652344h.085937zm0-70.621093v70.621093c19.472656 0 35.308593-15.839843 35.308593-35.3125 0-19.472656-15.835937-35.308593-35.308593-35.308593zm0 0" fill="#fff"/>' +
                '        </svg>\n',
        });
    }

    function setNextPageUrlVideo(url) {
        $('#videoContentNextPageUrl').val(url);
    }

    function getNextPageUrlVideo() {
        return $('#videoContentNextPageUrl').val();
    }

    function setNextPageUrlPamphlet(url) {
        $('#pamphletContentNextPageUrl').val(url);
    }

    function getNextPageUrlPamphlet() {
        return $('#pamphletContentNextPageUrl').val();
    }

    function hideAllTabPage() {
        $('.nav .nav-item .nav-link').removeClass('active');
        $('.tab-content .tab-pane').removeClass('active');
    }

    function openPamohletTabPage() {
        hideAllTabPage();
        $('.nav .nav-item .nav-link[href="#searchResult_pamphlet"]').addClass('active');
        $('#searchResult_pamphlet').addClass('active');
    }

    function openVideoTabPage() {
        hideAllTabPage();
        $('.nav .nav-item .nav-link[href="#searchResult_video"]').addClass('active');
        $('#searchResult_video').addClass('active');
    }

    function hideVideoTabPage() {
        $('.nav .nav-item .nav-link[href="#searchResult_video"]').fadeOut();
    }

    function showVideoTabPage() {
        $('.nav .nav-item .nav-link[href="#searchResult_video"]').fadeIn();
    }

    function hidePamohletTabPage() {
        $('.nav .nav-item .nav-link[href="#searchResult_pamphlet"]').fadeOut();
    }

    function showPamohletTabPage() {
        $('.nav .nav-item .nav-link[href="#searchResult_pamphlet"]').fadeIn();
    }

    function showLoading() {
        AlaaLoading.show();
        $('.searchResultLoading_video').fadeIn();
        $('.searchResultLoading_pamphlet').fadeIn();
        $('.btnLoadMore').fadeOut(0);
        hideNoVideoMessage();
        hideNoPamphleMessage();
    }

    function hideLoading() {
        $('.searchResultLoading_video').fadeOut();
        $('.searchResultLoading_pamphlet').fadeOut();
        AlaaLoading.hide();
    }

    function getNextPageUrl(type) {
        if (type === 'video') {
            return getNextPageUrlVideo();
        } else {
            return getNextPageUrlPamphlet();
        }
    }

    function showTitleOfSet(pid, sid) {
        var titleOfProduct = $('.produtItems .productItem[data-pid="'+pid+'"] .productItem-descriptionCol .productItem-description .title').html(),
            setName = '';
        if (typeof sid !== 'undefined' && sid !== null) {
            setName = $('[data-set-id="'+sid+'"]').attr('data-set-name');
        } else if ($('.produtItems .productItem[data-pid="'+pid+'"] .productItem-descriptionCol .productItem-description .action .CustomDropDown').length === 1) {
            setName = $('.produtItems .productItem[data-pid="'+pid+'"] .productItem-descriptionCol .productItem-description .action .CustomDropDown select option').attr('data-set-name').trim();
        }
        if (setName !== '') {
            setName = ' - ' + '<small>'+setName+'</small>';
        }
        $('.contentsetOfProductCol .titleOfSet').html(titleOfProduct + setName);
    }

    function showContentsOfSet(type, url, pid, sid) {
        showTitleOfSet(pid, sid);
        changeToModalAndList();
        showTabPage(type);
        loadNewData(url);
    }

    function getUrl(pid) {
        if ($('.productItem[data-pid="'+pid+'"]').find('.btnViewContentSet').length > 0) {
            return $('.productItem[data-pid="'+pid+'"]').find('.btnViewContentSet').data('content-url');
        } else {
            return $('.productItem[data-pid="'+pid+'"]').find('.CustomDropDown select option').first().val()
        }
    }

    function getType(pid) {
        if ($('.productItem[data-pid="'+pid+'"]').find('.btnViewContentSet').length > 0) {
            return $('.productItem[data-pid="'+pid+'"]').find('.btnViewContentSet').data('content-type');
        } else {
            if ($('.productItem[data-pid="'+pid+'"]').find('.CustomDropDown select option').first().data('has-video').toString() === '1') {
                return 'video';
            } else {
                return 'pamphlet';
            }
        }
    }

    function loadContentsBasedOnProductId(pid, sid) {
        var url = getUrl(pid),
            type = getType(pid);
        showContentsOfSet(type, url, pid, sid)
    }

    function addEvents() {
        $(document).on('click', '.btnViewVideo, .btnViewPamphlet', function () {
            let contentType = $(this).data('content-type'),
                contentUrl = $(this).data('content-url'),
                pid =  ($(this).parents('.productItem').length > 0) ? $(this).parents('.productItem').data('pid') : $(this).data('product-id'),
                sid =  $(this).data('set-id');
            showContentsOfSet(contentType, contentUrl, pid, sid);
        });
        $(document).on('click', '.btnLoadMore', function () {
            let contentType = $(this).data('content-type');
            loadNextPage(contentType);
        });


        $( window ).on( "orientationchange", function( event ) {
            changeToModalAndList();
        });
    }

    function showSelectedProduct(url) {
        var $selectedProduct = getProductItem(url);
        $('.productItem').removeClass('selectedProduct');
        $selectedProduct.addClass('selectedProduct');
    }

    function getProductItem(url) {
        var productKey = $('.btnViewContentSet[data-content-url="'+url+'"]').attr('data-product-key'),
            $selectedProduct = $('.productItem[data-product-key="'+productKey+'"]');
        return $selectedProduct;
    }

    function changeToModalAndList() {
        var ww = $(window).width();
        if (ww <= 1024) {
            if ($('.contentsetOfProductCol').html().length>0) {
                $('#smallScreenModal .modal-body').html($('.contentsetOfProductCol').html());
                $('.contentsetOfProductCol').html('');
            }
            $('#smallScreenModal').modal('show');
        } else {
            if ($('#smallScreenModal .modal-body').html().length>0) {
                $('.contentsetOfProductCol').html($('#smallScreenModal .modal-body').html());
                $('#smallScreenModal .modal-body').html('');
            }
            $('#smallScreenModal').modal('hide');
        }
    }

    return {
        init: function () {
            addEvents();

            var ww = $(window).width();
            if (ww <= 1024) {
                $('#smallScreenModal .modal-body').html($('.contentsetOfProductCol').html());
                $('.contentsetOfProductCol').html('');
            } else {
                var pid = $('.productsCol .productItem:first-child').attr('data-pid'),
                    sid = null;
                if (UrlParameter.getParam('p') !== null) {
                    pid = UrlParameter.getParam('p');
                    sid = UrlParameter.getParam('s');

                }
                loadContentsBasedOnProductId(pid, sid);
            }
        }
    };
}();

var FilterAndSort = function () {

    function addEvents() {
        $(document).on('click', '.CustomSelect.filter .CustomSelect-Item', function () {
            selectItem($(this));
            showSelectedCategoryItems($(this));
        });
        $(document).on('click', '.CustomSelect.sort .CustomSelect-Item', function () {
            selectItem($(this));
            sortList($(this).attr('data-value'));
        });
    }

    function showSelectedCategoryItems($this) {
        var category = $this.attr('data-value');
        if (category === 'all') {
            $('.productItem').fadeIn();
        } else {
            $('.productItem:not([data-pc="'+category+'"])').fadeOut();
            $('.productItem[data-pc="'+category+'"]').fadeIn();
        }
    }

    function getParentCustomSelect($this) {
        return $this.parents('.CustomSelect');
    }

    function deselectAll($this) {
        var $customSelect = getParentCustomSelect($this);
        $customSelect.find('.CustomSelect-Item').removeClass('selected');
    }

    function selectItem($this) {
        deselectAll($this);
        $this.addClass('selected');
    }

    function sortList(sortType) {

        $('.produtItems').Sort({
            order: 'asc', // asc - des
            sortAttribute: sortType,
            itemSelector: '.productItem'
        });

        reorganizeCustomDropDown();
    }

    function reorganizeCustomDropDown() {
        $('.CustomParentOptions').each(function () {
            var customParentOptionsId = $(this).attr('id').replace('CustomDropDown', '');
            $(this).insertAfter( $('.productItem[data-product-key="'+customParentOptionsId+'"]') );
        });
    }

    return {
        init: function () {
            addEvents();
            sortList('data-sort1');
        },
    };
}();

var PurchaseAndFavoriteTabPage = function () {

    function addEvents() {
        $(document).on('click', '.btnShowFavorites', function () {
            selectFavorites();
            showFavorites();
        });
        $(document).on('click', '.btnShowPurchase', function () {
            selectPurchase();
            showPurchase();
        });
    }

    function showFavorites() {
        hidePurchase();
        $('.myFavoritesRow').fadeIn();
    }

    function showPurchase() {
        hideFavorites();
        $('.myProductsRow').fadeIn();
    }

    function hidePurchase() {
        $('.myProductsRow').fadeOut(0);
    }

    function hideFavorites() {
        $('.myFavoritesRow').fadeOut(0);
    }

    function selectFavorites() {
        deselectPurchase();
        $('.btnShowFavorites').addClass('btn-warning').removeClass('btn-secondary');
    }
    function deselectFavorites() {
        $('.btnShowFavorites').removeClass('btn-warning').addClass('btn-secondary');
    }

    function selectPurchase() {
        deselectFavorites();
        $('.btnShowPurchase').addClass('btn-warning').removeClass('btn-secondary');
    }
    function deselectPurchase() {
        $('.btnShowPurchase').removeClass('btn-warning').addClass('btn-secondary');
    }

    return {
        init: function () {
            showPurchase();
            addEvents();
        },
    };
}();

// var completeUserInfoForm = null;

var InitPage = function() {

    function userIsLogin() {
        var userId = GlobalJsVar.userId();
        return (userId.trim().length > 0);
    }

    function isUserInfoComplete() {
        return (parseInt($('#js-var-userInfo-cmpletion').val())>=60);
    }

    function initCompleteInfoState() {
        var OwlCarouselType2Option = {
            OwlCarousel: {
                btnSwfitchEvent: function () {
                    imageObserver.observe();
                    gtmEecProductObserver.observe();
                }
            },
            grid: {
                btnSwfitchEvent: function () {
                    imageObserver.observe();
                    gtmEecProductObserver.observe();
                }
            },
            defaultView: 'grid',
        };
        $('#owlCarouselMyProduct').OwlCarouselType2(OwlCarouselType2Option);
        $('#owlCarouselMyFavoritSet').OwlCarouselType2(OwlCarouselType2Option);
        $('#owlCarouselMyFavoritContent').OwlCarouselType2(OwlCarouselType2Option);
        $('#owlCarouselMyFavoritProducts').OwlCarouselType2(OwlCarouselType2Option);

        PurchaseAndFavoriteTabPage.init();

        $('.CustomDropDown').CustomDropDown({
            onChange: function (data) {
                if (!data.target.hasClass('btnViewVideo') && !data.target.hasClass('btnViewContentSet')) {
                    return false;
                }
                // { index: 2, totalCount: 5, value: "3", text: "فرسنگ سوم" }
            },
            onChanged: function (data) {
                // { index: 2, totalCount: 5, value: "3", text: "فرسنگ سوم" }
            },
            onRendered: function (data) {
                $('[data-toggle="m-tooltip"]').tooltip();
            },
            parentOptions: function ($this) {
                var parentId = $this.attr('data-parent-id');
                return '#' + parentId;
            },
            renderOption: function (optionObject) {

                var label = optionObject.innerHTML,
                    value = optionObject.getAttribute('value'),
                    setId = optionObject.getAttribute('data-set-id'),
                    productId = optionObject.getAttribute('data-product-id'),
                    productKey = optionObject.getAttribute('data-product-key'),
                    hasVideo = optionObject.getAttribute('data-has-video'),
                    hasPamphlet = optionObject.getAttribute('data-has-pamphlet'),
                    btnVideo =
                        '    <button type="button"\n' +
                        '            class="btn btn-warning btnViewContentSet btnViewVideo"\n' +
                        '            data-set-id="' + setId + '"\n' +
                        '            data-product-key="' + productKey + '"\n' +
                        '            data-product-id="' + productId + '"\n' +
                        '            data-content-type="video"\n' +
                        '            data-content-url="' + value + '">\n' +
                        '        فیلم ها\n' +
                        '    </button>\n',
                    btnPamphlet =
                        '    <button type="button"\n' +
                        '            class="btn btn-secondary btnViewContentSet btnViewPamphlet"\n' +
                        '            data-set-id="' + setId + '"\n' +
                        '            data-product-id="' + productId + '"\n' +
                        '            data-product-key="' + productKey + '"\n' +
                        '            data-content-type="pamphlet"\n' +
                        '            data-content-url="' + value + '">\n' +
                        '        جزوات\n' +
                        '    </button>',
                    actionBtn = '';

                if (hasVideo === '1') {
                    actionBtn += btnVideo;
                }
                if (hasPamphlet === '1') {
                    actionBtn += btnPamphlet;
                }
                return '' +
                    '<div class="setRow">' +
                    '  <div class="setRow-label" data-toggle="m-tooltip" data-placement="top" data-original-title="' + label + '">' +
                    label +
                    '  </div>' +
                    '  <div class="setRow-action">' +
                    actionBtn +
                    '  </div>' +
                    '</div>';
            }
        });

        FilterAndSort.init();
        LoadContentSet.init();
    }

    function initIncompleteInfoState() {

        var validateForm = function(completeUserInfoForm) {

            var formData = completeUserInfoForm.getFormData(),
                status = true;

            if (formData.firstName.trim().length > 0) {
                completeUserInfoForm.inputFeedback('firstName', '', 'success');
            } else {
                status = false;
                completeUserInfoForm.inputFeedback('firstName', 'نام را مشخص کنید.', 'danger');
            }

            if (formData.lastName.trim().length > 0) {
                completeUserInfoForm.inputFeedback('lastName', '', 'success');
            } else {
                status = false;
                completeUserInfoForm.inputFeedback('lastName', 'نام خانوادگی را مشخص کنید.', 'danger');
            }

            if (formData.province.trim().length > 0) {
                completeUserInfoForm.inputFeedback('province', '', 'success');
            } else {
                status = false;
                completeUserInfoForm.inputFeedback('province', 'استان را مشخص کنید.', 'danger');
            }

            if (formData.city.trim().length > 0) {
                completeUserInfoForm.inputFeedback('city', '', 'success');
            } else {
                status = false;
                completeUserInfoForm.inputFeedback('city', 'شهر را مشخص کنید.', 'danger');
            }

            completeUserInfoForm.setAjaxData(formData);

            return status;
        };

        var completeUserInfoForm = $('.completeUserInfo').FormGenerator({
            ajax: {
                type: 'POST',
                url: $('#js-var-actionUrl-profile-update').val(),
                data: {},
                accept: 'application/json; charset=utf-8',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                beforeSend: function () {
                    var status = validateForm(completeUserInfoForm);

                    if (status) {
                        AlaaLoading.show();
                    }

                    return status;
                },
                success: function (data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    AlaaLoading.hide();
                    toastr.warning('خطایی رخ داده است.');
                },
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                }
            },
            inputData: [
                {
                    type: 'hidden',
                    name: 'updateType',
                    value: 'profile'
                },
                {
                    type: 'hidden',
                    name: '_token',
                    value: $('#js-var-formData-token').val(),
                },
                {
                    type: 'text',
                    name: 'firstName',
                    value: $('#js-var-userData-name').val(),
                    placeholder: 'نام',
                    label: 'نام',
                    iconsRight: '<i class="fa fa-id-card"></i>',
                    id: 'name'
                },
                {
                    type: 'text',
                    name: 'lastName',
                    value: $('#js-var-userData-lastName').val(),
                    placeholder: 'نام خانوادگی',
                    label: 'نام خانوادگی',
                    iconsRight: '<i class="fa fa-id-card"></i>',
                    id: 'lastName'
                },
                {
                    type: 'text',
                    name: 'city',
                    value: $('#js-var-userData-city').val(),
                    placeholder: 'شهر',
                    label: 'شهر',
                    iconsRight: '<i class="fa fa-map-marker-alt"></i>',
                    id: 'city'
                },
                {
                    type: 'text',
                    name: 'province',
                    value: $('#js-var-userData-province').val(),
                    placeholder: 'استان',
                    label: 'استان',
                    iconsRight: '<i class="fa fa-map-marker-alt"></i>',
                    id: 'ostan'
                },
                {
                    type: 'sendAjax',
                    text: 'بروزرسانی اطلاعات',
                    class: 'btn btn-primary',
                    id: null
                }
            ]
        });

        validateForm(completeUserInfoForm);
    }

    function initLoginState() {
        if (isUserInfoComplete()) {
            initCompleteInfoState();
        } else {
            initIncompleteInfoState();
        }
    }

    function initLogoutState() {
        AjaxLogin.showLogin(GlobalJsVar.loginActionUrl(), function () {
            window.location.reload();
        });
    }

    function init() {
        if (userIsLogin()) {
            initLoginState();
        } else {
            initLogoutState();
        }
    }

    return {
        init: init
    };

}();

$(document).ready(function () {
    InitPage.init();
});
