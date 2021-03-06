<?php

namespace Drupal\neibers_seat\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SeatTypeForm.
 */
class SeatTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_seat_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_seat_type->label(),
      '#description' => $this->t("Label for the Seat type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_seat_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_seat\Entity\SeatType::load',
      ],
      '#disabled' => !$neibers_seat_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_seat_type = $this->entity;
    $status = $neibers_seat_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Seat type.', [
          '%label' => $neibers_seat_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Seat type.', [
          '%label' => $neibers_seat_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_seat_type->toUrl('collection'));
  }

}
