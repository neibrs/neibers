<?php

namespace Drupal\neibers_category;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Category entity.
 *
 * @see \Drupal\neibers_category\Entity\Category.
 */
class CategoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\neibers_category\Entity\CategoryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished category');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published category');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit category');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete category');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add category');
  }

}
