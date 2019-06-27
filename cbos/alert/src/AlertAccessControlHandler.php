<?php

namespace Drupal\neibers_alert;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Alert entity.
 *
 * @see \Drupal\neibers_alert\Entity\Alert.
 */
class AlertAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_alert\Entity\AlertInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view neibers_alerts');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit neibers_alerts');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete neibers_alerts');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add neibers_alerts');
  }

}
