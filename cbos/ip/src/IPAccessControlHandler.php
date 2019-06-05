<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the IP entity.
 *
 * @see \Drupal\ip\Entity\IP.
 */
class IPAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ip\Entity\IPInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished ip');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published ip');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit ip');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete ip');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add ip');
  }

}
