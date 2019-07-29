<?php

namespace Drupal\neibers_fitting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Fitting.
 *
 * @ingroup neibers_fitting
 */
interface FittingInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Fitting name.
   *
   * @return string
   *   Name of the Fitting.
   */
  public function getName();

  /**
   * Sets the Fitting name.
   *
   * @param string $name
   *   The Fitting name.
   *
   * @return \Drupal\neibers_fitting\Entity\FittingInterface
   *   The called Fitting entity.
   */
  public function setName($name);

  /**
   * Gets the Fitting creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Fitting.
   */
  public function getCreatedTime();

  /**
   * Sets the Fitting creation timestamp.
   *
   * @param int $timestamp
   *   The Fitting creation timestamp.
   *
   * @return \Drupal\neibers_fitting\Entity\FittingInterface
   *   The called Fitting entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Fitting published status indicator.
   *
   * Unpublished Fitting are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Fitting is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Fitting.
   *
   * @param bool $published
   *   TRUE to set this Fitting to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\neibers_fitting\Entity\FittingInterface
   *   The called Fitting entity.
   */
  public function setPublished($published);

}
