<?php

/**
 * @file
 * Contains neibers_hardware.page.inc.
 *
 * Page callback for Server.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Server templates.
 *
 * Default template: neibers-hardware.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_hardware(array &$variables) {
  // Fetch Server Entity Object.
  $neibers_hardware = $variables['elements']['#neibers_hardware'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
