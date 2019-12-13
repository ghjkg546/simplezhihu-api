var $doc, $win, $bdy;
document.addEventListener("touchstart",
function() {},
!0),
$.getScript("js/jquery.finger.min.js");
var creatstyle_1 = function(a, b) {
    var c, d, e, f, g, h, i, j;
    c = "layout-css-1",
    d = $("<div>").attr("id", c).css("display", "none"),
    e = document.getElementById(c),
    f = 5;
    var k = $win.width();
    460 >= k && (f = 2),
    k > 460 && 800 >= k && (f = 3),
    g = $(a).width(),
    h = parseInt(g / f),
    i = "." + b + "{width:" + h + "px;}",
    j = '<style type="text/css">\n' + i + "</style>",
    d.html(j),
    e ? $(e).html(j) : $("body").prepend(d)
},
creatstyle_2 = function(a, b) {
    var c, d, e, g, h, i, j;
    c = "layout-css-2",
    d = $("<div>").attr("id", c).css("display", "none"),
    e = document.getElementById(c),
    g = $(a).width(),
    h = parseInt(g),
    i = "." + b + "{width:" + h + "px;}",
    j = '<style type="text/css">\n' + i + "</style>",
    d.html(j),
    e ? $(e).html(j) : $("body").prepend(d)
};
$(function() {
    window.appCarousel,
    window.partnerCarousel;
    var a, b, c, d, e, f, g;
    window.resetCarousel;
    var h = function() {},
    i = function() {};
    window.bannerCarousel;
    var n, o;
    $doc = $(document),
    $win = $(window),
    $bdy = $("body");
    var p = navigator.userAgent,
    q = /ipad/i.test(p),
    r = /applewebkit/i.test(p),
    s = /firefox/i.test(p);
    if (/ipad|ipod|iphone/i.test(p), $(".ai-logo").on("tap click",
    function() {
        window.location = "http://qiye.yurtree.com"
    }), $doc.jaySmoothScroll({
        eachscrollCont: ".ai-eachcont"
    }), /*e = $(".scrollbar"), f = e.children(".handle"), a = $(".ai-carousel-1 > .frame"), b = a.find(".cur-item"), c = a.children(".slidee"), resetCarousel = function() {
        var c, d, e, f;
        c = a.width(),
        e = b.width(),
        d = $win.width() > 360 ? 5 : 3,
        f = (c - e * d) / (d - 1),
        0 > f && (d -= 1, f = (c - e * d) / (d - 1)),
        b.css("margin-right", parseInt(f))
    },
    resetCarousel(),*/ d = {
        itemNav: "basic",
        horizontal: 1,
        smart: 1,
        mouseDragging: 1,
        touchDragging: 1,
        scrollBy: 0,
        releaseSwing: 1,
        speed: 300,
        elasticBounds: 1,
        scrollBar: e,
        dragHandle: 10,
        dynamicHandle: 0,
        minHandleSize: 50,
        clickBar: 1,
        syncSpeed: .5
    },
    g = function() {
        e.addClass("test"),
        e.removeClass("test")
    },
    /*appCarousel = new Sly(a[0], d).init(), appCarousel.on("moveStart",
    function() {
        $doc.on("touchmove.forcarousel",
        function(a) {
            a.preventDefault()
        }),
        c.addClass("hardware-acceleration"),
        f.addClass("hardware-acceleration")
    }), appCarousel.on("moveEnd",
    function() {
        $doc.off("touchmove.forcarousel"),
        c.removeClass("hardware-acceleration"),
        f.removeClass("hardware-acceleration")
    }),*/ bannerCarousel = $(".ai-banner-wrap"), creatstyle_2(bannerCarousel, "banner_list"), prevEl = bannerCarousel.find(".ai-banner-prev"), nextEl = bannerCarousel.find(".ai-banner-next"), bannerCarousel.length) {
        n = bannerCarousel.find(".ai-banner-frame"),
        o = n.find(".banner_list"),
        o.addClass("not-active"),
        window.bannerCarouselinit = new Sly(bannerCarousel, {
            horizontal: !0,
            itemNav: "forceCentered",
            itemSelector: o,
            smart: !0,
            activateMiddle: 1,
            pagesBar: $(".ai-banner-pages"),
            activatePageOn: "click",
            prevPage: prevEl,
            nextPage: nextEl,
            cycleBy: "pages",
            cycleInterval: function() {
                return 4e3
            } (),
            pauseOnHover: 1,
            startPaused: 0,
            touchDragging: 1,
            mouseDragging: 1,
            releaseSwing: 1,
            swingSpeed: .8,
            easing: "easeOutExpo",
            speed: 300,
            elasticBounds: 1
        }).init();
        var u = null;
        bannerCarouselinit.on("moveStart",
        function() {
            u ? clearTimeout(u) : n.addClass("hardware-acceleration")
        }),
        bannerCarouselinit.on("moveEnd",
        function() {
            u = setTimeout(function() {
                n.removeClass("hardware-acceleration")
            },
            3e3)
        });
        var v;
        bannerCarousel.on("touchstart",
        function(a) {
            v = a.originalEvent.changedTouches[0].clientY,
            $doc.on("touchmove.bannerevent",
            function(a) {
                a.preventDefault()
            })
        }).on("touchmove",
        function(a) {
            var b = Math.abs(a.originalEvent.changedTouches[0].clientY - v);
            b >= 8 && $doc.off(".bannerevent")
        }).on("touchend",
        function() {
            $doc.off(".bannerevent")
        })
    }

    var x = $(".flow-setps-wrap");
    x.length && $.getScript("js/jay.plugin.stepflow.js",
    function() {
        x.stepflow()
    });
    var y = $.ajax({
        url: "js/partners.txt"
    }),
    z = $("#partnerfill"),
    A = $(".cp-frame .sides");
    window.cpartners = $(".carousel-partners"),
    creatstyle_1(cpartners, "carousel-partners .eachrow"),
    y.always(function(a) {

    });
/*    var B = $(".blog-viewport"),
    C = B.find(".blog-frame"),
    D = B.find(".each-blog-view"),
    E = $(".blog-page-btnwrap"),
    F = E.find(".pageprv"),
    G = E.find(".pagenex"),
    H = new Sly(B, {
        horizontal: !0,
        itemNav: "basic",
        itemSelector: D,
        smart: !0,
        activateOn: null,
        activateMiddle: 0,
        touchDragging: 1,
        releaseSwing: 1,
        speed: 800,
        elasticBounds: 1,
        forward: null,
        backward: null,
        prev: null,
        next: null,
        prevPage: F,
        nextPage: G,
        easing: "easeOutElastic"
    }).init();
    H.on("moveStart",
    function() {
        q !== !0 && C.addClass("hardware-acceleration")
    }),
    H.on("moveEnd",
    function() {
        C.removeClass("hardware-acceleration")
    });*/
    var I = null;
    if ($(".count-content").scrollfire({
        offset: 0,
        topOffset: 0,
        bottomOffset: 150,
        onBottomIn: function() {
            I = setTimeout(function() {
                h()
            },
            500)
        },
        onBottomOut: function() {
            i(),
            clearTimeout(I)
        }
    }), document.getElementById("index_top")) {
        var J = $(".ai-nav-list"),
        K = ".ai-nav-listitem",
        L = $(K),
        M = J.find(K),
        N = "cur",
        O = $(".si-top"),
        P = [],
        Q = ["index_top", "index_serve", "index_case", "index_customer", "index_news", "index_about", "index_contact"],
        R = [],
        S = function() {
            $.each(Q,
            function(a, b) {
                "none" != $(document.getElementById(b)).css("display") && R.push(b)
            })
        };
        S(),
        $win.on("resize.getattr",
        function() {
            S()
        });
        var T = [];
        T = R;
        var U = function() {
            return r === !0 ? $("body") : s === !0 ? $("html") : $("html")
        } (),
        V = function(a) {
            $.each(a,
            function(a, b) {
                P[a] = document.getElementById(b).offsetTop
            })
        },
        X = function(a) {
            M.filter(function() {
                console.log('this='+a);
                return $(this).attr("toelem") == a ? $(this) : void 0
            }).addClass(N).siblings().removeClass(N)
        };
        V(T),
        O.on("click",
        function(a) {
            a.stopPropagation(),
            U.scrollTop(0)
        }),
        $win.on("load resize",
        function() {
            V(T)
        });
        var Y = null,
        Z = !1,
        _ = $(".right-side-flyelem"),
        ab = $(".ai-header").height();
        $win.on("scroll",
        function() {
            Y && clearTimeout(Y),
            Y = setTimeout(function() {
                V(T);
                var a = $win.scrollTop(),
                b = function() {
                    return $win.width() <= 800 ? 100 : 300
                } ();
                if ($.each(P,
                function(c, d) {
                    d + b >= a && a >= d - b ? (c > 0 ? bannerCarouselinit.pause() : 0 === c && bannerCarouselinit.resume(), X(T[c])) : a + $win.height() == $doc.height() && M.filter(":last:visible").addClass("cur").siblings().removeClass("cur")
                }), a >= ab - 200 && Z === !1) {
                    var c = _[0].classList;
                    c.add("show"),
                    Z = !0,
                    pb.length && sb === !0 && qb.stop(!0).animate({
                        right: "50px"
                    },
                    450, "easeOutExpo")
                } else if (ab - 200 > a && Z === !0) {
                    var c = _[0].classList;
                    c.remove("show"),
                    Z = !1,
                    pb.length && sb === !0 && qb.stop(!0).animate({
                        right: "10px"
                    },
                    450, "easeOutExpo")
                }
            },
            20)
        });
        var bb = $(".ai-nav");
        J.on("tap", K,
        function(a) {
            a.stopPropagation(),
            a.preventDefault(),
            $(this).trigger("click")
        }).on("click", K,
        function(a) {
            a.stopPropagation();
            var b = $(this);
            if (b.hasClass("cur")) return ! 1;
            var c = b.attr("toelem"),
            d = bb[0].clientHeight,
            e = document.getElementById(c).offsetTop,
            f = "";
            f = e >= 800 ? "easeOutExpo": "swing",
            U.animate({
                scrollTop: e - d
            },
            300),
            L.filter(".cur").removeClass("cur"),
            b.addClass("cur"),
            a.stopImmediatePropagation()
        }).on("flick",
        function(a) {
            return "horizontal" == a.orientation && "1" == a.direction ? ($(".sidebtn").trigger("tap"), !1) : void 0
        })
    }
    var cb = $(".minsizeToggle");
    if (cb.length) {
        var db = $(".third-big-wrap").find(".third-w"),
        eb = !1,
        fb = "",
        gb = "ontouchend" in document ? !0 : !1;
        gb === !0 ? cb.on("touchstart", ".circle-icon",
        function() {
            eb = !0
        }).on("touchmove", ".circle-icon",
        function() {
            eb = !1
        }).on("touchend", ".circle-icon",
        function() {
            eb === !0 && ($(this).addClass("cur").siblings().removeClass("cur"), fb = $(this).index(), eb = !1, db.eq(fb).addClass("cur").siblings().removeClass("cur"))
        }) : cb.on("click", ".circle-icon",
        function() {
            $(this).addClass("cur").siblings().removeClass("cur"),
            fb = $(this).index(),
            db.eq(fb).addClass("cur").siblings().removeClass("cur")
        })
    }
    var hb = function() {
        $(".mapContent_wrap").on("mousewheel.mapprv",
        function(a) {
            a.stopPropagation(),
            a.preventDefault()
        })
    },
    ib = function(a) {
        a.attr("clickable") && (a.css("opacity", "0").show().animate({
            opacity: "1"
        },
        120,
        function() {
            a.removeAttr("style")
        }), $(".mapContent_wrap").off("mousewheel.mapprv"))
    };
    if ($("#mapContent") && ($(".mapmask").on("click",
    function(a) {
        a.stopPropagation();
        var b = $(this);
        b.attr("clickable", "true"),
        b.hide(),
        hb()
    }), $(".mapContent_wrap").on("mouseleave",
    function(a) {
        a.stopPropagation();
        var b = $(".mapmask");
        ib(b),
        b.removeAttr("clickable")
    })), $(".count-content").length) {
        var kb = {
            useEasing: !0,
            useGrouping: !0,
            separator: ",",
            decimal: "."
        };
        $.getScript("js/countUp.min.js",
        function(a) {
            cachecount = a;
            var d = new countUp("count_1", 0, 8, 0, 2.5, kb),
            e = new countUp("count_2", 0, 80, 0, 2.5, kb),
            f = new countUp("count_3", 0, 3, 0, 2.5, kb),
            g = new countUp("count_4", 0, 100, 0, 2.5, kb);
            h = function() {
                d.start(),
                e.start(),
                f.start(),
                g.start()
            },
            i = function() {
                d.reset(),
                e.reset(),
                f.reset(),
                g.reset()
            }
        })
    }
    var lb = "hide";
    $doc.on("tap.minSideBar", ".sidebtn",
    function(a) {
        if (a.stopPropagation(), a.preventDefault(), "hide" === lb) $(this).addClass("sidebtn_show"),
        J.addClass("show"),
        lb = "show";
        else if ("show" === lb) {
            var b = $(this);
            J.addClass("hide"),
            J.one("webkitAnimationEnd animationend",
            function() {
                J.removeClass("show hide"),
                b.removeClass("sidebtn_show"),
                lb = "hide"
            })
        }
    });
    var mb = ".left-side-flyelem",
    nb = $(mb),
    ob = $(".left-side-flyelem-min");
    nb.length && "true" == nb.attr("setactive") && $doc.on("click.showleftfloatmin", ".msbtn",
    function(a) {
        a.stopPropagation(),
        nb.removeClass("showed"),
        ob.addClass("showed")
    }).on("click.showleftfloat", ".left-side-flyelem-min",
    function(a) {
        a.stopPropagation(),
        nb.addClass("showed"),
        ob.removeClass("showed")
    });
    var pb = $(".imweixin"),
    qb = $(".wx-2-dimensional-bar-code"),
    rb = $(".wx-cls-btn"),
    sb = !1;
    pb.length && (rb.on("click",
    function(a) {
        a.stopPropagation(),
        pb.trigger("click")
    }), pb.on("click",
    function(a) {
        a.preventDefault(),
        a.stopPropagation(),
        sb === !1 ? (Z && Z === !0 && qb.css("right", "50px"), qb.addClass("show"), pb.addClass("wxpopshowing"), sb = !0) : sb === !0 && (qb.addClass("hide"), qb.one("webkitAnimationEnd animationend",
        function(a) {
            a.stopPropagation(),
            qb.removeClass("show hide").removeAttr("style")
        }), pb.removeClass("wxpopshowing"), sb = !1)
    })),
    $.getScript("js/initMap2.js").always(function() {
        //initMap()
    })
});
var resizeTimer = null;
$(window).on("resize",
function() {
    resizeTimer && clearTimeout(resizeTimer),
    resizeTimer = setTimeout(function() {
        bannerCarousel.length && (creatstyle_2(bannerCarousel, "banner_list"), bannerCarouselinit.reload()),
        cpartners.length && (creatstyle_1(cpartners, "carousel-partners .eachrow"), partnerCarousel.reload()),
        "undefined" != typeof appCarousel && (resetCarousel(), appCarousel.reload())
    },
    300)
});