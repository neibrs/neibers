<?php

namespace Drupal\neibers_ip\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
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
//    $form['ips_collection'] = $this->buildIpsForm($form, $form_state, $order, $display);

    $form['ips'] = $this->buildIpsTableForm($form, $form_state, $order, $display);

    $form['bips'] = $this->buildBipsTableForm($form, $form_state, $order, $display);

    /** Build allocate ip form. */
    $form['all'] = $this->buildAllocateForm($form, $form_state, $order, $display);

    return $form;
  }

  /**
   * @description build bips form.
   */
  public function buildBipsTableForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    $form['bips'] = [
      '#caption' => $this->t('All business ip collection'),
      '#type' => 'table',
      '#title' => $this->t('IP'),
      '#header' => [$this->t('No.'), $this->t('IP'), $this->t('Operations')],
      '#sticky' => TRUE,
    ];

    $bips = $this->entityTypeManager->getStorage('neibers_ip')->getOnetsByOrder($this->order);

    $i = 1;
    foreach ($bips as $key => $bip) {
      $form['bips'][$key]['id'] = ['#markup' => $i++];
      $form['bips'][$key]['ip'] = ['#markup' => $bip->label()];
      $form['bips'][$key]['operations'] = \Drupal::service('neibers_ip.manager')->buildOperations($bip);
    }

    return $form['bips'];
  }

  /**
   * @description build ips form.
   */
  public function buildIpsTableForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {

    $header = [$this->t('Server')];
    if ($display->getMode() == 'default') {
      $header = array_merge($header, [$this->t('Administer IP')]);
    }
    $header = array_merge($header, [$this->t('Business IP'), $this->t('Fitting')]);

    $form['ips'] = [
      '#type' => 'table',
      '#title' => $this->t('All the Ips'),
      '#header' => $display->getMode() == 'default' ? array_merge($header, [$this->t('Operations')]) : $header,
      '#sticky' => TRUE,
    ];

    $inets = $this->entityTypeManager->getStorage('neibers_ip')->getInetsByOrder($order);
    foreach ($inets as $key => $inet) {
      $form['ips'][$key]['server'] = [
        '#markup' => $inet->seat->entity->hardware->entity->label(),
      ];
      if ($display->getMode() == 'default') {
        $form['ips'][$key]['inet'] = [
          '#markup' => $inet->label(),
        ];
      }

      $onets = array_map(function ($ip) {
        return $ip->label();
      }, $this->entityTypeManager->getStorage('neibers_ip')->getOnetsByInet($inet));

      $form['ips'][$key]['onet'] = [
        '#markup' => implode(', ', $onets),
      ];

      $form['ips'][$key]['fitting'] = [
        // TODO
        '#markup' => '',
      ];

      if ($display->getMode() == 'default') {
        $form['ips'][$key]['operation'] = \Drupal::service('neibers_ip.manager')->buildOperations($inet);
      }
    }


    return $form['ips'];
  }

  /**
   * @description build ips form.
   */
  public function buildIpsForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    $view = Views::getView('ips_order_form');

    $form['ips_collection'] = [
      '#type' => 'details',
      '#title' => $this->t('IP collection'),
      '#weight' => 10,
      '#open' => TRUE,
    ];
    $form['ips_collection']['ips'] = [
      '#type' => 'view',
      '#view' => $view,
      '#display_id' => 'default',
      '#arguments' => [
        $this->order->id(),
      ],
    ];
    return $form['ips_collection'];
  }

  /**
   * @description build allocate ip form.
   */
  public function buildAllocateForm(&$form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    $form['all'] = [
      '#type' => 'details',
      '#title' => $this->t('Allocate'),
      '#weight' => 10,
      '#open' => TRUE,
    ];
    // Filter conditions for administer ip:
    // 1. find hardware by product ? product variant in order
    // 2. find seat by hardware.
    // 3. find ip collection by seat
    // 4. find free administer ip
    $form['all']['resign'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Resign, Todo'),
      '#ajax' => [
        'callback' => '::ajaxAdminister',
        'wrapper' => 'administer-wrapper',
      ],
    ];
    $administer_conditions = [];
    if (!$form_state->getValue('resign')) {
      $administer_conditions['state'] = 'free';
    }

    $administer_conditions['type'] = 'inet';
    $administer_conditions['status'] = true;


    // TODO Add condition for filter ip.
    $form['all']['administer'] = [
      '#title' => $this->t('Administer IP'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'neibers_ip',
      '#prefix' => '<div id="administer-wrapper>',
      '#suffix' => '</div>',
      '#selection_settings' => [
        'conditions' => $administer_conditions,
      ],
      '#size' => 40,
    ];

    if ($form_state->getValue('resign')) {
      $form['all']['administer']['#selection_settings']['conditions'] = $administer_conditions;
    }


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

  public function ajaxAdminister(array &$form, FormStateInterface $form_state) {
    return $form['all']['administer'];
  }

// Warning: call_user_func_array() expects parameter 1 to be a valid callback, class 'Drupal\Core\Entity\Element\EntityAutocomplete' does not have a method 'validateEntityAutocomplete' in Drupal\Core\Form\FormValidator->doValidateForm() (line 282 of core/lib/Drupal/Core/Form/FormValidator.php).
//
//  /**
//   * {@inheritdoc}
//   */
//  public function validateForm(array &$form, FormStateInterface $form_state) {
//    $values = $form_state->getValues();
//    /** @var \Drupal\neibers_ip\Entity\IPInterface $administer */
//    $administer = $this->simplifyIp($values['administer']);
//    /** @var \Drupal\neibers_ip\Entity\IPInterface $business */
//    $business = $this->simplifyIp($values['business']);
//
//    if (empty($administer)) {
//      $this->messenger()->addError($this->t('Administer IP not exists.'));
//    }
//
//    if (!empty($values['business']) && empty($business)) {
//      $this->messenger()->addError($this->t('Business IP not exists.'));
//    }
//  }

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

    $administer->allocateInet($this->order);
    $administer->save();

    if (!empty($administer)) {
      $business->allocateOnet($administer->getSeat(), $this->order);
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