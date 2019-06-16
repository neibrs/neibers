<?php

namespace Drupal\neibers_ip;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the IP entity.
 *
 * @see \Drupal\neibers_ip\Entity\IP.
 */
class IPAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_ip\Entity\IPInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished ip entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published ip entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit ip entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete ip entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add ip entities');
  }

}
