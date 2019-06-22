<?php

namespace Drupal\neibers_hardware;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Hardware type entity.
 *
 * @see \Drupal\neibers_hardware\Entity\PersonType.
 */
class HardwareTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view published neibers_hardware');

      case 'delete':
        if ($entity->isLocked()) {
          return AccessResult::forbidden()->addCacheableDependency($entity);
        }
        else {
          return parent::checkAccess($entity, $operation, $account)->addCacheableDependency($entity);
        }
        break;

      default:
        return parent::checkAccess($entity, $operation, $account);

    }
  }

}
