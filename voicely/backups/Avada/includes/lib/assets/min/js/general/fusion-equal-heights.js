!function(a){"use strict";a.fn.equalHeights=function(b,c){var d;return Modernizr.mq("only screen and (min-width: "+fusionEqualHeightVars.content_break_point+"px)")||Modernizr.mq("only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait)")?(d=b||0,this.each(function(){a(this).css("min-height","0"),a(this).css("height","auto"),a(this).find(".fusion-column-content-centered").css("min-height","0"),a(this).find(".fusion-column-content-centered").css("height","auto"),a(this).outerHeight()>d&&(d=a(this).outerHeight())}),c&&d>c&&(d=c),this.each(function(){var b=d;"0"==b&&a(this).attr("data-empty-column","true"),a(this).children(".fusion-column-content-centered").length&&(b=d-(a(this).outerHeight()-a(this).height())),a(this).css("min-height",b),a(this).find(".fusion-column-content-centered").css("min-height",b)})):this.each(function(){a(this).css("min-height","0"),a(this).find(".fusion-column-content-centered").css("min-height","0")})}}(jQuery);