!function(r){"use strict";var e=function(){this.transitionDuration=0,this.shrinked,this.init=function(){if(this.$header=r(".rey-siteHeader.header-pos--fixed"),this.$header.length){this.fixedHeaderActivationPoint=parseFloat(r.reyHelpers.params.fixed_header_activation_point),0===this.fixedHeaderActivationPoint&&(this.fixedHeaderActivationPoint=r.reyHelpers.adminBar),this.$sections=r(".elementor-section.elementor-top-section",this.$header),this.$hiddenSections=this.$sections.filter(".hide-on-scroll"),this.$dropPanelsInHiddenSections=r(".rey-header-dropPanel-btn",this.$hiddenSections),this.$headerHelper=this.$header.nextAll(".rey-siteHeader-helper");var e=parseFloat(window.getComputedStyle(this.$header[0]).getPropertyValue("--header-fixed-shrank-speed"));e&&(this.transitionDuration=1e3*e+50),this.events(),this.checkFixedHeader(),this.checkShrinkingHeader(),this.removeHiddenClassOnMobiles(),this.mobilesColumnsHideOnScroll()}},this.events=function(){var i=this;r(window).on("scroll",r.reyHelpers.debounce(function(e){i.checkFixedHeader(),i.checkShrinkingHeader(),i.hiddenOnScrollFixes()},100)),r(window).on("resize",r.reyHelpers.debounce(function(e){i.removeHiddenClassOnMobiles(),i.mobilesColumnsHideOnScroll()},500));var e=r(".elementor-section.--show-hover-yes.hide-on-scroll",this.$header);e.length&&this.$header.on("mouseenter",function(){e.removeClass("hide-on-scroll")}).on("mouseleave",function(){e.addClass("hide-on-scroll")})},this.checkShrinkingHeader=function(){var e=this;if(this.$header.hasClass("--fixed-shrinking")&&(!r.reyHelpers.is_mobile||!this.$header.hasClass("--not-mobile"))){var i="--shrank";(window.pageYOffset||document.documentElement.scrollTop)>this.fixedHeaderActivationPoint?this.shrinked||(this.$header.addClass(i),this.shrinked=!0,this.transitionDuration&&setTimeout(function(){e.$header.trigger("reycore/header_shrink/on",[e.$header])},this.transitionDuration)):this.shrinked&&(this.$header.removeClass(i),this.shrinked=!1,this.transitionDuration&&setTimeout(function(){e.$header.trigger("reycore/header_shrink/off",[e.$header])},this.transitionDuration))}},this.checkFixedHeader=function(){var e="--scrolled";(window.pageYOffset||document.documentElement.scrollTop)>this.fixedHeaderActivationPoint?this.$header.hasClass(e)||(this.$header.trigger("reycore/header_fixed/scrolled",[this.$header,e,!0]),this.$header.addClass(e)):this.$header.hasClass(e)&&(this.$header.trigger("reycore/header_fixed/unscrolled",[this.$header,e,!1]),this.$header.removeClass(e))},this.hiddenOnScrollFixes=function(){if(this.$dropPanelsInHiddenSections.length){var e=this.$dropPanelsInHiddenSections.closest(".rey-header-dropPanel.--active");e.length&&(r.reyHelpers.overlay("header","close"),e.removeClass("--active"))}},this.removeHiddenClassOnMobiles=function(){r.reyHelpers.is_desktop||this.$header.hasClass("--not-mobile")&&this.$hiddenSections.removeClass("hide-on-scroll")},this.mobilesColumnsHideOnScroll=function(){r.reyHelpers.is_desktop||this.$header.hasClass("--not-mobile")||r(".elementor-column.elementor-top-column[data-hide-on-scroll-mobile]",this.$header).each(function(e,i){var t=r(i);t.toggleClass("hide-on-scroll","yes"===t.attr("data-hide-on-scroll-mobile"))})},this.init()};r(document).ready(function(){new e}),r(window).on("elementor/frontend/init",function(){if("undefined"!=typeof elementorFrontend){var h=r(".rey-siteHeader.header-pos--fixed");if(h.length){elementorFrontend.hooks.addAction("frontend/element_ready/reycore-header-logo.default",function(e,t){var i={},r=t(".custom-logo",e),s=r.attr("src"),n=e.attr("data-sticky-logo")||"";n&&(i.desktop={initialSrc:s,stickySrc:n,$img:r});var o=t(".rey-mobileLogo",e);if(o.length){var d=o.attr("src"),a=e.attr("data-sticky-mobile-logo")||"";a&&(i.mobile={initialSrc:d,stickySrc:a,$img:o})}t.each(i,function(e,i){t("<img />",{src:i.stickySrc}).one("load",function(){var e;e=i,h.on("reycore/header_fixed/scrolled",function(){e.$img.attr("src",e.stickySrc)}).on("reycore/header_fixed/unscrolled",function(){e.$img.attr("src",e.initialSrc)})})})});var i=h.outerHeight(),e=function(e){return h.is(".--scrolled")?i+"px":e};void 0!==r.reyHelpers.addFilter&&r.reyHelpers.addFilter("rey/headerHeight",e),void 0!==wp.hooks&&wp.hooks.addFilter("rey_headerHeight","reycore/fixed-header",e)}}})}(jQuery);