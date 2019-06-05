<?php

/**
 * @file
 * Contains machine_room.page.inc.
 *
 * Page callback for Machine Room.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Machine Room templates.
 *
 * Default template: room.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_room(array &$variables) {
  // Fetch Room Entity Object.
  $room = $variables['elements']['#room'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
