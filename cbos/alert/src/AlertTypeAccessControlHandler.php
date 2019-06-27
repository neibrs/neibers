<?php

namespace Drupal\neibers_alert;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Alert type entity.
 *
 * @see \Drupal\neibers_alert\Entity\AlertType.
 */
class AlertTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($operation == 'view') {
      return AccessResult::allowedIfHasPermission($account, 'view neibers_alerts');
    }
    else {
      return parent::checkAccess($entity, $operation, $account);
    }
  }

}
