<?php

namespace Drupal\neibers_ip\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class IPOrderForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ip_order_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    if (empty($order)) {
      return $form;
    }

    $form['all'] = [
      '#type' => 'detail',
      '#title' => $this->t('Ips in this order.'),
      '#attributes' => [
        'class' => [
          'container-inline',
        ],
      ],
      '#open' => TRUE,
    ];
    /** Build ips form. */
    $form['ips'] = $this->buildIpsForm($form, $form_state, $order, $display);

    /** Build allocate ip form. */
    $form['all'] = $this->buildAllocateForm($form, $form_state, $order, $display);

    return $form;
  }

  /**
   * @description build ips form.
   */
  public function buildIpsForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    $header = [$this->t('Hardware type'), $this->t('Hardware')];
    if ($display->getMode() == 'default') {
      $header = array_merge($header, [$this->t('Administer IP')]);
    }
    $header = array_merge($header, [$this->t('Business IP'), $this->t('Fitting'), $this->t('Operations')]);
    $form['ips'] = [
      '#caption' => $this->t('IP tables'),
      '#type' => 'table',
      '#title' => $this->t('All the Ips'),
      '#header' => $header,
      '#sticky' => TRUE,
      '#weight' => 0,
    ];

    return $form['ips'];
  }

  /**
   * @description build allocate ip form.
   */
  public function buildAllocateForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    $form['all'] = [
      '#type' => 'details',
      '#title' => $this->t('Allocate'),
      '#weight' => 10,
      '#attributes' => [
        'class' => [
          'container-inline',
        ],
      ],
      '#open' => TRUE,
    ];

    $form['all']['administer'] = [
      '#title' => $this->t('Administer IP'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'neibers_ip',
      '#selection_settings' => [
        'conditions' => [
          'type' => 'inet',
          'state' => 'free',
          'status' => true,
        ],
      ],
      '#size' => 40,
    ];
    $form['all']['business'] = [
      '#title' => $this->t('Business IP'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'neibers_ip',
      '#selection_settings' => [
        'conditions' => [
          'type' => 'onet',
          'state' => 'free',
          'status' => true,
        ],
      ],
      '#size' => 40,
    ];
    return $form['all'];
  }

  public function ajaxBusiness($form, FormStateInterface $form_state) {
    return $form['allocate']['business'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}