(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.exsen = {
    attach: function attach(context) {
      // --- Start set window size ----
      var bodyWidth = $("body").width();
      var bodyHeight = $("body").height();

      if(bodyWidth<1380){
        $("#workspace").attr("height","92%");
      }else{
        $("#workspace").attr("height","95%");
      }

      $('.admin-main-left').css("min-height", bodyHeight-48);
      $('.admin-main-left').css("height", bodyHeight-48);
      $('.admincj_nav').css("height", bodyHeight-48);
      $('.admin-main-right').css("min-height", bodyHeight-48);
      $(window).resize(function(e) {
        bodyWidth = $("body").width();

        if(bodyWidth<1380){
          $("#workspace").attr("height","92%");
        }else{
          $("#workspace").attr("height","95%");
        }

        $('.admin-main-left').css("height", bodyHeight-48);
        $('.admin-main-right').css("height", bodyHeight-48);
      });
      // --- End set window size ----

      //Top navigate menu toggle
      $(".module-menu li a").once('module-menu-click').on("click",function(){
        var modules = $(this).parent().data("param");
        var items = $("#adminNavTabs_"+ modules).find(".item");
        var first_item = items.first();

        items.find('.sub-menu').hide();
        $(this).parent().addClass("active").siblings().removeClass("active");
        $("#adminNavTabs_" + modules).show().siblings().hide();
        items.removeClass("current");
        first_item.addClass('current');
        first_item.find('.sub-menu').show();

        var default_a = first_item.find('li').eq(0).find('a').data('url');

        $(window.location).attr('href', default_a);
      });

      // Sidebar click toggle
      // $('.navLeftTab .item').once('sidebar-menu-click').on('click', function (i) {
      //   console.log('click sidebar menu');
      // });



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
