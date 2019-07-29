<?php

namespace Drupal\eabax_core;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Overrides the access_check.entity_create service.
 */
class EabaxCoreServiceProvider extends ServiceProviderBase {

  public function alter(ContainerBuilder $container) {

    // Add entity_type_id placeholder support for access checker.
    $definition = $container->getDefinition('access_check.entity_create');
    $definition->setClass('Drupal\eabax_core\Entity\EntityCreateAccessCheck');

    $definition = $container->getDefinition('access_check.entity');
    $definition->setClass('Drupal\eabax_core\Entity\EntityAccessCheck');
  }

}
