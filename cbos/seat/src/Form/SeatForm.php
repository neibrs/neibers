<?php

namespace Drupal\neibers_seat\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Seat edit forms.
 *
 * @ingroup neibers_seat
 */
class SeatForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\neibers_seat\Entity\Seat */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    $form['occupation'] = [
      '#type' => 'integer',
      '#title' => $this->t('Occupation'),
      '#description' => $this->t('The number of hardware occupation.'),
      '#default_value' => 1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    for ($i=1; $i < $form_state->getValue('occupation'); $i++) {
      // TODO save times by occupation.
    }
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Seat.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Seat.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.neibers_seat.canonical', ['neibers_seat' => $entity->id()]);
  }

}
