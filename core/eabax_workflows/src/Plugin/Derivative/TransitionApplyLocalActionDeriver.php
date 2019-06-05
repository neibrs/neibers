<?php

namespace Drupal\eabax_workflows\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\eabax_workflows\Plugin\WorkflowType\EntityWorkflowBase;

/**
 * Provides local action definitions for all workflow transition.
 */
class TransitionApplyLocalActionDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives = [];

    /** @var \Drupal\workflows\WorkflowInterface[] $workflows */
    $workflows = \Drupal::entityTypeManager()->getStorage('workflow')->loadMultiple();
    foreach ($workflows as $workflow) {
      /** @var \Drupal\eabax_workflows\Plugin\WorkflowType\EntityWorkflowBase $plugin */
      $plugin = $workflow->getTypePlugin();
      if (!$plugin instanceof EntityWorkflowBase) {
        continue;
      }

      $entity_type_id = @$plugin->getConfiguration()['entity_type_id'];
      // Ensure entity_type_id has been setup in workflows.workflow.*.
      if (empty($entity_type_id)) {
        continue;
      }
      $entity_type = \Drupal::entityTypeManager()->getDefinition($entity_type_id);

      $appears_on = [];
      if ($entity_type->hasLinkTemplate('edit-form')) {
        $appears_on[] = "entity.$entity_type_id.edit_form";
      }
      if ($entity_type->hasLinkTemplate('canonical')) {
        $appears_on[] = "entity.$entity_type_id.canonical";
      }
      if (empty($appears_on)) {
        continue;
      }

      $plugin_id = $plugin->getPluginId();
      $transitions = $plugin->getTransitions();
      foreach ($transitions as $transition) {
        $transition_id = $transition->id();
        $this->derivatives["$plugin_id.$transition_id"] = [
          'route_name' => "eabax_workflows.apply_transition",
          'route_parameters' => [
            'workflow_type' => $workflow->id(),
            'transition_id' => $transition_id,
            'entity_type' => $entity_type_id,
          ],
          'title' => $transition->label(),
          'appears_on' => $appears_on,
        ];
      }
    }

    foreach ($this->derivatives as &$entry) {
      $entry += $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
