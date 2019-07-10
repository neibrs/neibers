(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.sidebar = {
    attach: function attach(context, settings) {
      $(context).find('body').once('neibers_codex_sidebar').each(Drupal.codexSidebar);
    }
  };

  Drupal.codexSidebar = function () {
    $(this).on('collapsed.pushMenu', function (event) {
      $.cookie('Drupal.collapsed.pushMenu', 1, {
        path: drupalSettings.basePath,
        expires: 365
      });
    });
    $(this).on('expanded.pushMenu', function (event) {
      $.cookie('Drupal.collapsed.pushMenu', 0, {
        path: drupalSettings.basePath,
        expires: 365
      });
    });
  };

})(jQuery, Drupal, drupalSettings);