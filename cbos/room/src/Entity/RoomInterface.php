<?php

namespace Drupal\neibers_room\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Room.
 *
 * @ingroup neibers_room
 */
interface RoomInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Room name.
   *
   * @return string
   *   Name of the Room.
   */
  public function getName();

  /**
   * Sets the Room name.
   *
   * @param string $name
   *   The Room name.
   *
   * @return \Drupal\neibers_room\Entity\RoomInterface
   *   The called Room entity.
   */
  public function setName($name);

  /**
   * Gets the Room creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Room.
   */
  public function getCreatedTime();

  /**
   * Sets the Room creation timestamp.
   *
   * @param int $timestamp
   *   The Room creation timestamp.
   *
   * @return \Drupal\neibers_room\Entity\RoomInterface
   *   The called Room entity.
   */
  public function setCreatedTime($timestamp);


}
