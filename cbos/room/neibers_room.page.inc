<?php

/**
 * @file
 * Contains neibers_room.page.inc.
 *
 * Page callback for Room.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Room templates.
 *
 * Default template: neibers_room.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_neibers_room(array &$variables) {
  // Fetch Room Entity Object.
  $neibers_room = $variables['elements']['#neibers_room'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
