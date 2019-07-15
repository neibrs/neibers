(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mall = {
    attach: function attach(context, settings) {
      // 顶部快捷栏 地区切换 and 网站导航
      $("*[data-ectype='dorpdown']").once('dropdown').hover(function(){
        $(this).addClass("hover");
      },function(){
        $(this).removeClass("hover");
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


    }
  };
})(jQuery, Drupal, drupalSettings);
