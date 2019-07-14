<?php

namespace Drupal\neibers_category\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Category entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_category",
 *   label = @Translation("Category"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_category\CategoryListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_category\Form\CategoryForm",
 *       "edit" = "Drupal\neibers_category\Form\CategoryForm",
 *       "delete" = "Drupal\neibers_category\Form\CategoryDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_category\CategoryHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "neibers_category",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "//neibers_category/{neibers_category}",
 *     "add-form" = "//neibers_category/add",
 *     "edit-form" = "//neibers_category/{neibers_category}/edit",
 *     "delete-form" = "//neibers_category/{neibers_category}/delete",
 *     "collection" = "//neibers_category"
 *   }
 * )
 */
class Category extends ConfigEntityBase implements CategoryInterface {

  /**
   * The Category ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Category label.
   *
   * @var string
   */
  protected $label;

}
