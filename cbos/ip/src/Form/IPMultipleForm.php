<?php

namespace Drupal\ip\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class IPMultipleForm.
 *
 * @ingroup ip
 */
class IPMultipleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ip_multiple_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['ip'] = [
      '#type' => 'container',
      '#title' => 'IP',
      '#attributes' => [
        'class' => [
          'container-inline',
        ],
      ],
    ];

    $form['ip']['segment'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Segment:'),
      '#description' => $this->t('Example: 192.168.15.0/24'),
    ];

    $form['ip']['compute'] = [
      '#type' => 'submit',
      '#value' => $this->t('Compute'),
      '#ajax' => [
        'callback' => '::computeFormCallback',
        'wrapper' => 'file-wrapper',
      ],
    ];

    $form['ips'] = [
      '#type' => 'table',
      '#header' => [$this->t('IP'), $this->t('Mark'), $this->t('Operations')],
      '#rows' => [],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

  /**
   * Ajax for compute ip
   */
  public function computeFormCallback(array &$form, FormStateInterface $form_state) {

  }
}