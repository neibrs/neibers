<?php

namespace Drupal\fitting\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Fitting type entity.
 *
 * @ConfigEntityType(
 *   id = "fitting_type",
 *   label = @Translation("Fitting type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\fitting\FittingTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\fitting\Form\FittingTypeForm",
 *       "edit" = "Drupal\fitting\Form\FittingTypeForm",
 *       "delete" = "Drupal\fitting\Form\FittingTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\fitting\FittingTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "fitting",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/fitting/type/{fitting_type}",
 *     "add-form" = "/fitting/type/add",
 *     "edit-form" = "/fitting/type/{fitting_type}/edit",
 *     "delete-form" = "/fitting/type/{fitting_type}/delete",
 *     "collection" = "/fitting/type"
 *   }
 * )
 */
class FittingType extends ConfigEntityBundleBase implements FittingTypeInterface {

  /**
   * The Fitting type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Fitting type label.
   *
   * @var string
   */
  protected $label;

}
