<?php

/**
 * @file
 * Contains neibers_fitting.page.inc.
 *
 * Page callback for Fitting.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Fitting templates.
 *
 * Default template: neibers_fitting.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_fitting(array &$variables) {
  // Fetch Fitting Entity Object.
  $neibers_fitting = $variables['elements']['#neibers_fitting'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
