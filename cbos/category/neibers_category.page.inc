<?php

/**
 * @file
 * Contains neibers_category.page.inc.
 *
 * Page callback for Category entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Category templates.
 *
 * Default template: neibers_category.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_category(array &$variables) {
  // Fetch Category Entity Object.
  $neibers_category = $variables['elements']['#neibers_category'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
