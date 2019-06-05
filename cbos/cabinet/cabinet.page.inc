<?php

/**
 * @file
 * Contains cabinet.page.inc.
 *
 * Page callback for Cabinet.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Cabinet templates.
 *
 * Default template: cabinet.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_cabinet(array &$variables) {
  // Fetch Cabinet Entity Object.
  $cabinet = $variables['elements']['#cabinet'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
