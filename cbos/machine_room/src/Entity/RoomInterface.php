<?php

namespace Drupal\machine_room\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Machine Room.
 *
 * @ingroup machine_room
 */
interface RoomInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Machine Room name.
   *
   * @return string
   *   Name of the Machine Room.
   */
  public function getName();

  /**
   * Sets the Machine Room name.
   *
   * @param string $name
   *   The Machine Room name.
   *
   * @return \Drupal\machine_room\Entity\RoomInterface
   *   The called Machine Room entity.
   */
  public function setName($name);

  /**
   * Gets the Machine Room creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Machine Room.
   */
  public function getCreatedTime();

  /**
   * Sets the Machine Room creation timestamp.
   *
   * @param int $timestamp
   *   The Machine Room creation timestamp.
   *
   * @return \Drupal\machine_room\Entity\RoomInterface
   *   The called Machine Room entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Machine Room published status indicator.
   *
   * Unpublished Machine Room are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Machine Room is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Machine Room.
   *
   * @param bool $published
   *   TRUE to set this Machine Room to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\machine_room\Entity\RoomInterface
   *   The called Machine Room entity.
   */
  public function setPublished($published);

}
