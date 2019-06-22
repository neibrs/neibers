<?php

namespace Drupal\neibers_hardware\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Hardware type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_hardware_type",
 *   label = @Translation("Hardware type"),
 *   label_collection = @Translation("Hardware type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_hardware\HardwareTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_hardware\Form\HardwareTypeForm",
 *       "edit" = "Drupal\neibers_hardware\Form\HardwareTypeForm",
 *       "delete" = "Drupal\neibers_hardware\Form\HardwareTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_hardware\HardwareTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "neibers_hardware",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/hardware/type/{neibers_hardware_type}",
 *     "add-form" = "/hardware/type/add",
 *     "edit-form" = "/hardware/type/{neibers_hardware_type}/edit",
 *     "delete-form" = "/hardware/type/{neibers_hardware_type}/delete",
 *     "collection" = "/hardware/type"
 *   }
 * )
 */
class HardwareType extends ConfigEntityBundleBase implements HardwareTypeInterface {

  /**
   * The Hardware type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Hardware type label.
   *
   * @var string
   */
  protected $label;

}
