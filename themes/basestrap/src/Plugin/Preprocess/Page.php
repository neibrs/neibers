<?php

namespace Drupal\neibers_basestrap\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;

/**
 * Pre-processes variables for the "page" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("page")
 */
class Page extends PreprocessBase implements PreprocessInterface {

  public function preprocess(array &$variables, $hook, array $info) {
    global $base_url;
    $theme = \Drupal::theme()->getActiveTheme();
    $variables['#attached']['drupalSettings']['theme'] = [
      'path' => $base_url . '/' . $theme->getPath(),
    ];
    $variables['themePath'] = $base_url . '/' . $theme->getPath();
    parent::preprocess($variables, $hook, $info);
  }
}