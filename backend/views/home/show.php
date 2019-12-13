

<link href="/css/layout.css" rel="stylesheet" type="text/css">
<link href="/css/news_style.css" rel="stylesheet" type="text/css">
			
<div class="art_box" id="#app">
	<div class="art_content">
		<h1 class="art_show_title">
            {{news.title}}		</h1>
		<div class="art_show1">
			<i class="addtime"></i>发布时间：{{news.create_time}} <i class="view_num"></i>阅读次数：{{news.view_count}}		</div>
		<style type="text/css">
			.art_show2 p{
				line-height: 2em;font-family: arial, helvetica, sans-serif; font-size: 16px;
			}
		</style>
        
		<div class="art_show2" v-html="news.content"></div>
        
        <link rel="canonical" href="http://www.shechem.net/">

        <div style="padding-left: 17px; padding-right: 17px;">

		<div class="art_show1">
		</div>

		<div class="art_show_nav">
						<span v-if="prev !==null && prev.id>0">上一条:</span><a v-if="prev !==null && prev.id>0" :href="'/home/show?id='+prev.id">{{prev.title}}</a>
            <br>
            <span v-if="next!==null &&next.id>0">下一条:</span>
			<a v-if="next!==null &&next.id>0" :href="'/home/show?id='+next.id">{{next.title}}</a>		</div>
		<!-- <br clear="all"> -->
	</div>
</div>


		
			<!--右边飞行元素-->
<!-- <ul class="right-side-flyelem show">
	<a href="javascript:scroll(0,0);"><li class="rsf-items side-icon si-top"></li></a>
	<li class="right_wc_pic">
		<img src="/images/icon_wechart.png" class="wc_pic_center">
		<div class="Wc_qr_pic">
			<img src="/images/wc_add_pic.jpg">
		</div>
	</li>
	<a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&amp;key=XzkzODAwNTgxMF8xNzcwNDVfNDAwMDIxODY1NV8yXw" target="_blank">
		</a><a href="tencent://message/?uin=672651202&amp;Site=%E7%A4%BA%E5%89%91%E7%BD%91%E7%BB%9C&amp;Menu=yes" onclick="contact(1)" target="_blank">
		<li class="rsf-items side-icon si-qq"></li>
	</a>
	<li class="rsf-items side-icon si-phone"></li>
</ul> -->

<div class="wx-2-dimensional-bar-code">
	<div class="wx-pop-title">
		<span>微信扫一扫</span>
	</div>
	<div class="show-wx2dc"></div>
	
	<div class="wx2dc_tip">
		<em>或者在 <span>添加朋友</span> - <span>查找公众号</span> 搜索 <span>示剑网络</span></em>
	</div>
	<div class="wx-cls-btn"></div>
</div>
		
	</div>


	<script type="text/javascript" src="/css/index91f8.js.下载"></script>
	<script src="/css/swiper.3.1.2.jquery.min.html"></script>
	
	<script>
		// 小窗口下的顶右小导航操作
		$(function(){
			var targetNav = $(".ai-nav-list");
			var minside_states = "hide";
				$(".sidebtn").on("click",function(e) {
					e.stopPropagation();
					e.preventDefault();
					if (minside_states === "hide") {
						$(this).addClass("sidebtn_show");
						targetNav.addClass("show");
						minside_states= "show";
					} else if (minside_states === "show" ) {
						$(this).removeClass("sidebtn_show");
						targetNav.addClass("hide");
						targetNav.one("webkitAnimationEnd animationend", function(e) {
							targetNav.removeClass("show hide");
						});
						minside_states= "hide";
					}
				});	
		});
		function contact(account_id)
		{
			var xhr=new createXHR();

			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					if ((xhr.status > 200 && xhr.status < 300) || xhr.status == 304) {
						// alert(xhr.responseText);
					} else {
						// alert("请求成功: " + xhr.status+",responseText:"+xhr.responseText);
					}
				}
			};

			xhr.open("../../../Common/page404.html", "/DataReceive/receive_contact_stat", true);
			xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");

			var data={
				"user_id": user_id,
				"ip": ip,
				"account_id": account_id
			};

			var sdata=JSON.stringify(data)
			// alert(sdata);
	xhr.send(sdata);
		}

	</script>

	
	<span style="display:none;"><img src="/images/wapstat.php" style="width:0px; height:0px"></span>
	
    	<script>

(function(b,a,e,h,f,c,g,s){b[h]=b[h]||function(){(b[h].c=b[h].c||[]).push(arguments)};
b[h].s=!!c;g=a.getElementsByTagName(e)[0];s=a.createElement(e);
s.src="//s.union.360.cn/"+f+".js";s.defer=!0;s.async=!0;g.parentNode.insertBefore(s,g)
})(window,document,"script","_qha",219370,false);
		</script>

<script>
    var app = new Vue({
        el:'#app',
        data: {
            current_cate_index:0,
            cates:[],
            news:[],
            big:[],
            links:[],
            prev:[],
            next:[],
            system_info:{},
            NavIndex:5,
            main:{
                class:'trans',
                // position: 'relative',
            },
            left:{
                ico:'back',
            },
            right:{
                ico:'iservice',
            },
            center:{
                title:'',
            }
        },
        methods: {
            loadData:function(cid){
                let that=this;
                that.current_cate_index = cid;

                let tmp_id=that.getQueryVariable('id')
                var param= {
                    id:tmp_id
                };
                $.ajax({
                    url: "/home/get-detail",
                    type: "post",
                    data:JSON.stringify(param),
                    contentType: "application/json; charset=utf-8",
                    dataType:'json',
                    success: function(res){
                        that.news = res.news;
                        that.links = res.links;
                        that.prev=res.prev;
                        that.next = res.next;
                    }});
            },
            getQueryVariable:function(variable)
            {
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i=0;i<vars.length;i++) {
                    var pair = vars[i].split("=");
                    if(pair[0] == variable){return pair[1];}
                }
                return(false);
            },
        },
        created:function(){
            this.loadData(1)
        }
    })
</script>
		


<!-- Mirrored from beyondin.com/FrontNews/news_detail/id/212.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 03 Apr 2018 16:24:28 GMT -->

</body></html>