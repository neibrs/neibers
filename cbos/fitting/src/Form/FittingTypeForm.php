<?php

namespace Drupal\neibers_fitting\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FittingTypeForm.
 */
class FittingTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_fitting_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_fitting_type->label(),
      '#description' => $this->t("Label for the Fitting type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_fitting_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_fitting\Entity\FittingType::load',
      ],
      '#disabled' => !$neibers_fitting_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_fitting_type = $this->entity;
    $status = $neibers_fitting_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Fitting type.', [
          '%label' => $neibers_fitting_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Fitting type.', [
          '%label' => $neibers_fitting_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_fitting_type->toUrl('collection'));
  }

}
