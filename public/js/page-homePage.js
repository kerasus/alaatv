function _typeof(e){return(_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function initCertificatesItemsHeight(){var e=$(".certificates-col").height()/2-$(".certificates-items").height()/2;$(".certificates-items").css({position:"absolute",top:e+"px"})}!function(e){e.fn.OwlCarouselType2=function(t){return e.fn.OwlCarouselType2.owlCarouselOptions=e.extend(!0,{},e.fn.OwlCarouselType2.owlCarouseldefaultOptions,t),this.each(function(){var t=e(this);e.fn.OwlCarouselType2.carouselElement=t;var o=t.find(".carousel").length;t.find(".a--owl-carousel-type-2").owlCarousel(e.fn.OwlCarouselType2.owlCarouselOptions.OwlCarousel),e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail(),t.find(".carousel").attr("data-owlcarousel-type-2-id",t.attr("id")),t.find(".btn-viewGrid").attr("data-owlcarousel-type-2-id",t.attr("id")),t.find(".btn-viewOwlCarousel").attr("data-owlcarousel-type-2-id",t.attr("id")).fadeOut(0),t.find(".a--owl-carousel-type-2-hide-detailes").attr("data-owlcarousel-type-2-id",t.attr("id")),t.find(".a--owl-carousel-type-2-show-detailes").attr("data-owlcarousel-type-2-id",t.attr("id")),e(t).on("click",".carousel",function(){var t=e("#"+e(this).attr("data-owlcarousel-type-2-id")),o=e(this).data("position");t.find(".a--owl-carousel-type-2").trigger("to.owl.carousel",o)}),e(t).on("click",".btn-viewGrid",function(t){var o=e("#"+e(this).attr("data-owlcarousel-type-2-id"));t.preventDefault(),e.fn.OwlCarouselType2.switchToGridView(o),e([document.documentElement,document.body]).animate({scrollTop:o.offset().top-e("#m_header").height()},300)}),e(t).on("click",".btn-viewOwlCarousel",function(t){var o=e("#"+e(this).attr("data-owlcarousel-type-2-id"));t.preventDefault(),e.fn.OwlCarouselType2.getGridViewWarper(o).html(""),o.find(".btn-viewGrid").fadeIn(0),o.find(".btn-viewOwlCarousel").fadeOut(0),o.find(".m-portlet.a--owl-carousel-type-2-slide-detailes").css({display:"block",position:"relative",width:"auto",top:"0"}),o.find(".subCategoryWarper").fadeOut(0),o.find(".a--owl-carousel-type-2-slide-detailes").slideUp(0),o.find(".detailesWarperPointerStyle").html(""),o.find(".a--owl-carousel-type-2").owlCarousel(e.fn.OwlCarouselType2.owlCarouselOptions.OwlCarousel),e.fn.OwlCarouselType2.getGridViewWarper(o).fadeOut(0),o.find(".a--owl-carousel-type-2").fadeIn(),e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail(),e([document.documentElement,document.body]).animate({scrollTop:o.offset().top-e("#m_header").height()},300)}),e(t).on("click",".a--owl-carousel-type-2-hide-detailes",function(){var t=e("#"+e(this).attr("data-owlcarousel-type-2-id"));t.find(".a--owl-carousel-type-2-slide-detailes").slideUp(),t.find(".subCategoryWarper").fadeOut(),e.fn.OwlCarouselType2.getGridViewWarper(t).find(" > div").css({"margin-bottom":"0px"})}),e(t).on("click",".a--owl-carousel-type-2-gridViewWarper .a--owl-carousel-type-2-show-detailes",function(){var t=e("#"+e(this).attr("data-owlcarousel-type-2-id"));e.fn.OwlCarouselType2.getGridViewWarper(t).find(" > div").css({"margin-bottom":"0px"});var o=e(this).parent("#"+t.attr("id")+" .m-widget_head-owlcarousel-item.carousel").data("position"),i="a--owl-carousel-type-2-slide-iteDetail-"+o;e.when(t.find(".subCategoryWarper").fadeOut(0)).done(function(){var a=t.find(".a--owl-carousel-type-2-slide-detailes"),r=t.find("."+i);e.when(a.slideUp(0)).done(function(){r.length>0&&(a.fadeIn(),r.slideDown());var i=t.find(".m-portlet.a--owl-carousel-type-2-slide-detailes"),s=e.fn.OwlCarouselType2.getGridViewWarper(t).find('.carousel[data-position="'+o+'"]').parent();s.css({"margin-bottom":parseInt(i.outerHeight())+"px"});var l=parseInt(s.outerHeight())+parseInt(s.position().top),n=parseInt(s.position().left)+parseInt(s.outerWidth())/2-5;i.css({display:"block",position:"absolute",width:"100%","z-index":"1",top:l+"px"}),0===t.find(".detailesWarperPointerStyle").length&&i.append('<div class="detailesWarperPointerStyle"></div>'),t.find(".detailesWarperPointerStyle").html("<style>.a--owl-carousel-type-2-slide-detailes::before { right: auto; left: "+n+"px; }</style>")})})}),o<e.fn.OwlCarouselType2.owlCarouselOptions.childCountHideOwlCarousel?(e.fn.OwlCarouselType2.switchToGridView(t),t.find(".btn-viewOwlCarousel").fadeOut()):"grid"===e.fn.OwlCarouselType2.owlCarouselOptions.defaultView&&e.fn.OwlCarouselType2.switchToGridView(t)})},e.fn.OwlCarouselType2.switchToGridView=function(t){e.fn.OwlCarouselType2.getGridViewWarper(t).fadeIn(),0===e.fn.OwlCarouselType2.getGridViewWarper(t).length&&t.find(".a--owl-carousel-type-2").after('<div class="m-widget_head-owlcarousel-items a--owl-carousel-type-2 owl-carousel row a--owl-carousel-type-2-gridViewWarper"></div>'),e.fn.OwlCarouselType2.getGridViewWarper(t).fadeOut(0),t.find(".subCategoryWarper").fadeOut(0),t.find(".a--owl-carousel-type-2-slide-detailes").slideUp(0),t.find(".btn-viewGrid").css("cssText","display: none !important;"),t.find(".btn-viewOwlCarousel").fadeIn(0),e.fn.OwlCarouselType2.getGridViewWarper(t).html(""),t.find(".a--owl-carousel-type-2").owlCarousel("destroy"),t.find(".carousel").each(function(){e.fn.OwlCarouselType2.getGridViewWarper(t).append('<div class="'+e.fn.OwlCarouselType2.owlCarouselOptions.grid.columnClass+'">'+e(this)[0].outerHTML+"</div>")}),t.find(".a--owl-carousel-type-2").fadeOut(),e.fn.OwlCarouselType2.getGridViewWarper(t).fadeIn()},e.fn.OwlCarouselType2.getGridViewWarper=function(e){return e.find(".a--owl-carousel-type-2-gridViewWarper")},e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail=function(t){var o="";o=void 0!==t?e(t.target).find(".carousel").attr("data-owlcarousel-type-2-id"):this.carouselElement.attr("id");var i=e("#"+o),a="a--owl-carousel-type-2-slide-iteDetail-"+i.find(".a--owl-carousel-type-2 .owl-item.active.center .carousel").data("position");i.find(".subCategoryWarper").fadeOut();var r=i.find(".a--owl-carousel-type-2-slide-detailes"),s=i.find("."+a);r.slideUp(),s.length>0&&(r.fadeIn(),s.slideDown(),e([document.documentElement,document.body]).animate({scrollTop:r.offset().top},300))},e.fn.OwlCarouselType2.owlCarouseldefaultOptions={OwlCarousel:{center:!0,rtl:!0,loop:!0,nav:!0,margin:10,responsive:{0:{items:1},400:{items:2},600:{items:3},800:{items:4},1000:{items:5}},onTranslated:e.fn.OwlCarouselType2.showAlaaOwlCarouselItemDetail},grid:{columnClass:"col-12 col-sm-6 col-md-3"},defaultView:"OwlCarousel",childCountHideOwlCarousel:5},e.fn.OwlCarouselType2.owlCarouselOptions=null,e.fn.OwlCarouselType2.carouselElement=null}(jQuery),function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"===("undefined"==typeof module?"undefined":_typeof(module))&&module.exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var t=Array.prototype.slice,o=Array.prototype.splice,i={topSpacing:0,bottomSpacing:0,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,container:"",hidePosition:{element:"",topSpace:0},getWidthFrom:"",widthFromWrapper:!0,responsiveWidth:!1,zIndex:"auto"},a=e(window),r=e(document),s=[],l=a.height(),n=function(){for(var t=a.scrollTop(),o=r.height(),i=o-l,n=t>i?i-t:0,d=0,c=s.length;d<c;d++){var p=s[d],u=p.stickyWrapper.offset().top-p.topSpacing-n,f=!1,w=!1;if(""!==p.container&&e(p.container).length>0)f=e(p.container).offset().top+e(p.container).height()-2*p.stickyWrapper.height();if(p.stickyWrapper.css("height",p.stickyElement.outerHeight()),""!==p.hidePosition.element&&e(p.hidePosition.element).length>0)w=e(p.hidePosition.element).offset().top-p.hidePosition.topSpace;if(t<=u)null!==p.currentTop&&(p.stickyElement.css({width:"",position:"",top:"","z-index":""}),p.stickyElement.parent().removeClass(p.className),p.stickyElement.trigger("sticky-end",[p]),p.currentTop=null);else{var h,y=o-p.stickyElement.outerHeight()-p.topSpacing-p.bottomSpacing-t-n;if(y<0?y+=p.topSpacing:y=p.topSpacing,p.currentTop!==y)p.getWidthFrom?h=e(p.getWidthFrom).width()||null:p.widthFromWrapper&&(h=p.stickyWrapper.width()),null==h&&(h=p.stickyElement.width()),p.stickyElement.css("width",h).css("position","fixed").css("top",y).css("z-index",p.zIndex),p.stickyElement.parent().addClass(p.className),null===p.currentTop?p.stickyElement.trigger("sticky-start",[p]):p.stickyElement.trigger("sticky-update",[p]),p.currentTop===p.topSpacing&&p.currentTop>y||null===p.currentTop&&y<p.topSpacing?p.stickyElement.trigger("sticky-bottom-reached",[p]):null!==p.currentTop&&y===p.topSpacing&&p.currentTop<y&&p.stickyElement.trigger("sticky-bottom-unreached",[p]),p.currentTop=y;var m=p.stickyWrapper.parent();p.stickyElement.offset().top+p.stickyElement.outerHeight()>=m.offset().top+m.outerHeight()&&p.stickyElement.offset().top<=p.topSpacing||!1!==f&&t>f||!1!==w&&t>=w?p.stickyElement.css("position","absolute").css("top","").css("bottom",0).css("z-index",""):p.stickyElement.css("position","fixed").css("top",y).css("bottom","").css("z-index",p.zIndex)}}},d=function(){l=a.height();for(var t=0,o=s.length;t<o;t++){var i=s[t],r=null;i.getWidthFrom?i.responsiveWidth&&(r=e(i.getWidthFrom).width()):i.widthFromWrapper&&(r=i.stickyWrapper.width()),null!=r&&i.stickyElement.css("width",r)}},c={init:function(t){var o=e.extend({},i,t);return o,this.each(function(){var t=e(this),a=t.attr("id"),r=a?a+"-"+i.wrapperClassName:i.wrapperClassName,l=e("<div></div>").attr("id",r).addClass(o.wrapperClassName);t.wrapAll(l);var n=t.parent();o.center&&n.css({width:t.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"===t.css("float")&&t.css({float:"none"}).parent().css({float:"right"}),o.stickyElement=t,o.stickyWrapper=n,o.currentTop=null,s.push(o),c.setWrapperHeight(this),c.setupChangeListeners(this)})},setWrapperHeight:function(t){var o=e(t),i=o.parent();i&&i.css("height",o.outerHeight())},setupChangeListeners:function(e){window.MutationObserver?new window.MutationObserver(function(t){(t[0].addedNodes.length||t[0].removedNodes.length)&&c.setWrapperHeight(e)}).observe(e,{subtree:!0,childList:!0}):(e.addEventListener("DOMNodeInserted",function(){c.setWrapperHeight(e)},!1),e.addEventListener("DOMNodeRemoved",function(){c.setWrapperHeight(e)},!1))},update:n,unstick:function(t){return this.each(function(){for(var t=e(this),i=-1,a=s.length;a-- >0;)s[a].stickyElement.get(0)===this&&(o.call(s,a,1),i=a);-1!==i&&(t.unwrap(),t.css({width:"",position:"",top:"",float:"","z-index":""}))})}};window.addEventListener?(window.addEventListener("scroll",n,!1),window.addEventListener("resize",d,!1)):window.attachEvent&&(window.attachEvent("onscroll",n),window.attachEvent("onresize",d)),e.fn.sticky=function(o){return c[o]?c[o].apply(this,t.call(arguments,1)):"object"!==_typeof(o)&&o?void e.error("Method "+o+" does not exist on jQuery.sticky"):c.init.apply(this,arguments)},e.fn.unstick=function(o){return c[o]?c[o].apply(this,t.call(arguments,1)):"object"!==_typeof(o)&&o?void e.error("Method "+o+" does not exist on jQuery.sticky"):c.unstick.apply(this,arguments)},e(function(){setTimeout(n,0)})}),$(".alaaAlert").fadeOut(0),$(".hightschoolAlert").fadeOut(0),$(document).on("click",".certificatesLogo",function(){var e=$(this).data("name");console.log(e),"alaa"===e?($(".hightschoolAlert").fadeOut(0),$(".alaaAlert").slideDown()):"sharif-school"===e?($(".alaaAlert").fadeOut(0),$(".hightschoolAlert").slideDown()):($(".alaaAlert").fadeOut(0),$(".hightschoolAlert").fadeOut(0))}),$(window).resize(function(){initCertificatesItemsHeight()}),$(document).ready(function(){initCertificatesItemsHeight()}),$(document).ready(function(){for(var e in GAEE.impressionView(gtmEecImpressions),$(".dasboardLessons").OwlCarouselType2({OwlCarousel:{center:!1,loop:!1,responsive:{0:{items:1},400:{items:2},600:{items:3},800:{items:4},1000:{items:4}}},grid:{columnClass:"col-12 col-sm-6 col-md-3 gridItem"},defaultView:"OwlCarousel",childCountHideOwlCarousel:4}),sections)$("."+sections[e]+".dasboardLessons .a--owl-carousel-head").sticky({container:"."+sections[e]+".dasboardLessons > .col > .m-portlet",topSpacing:$("#m_header").height(),zIndex:98})});
