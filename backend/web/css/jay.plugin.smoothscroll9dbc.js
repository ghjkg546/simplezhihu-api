!(function($) {
    /********************
		定义变量
	*********************/
    var resizeTimer = null;
    var resizeTimer2 = null;
    var delt = "";
    var delt2 = "";
    /********************
		jQuery Easing Plugins
		jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
		Copyright © 2008 George McGinley Smith
	*********************/
    $.easing["jswing"] = $.easing["swing"];
    $.extend($.easing, {
        def:"easeOutQuad",
        swing:function(x, t, b, c, d) {
            //alert(jQuery.easing.default);
            return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
        },
        easeInQuad:function(x, t, b, c, d) {
            return c * (t /= d) * t + b;
        },
        easeOutQuad:function(x, t, b, c, d) {
            return -c * (t /= d) * (t - 2) + b;
        },
        easeInOutQuad:function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t + b;
            return -c / 2 * (--t * (t - 2) - 1) + b;
        },
        easeInCubic:function(x, t, b, c, d) {
            return c * (t /= d) * t * t + b;
        },
        easeOutCubic:function(x, t, b, c, d) {
            return c * ((t = t / d - 1) * t * t + 1) + b;
        },
        easeInOutCubic:function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        },
        easeInQuart:function(x, t, b, c, d) {
            return c * (t /= d) * t * t * t + b;
        },
        easeOutQuart:function(x, t, b, c, d) {
            return -c * ((t = t / d - 1) * t * t * t - 1) + b;
        },
        easeInOutQuart:function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
            return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
        },
        easeInQuint:function(x, t, b, c, d) {
            return c * (t /= d) * t * t * t * t + b;
        },
        easeOutQuint:function(x, t, b, c, d) {
            return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
        },
        easeInOutQuint:function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
        },
        easeInSine:function(x, t, b, c, d) {
            return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
        },
        easeOutSine:function(x, t, b, c, d) {
            return c * Math.sin(t / d * (Math.PI / 2)) + b;
        },
        easeInOutSine:function(x, t, b, c, d) {
            return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
        },
        easeInExpo:function(x, t, b, c, d) {
            return t == 0 ? b :c * Math.pow(2, 10 * (t / d - 1)) + b;
        },
        easeOutExpo:function(x, t, b, c, d) {
            return t == d ? b + c :c * (-Math.pow(2, -10 * t / d) + 1) + b;
        },
        easeInOutExpo:function(x, t, b, c, d) {
            if (t == 0) return b;
            if (t == d) return b + c;
            if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
            return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
        },
        easeInCirc:function(x, t, b, c, d) {
            return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
        },
        easeOutCirc:function(x, t, b, c, d) {
            return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
        },
        easeInOutCirc:function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
            return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
        },
        easeInElastic:function(x, t, b, c, d) {
            var s = 1.70158;
            var p = 0;
            var a = c;
            if (t == 0) return b;
            if ((t /= d) == 1) return b + c;
            if (!p) p = d * .3;
            if (a < Math.abs(c)) {
                a = c;
                var s = p / 4;
            } else var s = p / (2 * Math.PI) * Math.asin(c / a);
            return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p)) + b;
        },
        easeOutElastic:function(x, t, b, c, d) {
            var s = 1.70158;
            var p = 0;
            var a = c;
            if (t == 0) return b;
            if ((t /= d) == 1) return b + c;
            if (!p) p = d * .3;
            if (a < Math.abs(c)) {
                a = c;
                var s = p / 4;
            } else var s = p / (2 * Math.PI) * Math.asin(c / a);
            return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * 2 * Math.PI / p) + c + b;
        },
        easeInOutElastic:function(x, t, b, c, d) {
            var s = 1.70158;
            var p = 0;
            var a = c;
            if (t == 0) return b;
            if ((t /= d / 2) == 2) return b + c;
            if (!p) p = d * .3 * 1.5;
            if (a < Math.abs(c)) {
                a = c;
                var s = p / 4;
            } else var s = p / (2 * Math.PI) * Math.asin(c / a);
            if (t < 1) return -.5 * a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p) + b;
            return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p) * .5 + c + b;
        },
        easeInBack:function(x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            return c * (t /= d) * t * ((s + 1) * t - s) + b;
        },
        easeOutBack:function(x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
        },
        easeInOutBack:function(x, t, b, c, d, s) {
            if (s == undefined) s = 1.70158;
            if ((t /= d / 2) < 1) return c / 2 * t * t * (((s *= 1.525) + 1) * t - s) + b;
            return c / 2 * ((t -= 2) * t * (((s *= 1.525) + 1) * t + s) + 2) + b;
        },
        easeInBounce:function(x, t, b, c, d) {
            return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
        },
        easeOutBounce:function(x, t, b, c, d) {
            if ((t /= d) < 1 / 2.75) {
                return c * 7.5625 * t * t + b;
            } else if (t < 2 / 2.75) {
                return c * (7.5625 * (t -= 1.5 / 2.75) * t + .75) + b;
            } else if (t < 2.5 / 2.75) {
                return c * (7.5625 * (t -= 2.25 / 2.75) * t + .9375) + b;
            } else {
                return c * (7.5625 * (t -= 2.625 / 2.75) * t + .984375) + b;
            }
        },
        easeInOutBounce:function(x, t, b, c, d) {
            if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
            return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
        }
    });
    /****************************************
	编写Smooth滚动方法
	加载此插件的桌面端必须包含mousewheel插件
	*****************************************/
    $.fn.extend({
        jaySmoothScroll:function(options) {
            var opt = $.extend({
                range:100,
                autoMovingRange:20,
                autoMovingSpeed:500,
                timeinv:20,
                easing:"easeOutBack",
                //smartScrollSetting
                smartScroll:true,
                conOffset:600,
                eachscrollCont:""
            }, options);
            var useragent = navigator.userAgent;
            var webkit = /applewebkit/i.test(useragent);
            var firefox = /firefox/i.test(useragent);
            var ios = /ipad|ipod|iphone/i.test(useragent);
            return this.each(function() {
                var _self;
                //目标元素
                var _count = 1;
                var _cache;
                var direction = "";
                var bfdir = "";
                _self = $(this);
                if ((_self[0].nodeName == "#document" || _self[0].nodeName == "HTML") && webkit === true) {
                    _self = $("body");
                } else if ((_self[0].nodeName == "#document" || _self[0].nodeName == "HTML") && firefox === true) {
                    _self = $("html");
                    _self.addClass("firefox");
                } else {
                    _self = $("html");
                }
                if (/safari/i.test(useragent) && !/chrome/i.test(useragent)) {}
                //smartScroll
                var $eachScrollCon = $(opt.eachscrollCont).filter(":visible");
                var eachSmartScrollElemSplit = [];
                var eachScrollConLength = $eachScrollCon.length;
                //定义每个框体距离顶部距离的方法
                var setScrollArry = function(element) {
                    element.each(function(i, el) {
                        eachSmartScrollElemSplit[i] = el.offsetTop;
                    });
                };
                window.onload = function() {
                    setScrollArry($eachScrollCon);
                };
                _self.on("mousewheel", function(e) {
                    //console.log(e.deltaFactor)
                    var dect = function() {
                        if (/safari/i.test(useragent) && !/chrome/i.test(useragent)) {
                            return false;
                        } else {
                            return true;
                        }
                    }();
                    if (e.deltaFactor >= 3 && dect === true) {
                        e.preventDefault();
                        var _deltaY = e.deltaY, _deltaF = function() {
                            if (e.deltaFactor <= 50) {
                                return 100;
                            } else {
                                return e.deltaFactor;
                            }
                        }();
                        _count = _count + _deltaF;
                        //判断滚动是不是相反方向执行动作
                        if (!bfdir) {
                            bfdir = _deltaY;
                        } else if (bfdir != _deltaY) {
                            _self.stop(true);
                            _count = 0;
                            bfdir = _deltaY;
                        }
                        //判断滚动方向
                        if (_deltaY < 0) {
                            direction = "+=";
                        } else if (_deltaY > 0) {
                            direction = "-=";
                        }
                        //如果滚动的距离很长的话，开启反弹easing
                        var dec_easing = function() {
                            if (_count > 800) {
                                return opt.easing;
                            } else {
                                return "easeOutExpo";
                            }
                        }();
                        //console.log(_count)
                        _self.stop(true).animate({
                            scrollTop:direction + _count
                        }, opt.autoMovingSpeed, dec_easing, function() {
                            _count = 1;
                        });
						return false;
                    }
					
                });
            });
        }
    });
})(jQuery, window, document);