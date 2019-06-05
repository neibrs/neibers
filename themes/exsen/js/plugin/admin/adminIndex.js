(function ($, Drupal, drupalSettings) {

	Drupal.behaviors.myModuleBehavior = {
		attach: function (context, settings) {

	//顶部管理员信息展开
	function adminSetup(){
		var hoverTimer, outTimer;
		$('#admin-manager-btn,.manager-menu,.admincp-map').mouseenter(function(){
			clearTimeout(outTimer);
			hoverTimer = setTimeout(function(){
				$('.manager-menu').show();
				$('#admin-manager-btn i').removeClass().addClass("arrow-close");
			},200);
		});
		
		$('#admin-manager-btn,.manager-menu,.admincp-map').mouseleave(function(){
			clearTimeout(hoverTimer);
			outTimer = setTimeout(function(){
				$('.manager-menu').hide();
				$('#admin-manager-btn i').removeClass().addClass("arrow");
			},100);	
		});
	}
	adminSetup();
	
	function loadEach(){
		$('.admincj_nav').find('div[id^="adminNavTabs_"]').once('admincj_nav').each(function(){
			var $this = $(this);
			
			var name = $this.attr("id").replace("adminNavTabs_","");

			// Set navigate block menu style.
			$('.module-menu ul li').once('first-module-menu').each(function(i) {
				var param = $(this).data('param');

				if (param == name) {
					$(this).addClass('active').siblings().removeClass('active');
				}
				else {
					$(this).removeClass('active');
				}
			});

			$this.find('.item > .tit > a').once('item_tit_a').each(function(i){
				$(this).parent().next().css('top', (-68)*i + 'px');
				$(this).once('left-menu-click').click(function(){
					var type = $(this).parent().parents(".item").data("type");
					if(type == "home"){
						var url = $(this).data('url');
						var param = $(this).data('param');
						
						//$(".admin-main").addClass("start_home");
						$(".admincj_nav").find(".item").eq(0).addClass("current").siblings().removeClass("current");
						$(".admincj_nav").find(".item").eq(0).show();
						$(".module-menu").find("li").removeClass("active");
						$this.find('.sub-menu').hide();
						openItem(param,1);
					}else{
						var url = '';
						$this.find('.sub-menu').hide();
						$this.find('.item').removeClass('current');
						if(name == "menushopping"){
							//商品 默认三级分类链接到第二个 商品列表
							var param = $(this).parent().next().find('a:first').data('param');
							var data_str = param.split('|');
							if($(this).parents('.item').index() == 0 && data_str[1] == "001_goods_setting"){
								$(this).parents('.item').eq(1).addClass('current');
								$(this).parent().next().find('a').eq(1).click();
								url = $(this).parent().next().find('a').eq(1).data('url');
							}else{
								$(this).parents('.item:first').addClass('current');
								$(this).parent().next().find('a:first').click();
								url = $(this).parent().next().find('a:first').data('url');
							}
						}else{
							$(this).parents('.item:first')
								.addClass('current')
								.find('.sub-menu')
								.show();
							$(this).parent().next().find('a:first').click();
							url = $(this).parent().next().find('a:first').data('url');
						}
						// loadUrl(url);
					}
				});
			});
		});
	}
	loadEach();
	
	//右侧二级导航选择切换
	$(".sub-menu li a").on("click",function(){
		var param = $(this).data("param");
		var url = $(this).data("url");
		if(param != null){
			loadUrl(url);
			openItem(param);
		}
	});
	

	
	//后台提示
	$(document).on("click","#msg_Container .msg_content a",function(){
		var param = $(this).data("param");
		var url = $(this).data("url");
		
		loadUrl(url);
		openItem(param);
	});

	
	function ready(){
		var bwidth = $(window).width();
		
		if(bwidth < 1380){
			$(".foldsider").click();
		}
		
		$(window).resize(function(){
			bwidth = $(window).width();

			if(bwidth < 1380 && !$(".admin-main").hasClass("fold")){
				$(".foldsider").click();
			}
		});
	}
	
	ready();
	
	var foldHoverTimer, foldOutTimer,foldHoverTimer2;
	$(document).on("mouseenter",".fold .tit",function(){
		var $this = $(this);
		var items = $this.parents(".item");
		
		var length = items.find(".sub-menu").find("li").length;
		items.parent().find(".item:gt(5)").find(".sub-menu").css("top",-((40*length)-68));
		$this.next().show();
		items.addClass("current");
		items.siblings(".item").removeClass("current");
	});
	
	$(document).on("mouseleave",".fold .tit",function(){
		var $this = $(this);
		clearTimeout(foldHoverTimer);
		foldOutTimer = setTimeout(function(){
			$this.next().hide();
		});
	});
	
	$(document).on("mouseenter",".fold .sub-menu",function(){
		clearTimeout(foldOutTimer);
		var $this = $(this);
		foldHoverTimer2 = setTimeout(function(){
			$this.show();
		});
	});
	
	$(document).on("mouseleave",".fold .sub-menu",function(){
		var $this = $(this);
		$this.hide();
	});
	
	//没有cookie默认选择起始页
	if ($.cookie('senActionParam') == null) {
        $('.admin-logo').find('a').click();
    } else {
        openItem($.cookie('senActionParam'));
    }

	//顶部布局换色设置
	var bgColorSelectorColors = [{ c: '#981767', cName: '' }, { c: '#AD116B', cName: '' }, { c: '#B61944', cName: '' }, { c: '#AA1815', cName: '' }, { c: '#C4182D', cName: '' }, { c: '#D74641', cName: '' }, { c: '#ED6E4D', cName: '' }, { c: '#D78A67', cName: '' }, { c: '#F5A675', cName: '' }, { c: '#F8C888', cName: '' }, { c: '#F9D39B', cName: '' }, { c: '#F8DB87', cName: '' }, { c: '#FFD839', cName: '' }, { c: '#F9D12C', cName: '' }, { c: '#FABB3D', cName: '' }, { c: '#F8CB3C', cName: '' }, { c: '#F4E47E', cName: '' }, { c: '#F4ED87', cName: '' }, { c: '#DFE05E', cName: '' }, { c: '#CDCA5B', cName: '' }, { c: '#A8C03D', cName: '' }, { c: '#73A833', cName: '' }, { c: '#468E33', cName: '' }, { c: '#5CB147', cName: '' }, { c: '#6BB979', cName: '' }, { c: '#8EC89C', cName: '' }, { c: '#9AD0B9', cName: '' }, { c: '#97D3E3', cName: '' }, { c: '#7CCCEE', cName: '' }, { c: '#5AC3EC', cName: '' }, { c: '#16B8D8', cName: '' }, { c: '#49B4D6', cName: '' }, { c: '#6DB4E4', cName: '' }, { c: '#8DC2EA', cName: '' }, { c: '#BDB8DC', cName: '' }, { c: '#8381BD', cName: '' }, { c: '#7B6FB0', cName: '' }, { c: '#AA86BC', cName: '' }, { c: '#AA7AB3', cName: '' }, { c: '#935EA2', cName: '' }, { c: '#9D559C', cName: '' }, { c: '#C95C9D', cName: '' }, { c: '#DC75AB', cName: '' }, { c: '#EE7DAE', cName: '' }, { c: '#E6A5CA', cName: '' }, { c: '#EA94BE', cName: '' }, { c: '#D63F7D', cName: '' }, { c: '#C1374A', cName: '' }, { c: '#AB3255', cName: '' }, { c: '#A51263', cName: '' }, { c: '#7F285D', cName: ''}];
	$("#trace_show").once('trace_show').click(function(){
		$("div.bgSelector").toggle(300, function() {
			if ($(this).html() == '') {
				$(this).sColor({
					colors: bgColorSelectorColors,  // 必填，所有颜色 c:色号（必填） cName:颜色名称（可空）
					colorsWidth: '50px',  // 必填，颜色的高度
					colorsHeight: '31px',  // 必填，颜色的高度
					curTop: '0', // 可选，颜色选择对象高偏移，默认0
					curImg: settings.path.baseUrl + settings.path.themeUrl+ '/css/images/cur.png',  //必填，颜色选择对象图片路径
					form: 'drag', // 可选，切换方式，drag或click，默认drag
					keyEvent: true,  // 可选，开启键盘控制，默认true
					prevColor: true, // 可选，开启切换页面后背景色是上一页面所选背景色，如不填则换页后背景色是defaultItem，默认false
					defaultItem: ($.cookie('bgColorSelectorPosition') != null) ? $.cookie('bgColorSelectorPosition') : 22  // 可选，第几个颜色的索引作为初始颜色，默认第1个颜色
				});
			}
		});//切换显示
	});
	if ($.cookie('bgColorSelectorPosition') != null) {
		$('body').css('background-color', bgColorSelectorColors[$.cookie('bgColorSelectorPosition')].c);
	} else {
		$('body').css('background-color', bgColorSelectorColors[30].c);
	}

	//上传管理员头像
	$("#_pic").change(function(){
		var actionUrl = "index.php?act=upload_store_img";
		$("#fileForm").ajaxSubmit({
			type: "POST",
			dataType: "json",
			url: actionUrl,
			data: { "action": "TemporaryImage" },
			success: function (data) {
				if (data.error == "0") {
					alert(data.massege);
				} else if (data.error == "1") {
					$(".avatar img").attr("src", data.content);
				}
			},
			async: true
		});
	});

	/*  @author-bylu 添加快捷菜单 start  */
	$('.admincp-map-nav li').click(function(){
		var i = $(this).index();
		$(this).addClass('selected');
		$(this).siblings().removeClass('selected');
		$('.admincp-map-div').eq(i).show();
		$('.admincp-map-div').eq(i).siblings('.admincp-map-div').hide();
	});

	$('.admincp-map-div dd i').click(function(){
		var auth_name = $(this).prev('a').text();
		var auth_href = $(this).prev('a').attr('href');
		if(!$(this).parent('dd').hasClass('selected')){

			if($('.admincp-map-div dd.selected').length >=10){
				alert('最多只允许添加10个快捷菜单!');return false;
			}

			$(this).parent('dd').addClass('selected');
			$('.quick_link ul').append('<li class="tl"><a href="'+auth_href+'" data-url="'+auth_href+'" data-param="" target="workspace">'+auth_name+'</a></li>')

			$.post('index.php?act=auth_menu',{'type':'add','auth_name':auth_name,'auth_href':auth_href});

		}else{
			$(this).parent('dd').removeClass('selected');
			$('.quick_link ul li').each(function(k,v){
				if(auth_name == $(v).text()){
					$(v).remove();
				}
			});
			$.post('index.php?act=auth_menu',{'type':'del','auth_name':auth_name,'auth_href':auth_href});
		}
	});

	$('.add_nav,.sitemap').click(function(){
		$('#allMenu').show();
	});
        
	//消息通知
	function message(){
		var hoverTimer, outTimer;
		$("*[ectype='oper_msg']").mouseenter(function(){
			clearTimeout(outTimer);
			hoverTimer = setTimeout(function(){
				$('#msg_Container').show();
			},200);
		});
		
		$("*[ectype='oper_msg']").mouseleave(function(){
			clearTimeout(hoverTimer);
			outTimer = setTimeout(function(){
				$('#msg_Container').hide();
			},100);	
		});
	}
	
	message();
	
	/* 后台消息提示 展开伸缩*/
	$("[ectype='msg_tit']").on("click",function(){
		var t = $(this),
			con = t.siblings(".msg_content"),
			Item = t.parents(".item");
			
		if(con.is(":hidden")){
			con.slideDown();
			Item.siblings().find(".msg_content").slideUp();
			t.find(".iconfont").addClass("icon-up").removeClass("icon-down");
			Item.siblings().find(".iconfont").removeClass("icon-up").addClass("icon-down");
		}else{
			con.slideUp();
			t.find(".iconfont").removeClass("icon-up").addClass("icon-down");
		}
	});
//判断浏览器是ie6 - ie8 后台不可以进入方法
// 	function notIe(){
// 		pb({
// 			id:'notIe',
// 			content:'<div class="noContent"><div class="noText"><p class="p1">您当前浏览器版本过低</p><p class="p1">不支持浏览</p><p class="p2">建议使用</p><p class="p3">谷歌、火狐、360极速、IE9以上版本</p></div></div>',
// 			drag:false,
// 			head:false,
// 			cl_cBtn:false,
// 			width:316,
// 			height:376,
// 			ok_title:"确定",
// 			onOk:function(){
// 				location.href = "../index.php";
// 			}
// 		});
//
// 		$("#pb-mask").css('cssText','position: fixed; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 1; overflow: hidden; z-index: 2000; background-color:#fff;')
// 	}
// 	/* 判断浏览器是ie6 - ie8 后台不可以进入*/
// 	if(!$.support.leadingWhitespace){
// 		notIe();
// 	}


	//iframe内页 a标签链接跳转方法
		function intheHref(obj){
			var url = obj.data("url"),
				param = obj.data("param");

			openItem(param);
			loadUrl(url);
		}

			function openItem(param,home){
				var $this = $('div[id^="adminNavTabs_"]').find('a[data-param="' + param + '"]');
				var url = $this.data('url');

				data_str = param.split('|');

				if(home == 0){
					//$this.parents('.admin-main').removeClass('start_home');
				}

				if($this.parents(".admin-main").hasClass("fold")){
					$this.parents('.sub-menu').hide();
				}else{
					$this.parents('.sub-menu').show();
				}

				$this.parents('.item').addClass('current').siblings().removeClass('current');
				$this.parents('.item').siblings().find(".sub-menu").hide();
				$this.parents('li').addClass('curr').siblings().removeClass('curr');
				$this.parents('div[id^="adminNavTabs_"]').show().siblings().hide();

				$('li[data-param="' + data_str[0] + '"]').addClass('active').siblings().removeClass("active");

				$.cookie('senActionParam', data_str[0] + '|' + data_str[1] , { expires: 1 ,path:'/'});

				if(param == 'home') {
					$('#adminNavTabs_home').show().siblings().hide();
					$('#adminNavTabs_home').find(".sub-menu").show();
					$('#adminNavTabs_home .sub-menu').find("li a:first").click();
					url = 'index.php?act=main';
					loadUrl(url);
				}

				/*if(param == "index|main"){
					$(".admin-main").addClass("start_home");
				}else{
					$(".admin-main").removeClass("start_home");
				}*/
			}

			function loadUrl(url){
				$.cookie('senUrl', url , { expires: 1 ,path:'/'});

				// $(window.location).attr('href', url);
				// $('.admin-main-right iframe[name="workspace"]').attr('src','dialog.php?act=getload_url');
				// setTimeout(function(){
				// 	$('.admin-main-right iframe[name="workspace"]').attr('src', url);
					/* 检查订单 */
					// startCheckOrder();

					/* 检查账单 */
					//startCheckBill();
				// },300);
			}

		}
	};

})(jQuery, Drupal, drupalSettings);

