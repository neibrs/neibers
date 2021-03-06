<?php

namespace Drupal\neibers_category\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Category type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_category_type",
 *   label = @Translation("Category type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_category\CategoryTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_category\Form\CategoryTypeForm",
 *       "edit" = "Drupal\neibers_category\Form\CategoryTypeForm",
 *       "delete" = "Drupal\neibers_category\Form\CategoryTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_category\CategoryTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer category",
 *   bundle_of = "neibers_category",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/category/type/{neibers_category_type}",
 *     "add-form" = "/category/type/add",
 *     "edit-form" = "/category/type/{neibers_category_type}/edit",
 *     "delete-form" = "/category/type/{neibers_category_type}/delete",
 *     "collection" = "/category/type"
 *   }
 * )
 */
class CategoryType extends ConfigEntityBundleBase implements CategoryTypeInterface {

  /**
   * The Category type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Category type label.
   *
   * @var string
   */
  protected $label;

}
