<?php

namespace Drupal\neibers_room;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity\EntityAccessControlHandler;

/**
 * Access controller for the Hardware type entity.
 *
 * @see \Drupal\neibers_room\Entity\RoomType.
 */
class RoomTypeAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view published room');

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