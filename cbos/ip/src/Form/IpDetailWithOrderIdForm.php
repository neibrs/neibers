<?php

namespace Drupal\ip\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ip\Entity\IPInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IpDetailWithOrderIdForm extends FormBase {
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
    return 'ip_detail_with_order_id_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, IPInterface $ip = NULL) {
    if (empty($ip) || empty($ip->order_id->target_id)) {
      return $form;
    }

    $form['ips'] = [
      '#caption' => $this->t('Ips within same order'),
      '#type' => 'table',
      '#title' => $this->t('Ips'),
      '#header' => [$this->t('ID'), $this->t('Type'), $this->t('IP'), $this->t('Operations')],
      '#sticky' => TRUE,
    ];

    $mips = $this->entityTypeManager->getStorage('ip')
      ->loadByProperties([
        'order_id' => $ip->order_id->target_id,
        'type' => 'inet',
      ]);
    $bips = $this->entityTypeManager->getStorage('ip')
      ->loadByProperties([
        'order_id' => $ip->order_id->target_id,
        'type' => 'onet',
      ]);
    foreach (array_merge($mips, $bips) as $key => $ip) {
      /** @var \Drupal\ip\Entity\IPInterface $ip */
      $form['ips'][$key] = [
        'id' => ['#markup' => $ip->id()],
        'type' => ['#markup' => $ip->get('type')->entity->label()],
        // TODO Add link to label.
        'ip' => ['#markup' => $ip->label()],
        'operations' => \Drupal::service('ip.manager')->buildOperations($ip),
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
