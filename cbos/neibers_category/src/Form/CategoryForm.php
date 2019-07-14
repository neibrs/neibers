<?php

namespace Drupal\neibers_category\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CategoryForm.
 */
class CategoryForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $neibers_category = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $neibers_category->label(),
      '#description' => $this->t("Label for the Category."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $neibers_category->id(),
      '#machine_name' => [
        'exists' => '\Drupal\neibers_category\Entity\Category::load',
      ],
      '#disabled' => !$neibers_category->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $neibers_category = $this->entity;
    $status = $neibers_category->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Category.', [
          '%label' => $neibers_category->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Category.', [
          '%label' => $neibers_category->label(),
        ]));
    }
    $form_state->setRedirectUrl($neibers_category->toUrl('collection'));
  }

}
