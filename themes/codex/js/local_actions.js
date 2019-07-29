(function ($, Drupal) {
  Drupal.behaviors.localAction = {
    attach: function attach(context) {
      $(context).find('.content-header .tabs.primary').once('tabs').each(function () {
        $(context).find('#block-eabax-seven-local-actions').addClass('absolute-right');
      });
    }
  }
})(jQuery, Drupal);
