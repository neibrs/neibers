<?php

/**
 * @file
 */

/**
 * Implements hook_theme_suggestions_node_alter().
 */
function exmall_theme_suggestions_node_alter(array &$suggestions, array $variables) {
  /*$node = $variables['elements']['#node'];

  if ($variables['elements']['#view_mode'] == "full") {

  }*/
}

/**
 * Implements hook_preprocess_node().
 */
function exmall_preprocess_node(array &$variables) {
  // Default to turning off byline/submitted.
  // $variables['display_submitted'] = FALSE;
}
