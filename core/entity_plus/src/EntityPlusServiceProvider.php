<?php

namespace Drupal\entity_plus;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

class EntityPlusServiceProvider extends ServiceProviderBase {

  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('route_enhancer.entity');
    $definition->setClass('Drupal\entity_plus\Enhancer\EntityRouteEnhancer');
  }

}
