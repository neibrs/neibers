(function ($, Drupal) {
  Drupal.behaviors.operationMenuLocalAction = {
    attach: function attach(context) {
      $(context).find('.block-entity-operations-menu').once('local-actions').each(function () {
        var block = $(context).find('#block-eabax-seven-local-actions').detach();
        $(block).addClass('absolute-right');
        $(this).before(block);
      });
    }
  }
})(jQuery, Drupal);
