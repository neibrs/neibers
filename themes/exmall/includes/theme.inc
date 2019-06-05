<?php

/**
 * @file
 */

/**
 * Implements hook_preprocess_HOOK() for html.html.twig.
 */
function exmall_preprocess_html(array &$variables) {
  /* Add class to html tag */
  // $variables['html_attributes']->addClass('no-js');

  // Don't display the site name twice on the front page (and potentially others)
  /*if (isset($variables['head_title_array']['title']) && isset($variables['head_title_array']['name']) && ($variables['head_title_array']['title'] == $variables['head_title_array']['name'])) {
  $variables['head_title'] = $variables['head_title_array']['name'];
  }*/
}

/**
 * Implements hook_page_attachments_alter().
 */
function exmall_page_attachments_alter(array &$page) {
  // Tell IE to use latest rendering engine (not to use compatibility mode).
  /*$ie_edge = [
  '#type' => 'html_tag',
  '#tag' => 'meta',
  '#attributes' => [
  'http-equiv' => 'X-UA-Compatible',
  'content' => 'IE=edge',
  ],
  ];
  $page['#attached']['html_head'][] = [$ie_edge, 'ie_edge'];*/
}

/**
 * Implements hook_preprocess_page() for page.html.twig.
 */
function exmall_preprocess_page(array &$variables) {
  $variables['#attached']['drupalSettings']['path']['themeUrl'] = \Drupal::theme()->getActiveTheme()->getPath();
}

/**
 * Implements hook_theme_suggestions_page_alter().
 */
function exmall_theme_suggestions_page_alter(array &$suggestions, array $variables) {

}
