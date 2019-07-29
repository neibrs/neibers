<?php

namespace Drupal\entity_plus\Enhancer;

use Drupal\Core\Entity\Enhancer\EntityRouteEnhancer as EntityRouteEnhancerBase;
use Symfony\Component\HttpFoundation\Request;

class EntityRouteEnhancer extends EntityRouteEnhancerBase {

  protected function enhanceEntityList(array $defaults, Request $request) {
    $defaults = parent::enhanceEntityList($defaults, $request);

    $defaults['_controller'] = '\Drupal\entity_plus\Controller\EntityListController::listing';

    return $defaults;
  }

}
