<?php

/**
 * @file
 * Contains neibers_alert.page.inc.
 *
 * Page callback for neibers_alerts.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Alert templates.
 *
 * Default template: neibers-alert.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_alert(array &$variables) {
  // Fetch Alert Entity Object.
  $neibers_alert = $variables['elements']['#neibers_alert'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
