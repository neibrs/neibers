<?php

namespace Drupal\neibers_ip\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IPOrderForm extends FormBase implements ContainerInjectionInterface {

  /** @var \Drupal\commerce_order\Entity\OrderInterface */
  protected $order;

  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

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

    $this->order = $order;

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
    // Filter conditions for administer ip:
    // 1. find hardware by product ? product variant in order
    // 2. find seat by hardware.
    // 3. find ip collection by seat
    // 4. find free administer ip

    // TODO Add condition for filter ip.
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

    // Filter conditions for business ip:
    // 1. find the business ip, and administer ip on the same seat.
    // 2. find free business ip
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

    $form['all']['actions'] = ['#type' => 'actions'];
    $form['all']['actions']['submit'] = ['#type' => 'submit', '#value' => $this->t('Save')];

    return $form['all'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    /** @var \Drupal\neibers_ip\Entity\IPInterface $administer */
    $administer = $this->simplifyIp($values['administer']);
    /** @var \Drupal\neibers_ip\Entity\IPInterface $business */
    $business = $this->simplifyIp($values['business']);

    if (empty($administer)) {
      $this->messenger()->addError($this->t('Administer IP not exists.'));
    }

    if (!empty($values['business']) && empty($business)) {
      $this->messenger()->addError($this->t('Business IP not exists.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    // TODO Polish.

    /** @var \Drupal\neibers_ip\Entity\IPInterface $administer */
    $administer = $this->simplifyIp($values['administer']);
    /** @var \Drupal\neibers_ip\Entity\IPInterface $business */
    $business = $this->simplifyIp($values['business']);
    $administer->allocateInet($this->order->id());
    $administer->save();

    if (!empty($administer)) {
      $business->allocateOnet($administer->getSeat(), $this->order->id());
      $business->save();
    }
  }

  // TODO Polished.
  public function simplifyIp($value) {
    $ip = explode('(', $value);
    $ip = reset($ip);
    $ip = $this->entityTypeManager->getStorage('neibers_ip')->loadByProperties([
      'name' => $ip,
    ]);

    return reset($ip);
  }
}