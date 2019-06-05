<?php

namespace Drupal\fitting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Fitting entity.
 *
 * @see \Drupal\fitting\Entity\Fitting.
 */
class FittingAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\fitting\Entity\FittingInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished fitting');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published fitting');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit fitting');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete fitting');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add fitting');
  }

}
