<?php

namespace Drupal\neibers_fitting\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Fitting type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_fitting_type",
 *   label = @Translation("Fitting type"),
 *   label_collection = @Translation("Fitting type"),
 *   handlers = {
 *     "access" = "Drupal\neibers_fitting\FittingTypeAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_fitting\FittingTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_fitting\Form\FittingTypeForm",
 *       "edit" = "Drupal\neibers_fitting\Form\FittingTypeForm",
 *       "delete" = "Drupal\neibers_fitting\Form\FittingTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_fitting\FittingTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "neibers_fitting",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/nidc/fitting/type/{neibers_fitting_type}",
 *     "add-form" = "/admin/config/nidc/fitting/type/add",
 *     "edit-form" = "/admin/config/nidc/fitting/type/{neibers_fitting_type}/edit",
 *     "delete-form" = "/admin/config/nidc/fitting/type/{neibers_fitting_type}/delete",
 *     "collection" = "/admin/config/nidc/fitting/type"
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

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    $locked = \Drupal::state()->get('neibers_fitting.type.locked');
    return isset($locked[$this->id()]) ? $locked[$this->id()] : FALSE;
  }
}
