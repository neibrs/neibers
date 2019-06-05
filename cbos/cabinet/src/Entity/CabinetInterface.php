<?php

namespace Drupal\cabinet\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Cabinet.
 *
 * @ingroup cabinet
 */
interface CabinetInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Cabinet name.
   *
   * @return string
   *   Name of the Cabinet.
   */
  public function getName();

  /**
   * Sets the Cabinet name.
   *
   * @param string $name
   *   The Cabinet name.
   *
   * @return \Drupal\cabinet\Entity\CabinetInterface
   *   The called Cabinet entity.
   */
  public function setName($name);

  /**
   * Gets the Cabinet creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Cabinet.
   */
  public function getCreatedTime();

  /**
   * Sets the Cabinet creation timestamp.
   *
   * @param int $timestamp
   *   The Cabinet creation timestamp.
   *
   * @return \Drupal\cabinet\Entity\CabinetInterface
   *   The called Cabinet entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Cabinet published status indicator.
   *
   * Unpublished Cabinet are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Cabinet is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Cabinet.
   *
   * @param bool $published
   *   TRUE to set this Cabinet to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\cabinet\Entity\CabinetInterface
   *   The called Cabinet entity.
   */
  public function setPublished($published);

}
