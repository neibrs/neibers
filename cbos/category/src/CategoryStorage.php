<?php

namespace Drupal\neibers_category;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\neibers_category\Entity\CategoryInterface;

/**
 * Defines the storage handler class for Category entities.
 *
 * This extends the base storage class, adding required special handling for
 * Category entities.
 *
 * @ingroup neibers_category
 */
class CategoryStorage extends SqlContentEntityStorage implements CategoryStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CategoryInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {neibers_category_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {neibers_category_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(CategoryInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {neibers_category_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('neibers_category_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
