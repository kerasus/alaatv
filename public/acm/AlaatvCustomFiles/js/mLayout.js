
mLayout = function() {
    var header;
    var horMenu;
    var asideMenu;
    var asideMenuOffcanvas;
    var horMenuOffcanvas;
    var asideLeftToggle;
    var asideLeftHide;
    var scrollTop;
    var quicksearch;
    var mainPortlet;

    //== Header
    var initStickyHeader = function() {
        var tmp;
        var headerEl = mUtil.get('m_header');
        var options = {
            offset: {},
            minimize:{}
        };

        if (mUtil.attr(headerEl, 'm-minimize-mobile') == 'hide') {
            options.minimize.mobile = {};
            options.minimize.mobile.on = 'm-header--hide';
            options.minimize.mobile.off = 'm-header--show';
        } else {
            options.minimize.mobile = false;
        }

        if (mUtil.attr(headerEl, 'm-minimize') == 'hide') {
            options.minimize.desktop = {};
            options.minimize.desktop.on = 'm-header--hide';
            options.minimize.desktop.off = 'm-header--show';
        } else {
            options.minimize.desktop = false;
        }

        if (tmp = mUtil.attr(headerEl, 'm-minimize-offset')) {
            options.offset.desktop = tmp;
        }

        if (tmp = mUtil.attr(headerEl, 'm-minimize-mobile-offset')) {
            options.offset.mobile = tmp;
        }

        header = new mHeader('m_header', options);
    };

    //== Hor menu
    var initHorMenu = function() {
        // init aside left offcanvas
        horMenuOffcanvas = new mOffcanvas('m_header_menu', {
            overlay: true,
            baseClass: 'm-aside-header-menu-mobile',
            closeBy: 'm_aside_header_menu_mobile_close_btn',
            toggleBy: {
                target: 'm_aside_header_menu_mobile_toggle',
                state: 'm-brand__toggler--active'
            }
        });

        horMenu = new mMenu('m_header_menu', {
            submenu: {
                desktop: 'dropdown',
                tablet: 'accordion',
                mobile: 'accordion'
            },
            accordion: {
                slideSpeed: 200,  // accordion toggle slide speed in milliseconds
                expandAll: false   // allow having multiple expanded accordions in the menu
            }
        });
    };

    //== Aside menu
    var initLeftAsideMenu = function() {
        //== Init aside menu
        var menu = mUtil.get('m_ver_menu');
        var menuDesktopMode = (mUtil.attr(menu, 'm-menu-dropdown') === '1' ? 'dropdown' : 'accordion');

        var scroll;
        if ( mUtil.attr(menu, 'm-menu-scrollable') === '1' ) {
            scroll = {
                height: function() {
                    if (mUtil.isInResponsiveRange('desktop')) {
                        return mUtil.getViewPort().height - parseInt(mUtil.css('m_header', 'height'));
                    }
                }
            };
        }

        asideMenu = new mMenu('m_ver_menu', {
            // vertical scroll
            scroll: scroll,

            // submenu setup
            submenu: {
                desktop: {
                    // by default the menu mode set to accordion in desktop mode
                    default: menuDesktopMode,
                    // whenever body has this class switch the menu mode to dropdown
                    state: {
                        body: 'm-aside-left--minimize',
                        mode: 'dropdown'
                    }
                },
                tablet: 'accordion', // menu set to accordion in tablet mode
                mobile: 'accordion'  // menu set to accordion in mobile mode
            },

            //accordion setup
            accordion: {
                autoScroll: false, // enable auto scrolling(focus) to the clicked menu item
                expandAll: false   // allow having multiple expanded accordions in the menu
            }
        });
    };

    //== Aside
    var initLeftAside = function() {
        // init aside left offcanvas
        var body = mUtil.get('body');
        var asideLeft = mUtil.get('m_aside_left');
        var asideOffcanvasClass = mUtil.hasClass(asideLeft, 'm-aside-left--offcanvas-default') ? 'm-aside-left--offcanvas-default' : 'm-aside-left';

        asideMenuOffcanvas = new mOffcanvas('m_aside_left', {
            baseClass: asideOffcanvasClass,
            overlay: true,
            closeBy: 'm_aside_left_close_btn',
            toggleBy: {
                target: 'm_aside_left_offcanvas_toggle',
                state: 'm-brand__toggler--active'
            }
        });

        //== Handle minimzied aside hover
        if (mUtil.hasClass(body, 'm-aside-left--fixed')) {
            var insideTm;
            var outsideTm;

            mUtil.addEvent(asideLeft, 'mouseenter', function() {
                if (outsideTm) {
                    clearTimeout(outsideTm);
                    outsideTm = null;
                }

                insideTm = setTimeout(function() {
                    if (mUtil.hasClass(body, 'm-aside-left--minimize') && mUtil.isInResponsiveRange('desktop')) {
                        mUtil.removeClass(body, 'm-aside-left--minimize');
                        mUtil.addClass(body, 'm-aside-left--minimize-hover');
                        asideMenu.scrollerUpdate();
                        asideMenu.scrollerTop();
                    }
                }, 300);
            });

            mUtil.addEvent(asideLeft, 'mouseleave', function() {
                if (insideTm) {
                    clearTimeout(insideTm);
                    insideTm = null;
                }

                outsideTm = setTimeout(function() {
                    if (mUtil.hasClass(body, 'm-aside-left--minimize-hover') && mUtil.isInResponsiveRange('desktop')) {
                        mUtil.removeClass(body, 'm-aside-left--minimize-hover');
                        mUtil.addClass(body, 'm-aside-left--minimize');
                        asideMenu.scrollerUpdate();
                        asideMenu.scrollerTop();
                    }
                }, 500);
            });
        }
    };

    //== Sidebar toggle
    var initLeftAsideToggle = function() {

        $(document).on('click', '#m_aside_left_hide_toggle', function () {
            if ($('body').hasClass('m-aside-left--hide')) {
                $(this).removeClass('m-brand__toggler--active');
                $('body').removeClass('m-aside-left--hide');
            } else {
                $(this).addClass('m-brand__toggler--active');
                $('body').addClass('m-aside-left--hide');
            }
        });
        $(document).on('click', '#m_aside_left_offcanvas_toggle', function () {
            if ($('body').hasClass('m-aside-left--on')) {
                $(this).addClass('m-brand__toggler--active');
                $('body').removeClass('m-aside-left--on');
            } else {
                $(this).removeClass('m-brand__toggler--active');
                $('body').addClass('m-aside-left--on');
            }
        });
    };

    //== Sidebar hide
    var initLeftAsideHide = function() {
        if ($('#m_aside_left_hide_toggle').length === 0 ) {
            return;
        }

        initLeftAsideHide = new mToggle('m_aside_left_hide_toggle', {
            target: 'body',
            targetState: 'm-aside-left--hide',
            togglerState: 'm-brand__toggler--active'
        });

        initLeftAsideHide.on('toggle', function(toggle) {
            horMenu.pauseDropdownHover(800);
            asideMenu.pauseDropdownHover(800);

            //== Remember state in cookie
            // Cookies.set('sidebar_hide_state', toggle.getState());
            // to set default minimized left aside use this cookie value in your
            // server side code and add "m-brand--minimize m-aside-left--minimize" classes to
            // the body tag in order to initialize the minimized left aside mode during page loading.
        });
    };

    //== Topbar
    var initTopbar = function() {
        $('#m_aside_header_topbar_mobile_toggle').click(function() {
            $('body').toggleClass('m-topbar--on');
        });
    };

    //== Quicksearch
    var initQuicksearch = function() {
        if ($('#m_quicksearch').length === 0 ) {
            return;
        }

        quicksearch = new mQuicksearch('m_quicksearch', {
            mode: mUtil.attr( 'm_quicksearch', 'm-quicksearch-mode' ), // quick search type
            minLength: 1
        });

        //<div class="m-search-results m-search-results--skin-light"><span class="m-search-result__message">Something went wrong</div></div>

        quicksearch.on('search', function(the) {

            if ($('#m_quicksearch_input').val().trim().length < 3) {
                showDataOnQuickSearchResultPannel('');
                return false;
            }
            showDataOnQuickSearchResultPannel('کمی صبر کنید ...');
            the.showProgress();

            $.ajax({
                url: '/c?q=' + $('#m_quicksearch_input').val(),
                data: {},
                dataType: 'json',
                success: function(res) {
                    the.hideProgress();
                    the.showResult(res);

                    showResultForQuickSearch(res);
                },
                error: function(res) {
                    the.hideProgress();
                    the.showError('مشکلی پیش آمده است. لطفا بعدا امتحان کنید.');
                }
            });
        });

        $(document).on('click', '#m_quicksearch_close', function () {
            showDataOnQuickSearchResultPannel('');
        });
    };

    function showDataOnQuickSearchResultPannel(html) {
        $('.a-quick-search .m-dropdown__content').html('');
        $('.a-quick-search .m-dropdown__content').append('<dvi class="a-dropdown__search-result">'+html+'</dvi>');
    }

    function showResultForQuickSearch(res) {
        var maxRecordOfEachCategory = 3;
        var article = res.result.article;
        var pamphlet = res.result.pamphlet;
        var product = res.result.product;
        var set = res.result.set;
        var video = res.result.video;

        var html = '';

        html += gteQuickSearchResultCategory('article', 'مقالات', article, maxRecordOfEachCategory);
        html += gteQuickSearchResultCategory('pamphlet', 'جزوات', pamphlet, maxRecordOfEachCategory);
        html += gteQuickSearchResultCategory('product', 'محصولات', product, maxRecordOfEachCategory);
        html += gteQuickSearchResultCategory('set', 'دسته ها', set, maxRecordOfEachCategory);
        html += gteQuickSearchResultCategory('video', 'ویدیوها', video, maxRecordOfEachCategory);
        showDataOnQuickSearchResultPannel(html);
    }

    function getQuickSearchResultItem(data) {
        return '    <!--begin::Widget 14 Item-->\n' +
            '    <div class="m-widget4__item">\n' +
            '        <div class="m-widget4__img m-widget4__img--pic">\n' +
            '            <img src="'+data.photo+'" alt="">\n' +
            '        </div>\n' +
            '        <div class="m-widget4__info">\n' +
            '            <span class="m-widget4__title">\n' +
            '                '+data.title+'\n' +
            '            </span>' +
            '            <br>\n' +
            '            <span class="m-widget4__sub">\n' +
            '                '+data.subtitle+'\n' +
            '            </span>\n' +
            '        </div>\n' +
            '        <div class="m-widget4__ext">\n' +
            '            <a href="'+data.link+'" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">مشاهده</a>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <!--end::Widget 14 Item-->\n';
    }

    function gteQuickSearchResultCategory(categoryType, categoryName, data, maxRecordOfCategory) {
        var html = '';
        if (data !== null && data.total > 0) {
            html += '<div class="kt-quick-search__category">'+categoryName+'</div>';
            html += '<div class="m-widget4">';
            for (var index in data.data) {
                if(isNaN(index)) {
                    continue;
                }
                if(index > maxRecordOfCategory) {
                    break;
                }
                var dataItem = data.data[index];
                var inputData = getInputDataForQuickSearchShowResult(categoryType, dataItem);
                html += getQuickSearchResultItem(inputData);
            }
            html += '</div>';
        }
        return html;
    }

    function getInputDataForQuickSearchShowResult(categoryType, data) {
        if (categoryType === 'video') {
            return {
                title: data.author.full_name,
                subtitle: data.name,
                photo: data.thumbnail,
                link: data.url,
            };
        } else if (categoryType === 'set') {
            return {
                title: data.shortName,
                subtitle: data.name,
                photo: data.photo,
                link: data.url,
            };
        } else if (categoryType === 'product') {
            return {
                title: data.name,
                subtitle: '',
                photo: data.photo,
                link: data.url,
            };
        } else if (categoryType === 'pamphlet') {
            return {
                title: data.author.full_name,
                subtitle: data.name,
                photo: data.thumbnail,
                link: data.url,
            };
        } else if (categoryType === 'article') {
            return {
                title: data.author.full_name,
                subtitle: data.name,
                photo: data.thumbnail,
                link: data.url,
            };
        }
    }




    //== Scrolltop
    var initScrollTop = function() {
        $(document).on('click', '#m_scroll_top', function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $('body').offset().top
            }, 300);
        });
    };

    //== Main portlet(sticky portlet)
    var createMainPortlet = function() {
        return new mPortlet('main_portlet', {
            sticky: {
                offset: parseInt(mUtil.css( mUtil.get('m_header'), 'height')) + parseInt(mUtil.css( mUtil.get('a_top_section'), 'height')),
                zIndex: 90,
                position: {
                    top: function() {
                        return parseInt(mUtil.css( mUtil.get('m_header'), 'height') );
                    },
                    left: function() {
                        var left = parseInt(mUtil.css( mUtil.getByClass('m-content'), 'paddingLeft'));

                        if (mUtil.isInResponsiveRange('desktop')) {
                            //left += parseInt(mUtil.css(mUtil.get('m_aside_left'), 'width') );
                            if (mUtil.hasClass(mUtil.get('body'), 'm-aside-left--minimize')) {
                                left += 78; // need to use hardcoded width of the minimize aside
                            } else {
                                left += 255; // need to use hardcoded width of the aside
                            }
                        }

                        return left;
                    },
                    right: function() {
                        return parseInt(mUtil.css( mUtil.getByClass('m-content'), 'paddingRight') );
                    }
                }
            }
        });
    };

    return {
        init: function() {
            this.initHeader();
            this.initAside();
            this.initMainPortlet();
        },
        initMainPortlet: function() {
            if (!mUtil.get('main_portlet')) {
                return;
            }

            mainPortlet = createMainPortlet();
            mainPortlet.initSticky();

            mUtil.addResizeHandler(function(){
                mainPortlet.updateSticky();
            });
        },

        resetMainPortlet: function() {
            mainPortlet.destroySticky();
            mainPortlet = createMainPortlet();
            mainPortlet.initSticky();
        },

        initHeader: function() {
            initStickyHeader();
            initHorMenu();
            initTopbar();
            initQuicksearch();
            initScrollTop();
        },

        initAside: function() {
            initLeftAside();
            initLeftAsideMenu();
            initLeftAsideToggle();
            initLeftAsideHide();

            this.onLeftSidebarToggle(function(e) {
                //== Update sticky portlet
                if (mainPortlet) {
                    mainPortlet.updateSticky();
                }

                var datatables = $('.m-datatable');

                $(datatables).each(function() {
                    $(this).mDatatable('redraw');
                });
            });
        },

        getAsideMenu: function() {
            return asideMenu;
        },

        onLeftSidebarToggle: function(handler) {
            if (asideLeftToggle) {
                asideLeftToggle.on('toggle', handler);
            }
        },

        closeMobileAsideMenuOffcanvas: function() {
            if (mUtil.isMobileDevice()) {
                asideMenuOffcanvas.hide();
            }
        },

        closeMobileHorMenuOffcanvas: function() {
            if (mUtil.isMobileDevice()) {
                horMenuOffcanvas.hide();
            }
        }
    };
}();
