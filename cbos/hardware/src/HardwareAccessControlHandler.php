<?php

namespace Drupal\neibers_hardware;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Hardware entity.
 *
 * @see \Drupal\neibers_hardware\Entity\Hardware.
 */
class HardwareAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_hardware\Entity\HardwareInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished hardware');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published hardware');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit hardware');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete hardware');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add hardware');
  }

}
