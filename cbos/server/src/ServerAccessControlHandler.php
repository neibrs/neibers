<?php

namespace Drupal\server;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Server entity.
 *
 * @see \Drupal\server\Entity\Server.
 */
class ServerAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\server\Entity\ServerInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished server');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published server');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit server');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete server');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add server');
  }

}
