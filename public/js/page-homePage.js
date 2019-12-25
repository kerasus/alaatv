!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof module&&module.exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var t=Array.prototype.slice,i=Array.prototype.splice,o={topSpacing:0,bottomSpacing:0,scrollDirectionSensitive:!1,unstickUnder:!1,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,container:"",hidePosition:{element:"",topSpace:0},getWidthFrom:"",widthFromWrapper:!0,responsiveWidth:!1,zIndex:"auto"},n=e(window),s=e(document),l=[],r=n.height(),a=function(){var t=e(this).scrollTop(),i="down";i=t>u?"down":"up",u=t;for(var o=n.scrollTop(),a=s.height(),c=a-r,d=o>c?c-o:0,h=0,w=l.length;h<w;h++){var m=l[h],g=m.stickyWrapper.offset().top,y=g-m.topSpacing-d,v=!1,C=!1;if(!1!==m.unstickUnder&&n.width()<m.unstickUnder)m.stickyElement.css({width:"",position:"",top:"","z-index":""}),m.stickyElement.parent().css("height","auto");else{var k,O;if(""!==m.container&&e(m.container).length>0)v=e(m.container).offset().top+e(m.container).height()-2*m.stickyWrapper.height();if(m.stickyWrapper.css("height",m.stickyElement.outerHeight()),""!==m.hidePosition.element&&e(m.hidePosition.element).length>0)C=e(m.hidePosition.element).offset().top-m.hidePosition.topSpace;if(m.scrollDirectionSensitive){var T=m.stickyElement.height(),b=o+r,S=(m.bottomSpacing,p-o+g),E=r-T;if(i!==f&&(f=i,p=m.stickyElement.offset().top),m.getWidthFrom?O=e(m.getWidthFrom).width()||null:m.widthFromWrapper&&(O=m.stickyWrapper.width()),null==O&&(O=m.stickyElement.width()),"up"===i)(k=S)<g?m.stickyElement.css("width",O).css("position","fixed").css("top",k).css("z-index",m.zIndex):m.stickyElement.css("width",O).css("position","fixed").css("top",g).css("z-index",m.zIndex);else if("down"===i){var W=m.stickyElement.offset().top;m.stickyElement.position().top;r<T&&(W+T>b+1&&b<e(document).height()-g?m.stickyElement.css("position","fixed").css("top",S-g).css("bottom","").css("z-index",""):m.stickyElement.css("position","fixed").css("top",E-m.bottomSpacing).css("bottom","").css("z-index",""))}m.stickyElement.parent().addClass(m.className),null===m.currentTop?m.stickyElement.trigger("sticky-start",[m]):m.stickyElement.trigger("sticky-update",[m])}else if(o<=y)null!==m.currentTop&&(m.stickyElement.css({width:"",position:"",top:"","z-index":""}),m.stickyElement.parent().removeClass(m.className),m.stickyElement.trigger("sticky-end",[m]),m.currentTop=null);else{(k=a-m.stickyElement.outerHeight()-m.topSpacing-m.bottomSpacing-o-d)<0?k+=m.topSpacing:k=m.topSpacing,m.currentTop!==k&&(m.getWidthFrom?O=e(m.getWidthFrom).width()||null:m.widthFromWrapper&&(O=m.stickyWrapper.width()),null==O&&(O=m.stickyElement.width()),m.stickyElement.css("width",O).css("position","fixed").css("top",k).css("z-index",m.zIndex),m.stickyElement.parent().addClass(m.className),null===m.currentTop?m.stickyElement.trigger("sticky-start",[m]):m.stickyElement.trigger("sticky-update",[m]),m.currentTop===m.topSpacing&&m.currentTop>k||null===m.currentTop&&k<m.topSpacing?m.stickyElement.trigger("sticky-bottom-reached",[m]):null!==m.currentTop&&k===m.topSpacing&&m.currentTop<k&&m.stickyElement.trigger("sticky-bottom-unreached",[m]),m.currentTop=k);var L=m.stickyWrapper.parent();m.stickyElement.offset().top+m.stickyElement.outerHeight()>=L.offset().top+L.outerHeight()&&m.stickyElement.offset().top<=m.topSpacing||!1!==v&&o>v||!1!==C&&o>=C?m.stickyElement.css("position","absolute").css("top","").css("bottom",0).css("z-index",""):m.stickyElement.css("position","fixed").css("top",k).css("bottom","").css("z-index",m.zIndex)}}}},c=function(){r=n.height();for(var t=0,i=l.length;t<i;t++){var o=l[t],s=null;!1!==o.unstickUnder&&n.width()<o.unstickUnder?(o.stickyElement.css({width:"",position:"",top:"","z-index":""}),o.stickyElement.parent().css("height","auto")):(o.getWidthFrom?o.responsiveWidth&&(s=e(o.getWidthFrom).width()):o.widthFromWrapper&&(s=o.stickyWrapper.width()),null!=s&&o.stickyElement.css("width",s))}},d={init:function(t){var i=e.extend({},o,t);return i,this.each(function(){for(var t=e(this),n=t.attr("id"),s=n?n+"-"+o.wrapperClassName:o.wrapperClassName;null!==document.getElementById(s);)s+="1";var r=e("<div></div>").attr("id",s).addClass(i.wrapperClassName);t.wrapAll(r);var a=t.parent();i.center&&a.css({width:t.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"===t.css("float")&&t.css({float:"none"}).parent().css({float:"right"}),i.stickyElement=t,i.stickyWrapper=a,i.currentTop=null,l.push(i),d.setWrapperHeight(this),d.setupChangeListeners(this)})},setWrapperHeight:function(t){var i=e(t),o=i.parent();o&&o.css("height",i.outerHeight())},setupChangeListeners:function(e){window.MutationObserver?new window.MutationObserver(function(t){(t[0].addedNodes.length||t[0].removedNodes.length)&&d.setWrapperHeight(e)}).observe(e,{subtree:!0,childList:!0}):(e.addEventListener("DOMNodeInserted",function(){d.setWrapperHeight(e)},!1),e.addEventListener("DOMNodeRemoved",function(){d.setWrapperHeight(e)},!1))},update:a,unstick:function(t){return this.each(function(){for(var t=e(this),o=-1,n=l.length;n-- >0;)l[n].stickyElement.get(0)===this&&(i.call(l,n,1),o=n);-1!==o&&(t.unwrap(),t.css({width:"",position:"",top:"",float:"","z-index":""}))})}},u=0,p=0,f="down";window.addEventListener?(window.addEventListener("scroll",a,!1),window.addEventListener("resize",c,!1)):window.attachEvent&&(window.attachEvent("onscroll",a),window.attachEvent("onresize",c)),e.fn.sticky=function(i){return d[i]?d[i].apply(this,t.call(arguments,1)):"object"!=typeof i&&i?void e.error("Method "+i+" does not exist on jQuery.sticky"):d.init.apply(this,arguments)},e.fn.unstick=function(i){return d[i]?d[i].apply(this,t.call(arguments,1)):"object"!=typeof i&&i?void e.error("Method "+i+" does not exist on jQuery.sticky"):d.unstick.apply(this,arguments)},e.fn.update=function(i){return d[i]?d[i].apply(this,t.call(arguments,1)):"object"!=typeof i&&i?void e.error("Method "+i+" does not exist on jQuery.sticky"):d.update.apply(this,arguments)},e(function(){setTimeout(a,0)})});var ScrollCarousel=function(){let e,t=[];function i(e){let i=e.length;for(let s=0;s<i;s++)t[s]={isDown:!1,startX:null,scrollLeft:null},o(e[s],t[s]),e[s].onscroll=function(){n($(this).parents(".ScrollCarousel"))};$(document).on("click",".ScrollCarousel .ScrollCarousel-previous",function(){a($(this).parents(".ScrollCarousel"),"right")}),$(document).on("click",".ScrollCarousel .ScrollCarousel-next",function(){a($(this).parents(".ScrollCarousel"),"left")})}function o(e,t){!function(e,t){e.addEventListener("mousedown",function(i){t.isDown=!0,e.classList.add("active"),t.startX=i.pageX-e.offsetLeft,t.scrollLeft=e.scrollLeft})}(e,t),function(e,t){e.addEventListener("mouseleave",function(i){t.isDown=!1,e.classList.remove("active")})}(e,t),function(e,t){e.addEventListener("mouseup",function(i){t.isDown=!1,e.classList.remove("active")})}(e,t),function(e,t){e.addEventListener("mousemove",function(i){if(null!=t&&!t.isDown)return;i.preventDefault();const o=i.pageX-e.offsetLeft,n=3*(o-t.startX);e.scrollLeft=t.scrollLeft-n})}(e,t)}function n(e){e.each(function(){var e=r($(this)),t=e.length,i=e[0],o=e[t-1],n=c(i),a=c(o);s(n)?$(this).find(".ScrollCarousel-previous").fadeOut():$(this).find(".ScrollCarousel-previous").fadeIn(),l(a)?$(this).find(".ScrollCarousel-next").fadeOut():$(this).find(".ScrollCarousel-next").fadeIn()})}function s(e){return!1===e||e.pltrp===e.thisWidthWithMargin}function l(e){var t=e.thisPositionLeft+e.thisMarginLeft;return!(!1!==e&&t<0&&Math.abs(e.thisPositionLeft)-e.thisMarginLeft>1)&&(!1===e||t>0||Math.abs(e.thisPositionLeft)-e.thisMarginLeft<=1||void 0)}function r(e){return e.find(".item").toArray()}function a(e,t){f(e,t);for(var i=r(e),o=i.length,n=0;n<o;n++){var s=c(i[n]),l=!1;if("right"===t&&u(s)?l=s.newScrollPositionToRight:"left"===t&&d(s)&&(l=s.newScrollPositionToLeft),!1!==l){p(e,l);break}}}function c(e){if(void 0===e)return!1;var t=$(e),i=t.parent(),o=parseInt(t.width()),n=parseFloat(t.css("marginLeft")),s=t.position().left,l=i.width(),r=Math.round(1e3*(l-s))/1e3;return{this:t,thisWidth:o,thisPositionLeft:s,thisMarginLeft:n,thisWidthWithMargin:Math.round(1e3*t.outerWidth(!0))/1e3,pltrp:r,newScrollPositionToRight:-1*(r-i.scrollLeft()-o-n),newScrollPositionToLeft:-1*Math.ceil(r-i.scrollLeft())}}function d(e){return e.pltrp>=1&&e.pltrp<=e.thisWidthWithMargin}function u(e){return e.pltrp>=-1&&e.pltrp<=e.thisWidth}function p(e,t){e.each(function(){$(this).find(".ScrollCarousel-Items").animate({scrollLeft:t},200)})}function f(e,t){e.attr("direction",t)}return{init:function(){i(e=document.getElementsByClassName("ScrollCarousel-Items"))},addSwipeIcons:function(e){!function(e){e.append('<div class="ScrollCarousel-next"><i class="fa fa-chevron-left"></i></div>'),function(e){e.append('<div class="ScrollCarousel-previous"><i class="fa fa-chevron-right"></i></div>')}(e),n(e)}(e)},checkSwipIcons:function(e){n(e)},animateScroll:function(e){!function(e){var t=0,i=[];e.each(function(){var e=r($(this)),o=e.length,n=e[0],a=e[o-1];i[t]="left";var d=$(this),u=0;setInterval(function(){var e,t=c(n),i=c(a),o=void 0!==(e=d).attr("direction")?e.attr("direction"):"left";"right"===o&&s(t)?(f(d,"left"),u-=1):"right"===o&&(u+=1),"left"===o&&l(i)?(f(d,"right"),u+=1):"left"===o&&(u-=1),d.find(".ScrollCarousel-Items").animate({scrollLeft:u},0)},1),t++})}(e)}}}();function loadCarousels(){LazyLoad.loadElementByQuerySelector(".dasboardLessons",function(e){$(e).OwlCarouselType2({OwlCarousel:{stagePadding:30,center:!1,loop:!1,lazyLoad:!1,responsive:{0:{items:1},400:{items:2},600:{items:3},800:{items:4},1000:{items:6}},btnSwfitchEvent:function(){imageObserver.observe(),gtmEecProductObserver.observe()}},grid:{columnClass:"col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 gridItem",btnSwfitchEvent:function(){imageObserver.observe(),gtmEecProductObserver.observe()}},defaultView:"OwlCarousel",childCountHideOwlCarousel:4})})}function loadStickeHeader(){for(let e in sections)$("."+sections[e]+".dasboardLessons .m-portlet__head").sticky({container:"."+sections[e]+".dasboardLessons > .col > .m-portlet",topSpacing:$("#m_header").height(),zIndex:98})}ScrollCarousel.init(),function(e){e.fn.OwlCarouselType2=function(t){return e.fn.OwlCarouselType2.owlCarouselOptions=e.extend(!0,{},e.fn.OwlCarouselType2.owlCarouseldefaultOptions,t),this.each(function(){let t=e(this);e.fn.OwlCarouselType2.carouselElement=t;let i=t.find(".a--block-item").length;e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail(),t.find(".a--block-item").attr("data-owlcarousel-id",t.attr("id")),t.find(".btn-viewGrid").attr("data-owlcarousel-id",t.attr("id")),t.find(".btn-viewOwlCarousel").attr("data-owlcarousel-id",t.attr("id")).fadeOut(0),t.find(".a--owl-carousel-hide-detailes").attr("data-owlcarousel-id",t.attr("id")),t.find(".a--owl-carousel-show-detailes").attr("data-owlcarousel-id",t.attr("id")),e.fn.OwlCarouselType2.addEvents(t),i<e.fn.OwlCarouselType2.owlCarouselOptions.childCountHideOwlCarousel?(e.fn.OwlCarouselType2.switchToGridView(t),t.find(".btn-viewOwlCarousel").fadeOut()):"grid"===e.fn.OwlCarouselType2.owlCarouselOptions.defaultView&&e.fn.OwlCarouselType2.switchToGridView(t)})},e.fn.OwlCarouselType2.addEvents=function(t){e(t).on("click",".a--block-item",function(){let t=e("#"+e(this).attr("data-owlcarousel-id")),i=e(this).data("position");t.find(".a--owl-carousel-type-2").trigger("to.owl.a--block-item",i)}),e(t).on("click",".btn-viewGrid",function(t){t.preventDefault();let i=e("#"+e(this).attr("data-owlcarousel-id"));e.fn.OwlCarouselType2.switchToGridView(i)}),e(t).on("click",".btn-viewOwlCarousel",function(t){t.preventDefault();let i=e("#"+e(this).attr("data-owlcarousel-id"));e.fn.OwlCarouselType2.switchToCarousel(i)}),e(t).on("click",".a--owl-carousel-hide-detailes",function(){let t=e("#"+e(this).attr("data-owlcarousel-id"));t.find(".a--owl-carousel-slide-detailes").slideUp(),t.find(".subCategoryWarper").fadeOut(),e.fn.OwlCarouselType2.getGridViewWarper(t).find(" > div").css({"margin-bottom":"0px"})}),e(t).on("click",".a--owl-carousel-gridViewWarper .a--owl-carousel-show-detailes",function(){let t=e("#"+e(this).attr("data-owlcarousel-id"));e.fn.OwlCarouselType2.getGridViewWarper(t).find(" > div").css({"margin-bottom":"0px"});let i=e(this).parents("#"+t.attr("id")+" .a--block-item").data("position"),o="a--owl-carousel-slide-iteDetail-"+i;e.when(t.find(".subCategoryWarper").fadeOut(0)).done(function(){let n=t.find(".a--owl-carousel-slide-detailes"),s=t.find("."+o);e.when(n.slideUp(0)).done(function(){s.length>0&&(n.fadeIn(),s.slideDown());let o=t.find(".a--owl-carousel-slide-detailes"),l=e.fn.OwlCarouselType2.getGridViewWarper(t).find('.a--block-item[data-position="'+i+'"]').parent();l.css({"margin-bottom":parseInt(o.outerHeight())+50+"px"});let r=parseInt(l[0].offsetTop)+parseInt(l.outerHeight())+20,a=parseInt(l.position().left)+parseInt(l.outerWidth())/2-20;o.css({display:"block",position:"absolute",width:"100%","z-index":"1",top:r+"px"}),0===t.find(".detailesWarperPointerStyle").length&&o.append('<div class="detailesWarperPointerStyle"></div>'),t.find(".detailesWarperPointerStyle").html("<style>.a--owl-carousel-slide-detailes::before { right: auto; left: "+a+"px !important; }</style>")})})})},e.fn.OwlCarouselType2.switchToCarousel=function(t){e.fn.OwlCarouselType2.getGridViewWarper(t).html(""),t.find(".btn-viewGrid").fadeIn(0),t.find(".btn-viewOwlCarousel").fadeOut(0),t.find(".m-portlet.a--owl-carousel-slide-detailes").css({display:"block",position:"relative",width:"auto",top:"0"}),t.find(".subCategoryWarper").fadeOut(0),t.find(".a--owl-carousel-slide-detailes").slideUp(0),t.find(".detailesWarperPointerStyle").html(""),e.fn.OwlCarouselType2.getGridViewWarper(t).fadeOut(0),t.find(".a--owl-carousel-type-2").fadeIn(),e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail()},e.fn.OwlCarouselType2.switchToGridView=function(t){e.fn.OwlCarouselType2.getGridViewWarper(t).length>0?e.fn.OwlCarouselType2.getGridViewWarper(t).css("display","flex").hide().fadeIn():0===e.fn.OwlCarouselType2.getGridViewWarper(t).length&&t.find(".a--owl-carousel-type-2").after('<div class="m-widget_head-owlcarousel-items a--owl-carousel-type-2 owl-carousel row a--owl-carousel-gridViewWarper"></div>'),e.fn.OwlCarouselType2.getGridViewWarper(t).fadeOut(0),t.find(".subCategoryWarper").fadeOut(0),t.find(".a--owl-carousel-slide-detailes").slideUp(0),t.find(".btn-viewGrid").css("cssText","display: none !important;"),t.find(".btn-viewOwlCarousel").fadeIn(0),e.fn.OwlCarouselType2.getGridViewWarper(t).html(""),t.find(".a--block-item").each(function(){e.fn.OwlCarouselType2.getGridViewWarper(t).append('<div class="'+e.fn.OwlCarouselType2.owlCarouselOptions.grid.columnClass+'">'+e(this)[0].outerHTML+"</div>")}),t.find(".a--owl-carousel-type-2").fadeOut(),e.fn.OwlCarouselType2.getGridViewWarper(t).css("display","flex").hide().fadeIn(),e.fn.OwlCarouselType2.owlCarouselOptions.grid.btnSwfitchEvent()},e.fn.OwlCarouselType2.getGridViewWarper=function(e){return e.find(".a--owl-carousel-gridViewWarper")},e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail=function(t){let i="";i=void 0!==t?e(t.target).find(".a--block-item").attr("data-owlcarousel-id"):this.carouselElement.attr("id");let o=e("#"+i),n="a--owl-carousel-slide-iteDetail-"+o.find(".a--owl-carousel-type-2 .owl-item.active.center .a--block-item").data("position");o.find(".subCategoryWarper").fadeOut();let s=o.find(".a--owl-carousel-slide-detailes"),l=o.find("."+n);s.slideUp(),l.length>0&&(s.fadeIn(),l.slideDown(),e([document.documentElement,document.body]).animate({scrollTop:s.offset().top},300)),e.fn.OwlCarouselType2.owlCarouselOptions.OwlCarousel.btnSwfitchEvent()},e.fn.OwlCarouselType2.owlCarouseldefaultOptions={OwlCarousel:{stagePadding:0,center:!0,rtl:!0,loop:!0,nav:!0,margin:10,lazyLoad:!0,responsive:{0:{items:1},400:{items:2},600:{items:3},800:{items:4},1000:{items:5}},onTranslated:e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail,btnSwfitchEvent:function(){},onTranslatedEvent:function(){}},grid:{btnSwfitchEvent:function(){},columnClass:"col-12 col-sm-6 col-md-3"},defaultView:"OwlCarousel",childCountHideOwlCarousel:5},e.fn.OwlCarouselType2.owlCarouselOptions=null,e.fn.OwlCarouselType2.carouselElement=null}(jQuery),function(e){e.fn.AnimateScrollTo=function(t){return e.fn.AnimateScrollTo.options=e.extend(!0,{},e.fn.AnimateScrollTo.defaultOptions,t),this.each(function(){let t=e(this);e(document).trigger("AnimateScrollTo.beforeScroll",[t]),e([document.documentElement,document.body]).animate({scrollTop:t.offset().top-e.fn.AnimateScrollTo.options.topPadding},e.fn.AnimateScrollTo.options.speed),e(document).trigger("AnimateScrollTo.afterScroll",[t])})},e.fn.AnimateScrollTo.getHeaderHeight=function(){return e("#m_header").height()},e.fn.AnimateScrollTo.defaultOptions={topPadding:e.fn.AnimateScrollTo.getHeaderHeight()+5,speed:500},e.fn.AnimateScrollTo.options=null}(jQuery),loadCarousels(),loadStickeHeader(),$(document).ready(function(){$(document).on("click",".btnScrollTo",function(){var e=$(this).attr("data-scroll-to");$(e).AnimateScrollTo()})});
