<?php

/**
 * @file
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_preprocess_form().
 */
function exmall_preprocess_form(array &$variables) {
  // $variables['attributes']['novalidate'] = 'novalidate';

}

/**
 * Implements hook_preprocess_select().
 */
function exmall_preprocess_select(array &$variables) {
  // $variables['attributes']['class'][] = 'select-chosen';
}

/**
 * Implements hook_form_alter() for form.
 */
function exmall_form_alter(&$form, FormStateInterface $form_state) {
  if (isset($form['actions']['delete'])) {
    $form['actions']['delete']['#attributes']['class'] = [
      'btn',
      'btn-danger',
      'btn-sm',
    ];
  }
  if (isset($form['actions']['cancel'])) {
    $form['actions']['cancel']['#attributes']['class'] = [
      'btn',
      'btn-warning',
      'btn-sm',
    ];
  }

  return $form;
}

function exmall_form_views_form_commerce_cart_block_default_1_alter(&$form, FormStateInterface $form_state) {
  $a = 'a';
}
