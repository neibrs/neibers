<?php

namespace Drupal\neibers_ip\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining IP entities.
 *
 * @ingroup neibers_ip
 */
interface IPInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the IP name.
   *
   * @return string
   *   Name of the IP.
   */
  public function getName();

  /**
   * Sets the IP name.
   *
   * @param string $name
   *   The IP name.
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface
   *   The called IP entity.
   */
  public function setName($name);

  /**
   * Gets the IP creation timestamp.
   *
   * @return int
   *   Creation timestamp of the IP.
   */
  public function getCreatedTime();

  /**
   * Sets the IP creation timestamp.
   *
   * @param int $timestamp
   *   The IP creation timestamp.
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface
   *   The called IP entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the IP revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the IP revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface
   *   The called IP entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the IP revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the IP revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\neibers_ip\Entity\IPInterface
   *   The called IP entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Unbind business ip from used hardware for user.
   *
   * @return IPInterface
   */
  public function unbindOnet(IPInterface $ip);

  /**
   * Unbind administer ip from used hardware for user.
   */
  public function unbindInet(IPInterface $ip);


}
