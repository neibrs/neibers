<?php

namespace Drupal\neibers_fitting\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Fitting edit forms.
 *
 * @ingroup neibers_fitting
 */
class FittingForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\neibers_fitting\Entity\Fitting */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Fitting.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Fitting.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.neibers_fitting.canonical', ['neibers_fitting' => $entity->id()]);
  }

}
