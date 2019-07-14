<?php

namespace Drupal\neibers_category\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CategoryTypeForm.
 */
class CategoryTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_category_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_category_type->label(),
      '#description' => $this->t("Label for the Category type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_category_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_category\Entity\CategoryType::load',
      ],
      '#disabled' => !$neibers_category_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_category_type = $this->entity;
    $status = $neibers_category_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Category type.', [
          '%label' => $neibers_category_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Category type.', [
          '%label' => $neibers_category_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_category_type->toUrl('collection'));
  }

}
