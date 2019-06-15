<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityViewBuilder;

/**
 * View builder handler for ip.
 */
class IPViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildComponents(array &$build, array $entities, array $displays, $view_mode) {
    if (empty($entities)) {
      return;
    }

    foreach ($entities as $id => $entity) {
      $bundle = $entity->bundle();
      $display = $displays[$bundle];
      // Add Orders ip
      if ($display->getComponent('order_ips')) {
        $build[$id]['order_ips'] = \Drupal::formBuilder()->getForm('\Drupal\ip\Form\IPOrderIdForm', $entity);
      }
    }
    parent::buildComponents($build, $entities, $displays, $view_mode);
  }

}
