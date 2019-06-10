<?php

namespace Drupal\ip\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGenerator;
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
        'type' => ['#markup' => $ip->bundle()],
        // TODO Add link to label.
        'ip' => ['#markup' => $ip->label()],
        'operations' => $this->buildOperations($ip),
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

  protected function buildOperations(Entityinterface $entity) {
    $build = [
      '#type' => 'operations',
      '#links' => $this->getOperations($entity),
    ];

    return $build;
  }

  protected function getOperations(EntityInterface $entity) {
    $operations = [];

    $transitions = [];
    /** @var \Drupal\workflows\WorkflowInterface $workflow */
    $workflow = $this->entityTypeManager->getStorage('workflow')
      ->load('default_ip_state');
    $workflow_type = $workflow->getTypePlugin();

    $pre_transitions = $workflow_type->getTransitions();

    foreach ($pre_transitions as $pre_transition) {
      if (!in_array($entity->get('state')->value, array_keys($pre_transition->from()))) {
        continue;
      }
      $transitions[$pre_transition->id()] = $pre_transition;
    }

    // Fix Administer ip state transition operation
    // TODO add business ip state transition operation
    foreach ($transitions as $transition) {
      $operations[$transition->id()] = [
        'title' => $transition->label(),
        'weight' => 10,
        'url' => Url::fromRoute('eabax_workflows.apply_transition', [
          'workflow_type' => $workflow->id(),
          'transition_id' => $transition->id(),
          'entity_type' => 'ip',
          'entity_id' => $entity->id(),
        ]),
      ];
    }

    return $operations;
  }
}