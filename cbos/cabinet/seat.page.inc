<?php

/**
 * @file
 * Contains seat.page.inc.
 *
 * Page callback for Seat.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Seat templates.
 *
 * Default template: seat.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seat(array &$variables) {
  // Fetch Seat Entity Object.
  $seat = $variables['elements']['#seat'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
