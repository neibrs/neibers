<?php

/**
 * @file
 */

/**
 * Implements hook_theme_suggestions_field_alter().
 */
function exsen_theme_suggestions_field_alter(array &$suggestions, array $variables) {
  /*$element = $variables['element'];
  $suggestions[] = 'field__' . $element['#view_mode'];
  $suggestions[] = 'field__' . $element['#view_mode'] . '__' . $element['#field_name'];*/
}

/**
 * Implements hook_theme_suggestions_field_alter().
 */
function exsen_theme_suggestions_fieldset_alter(array &$suggestions, array $variables) {
  /*$element = $variables['element'];
  if (isset($element['#attributes']['class']) && in_array('form-composite', $element['#attributes']['class'])) {
  $suggestions[] = 'fieldset__form_composite';
  }*/
}

/**
 * Implements hook_preprocess_field().
 */
function exsen_preprocess_field(array &$variables, $hook) {
  /*switch ($variables['element']['#field_name']) {
  }*/
}

/**
 * Implements hook_preprocess_details().
 */
function exsen_preprocess_details(array &$variables) {
  /*$variables['attributes']['class'][] = 'details';
  $variables['summary_attributes']['class'] = 'summary';*/
}

/**
 * Implements hook_theme_suggestions_details_alter().
 */
function exsen_theme_suggestions_details_alter(array &$suggestions, array $variables) {

}
