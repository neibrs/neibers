<?php

namespace Drupal\entity_plus\Controller;

use Drupal\Core\Entity\Controller\EntityController as EntityControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;

class EntityController extends EntityControllerBase {

  public function bundleTitle(RouteMatchInterface $route_match, $entity_type_id, $bundle_parameter) {
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
    // If the entity has bundle entities, the parameter might have been upcasted
    // so fetch the raw parameter.
    $bundle = $route_match->getRawParameter($bundle_parameter);
    if ((count($bundles) > 1) && isset($bundles[$bundle])) {
      return $bundles[$bundle]['label'];
    }
    // If the entity supports bundles generally, but only has a single bundle,
    // the bundle is probably something like 'Default' so that it preferable to
    // use the entity type label.
    else {
      $entity_type = $this->entityTypeManager->getDefinition($entity_type_id);
      return $entity_type->getLabel();
    }
  }

}
