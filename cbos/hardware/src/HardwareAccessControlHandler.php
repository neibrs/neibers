<?php

namespace Drupal\neibers_hardware;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Server entity.
 *
 * @see \Drupal\neibers_hardware\Entity\Server.
 */
class ServerAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_hardware\Entity\ServerInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished neibers_hardware');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published neibers_hardware');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit neibers_hardware');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete neibers_hardware');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add neibers_hardware');
  }

}
