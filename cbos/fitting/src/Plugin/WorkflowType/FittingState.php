<?php

namespace Drupal\neibers_fitting\Plugin\WorkflowType;

use Drupal\eabax_workflows\Plugin\WorkflowType\EntityWorkflowBase;

/**
 * Attaches workflow to neibers_fitting entity types and their bundles.
 *
 * @WorkflowType(
 *   id = "fitting_state",
 *   label = @Translation("Fitting state"),
 *   required_states = {
 *     "draft",
 *     "free",
 *     "used",
 *   },
 * )
 */
class FittingState extends EntityWorkflowBase {

  public function defaultConfiguration() {
    return [
      'states' => [
        'draft' => [
          'label' => 'Draft',
          'weight' => 0,
        ],
        'free' => [
          'label' => 'Free',
          'weight' => 1,
        ],
        'used' => [
          'label' => 'Used',
          'weight' => 2,
        ],
      ],
      'transitions' => [
        'approve' => [
          'label' => 'Approve',
          'to' => 'free',
          'from' => [
            'draft',
          ],
          'weight' => 1,
        ],
        'occupied' => [
          'label' => 'Occupied',
          'to' => 'used',
          'from' => [
            'draft',
            'free',
          ],
          'weight' => 2,
        ],
      ],
    ];
  }

}
