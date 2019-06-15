<?php

/**
 * @file
 * Contains neibers_cabinet.page.inc.
 *
 * Page callback for Cabinet.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Cabinet templates.
 *
 * Default template: neibers_cabinet.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_cabinet(array &$variables) {
  // Fetch Cabinet Entity Object.
  $neibers_cabinet = $variables['elements']['#neibers_cabinet'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
