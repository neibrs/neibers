<?php

namespace Drupal\neibers_ip;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\neibers_hardware\Entity\HardwareInterface;
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

  /**
   * @param IPInterface $ip
   * @return \Drupal\neibers_seat\Entity\SeatInterface
   */
  public function getSeatByInet(IPInterface $ip);
  /**
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface[]
   * @description Get Inet ip by order.
   */
  public function getInetsByOrder(OrderInterface $order);

  /**
   * @param \Drupal\commerce_order\Entity\OrderInterface $order
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface[]
   * @description Get Inet ip by order.
   */
  public function getOnetsByOrder(OrderInterface $order);

  /**
   * @param \Drupal\neibers_cabinet\Entity\SeatInterface $seat
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface[]
   * @description Get ips by seat.
   */
  public function getOnetsByInet(IPInterface $ip);

  /**
   * @return \Drupal\neibers_ip\Entity\IPInterface[]
   */
  public function getAvailableInetByHardware(HardwareInterface $neibers_hardware);

  /**
   * @return \Drupal\neibers_ip\Entity\IPInterface[]
   */
  public function getAvailableOnets();

}
