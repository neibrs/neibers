(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mall = {
    attach: function attach(context, settings) {
      // 顶部快捷栏 地区切换 and 网站导航
      $("*[data-ectype='dorpdown']").once('dropdown').hover(function(){
        $(this).addClass("hover");
      },function(){
        $(this).removeClass("hover");
      });

    }
  };
})(jQuery, Drupal, drupalSettings);
