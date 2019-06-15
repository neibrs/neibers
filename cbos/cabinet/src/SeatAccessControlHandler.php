<?php

namespace Drupal\neibers_cabinet;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Seat entity.
 *
 * @see \Drupal\neibers_cabinet\Entity\Seat.
 */
class SeatAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_cabinet\Entity\SeatInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished neibers seat');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published neibers seat');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit neibers seat');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete neibers seat');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add neibers seat');
  }

}
