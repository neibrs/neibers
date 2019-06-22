(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.simpleChart = {
    attach: function attach(context) {
      $(context).find('.c3-chart').once('c3-chart').each(function () {
        var id = $(this).attr('id');
        var config = drupalSettings.c3Charts[id];
        config['bindto'] = '#' + id;
        c3.generate(config);
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
