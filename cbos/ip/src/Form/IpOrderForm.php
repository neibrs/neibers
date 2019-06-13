<?php

namespace Drupal\ip\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IpOrderForm extends FormBase {

  /**
 * @var \Drupal\commerce_order\Entity\OrderInterface*/
  protected $order;

  /**
 * @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * {@inheritDoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
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
    return 'ip_with_specific_order';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $order = NULL, $display = NULL) {
    if (empty($order)) {
      return $form;
    }
    $this->order = $order;
    // TODO add ip table for this order

    $header = [$this->t('Server')];
    if ($display->getMode() == 'default') {
      $header = array_merge($header, [$this->t('Administer IP')]);
    }
    $header = array_merge($header, [$this->t('Business IP'), $this->t('Fitting')]);
    $form['ips'] = [
      '#caption' => $this->t('IP tables'),
      '#type' => 'table',
      '#title' => $this->t('All the Ips'),
      '#header' => $display->getMode() == 'default' ? array_merge($header, [$this->t('Operations')]) : $header,
      '#sticky' => TRUE,
    ];

    $inets = $this->entityTypeManager->getStorage('ip')->getInetsByOrder($this->order);
    foreach ($inets as $key => $entity_ip) {
      $form['ips'][$key]['server'] = [
        '#markup' => $entity_ip->server->entity->label(),
      ];
      if ($display->getMode() == 'default') {
        $form['ips'][$key]['inet'] = [
          '#markup' => $entity_ip->label(),
        ];
      }

      $onets = array_map(function ($ip) {
        return $ip->label();
      }, $this->entityTypeManager->getStorage('ip')->getOnetsByInet($entity_ip));
      $form['ips'][$key]['onet'] = [
        '#markup' => implode(', ', $onets),
      ];

      $form['ips'][$key]['fitting'] = [
      // TODO
        '#markup' => '',
      ];

      if ($display->getMode() == 'default') {
        $form['ips'][$key]['operation'] = \Drupal::service('ip.manager')->buildOperations($entity_ip);
      }
    }

    $form['bips'] = [
      '#caption' => $this->t('All business ip collection'),
      '#type' => 'table',
      '#title' => $this->t('IP'),
      '#header' => [$this->t('No.'), $this->t('IP'), $this->t('Operations')],
      '#sticky' => TRUE,
    ];

    $bips = $this->entityTypeManager->getStorage('ip')->getOnetsByOrder($this->order);

    $i = 1;
    foreach ($bips as $key => $bip) {
      $form['bips'][$key]['id'] = ['#markup' => $i++];
      $form['bips'][$key]['ip'] = ['#markup' => $bip->label()];
      $form['bips'][$key]['operations'] = \Drupal::service('ip.manager')->buildOperations($bip);
    }

    if ($display->getMode() == 'default') {
      // TODO Business ip table for disable and replace ip.

      // Allocate ip form builder.
      $form['allocate'] = [
        '#type' => 'details',
        '#title' => $this->t('Allocate'),
        '#open' => TRUE,
      ];
      $machine_rooms = $this->entityTypeManager->getStorage('room');
      $rooms = array_map(function ($room) {
        return $room->label();
      }, $machine_rooms->loadMultiple());
      $form['allocate']['room'] = [
        '#title' => $this->t('Room'),
        '#type' => 'select',
        '#options' => $rooms,
        '#required' => TRUE,
      ];
      $form['allocate']['server'] = [
        '#title' => $this->t('Server'),
        '#type' => 'entity_autocomplete',
        '#target_type' => 'server',
        '#required' => TRUE,
        '#size' => 40,
      ];
      $form['allocate']['administer'] = [
        '#title' => $this->t('Administer IP'),
        '#type' => 'entity_autocomplete',
        '#target_type' => 'ip',
        '#required' => TRUE,
        '#size' => 40,
      ];
      $form['allocate']['business'] = [
        '#title' => $this->t('Business IP'),
        '#type' => 'entity_autocomplete',
        '#target_type' => 'ip',
        '#size' => 40,
      ];

      $form['allocate']['actions']['#type'] = 'actions';
      $form['allocate']['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => t('Save'),
        '#button_type' => 'primary',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!empty($form_state->getValue('administer'))) {
      $this->inet = $this->entityTypeManager->getStorage('ip')->load($form_state->getValue('administer'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $ip_storage = $this->entityTypeManager->getStorage('ip');
    // TODO Polished
    if ($values['administer'] || $values['business']) {
      $administer = $ip_storage->load($values['administer']);
      $administer->server->target_id = $values['server'];
      $administer->order_id->target_id = $this->order->id();
      $administer->state->value = 'used';
      $administer->user_id->target_id = \Drupal::currentUser()->id();
      $administer->save();

      if (!empty($values['business'])) {
        $business = $ip_storage->load($values['business']);
        $business->order_id->target_id = $this->order->id();
        $business->state->value = 'used';
        if (!empty($this->inet)) {
          $business->server->target_id = $this->inet->server->target_id;
          $business->seat->target_id = $this->inet->seat->target_id;
          $business->user_id->target_id = \Drupal::currentUser()->id();
        }
        $business->save();
      }

      $this->messenger()->addStatus($this->t('Save success.'));
    }
  }

}
