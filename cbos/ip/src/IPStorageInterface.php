<?php

namespace Drupal\neibers_ip;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\neibers_ip\Entity\IPInterface;

/**
 * Defines the storage handler class for IP.
 *
 * This extends the base storage class, adding required special handling for
 * IP.
 *
 * @ingroup neibers_ip
 */
interface IPStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of IP revision IDs for a specific IP.
   *
   * @param \Drupal\neibers_ip\Entity\IPInterface $entity
   *   The IP entity.
   *
   * @return int[]
   *   IP revision IDs (in ascending order).
   */
  public function revisionIds(IPInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as IP author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   IP revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\neibers_ip\Entity\IPInterface $entity
   *   The IP entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(IPInterface $entity);

  /**
   * Unsets the language for all IP with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
