<?php

/**
 * @file
 * Contains ip.page.inc.
 *
 * Page callback for IP.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for IP templates.
 *
 * Default template: ip.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ip(array &$variables) {
  // Fetch IP Entity Object.
  $ip = $variables['elements']['#ip'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
