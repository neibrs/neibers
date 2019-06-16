<?php

namespace Drupal\neibers_ip;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\neibers_ip\Entity\IPInterface;

/**
 * Defines the storage handler class for IP entities.
 *
 * This extends the base storage class, adding required special handling for
 * IP entities.
 *
 * @ingroup neibers_ip
 */
class IPStorage extends SqlContentEntityStorage implements IPStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(IPInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {neibers_ip_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {neibers_ip_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(IPInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {neibers_ip_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('neibers_ip_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
