(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mall = {
    attach: function attach(context, settings) {

      var doc = $(document);

      // 顶部快捷栏 地区切换 and 网站导航
      $("*[data-ectype='dorpdown']").once('dropdown').hover(function(){
        $(this).addClass("hover");
      },function(){
        $(this).removeClass("hover");
      });
      //顶部快捷栏 地区选择
      $("*[data-ectype='dorpdown'] *[ectype='dsc-choie']").on("mouseenter",function(){
        $("*[ectype='dsc-choie-content']").html(load_cart_info);
        $.jqueryAjax('get_ajax_content.php', 'act=insert_header_region', function(data){
          if(data.content){
            $("*[ectype='dsc-choie-content']").html(data.content);
          }
        });
      });

      // 面包屑
      $(".crumbs-nav-item .menu-drop").hover(function(){
        $(this).addClass("menu-drop-open");
      },function(){
        $(this).removeClass("menu-drop-open");
      });

      //返回顶部
      doc.on("click","[ectype='returnTop']",function(){
        $("body,html").animate({scrollTop:0});
      });

      //top_banner关闭
      $("*[ectype='close']").click(function(){
        $(this).parents(".top-banner").hide();
      });

      //底部二维码切换
      $(".help-scan .tabs li").hover(function(){
        var t = $(this);
        var index = t.index();
        t.addClass("curr").siblings().removeClass("curr");
        $(".code").find(".code_tp").eq(index).show().siblings().hide();
      });

      //价格筛选
      $(".fP-box input").click(function(){
        $('.fP-expand').show();
      });

      //价格筛选提交
      $('.ui-btn-submit').click(function(){
        var min_price = Number($(".price-min").val());
        var max_price = Number($(".price-max").val());

        if(min_price == '' && max_price == ''){
          pbDialog(json_languages.screen_price,"",0);
          return false;
        }else if(min_price == ''){
          pbDialog(json_languages.screen_price_left,"",0);
          return false;
        }else if(max_price == ''){
          pbDialog(json_languages.screen_price_right,"",0);
          return false;
        }else if(min_price > max_price || min_price == max_price){
          pbDialog(json_languages.screen_price_dy,"",0,"","",70);
          return false;
        }
        $("form[name='listform']").submit();
      });

      $('.ui-btn-clear').click(function(){
        $("input[name='price_min']").val('');
        $("input[name='price_max']").val('');
      });

      //优惠活动价格筛选提交（团购、夺宝奇兵等）
      $('.ui-btn-submit').click(function(){
        $("form[name='listform']").submit();
      });

      //Expanded all the nav category
      $("*[ectype='cateItem']").once('cateItem').on('mouseenter',function(){
        var T = $(this),
          cat_id = T.data('id'),
          eveval = T.data('eveval'),
          layer = T.find("*[ectype='cateLayer']");

        if(eveval != 1){
          T.data('eveval', '1');
          /*加载中*/
          // layer.find("*[ectype='cateLayerCon_" + cat_id + "']").html('<img src="'+ settings.theme.path +'/images/load/loadGoods.gif" width="200" height="200" class="lazy">');
        //   $.ajax({
        //     type: "GET",
        //     url: "ajax_dialog.php",
        //     data: "act=getCategoryCallback&cat_id=" + cat_id,
        //     dataType:'json',
        //     success: function(data){
        //       if(data.has_child == 0){
        //         T.find("*[ectype='cateLayer']").addClass("cateLayer_items_all");
        //       }else{
        //         T.find("*[ectype='cateLayer']").removeClass("cateLayer_items_all");
        //       }
        //
        //       var channels = $("*[ectype='cateLayerCon_" + data.cat_id + "']");
        //       channels.html(data.cat_content);
        //     }
        //   });
        }

        T.addClass("selected");
        layer.show();
      }).on("mouseleave",function(){
        var T = $(this),layer = T.parent().find("*[ectype='cateLayer']");
        T.removeClass("selected");
        layer.hide();
      });



      /***************************** Click event in sidebar second float content start***************************************/
      //移动图标出现文字提示
      $(".quick_links_panel li").once('quick_links_panel_li').hover(function(){
        $(this).find(".mp_tooltip").stop().animate({left:-92,queue:true});
        $(this).find(".mp_tooltip").css("visibility","visible");
        $(this).find(".ibar_login_box").show();
      },function(){
        $(this).find(".mp_tooltip").css("visibility","hidden");
        $(this).find(".mp_tooltip").stop().animate({left:-121,queue:true});
        $(this).find(".ibar_login_box").hide();
      });

      //点击图标判断用户是否登录
      $(".quick_links li").find("a").once('quick_links_li_a').click(function(){
        var $this = $(this),
          user_id = $this.parents(".quick_link_main").data("userid");

        if(user_id < 1 && !$this.hasClass('cart_list') && !$this.hasClass('mpbtn_history') && !$this.hasClass('mpbtn_email')){
          $.notLogin("get_ajax_content.php?act=get_login_dialog",'');
          return false;
        }
      });

      //点击展开邮箱订阅
      $(".mpbtn_email").once('mpbtn_email').click(function(){
        var obj = $(".email_sub");
        if(obj.hasClass("show")){
          obj.removeClass("show");
        }else{
          obj.addClass("show");
        }
      });

      //判断浏览器下滚还是上滚，向上滚动邮箱验证隐藏
      $(document).ready(function(){
        var p=0,t=0;
        var obj = $(".email_sub");
        $(window).scroll(function(e){
          p = $(this).scrollTop();
          if(t<=p){
            if(obj.hasClass("show")){
              obj.addClass("show");
            }
          }else{
            obj.removeClass("show");
          }
          setTimeout(function(){t = p;},0);
        });
      });
      /***************************** Click event in sidebar second float content end***************************************/

      // Brand
      $(document).on("click","*[ectype='coll_brand']",function(){
        var user_id = $("input[name=user_id]").val();
        if(user_id > 0){
          var brand_id = $(this).data('bid');
          if($(this).find("i").hasClass("icon-zan-alts")){
            $(this).find("i").removeClass("icon-zan-alts").addClass("icon-zan-alt");
            $(this).find("*[ectype='follow_span']").html("关注");
            Ajax.call('brandn.php', 'act=cancel&id=' + brand_id +'&user_id='+user_id, collect_brandResponse, 'POST', 'JSON');
          }else{
            $(this).find("i").removeClass("icon-zan-alt").addClass("icon-zan-alts");
            $(this).find("*[ectype='follow_span']").html("已关注");
            Ajax.call('brandn.php', 'act=collect&id=' + brand_id, collect_brandResponse, 'POST', 'JSON');
          }
        }else{
          var back_url = "brand.php";
          $.notLogin("get_ajax_content.php?act=get_login_dialog",back_url);
        }
      });

      //关注品牌回调函数
      function collect_brandResponse(result) {
        $("#collect_count").html(result.collect_count);
        $("#collect_count_"+result.brand_id).html(result.collect_count);;
      }

      /****Get coupons start***/
      $(document).on("click",".get-coupon",function(){
        var cou_id = $(this).attr('cou_id');
        var coupon = '';
        if($(this).data('coupon')){
          coupon = $(this).data('coupon');
        }
        receiveCoupon(cou_id,coupon);
      });

      function receiveCoupon(cou_id,coupon){
        if(user_id > 0){
          $.post('coupons.php?act=coupons_receive',{'cou_id':cou_id},function(data){
            if(data.status=='ok'){
              $(".item-fore h3").html(data.msg);
              $(".success-icon").removeClass("i-icon").addClass("m-icon");
              var content =$("#pd_coupons").html();
              pb({
                id:"coupons_dialog",
                title:json_languages.receive_coupons,
                width:550,
                height:140,
                ok_title:json_languages.Immediate_use, 	//按钮名称
                cl_title:json_languages.close, 	//按钮名称
                content:content, 	//调取内容
                drag:false,
                foot:true,
                onOk:function(){
                  location.href="search.php?cou_id="+cou_id
                },
                onCancel:function(){
                  $(".cou-data").html(data.content);
                  $(".cou-seckill").html(data.content_kill);
                  $(".cou_shipping").html(data.content_shipping);
                },
              });

              $(".pb-ok").addClass("color_df3134");
            }else{
              $(".success-icon").removeClass("m-icon").addClass("i-icon");
              $(".item-fore h3").addClass("red");
              $(".item-fore h3").html(data.msg);
              var content =$("#pd_coupons").html();
              pb({
                id:"coupons_dialog",
                title:json_languages.receive_coupons,
                width:550,
                height:140,
                ok_title:json_languages.close, 	//按钮名称
                content:content, 	//调取内容
                cl_cBtn:false,
                onOk:function(){}
              });
            }
          },'json');
        }else{
          var back_url = "coupons.php?act=coupons_index";
          if(coupon == 1){
            back_url = 'coupons.php?act=coupons_info&id=' + cou_id;
          }
          $.notLogin("get_ajax_content.php?act=get_login_dialog",back_url);
          return false;
        }
      }
      /**** Get coupons end***/

      /* 商品收藏 品牌关注 店铺关注 */
      $(document).on('click',"*[data-dialog='goods_collect_dialog']",function(){
        var url = $(this).data('url'),
          id = $(this).data('goodsid'),
          divId = $(this).data("divid"),
          width = 455,
          height = 58,
          content = "",
          goods_url = "",
          type = $(this).data("type");

        if(user_id == 0 && type == "goods"){
          goods_url = "goods.php?id=" + id;
          $.notLogin("get_ajax_content.php?act=get_login_dialog", goods_url);
          return false;
        }

        if(id > 0){
          Ajax.call(url, 'id=' + id, function(data){
            if(data.error > 0){
              if(data.error == 2){
                $.notLogin("get_ajax_content.php?act=get_login_dialog", data.url);
                return false;
              }

              pbDialog(data.message,"",0,width,height,65,true,function(){
                location.href = "user.php?act=collection_list";
              },"会员中心");

            }else{
              $(".choose-btn-coll").addClass('selected');
              $(".choose-btn-icon").addClass('icon-collection-alt').removeClass('icon-collection');
              $("#collect_count").html(data.collect_count);

              pbDialog("您已成功收藏该商品！","",1,width,height,95,false,function(){
                location.href = "user.php?act=collection_list";
              },json_languages.My_collection);
            }

          }, 'GET', 'JSON');
        }else{
          if(divId == 'delete_goods_collect'){
            content = "您确定要取消收藏该商品吗？";
          }else if(divId == "delete_brand_collect"){
            content = "您确定要取消关注该品牌吗？";
          }else if(divId == "user_attention"){
            content = $(this).data("confirmtitle");
          }

          pbDialog(content,"",0,width,height,95,true,function(){
            location.href = url;
          });
        }
      });

      /* 对比框隐藏 */
      $("[ectype='db_hide']").on("click",function(){
        $("#slideTxtBox").hide();
      });

      /* 对比 */
      var db_winWidth = $(window).width();
      var db_left = (db_winWidth-1200)/2;
      $("#slideTxtBox").css({"left":db_left});

      $(window).resize(function(){
        db_winWidth = $(this).width();
        if(db_winWidth>1200){
          db_left = (db_winWidth-1200)/2;
          $("#slideTxtBox").css({"left":db_left});
        }else{
          $("#slideTxtBox").css({"left":0});
        }
      });


      //商品名称title设置了颜色 前台处理title html代码
      $(".p-name a").each(function(){
        if($(this).prop("title") != ""){
          var title = $(this).attr('title');
          var newTitle = title.replace(/<\/?[^>]*>/g,'');

          $(this).attr('title',newTitle);
        }
      });

      /*var brand_select = $(".brand_select_more");
      if(brand_select.length>0){
        brand_select.hover(function(e){
                $(".brand_select_more").perfectScrollbar("destroy");
          $(".brand_select_more").perfectScrollbar();
            });
      }*/

      //点击空白处隐藏展开框
      $(document).click(function(e){
        //购物车更多促销活动
        if(e.target.className !='sales-promotion' && !$(e.target).parents("div").is("[ectype='promInfo']")){
          $("[ectype='promInfo']").removeClass("prom-hover");
        }

        if(e.target.id !='price-min' && e.target.id !='price-max'){
          $('.fP-expand').hide();
        }

        //仿select
        if(e.target.className !='cite' && !$(e.target).parents("div").is(".imitate_select")){
          $('.imitate_select ul').hide();
        }

        if(e.target.id !='btn-anchor' && !$(e.target).parents("div").is(".tb-popsku")){
          $('.tb-popsku').hide();
        }

        //首页弹出广告
        if(e.target.className == 'ejectAdvbg' && !$(e.target).parents("div").is(".ejectAdvimg")){
          $("*[ectype='ejectAdv']").hide();
        }
      });


      $(".value-item").click(function(){
        $(this).addClass("selected").siblings().removeClass("selected");
      });

      //div仿select下拉选框 start
      $(document).on("click",".imitate_select .cite",function(){
        $(".imitate_select ul").hide();
        $(this).parents(".imitate_select").find("ul").show();
        $(this).siblings("ul").perfectScrollbar("destroy");
        $(this).siblings("ul").perfectScrollbar();
      });

      $(document).on("click",".imitate_select li  a",function(){
        var _this = $(this);
        var val = _this.data('value');
        var text = _this.html();
        _this.parents(".imitate_select").find(".cite span").html(text).css("color","#707070");
        _this.parents(".imitate_select").find("input[type=hidden]").val(val);
        _this.parents(".imitate_select").find("ul").hide();
      });
      //div仿select下拉选框 end


      //input获得焦点加样式
      $("input.text").focus(function(){
        $(this).parents(".item").addClass("item-focus");
      });

      $("input.text").blur(function(){
        $(this).parents(".item").removeClass("item-focus");
      });

    }
  };
})(jQuery, Drupal, drupalSettings);
