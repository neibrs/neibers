<?php

namespace Drupal\neibers_recurring;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

class RecurringServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('commerce_recurring.order_manager');
    $definition->setClass('Drupal\neibers_recurring\RecurringOrderManager');
  }
}