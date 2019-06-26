<?php

namespace Drupal\neibers_ip;

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

      // Add ip associated
      if ($display->getComponent('ip_associated')) {
        $build[$id]['ip_associated'] = views_embed_view('ip_associated', 'default', $entity->seat->target_id);
      }
    }
    parent::buildComponents($build, $entities, $displays, $view_mode);
  }

}
