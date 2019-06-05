(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.exmall = {
    attach: function attach(context) {
      console.log('global js');
      // var json_languages = {$json_languages};
      //   $("#site-nav").jScroll();
  
      //加载效果
      // var load_cart_info = '<img src="{$site_domain}themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/load/loadGoods.gif" class="load" />';
      // var load_icon = '<img src="{$site_domain}themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/load/load.gif" width="200" height="200" />';

      // cart block
      $(".shopCart").once('shopCart').on('mouseenter', function () {
        $(this).addClass('hover');
      }).on('mouseleave', function () {
        $(this).removeClass('hover');
      });
  
    }
  };
})(jQuery, Drupal, drupalSettings);
