<?php

namespace Drupal\neibers_ip\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class IPTypeForm.
 */
class IPTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_ip_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_ip_type->label(),
      '#description' => $this->t("Label for the IP type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_ip_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_ip\Entity\IPType::load',
      ],
      '#disabled' => $neibers_ip_type->isLocked(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_ip_type = $this->entity;
    $status = $neibers_ip_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label IP type.', [
          '%label' => $neibers_ip_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label IP type.', [
          '%label' => $neibers_ip_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_ip_type->toUrl('collection'));
  }

}
