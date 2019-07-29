<?php

namespace Drupal\neibers_seat;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Seat entity.
 *
 * @see \Drupal\neibers_seat\Entity\Seat.
 */
class SeatAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_seat\Entity\SeatInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished seat');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published seat');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit seat');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete seat');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add seat');
  }

}
