<?php

namespace Drupal\cabinet\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Seat.
 *
 * @ingroup cabinet
 */
interface SeatInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Seat name.
   *
   * @return string
   *   Name of the Seat.
   */
  public function getName();

  /**
   * Sets the Seat name.
   *
   * @param string $name
   *   The Seat name.
   *
   * @return \Drupal\cabinet\Entity\SeatInterface
   *   The called Seat entity.
   */
  public function setName($name);

  /**
   * Gets the Seat creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Seat.
   */
  public function getCreatedTime();

  /**
   * Sets the Seat creation timestamp.
   *
   * @param int $timestamp
   *   The Seat creation timestamp.
   *
   * @return \Drupal\cabinet\Entity\SeatInterface
   *   The called Seat entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Seat published status indicator.
   *
   * Unpublished Seat are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Seat is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Seat.
   *
   * @param bool $published
   *   TRUE to set this Seat to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\cabinet\Entity\SeatInterface
   *   The called Seat entity.
   */
  public function setPublished($published);

}
