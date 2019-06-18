<?php

/**
 * @file
 * Enables modules and site configuration for a neibrs site installation.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function neibers_neibrs_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['#submit'][] = 'neibers_neibrs_form_install_configure_submit';
}

/**
 * Submission handler to sync the contact.form.feedback recipient.
 */
function neibers_neibrs_form_install_configure_submit($form, FormStateInterface $form_state) {
  $site_mail = $form_state->getValue('site_mail');
  // TODO
  //  ContactForm::load('feedback')->setRecipients([$site_mail])->trustData()->save();
}

function neibers_neibrs_form_user_login_form_alert(&$form, FormStateInterface $form_state) {
  $form['name']['#placeholder'] = 'Test username: admin';
  $form['pass']['#placeholder'] = 'Test password: admin';
}