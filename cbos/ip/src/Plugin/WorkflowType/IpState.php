<?php

namespace Drupal\neibers_ip\Plugin\WorkflowType;

use Drupal\eabax_workflows\Plugin\WorkflowType\EntityWorkflowBase;

/**
 * Attaches workflow to ip entity types and their bundles.
 *
 * @WorkflowType(
 *   id = "ip_state",
 *   label = @Translation("IP state"),
 *   required_states = {
 *     "draft",
 *     "free",
 *     "used",
 *   },
 * )
 */
class IpState extends EntityWorkflowBase {

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
        'stop' => [
          'label' => 'Stop',
          'to' => 'free',
          'from' => [
            'used',
          ],
          'weight' => 3,
        ],
      ],
    ];
  }

}
