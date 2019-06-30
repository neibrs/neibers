(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.admin = {
    attach: function attach(context) {
      // Message notify.
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
      //操作提示展开收起
      $("#explanationZoom").once('explanationZoom').on("click",function(){
        var explanation = $(this).parents(".explanation");
        var width = $(".region-help").width();
        if($(this).hasClass("shopUp")){
          $(this).removeClass("shopUp");
          $(this).attr("title","收起提示");
          explanation.find(".ex_tit").css("margin-bottom",10);
          explanation.animate({
            width: width-28
          },300,function(){
            $(".explanation").find("ul").show();
            $(".explanation").find("p").show();
          });
        }else{
          $(this).addClass("shopUp");
          $(this).attr("title","提示相关设置操作时应注意的要点");
          explanation.find(".ex_tit").css("margin-bottom",0);
          explanation.animate({
            width:"100"
          },300);
          explanation.find("ul").hide();
          explanation.find("p").hide();
        }
      });

      // fold left sidebar menu block
      $(".foldsider").once('foldsider').on('click', function(){
        var leftdiv = $(".admin-main");
        if(leftdiv.hasClass("fold")){
          leftdiv.removeClass("fold");
          $(this).find("i.icon").removeClass("icon-indent-right").addClass("icon-indent-left");
          leftdiv.find(".current").children(".sub-menu").show();

          loadEach();
        }else{
          leftdiv.addClass("fold");
          $(this).find("i.icon").removeClass("icon-indent-left").addClass("icon-indent-right");
          leftdiv.find(".sub-menu").hide();
          leftdiv.find(".sub-menu").css("top","0px");
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);