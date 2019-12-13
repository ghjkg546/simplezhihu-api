
				
		
			
<!-- *********************************** 
案例-列表
*********************************** -->
<div class="ai-eachcont con-wite clearfix" id="app">
	<div class="component-caselist-content clearfix">
		<div class="fl-l component-caselist-text">
			<div class="component-caselist-info">
				<!-- 旧版 -->
				<h3>{{news.title}}</h3>
				<p v-html="news.content"></p><br><strong>所属行业:</strong>{{news.industry
}}<br><strong>使用产品: </strong>{{news.product==null?'':news.product
}}<br><strong>关键词: </strong>响应式网站
											
			</div>
			<a href="/home/index" class="component-caselist-back"><span><i class="icon iconfont">&#xe628;</i>&nbsp;返回</span></a>
		</div>
		<div class="fl-r component-caselist-img con-gry">

			<a v-if="prev !==null && prev.id>0" :href="'/home/case-detail?id='+prev.id" alt="一触即发" class="ai-caselist-btnPrev"><i class="icon iconfont">&#xe628;</i></a>			<a v-if="next!==null &&next.id>0" :href="'/home/case-detail?id='+next.id" alt="交通汽校" class="ai-caselist-btnNext"><i class="icon iconfont">&#xe642;</i></a>			<div class="ai-caselist-pic">
				<span class="pc-img"><img :src="news.img_url" alt=""></span>
				<span class="pad-img"><img :src="news.img_url" alt=""></span>
				<span class="phone-img"><img :src="news.img_url" alt=""></span>
		</div>
		</div>
	</div>
</div>


		
			<!--右边飞行元素-->
<!-- <ul class="right-side-flyelem show">
	<a href="javascript:scroll(0,0);"><li class="rsf-items side-icon si-top"></li></a>
	<li class="right_wc_pic">
		<img src="/css/icon_wechart.png" class="wc_pic_center">
		<div class="Wc_qr_pic">
			<img src="/css/wc_add_pic.jpg">
		</div>
	</li>
	<a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&amp;key=XzkzODAwNTgxMF8xNzcwNDVfNDAwMDIxODY1NV8yXw" target="_blank">
		</a><a href="tencent://message/?uin=672651202&amp;Site=%E7%A4%BA%E5%89%91%E7%BD%91%E7%BB%9C&amp;Menu=yes" onclick="contact(1)" target="_blank">
		<li class="rsf-items side-icon si-qq"></li>
	</a>
	<li class="rsf-items side-icon si-phone"></li>
</ul> -->
<!--关注微信-->
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
	            NavIndex:3,
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
	                    url: "/home/get-case-detail",
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

	<link href="/css/case.css" rel="stylesheet" type="text/css">
	<link href="/css/case_1280.css" rel="stylesheet" type="text/css">
	<link href="/css/case_1024.css" rel="stylesheet" type="text/css">
	<link href="/css/case_640.css" rel="stylesheet" type="text/css">
	<link href="/css/case_320.css" rel="stylesheet" type="text/css">

	<style>
		.component-caselist-img img{width:100%;}
		.component-caselist-img .phone-img img{/*width:280px;*/width: 100%;}
		.component-caselist-img .pad-img img{width:100%;/*width:500px;*/}
		.left-side-flyelem-min.showed{display: none;}
		.right-side-flyelem.show{display: none;}
		.ai-caselist-pic img{
			margin:-1px;
		}
	</style>