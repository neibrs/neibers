<?php

namespace Drupal\neibers_category\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Category entities.
 *
 * @ingroup neibers_category
 */
interface CategoryInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Category name.
   *
   * @return string
   *   Name of the Category.
   */
  public function getName();

  /**
   * Sets the Category name.
   *
   * @param string $name
   *   The Category name.
   *
   * @return \Drupal\neibers_category\Entity\CategoryInterface
   *   The called Category entity.
   */
  public function setName($name);

  /**
   * Gets the Category creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Category.
   */
  public function getCreatedTime();

  /**
   * Sets the Category creation timestamp.
   *
   * @param int $timestamp
   *   The Category creation timestamp.
   *
   * @return \Drupal\neibers_category\Entity\CategoryInterface
   *   The called Category entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Category revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Category revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\neibers_category\Entity\CategoryInterface
   *   The called Category entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Category revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Category revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\neibers_category\Entity\CategoryInterface
   *   The called Category entity.
   */
  public function setRevisionUserId($uid);

}
