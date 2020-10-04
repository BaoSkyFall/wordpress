!function(v){"use strict";var r={widgets:{},$widgets:{},init:function(){"undefined"!=typeof reyajaxfilter_params&&(v(".reyajfilter-ajax-term-filter").each(function(e,t){r.widgets[e]=v(t).attr("id"),r.$widgets[e]=v(t)}),this.$shopLoopContainer=v(reyajaxfilter_params.shop_loop_container),this.$shopLoopEmptyContainer=v(reyajaxfilter_params.not_found_container),this.getApplyFilterBtn(),this.applyFilterBtnID=this.applyFilterBtn.parent().attr("id")||"",this.isActiveFilterEnabled=this.applyFilterBtn.length,this.applyFilterBtnID&&(r.widgets[Object.keys(r.widgets).length]=this.applyFilterBtnID),this.events(),this.dropDownFilter(),this.initScrollbarContainer(),this.initOrder(),this.priceSlider(),this.stickyActiveFilter(),this.alphabeticMenu(),this.accordionItems(),this.changeVariableProductImage(),this.stickyTopFilter())},getApplyFilterBtn:function(){this.applyFilterBtn=v(".js-rey-applyFilters-btn")},setHistory:function(e){e.replace("?reynotemplate=1",""),e.replace("&reynotemplate=1",""),history.pushState({},"",e)},beforeUpdate:function(){var e,t,r=0;(this.$shopLoopContainer.length?e=this.$shopLoopContainer:this.$shopLoopEmptyContainer.length&&(e=this.$shopLoopEmptyContainer),e.length&&"default"===reyajaxfilter_params.animation_type)&&(void 0!==reyajaxfilter_params.scroll_to_top&&1==reyajaxfilter_params.scroll_to_top&&(t=void 0!==reyajaxfilter_params.scroll_to_top_offset?parseInt(reyajaxfilter_params.scroll_to_top_offset):100,(r=e.offset().top-t)<0&&(r=0),v("html, body").animate({scrollTop:r},"slow",function(e){return--e*e*e+1})))},filterProducts:function(e){if(!this.isActiveFilterEnabled||e){var n=this;v(document).trigger("reycore/ajaxfilters/started"),this.beforeUpdate();var t="";v("body").hasClass("elementor-page")||(t=(-1!==window.location.href.indexOf("?")?"&":"?")+"reynotemplate=1"),v.get(window.location.href+t,function(e){var i=jQuery(e),t=i.find(reyajaxfilter_params.shop_loop_container),r=i.find(reyajaxfilter_params.not_found_container);v.each(n.widgets,function(e,t){var r=i.find("#"+t),a=v(r).attr("class");v("#"+t).attr("class",a),v("#"+t).html(r.html())});var a=!1;reyajaxfilter_params.shop_loop_container==reyajaxfilter_params.not_found_container?a=v(reyajaxfilter_params.shop_loop_container).html(t.html()):v(reyajaxfilter_params.not_found_container).length?t.length?a=v(reyajaxfilter_params.not_found_container).html(t.html()):r.length&&(a=v(reyajaxfilter_params.not_found_container).html(r.html())):v(reyajaxfilter_params.shop_loop_container).length&&(t.length?a=v(reyajaxfilter_params.shop_loop_container).html(t.html()):r.length&&(a=v(reyajaxfilter_params.shop_loop_container).html(r.html()))),v("li.product.is-animated-entry",a).css({visibility:"",opacity:""}),n.getApplyFilterBtn(),n.initOrder(),n.dropDownFilter(),n.initScrollbarContainer(),n.stickyActiveFilter(),n.topActiveFilter(i),n.alphabeticMenu(),n.accordionItems(),n.changeVariableProductImage(a),v(document).trigger("reycore/ajaxfilters/finished",[a,i,n.widgets])})}},getUrlVars:function(e){for(var t,r={},a=(e=void 0===e?window.location.href:e).slice(e.indexOf("?")+1).split("&"),i=0;i<a.length;i++)r[(t=a[i].split("="))[0]]=t[1];return r},fixPagination:function(){var e,t=window.location.href,r=this.getUrlVars(t);return(e=parseInt(t.replace(/.+\/page\/([0-9]+)+/,"$1")))?1<e&&(t=t.replace(/page\/([0-9]+)/,"page/1")):void 0!==r.paged&&1<(e=parseInt(r.paged))&&(t=t.replace("paged="+e,"paged=1")),t},updateQueryStringParameter:function(e,t,r,a){void 0===r&&(r=!0),this.isActiveFilterEnabled&&this.applyFilterBtn.removeClass("--disabled"),void 0===a&&(a=this.fixPagination());var i,n=new RegExp("([?&])"+e+"=.*?(&|$)","i"),l=-1!==a.indexOf("?")?"&":"?";return i=a.match(n)?a.replace(n,"$1"+e+"="+t+"$2"):a+l+e+"="+t,!0===r?this.setHistory(i):i},removeQueryStringParameter:function(e,t){void 0===t&&(t=this.fixPagination()),this.isActiveFilterEnabled&&this.applyFilterBtn.removeClass("--disabled");var r,a=this.getUrlVars(t),i=Object.keys(a).length,n="?",l=t.indexOf(n),s=t.indexOf(e);1<i?r=n+(a=(1<s-l?t.replace("&"+e+"="+a[e],""):t.replace(e+"="+a[e]+"&","")).split(n))[1]:r=t.replace(n+e+"="+a[e],"");return r},removeURLParameter:function(e,t){var r=e.split("?");if(2<=r.length){for(var a=r.shift(),i=r.join("?"),n=encodeURIComponent(t)+"=",l=i.split(/[&;]/g),s=l.length;0<s--;)-1!==l[s].lastIndexOf(n,0)&&l.splice(s,1);e=a+"?"+l.join("&")}return e},singleFilter:function(e,t){var r,a=this.getUrlVars();r=void 0!==a[e]&&a[e]==t?this.removeQueryStringParameter(e):this.updateQueryStringParameter(e,t,!1),this.setHistory(r),this.filterProducts()},makeParameters:function(e,t,r){var a,i,n=!1;if(void 0!==(a=void 0!==r?this.getUrlVars(r):this.getUrlVars())[e]){var l=a[e],s=l.split(",");if(0<l.length){var o=jQuery.inArray(t,s);0<=o?(s.splice(o,1),0==s.length&&(n=!0)):s.push(t),i=1<s.length?s.join(","):s}else i=t}else i=t;if(0==n)this.updateQueryStringParameter(e,i);else{var c=this.removeQueryStringParameter(e);this.setHistory(c)}this.filterProducts()},replaceCategory:function(e){e=v.extend({url:"",redirect:!1,filter_out:[],params:{}},e);var r=this,a=window.location.href;e.filter_out&&v.each(e.filter_out,function(e,t){a=r.removeURLParameter(a,t)});var t=a.slice(0,a.indexOf("?")),i=a.replace(t,e.url);Object.keys(e.params).length&&(i=this.updateQueryStringParameter(e.params.key,e.params.value,!1,i)),e.redirect?window.location=i:(this.setHistory(i),this.filterProducts())},initOrder:function(){var r=this;void 0!==reyajaxfilter_params.sorting_control&&reyajaxfilter_params.sorting_control.length&&1==reyajaxfilter_params.sorting_control&&v(reyajaxfilter_params.shop_loop_container).find(".woocommerce-ordering").each(function(e){v(this).on("submit",function(e){e.preventDefault()}),v(this).on("change","select.orderby",function(e){e.preventDefault();var t=v(this).val();r.updateQueryStringParameter("orderby",t),r.filterProducts(!0)})})},dropDownFilter:function(){var l=this;v(".reyajfilter-select2").each(function(e,t){var r=v(t),a="select2-reyStyles",i=r.is('[data-checkboxes="true"]'),n={templateResult:l.formatState,minimumResultsForSearch:-1,allowClear:!0,containerCssClass:a,dropdownCssClass:a,dropdownAutoWidth:!0,placeholder:r.attr("data-placeholder")||"Choose",dir:v.reyHelpers.is_rtl?"rtl":"ltr"};r.is('[data-search="true"]')&&(n.minimumResultsForSearch=parseInt(reyajaxfilter_params.dd_search_threshold)),i&&(n.containerCssClass+=" --checkboxes",n.dropdownCssClass+=" --checkboxes"),l.isActiveFilterEnabled&&(n.closeOnSelect=!1),r.is("[data-ddcss]")&&(n.dropdownAutoWidth=!1,n.dropdownCss=JSON.parse(r.attr("data-ddcss")||"{}")),r.hasClass("reyajfilter-select2-single")?r.select2(n):r.hasClass("reyajfilter-select2-multiple")&&(i?(n.templateSelection=function(e,t){return n.placeholder?n.placeholder:"Selected "+e.length+" of "+t},r.select2MultiCheckboxes(n)):r.select2(n))}),v(".select2-dropdown").css("display","none")},initScrollbarContainer:function(e){var t=e||v(document);v(".reyajfilter-layered-nav[data-height]",t).each(function(e,t){var r=v(t),a=v(".reyajfilter-layered-navInner",r),i=r.attr("data-height")||0;if(v(".reyajfilter-layered-list",a).height()<parseFloat(i))return a.css("height",""),void v(".reyajfilter-customHeight-all",r).hide();a.length&&"undefined"!=typeof SimpleScrollbar&&SimpleScrollbar.initEl(a[0])})},priceSlider:function(){var e=document.getElementById("reyajfilter-noui-slider"),t=v(e),l=this;if(t.length&&"undefined"!=typeof noUiSlider){var s=parseInt(t.attr("data-min")),o=parseInt(t.attr("data-max")),r=parseInt(t.attr("data-set-min")),a=parseInt(t.attr("data-set-max"));if(r||(r=s),a||(a=o),r===a)return void t.closest(".reyajfilter-price-filter-widget").hide();noUiSlider.create(e,{start:[r,a],step:1,connect:!0,margin:5,tooltips:!0,format:wNumb({decimals:0}),range:{min:s,max:o}}),e.noUiSlider.on("change",function(e,t){l.getUrlVars();if(t){var r=parseInt(e[t]),a="max-price";if(r==o){var i=l.removeQueryStringParameter(a);l.setHistory(i)}else l.updateQueryStringParameter(a,r)}else{var n=parseInt(e[t]);a="min-price";if(n==s){i=l.removeQueryStringParameter(a);l.setHistory(i)}else l.updateQueryStringParameter(a,n)}l.filterProducts()})}},handlePriceSelect2:function(e){var t=v(e.target),a=t.val(),r=v('option[value="'+a+'"]',t),i=[{key:r.attr("data-key-min")||"min-price",val:r.attr("data-value-min")},{key:r.attr("data-key-max")||"max-price",val:r.attr("data-value-max")}],n=this;v.each(i,function(e,t){if(a)n.updateQueryStringParameter(t.key,t.val);else{var r=n.removeQueryStringParameter(t.key);n.setHistory(r)}}),this.filterProducts()},handleDefaultSelect2:function(e){var t=v(e.target).attr("name"),r=v(e.target).val();if(r)r=r.toString(),this.updateQueryStringParameter(t,r);else{var a=this.removeQueryStringParameter(t);this.setHistory(a)}this.filterProducts()},formatState:function(e){if(void 0!==e.loading)return e.text;var t="",r=v(e.element);if(r.is("[data-depth]")){var a=r.attr("data-depth");a&&(t+='<span class="__depth __depth--'+a+'"></span>')}if(r.parent("select").is("[data-checkboxes]")&&"true"==r.parent("select").attr("data-checkboxes")&&(t+='<span class="__checkbox"></span>'),t+='<span class="__text">'+e.text+"</span>",r.is("[data-count]")){var i=r.attr("data-count");i&&(t+='<span class="__count">'+i+"</span>")}return v(t)},objDiff:function(r,e){var a=this,i={};return _.each(e,function(e,t){null!=r[t]?Array.isArray(e)?i[t]=_.difference(e,r[t]):"object"==typeof e?i[t]=a.objDiff(r[t],e):null==r||null!=r[t]&&r[t]==e||(i[t]=e):i[t]=e}),i},stickyActiveFilter:function(){if(reyajaxfilter_params.apply_filter_fixed&&v.reyHelpers.is_desktop){var e=this.applyFilterBtn.closest(".rey-sidebar.shop-sidebar");this.applyFilterBtn.closest(".rey-filterPanel");if(e.length){var r,a,i,n,l=v(".rey-applyFilters-btn-wrapper",e),t=function(){r=v(window).height(),n=e.offset().top,i=n+e.height(),a=e.height(),l.css("width",l.width())},s=function(){var e=window.pageYOffset||document.documentElement.scrollTop,t=r<a&&r+e<i&&n<e+r;l.toggleClass("--sticky",t)};t(),s(),v(window).on("resize",v.reyHelpers.debounce(function(){t(),s()},400)),v(window).on("scroll",s)}}},topActiveFilter:function(e){v(".rey-filterTop-head",document).html(v(".rey-filterTop-head",e).html())},alphabeticMenu:function(){v.each(this.$widgets,function(e,t){var r,a=v(".reyajfilter-alphabetic",t),i=[];a.length&&(v("li[data-letter]",t).each(function(e,t){i.push(v(this).attr("data-letter"))}),r=i.filter(function(e,t,r){return r.indexOf(e)===t}).sort(),v.each(r,function(e,t){v('<span data-letter="'+t+'">'+t+"</span>").appendTo(a)}))})},accordionItems:function(){v(".reyajfilter-layered-nav.--accordion").each(function(e,t){var r=v(t);v(".__toggle",r).each(function(e,t){v(t).addClass("--collapsed"),v(t).nextAll("ul.children").addClass("--hidden")}),v(".chosen",r).each(function(e,t){var r=v(t).parents("ul");r.siblings(".__toggle").removeClass("--collapsed"),r.removeClass("--hidden")}),v(document).trigger("reycore/ajaxfilters/accordion_loaded",[r.parent()])})},stickyTopFilter:function(){if(v.reyHelpers.is_desktop){var t=v(".filters-top-sidebar.rey-filterSidebar.--sticky");if(t.length){var r=t.offset().top,e=50;void 0!==v.reyHelpers.applyFilter&&(e=v.reyHelpers.applyFilter("reycore/ajaxfilter/top_sidebar/sticky_debounce",e));var a=function(){t.toggleClass("--is-sticked",(window.pageYOffset||document.documentElement.scrollTop)>r)};a(),v(window).on("scroll",v.reyHelpers.debounce(function(e){a()},e)),v(window).on("resize",v.reyHelpers.debounce(function(e){r=t.offset().top},500))}}},changeVariableProductImage:function(e){e=e||v(".reyajfilter-before-products ul.products"),v("li.product .rey-productVariations",e).each(function(e,t){var r=v(t),a=r.attr("data-attribute-name");if(a){var i=v('.reyajfilter-layered-nav[data-taxonomy="'+a.replace("attribute_","")+'"] li.chosen > a');if(i.length){var n=i.first().attr("data-slug")||"";if(n){var l=v('span[data-slug="'+n+'"]',r);l.length&&setTimeout(function(){l.parent("li").trigger("click")},100)}}}}),v("li.product .wvs-archive-variation-wrapper",e).each(function(e,t){var r=v("li:first-child > select",t),a=r.attr("data-attribute_name");if(a){var i=v('.reyajfilter-layered-nav[data-taxonomy="'+a.replace("attribute_","")+'"] li.chosen > a');if(i.length){var n=i.first().attr("data-slug")||"";if(n){var l=r.next("ul.variable-items-wrapper");v('li[data-value="'+n+'"]',l).trigger("click"),r.val(n).trigger("change").trigger("click").trigger("focusin"),l.trigger("wvs-selected-item",[n,r,v(t)])}}}})},events:function(){var m=this;if(v(document).on("reycore/woocommerce/filter_panel/open",function(){m.stickyActiveFilter()}),v(".reyajfilter-ajax-term-filter").not(".reyajfilter-price-filter-widget").on("click","li a",function(e){e.preventDefault();var t=v(this),r=t.parent(),a=t.closest("ul"),i=t.attr("data-key"),n=t.attr("data-value"),l=t.attr("data-multiple-filter"),s=r.hasClass("chosen");if(t.is('[data-category-behavior="1"]'))m.replaceCategory({url:t.attr("href"),redirect:!0,filter_out:["product-cato","product-cata"]});else if(t.is('[data-category-behavior="2"]'))m.replaceCategory({url:t.attr("href"),filter_out:["product-cato","product-cata"]});else if(!t.is('[data-category-behavior="3"]')||s){var o=t.prevAll(".__toggle"),c=v(e.target);if(!o.length||c.is("span.__checkbox"))if(m.isActiveFilterEnabled&&(1==l?r.toggleClass("chosen"):s?r.removeClass("chosen"):(a.children().removeClass("chosen"),r.addClass("chosen"))),t.is('[data-back="true"]')){var d=t.closest(".reyajfilter-layered-nav").attr("data-shop"),p=window.location.href,f=p.slice(p.indexOf("?")+1);window.location.href=d+(f!==p?"?"+f:"")}else 1==l?m.makeParameters(i,n):m.singleFilter(i,n);else o.trigger("click")}else{var h=t.parent().siblings("li.chosen").children("a").attr("data-value")||"",u=t.attr("data-value")||"",y=t.closest(".reyajfilter-layered-nav").attr("data-shop");m.replaceCategory({url:y,redirect:!0,params:{key:t.attr("data-key"),value:[u,h].join(",")}})}}),v(".reyajfilter-price-filter-widget.reyajfilter-ajax-term-filter").on("click","li a",function(e){e.preventDefault();var t,r=v(this),a=r.attr("data-key-min"),i=r.attr("data-value-min"),n=r.attr("data-key-max"),l=r.attr("data-value-max");r.parent().hasClass("chosen")?(t=m.removeQueryStringParameter(a),""==(t=m.removeQueryStringParameter(n,t))&&(t=window.location.href.split("?")[0]),m.setHistory(t)):(t=m.updateQueryStringParameter(a,i,!1),t=m.updateQueryStringParameter(n,l,!0,t)),m.filterProducts()}),0<reyajaxfilter_params.pagination_container.length){var e=reyajaxfilter_params.pagination_container+" a";v(document).on("click",e,function(e){e.preventDefault();var t=v(this).attr("href");m.setHistory(t),m.filterProducts(!0)})}v(window).bind("popstate",function(e){v(reyajaxfilter_params.shop_loop_container).length&&m.filterProducts()}),v(document).on("click",".reyajfilter-active-filters a:not(.reset)",function(e){e.preventDefault();var t=v(this),r=t.attr("data-key"),a=t.attr("data-value");if(void 0===a){var i=m.removeQueryStringParameter(r);if(m.setHistory(i),v("#reyajfilter-noui-slider").length&&jQuery().noUiSlider){var n=document.getElementById("reyajfilter-noui-slider"),l=parseInt(v(n).attr("data-min")),s=parseInt(v(n).attr("data-max"));l&&s&&("min-price"===r?n.noUiSlider.set([l,null]):"max-price"===r&&n.noUiSlider.set([null,s]))}m.filterProducts()}else m.makeParameters(r,a)}),v(document).on("click",".reyajfilter-active-filters a.reset",function(e){e.preventDefault();var t=v(this).attr("data-location");m.setHistory(t),m.filterProducts(!0)}),v(document).on("change",'.js-reyajfilter-check-filter input[type="checkbox"]',function(e){e.preventDefault();var t=v(this),r=t.attr("data-key"),a=t.val();if(t.prop("checked"))m.updateQueryStringParameter(r,a);else{var i=m.removeQueryStringParameter(r);m.setHistory(i)}m.filterProducts()}),v(document).on("change",".reyajfilter-select2",function(e){e.preventDefault(),v(this).hasClass("reyajfilter-select2--prices")?m.handlePriceSelect2(e):m.handleDefaultSelect2(e)}),v(document).on("click",".js-rey-applyFilters-btn",function(e){e.preventDefault(),v(this).addClass("--loading"),m.filterProducts(!0)}),v(document).on("click",".reyajfilter-layered-nav[data-height] .reyajfilter-customHeight-all",function(e){e.preventDefault();var t=v(this).closest(".reyajfilter-layered-nav"),r=t.find(".reyajfilter-layered-navInner");if(t.hasClass("--reset-height"))return r.css("height",t.attr("data-height")),void t.removeClass("--reset-height");r.css("height",""),t.addClass("--reset-height")}),v(document).on("input",".js-reyajfilter-searchbox input",v.reyHelpers.debounce(function(e){e.preventDefault();var t=v(this).closest(".widget").find(".reyajfilter-layered-list"),r=v("li > a",t),a=new RegExp(e.target.value,"gi");r.parent().addClass("--hidden"),r.filter(function(){var e=v(this).closest("li").attr("data-rey-tooltip");return v(this).text().match(a)||(e?e.match(a):"")}).parents("li").removeClass("--hidden")},400)),v(document).on("click",".reyajfilter-alphabetic span",function(e){e.preventDefault();var t=v(this),r=t.parent(),a=t.attr("data-letter")||"",i=r.nextAll(".reyajfilter-layered-nav").find("li[data-letter]");if(t.hasClass("reyajfilter-alphabetic-all")){if(t.hasClass("--reset-filter")&&t.is("[data-key]")){var n=m.removeQueryStringParameter(t.attr("data-key"));m.setHistory(n),m.filterProducts()}return r.children().removeClass("--active"),t.addClass("--active"),i.removeClass("--hidden"),void m.initScrollbarContainer(r)}r.children().removeClass("--active"),t.addClass("--active"),i.addClass("--hidden");var l=i.filter('[data-letter="'+a+'"]');l.removeClass("--hidden"),l.parents("li[data-letter]").removeClass("--hidden"),m.initScrollbarContainer(r)}),v(document).on("click",".js-rey-filter-reset",function(e){e.preventDefault();var t=v(this).attr("data-location");m.setHistory(t),m.filterProducts(!0)}),v(document).on("reycore/ajaxfilters/started",function(e){v(".--anim-default .is-animated-entry").animate({visibility:"hidden",opacity:0},40),v("body").addClass("--is-filtering")}),v(document).on("click",".reyajfilter-layered-nav.--accordion .__toggle",function(e){e.preventDefault(),v(this).toggleClass("--collapsed"),v(this).nextAll("ul.children").toggleClass("--hidden")})}};v(document).ready(function(){r.init()})}(jQuery);