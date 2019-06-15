<?php

namespace Drupal\neibers_hardware\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class HardwareTypeForm.
 */
class HardwareTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_hardware_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_hardware_type->label(),
      '#description' => $this->t("Label for the Hardware type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_hardware_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_hardware\Entity\HardwareType::load',
      ],
      '#disabled' => !$neibers_hardware_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_hardware_type = $this->entity;
    $status = $neibers_hardware_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Hardware type.', [
          '%label' => $neibers_hardware_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Hardware type.', [
          '%label' => $neibers_hardware_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_hardware_type->toUrl('collection'));
  }

}
