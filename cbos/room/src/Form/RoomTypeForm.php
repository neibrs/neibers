<?php

namespace Drupal\neibers_room\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RoomTypeForm.
 */
class RoomTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_room_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_room_type->label(),
      '#description' => $this->t("Label for the Room type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_room_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_room\Entity\RoomType::load',
      ],
      '#disabled' => !$neibers_room_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_room_type = $this->entity;
    $status = $neibers_room_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Room type.', [
          '%label' => $neibers_room_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Room type.', [
          '%label' => $neibers_room_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_room_type->toUrl('collection'));
  }

}
