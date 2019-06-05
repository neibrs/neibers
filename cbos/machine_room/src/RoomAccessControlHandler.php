<?php

namespace Drupal\machine_room;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Machine Room entity.
 *
 * @see \Drupal\machine_room\Entity\Room.
 */
class RoomAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\machine_room\Entity\RoomInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished machine room');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published machine room');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit machine room');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete machine room');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add machine room');
  }

}
