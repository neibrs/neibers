<?php

namespace Drupal\neibers_hardware\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Hardware.
 *
 * @ingroup neibers_hardware
 */
interface HardwareInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Hardware name.
   *
   * @return string
   *   Name of the Hardware.
   */
  public function getName();

  /**
   * Sets the Hardware name.
   *
   * @param string $name
   *   The Hardware name.
   *
   * @return \Drupal\neibers_hardware\Entity\HardwareInterface
   *   The called Hardware entity.
   */
  public function setName($name);

  /**
   * Gets the Hardware creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Hardware.
   */
  public function getCreatedTime();

  /**
   * Sets the Hardware creation timestamp.
   *
   * @param int $timestamp
   *   The Hardware creation timestamp.
   *
   * @return \Drupal\neibers_hardware\Entity\HardwareInterface
   *   The called Hardware entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Hardware published status indicator.
   *
   * Unpublished Hardware are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Hardware is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Hardware.
   *
   * @param bool $published
   *   TRUE to set this Hardware to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\neibers_hardware\Entity\HardwareInterface
   *   The called Hardware entity.
   */
  public function setPublished($published);

}
