<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;

class IpManager implements IpManagerInterface {

  /**
 * @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @return array
   */
  public function buildOperations(EntityInterface $entity) {
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
