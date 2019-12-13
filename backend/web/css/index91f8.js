$(function(){
    $('.sidebtn').on('click', function(event) {
        if($(this).hasClass('sidebtn_show'))
        {
             $(this).removeClass('sidebtn_show');
             $('.ai-nav-list').removeClass('show');
        }
        else
        {
            $(this).addClass('sidebtn_show');
            $('.ai-nav-list').addClass('show');
        }

    });
    $('.ai-nav-listitem').on('click', function() {
        $('.sidebtn').removeClass('sidebtn_show');
        $('.ai-nav-list').removeClass('show');
    });
})
function check() {
    var qqReg = /^[1-9][0-9]{4,9}/;
    var telreg = /^1[23456789]\d{9}$/gi;
    var name = jQuery("#txtname").val();
    var require_type = jQuery("#txtrequire_type").val();
    var Content = jQuery("#txtContent").val();
    var qq = jQuery("#txtqq").val();
    var tel = jQuery("#txttel").val();
    if (name == "" || name == "您的姓名") {
        alert("请输入您的姓名！");
        jQuery("#txtname").focus();
        return false;
    }
    else if (tel == "" || tel == "您的联系电话") {
        alert("请输入联系电话")
        jQuery("#txttel").focus();
    } else if (!telreg.test(tel)) {
        alert("请输入正确的联系电话")
        jQuery("#txttel").focus();
    }
    /*else if (qq == "" || qq == "您的QQ号") {
        alert("请输入QQ号")
        jQuery("#txtqq").focus();
        return false;
    }
    else if (!qqReg.test(qq)) {
        alert("QQ号输入有误！")
        jQuery("#txtqq").focus();
        return false;
    }*/
    else if (Content == "" || Content == "详细商务合作描述") {
        alert("请输入业务需求")
        jQuery("#txtContent").focus();
        return false;
    }
    else {
        var button = $('button[type="button"]');
        button.html('提交中...').attr('disabled', true);
        jQuery.ajax({
            type: "POST",
            url: "/Index/add_message",
            data: { txtname: jQuery("#txtname").val(), txttype: require_type, txtContent: jQuery("#txtContent").val(), txttel: tel},
            cache: false,
            success: function (msg)
			{
                if (msg != 'failure') {
                    alert("恭喜您，业务咨询信息已提交成功!");

                    jQuery("#txtname").val("");
                    jQuery("#txtContent").val("");
                    jQuery("#txtqq").val("");
                    jQuery("#txttel").val("");
                    button.html('提交').attr('disabled', false);
                }
                else
				{
                    alert('对不起，信息提交失败！');
                    button.html('提交').attr('disabled', false);
				}
			}
        })
    }
}

function check_free() {
    var telreg = /^1[3458]\d{9}$/gi;
    var name = jQuery("#freename").val();
    var tel = jQuery("#freetel").val();
    var qq = jQuery("#freeqq").val();
    var selectpdt = jQuery("#selectpdt").val();
    if (name == "" || name == "您的姓名") {
        alert("请输入您的姓名！");
        jQuery("#freename").focus();
        return false;
    }
    else if (tel == "" || tel == "您的联系电话") {
        alert("请输入联系电话")
        jQuery("#freetel").focus();

    } else if (!telreg.test(tel)) {

        alert("请输入正确的联系电话")
        jQuery("#freetel").focus();
    }else if(qq == "" || qq == "联系QQ") {
        alert("请输入您的联系QQ！");
        jQuery("#freeqq").focus();
        return false;
    }else if(selectpdt == 0) {
        alert("请选择产品！");
        jQuery("#selectpdt").focus();
        return false;
    }
    else {
        jQuery.ajax({
            type: "POST",
            url: "/Index/free_experience_apply",
            data: { freename: jQuery("#freename").val(),freetel: jQuery("#freetel").val(),freeqq: jQuery("#freeqq").val(),selectpdt: jQuery("#selectpdt").val() },
            cache: false,
            success: function (msg) {
                if (msg != 'failure') {
                    alert(msg);
                }
                else
				{
                    alert("抱歉，系统错误，请点击客服咨询获取免费体验账号!");
				}

            }
        })
    }
}


function check_copt() {
	var emailReg = /^[_a-z0-9.]+@([_a-z0-9]+\.)+[a-z0-9]{2,3}/;
    var qqReg = /^[1-9][0-9]{4,9}/;
    var telreg = /^1[3458]\d{9}$/gi;
    var mobilereg = /^(0[0-9]{2,3}[\-]?)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?/;
    var Company = jQuery("#coptCompany").val();
    var name = jQuery("#coptname").val();
    var email = jQuery("#coptemail").val();
    var qq = jQuery("#coptqq").val();
    var tel = jQuery("#copttel").val();
    var mobile = jQuery("#coptmobile").val();
    var province = jQuery("#province_id").val();
    var city = jQuery("#city_id").val();
    var industry = jQuery("#industry").val();
    var sales_mode = jQuery("#sales_mode").val();
    if (Company == "") {
        alert("请输入公司名称");
        jQuery("#coptCompany").focus();
        return false;
    }
    else if (name == "" || name == "您的姓名") {
        alert("请输入您的姓名！");
        jQuery("#coptname").focus();
        return false;
    }
		else if (email == "") {
        alert("请输入email地址")
        jQuery("#coptemail").focus();
        return false;
    }
    else if (!emailReg.test(email)) {
        alert("邮箱输入有误！")
        jQuery("#coptemail").focus();
        return false;
    }
    else if (qq == "" || qq == "您的QQ号") {
        alert("请输入QQ号")
        jQuery("#coptqq").focus();
        return false;
    }
    else if (!qqReg.test(qq)) {
        alert("QQ号输入有误！")
        jQuery("#coptqq").focus();
        return false;
    }
    
    else if (tel == "" || tel == "移动电话：") {
        alert("请输入移动电话")
        jQuery("#copttel").focus();

    } else if (!telreg.test(tel)) {

        alert("请输入正确的移动电话")
        jQuery("#copttel").focus();
    }
    /*else if (mobile == "" || mobile == "固定电话：") {
        alert("请输入固定电话")
        jQuery("#coptmobile").focus();

    }*/ else if (mobile != "" && mobile != "固定电话：" && !mobilereg.test(mobile)) {

        alert("请输入正确的固定电话")
        jQuery("#coptmobile").focus();
    }
    /*else if (province == 0) {
        alert("请输入省份")
        jQuery("#province_id").focus();
				return false;
    }
     else if (city == 0) {
        alert("请输入城市")
        jQuery("#city_id").focus();
				return false;
    }*/else if (industry == 0) {
        alert("请选择行业")
        jQuery("#industry").focus();
				return false;
    }else if (sales_mode == 0) {
        alert("请选择销售模式")
        jQuery("#sales_mode").focus();
				return false;
    }
    else {
        jQuery.ajax({
            type: "POST",
            url: "/Index/agent_apply",
            data: {"company_name":Company, "realname":name, "email":email, "qq":qq, "mobile":tel, "phone":mobile, "industry":industry, "sales_mode":sales_mode},
            cache: false,
            success: function (msg) {
                if (msg != 'failure') {
                    alert("恭喜您，加盟信息已提交成功!");
                }
                else
				{
                    alert("抱歉，系统错误，请点击客服咨询取得联系!");
				}
            }
        })
    }
}
function leftside_show(){
	$("#leftside_min").hide();
	$("#left-side-flyelem").show();
	}
function leftside_hide(){
	$("#leftside_min").show();
	$("#left-side-flyelem").hide();
}
/*function oncase(nodeid) {
    $.getJSON("/include/casegallery.ashx?cate=" + nodeid).always(function (date) {
        var cacheDate = date;
        var cahceDiv = $("<div>");
        var target_grid = $("#case_grid");
        var target_magic = $(".magicwall");
        $.each(cacheDate, function (key, value) {
            //console.log(key,value["image_url"],value["image_title"],value["image_detal"],value["image_mark"])
            var wrapLink = $("<a>").addClass("hover-content").attr("href", value["image_link"])
				.append(
					$("<div>").addClass("hover-content-inner")
						.append($("<h2>").text(value["image_title"]))
						.append($("<p>").text(value["image_detal"]))
				);
            var items = $("<li>").addClass(value["image_mark"]).attr({
                "data-thumb": value["image_url"]
            });
            var itemsMerge = items.append(wrapLink);
            cahceDiv.append(itemsMerge);
        });

        target_grid.html(cahceDiv.html());
        $.getScript("/js/jquery.magicwall.min.js", function () {
			var options = {
				delay: 1300,
				preloadBeforeSwitch: true,
				animations: " flipX,flipY,slideX,slideY,slideRow,slideColumn,fade,-flipX,-flipY,-slideX,-slideY,-slideRow,-slideColumn,-fade",
				maxItemHeight: 120,
				maxColumnsCount: 5,
				flipXDuration: 1200,
				flipXEasing: "easeInOutBack",
				flipYDuration: 1200,
				flipYEasing: "easeInOutBack",
				slideXDuration: 1500,
				slideXEasing: "easeInOutBack",
				slideYDuration: 1500,
				slideYEasing: "easeInOutBack",
				rowsCount :3,
				pauseOnHover: "all"
			};
            target_magic.magicWall(options);
        });
    });
}*/

/*$(function () { */   
   /* $(".ai-banner-1").click(function () { location.href = "/about/us.html"; });
    $(".ai-banner-2").click(function () { location.href = "/service/weixin.html"; });
    $(".ai-banner-3").click(function () { location.href = "/service/mobile.html"; });
    $(".ai-banner-4").click(function () { location.href = "/service/3d.html"; });
    $(".ai-banner-5").click(function () { location.href = "/service/chart.html"; });

    $(".count-wrap").click(function () { location.href = "/about/us.html"; });*/

    //$(".si-qq").click(function () {
    //    window.open('http://b.qq.com/webc.htm?new=0&sid=4000218655&eid=218808P8z8p8p8R8z8q8p&o=www.adinnet.cn&q=7&ref=' + document.location, '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');
    //    window.opener = null; window.close();
    //});

    //var useragent = navigator.userAgent;
    //var ipad = /ipad/i.test(useragent);
    //var webkit = /applewebkit/i.test(useragent);
    //var firefox = /firefox/i.test(useragent);
    //var ios = /ipad|ipod|iphone/i.test(useragent);
    //var mao = location.hash.replace("#","");
    //var targetNav = $(".ai-nav-list");
    //var scrollelem = (function () {
    //    if (webkit === true) {
    //        return $("body")
    //    } else if (firefox === true) {
    //        return $("html")
    //    } else {
    //        return $("html")
    //    }
    //})();

    //if ($this.hasClass("cur")) { return false; }
    //var idx = mao;
    //var idx_head_height = targetNav[0].clientHeight;
    //var sct = document.getElementById(idx).offsetTop;
    //alert(sct + "===" + document.getElementById("index_blog").offsetTop);
    //var _easing = "";
    //if (sct >= 800) {
    //    _easing = "easeOutExpo";
    //} else {
    //    _easing = "swing";
    //}
    //scrollelem.animate({
    //    "scrollTop": sct - idx_head_height
    //}, 300);
/*});*/

function loadM() {

   // var $this = $("#" + mao + "1");
    alert(1);
    // $this.click();
}
