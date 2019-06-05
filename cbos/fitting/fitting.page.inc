<?php

/**
 * @file
 * Contains fitting.page.inc.
 *
 * Page callback for Fitting.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Fitting templates.
 *
 * Default template: fitting.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_fitting(array &$variables) {
  // Fetch Fitting Entity Object.
  $fitting = $variables['elements']['#fitting'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
