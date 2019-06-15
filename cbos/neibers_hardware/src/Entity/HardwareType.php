<?php

namespace Drupal\neibers_hardware\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Server type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_hardware_type",
 *   label = @Translation("Server type"),
 *   label_collection = @Translation("Server type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_hardware\ServerTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_hardware\Form\ServerTypeForm",
 *       "edit" = "Drupal\neibers_hardware\Form\ServerTypeForm",
 *       "delete" = "Drupal\neibers_hardware\Form\ServerTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_hardware\ServerTypeHtmlRouteProvider",
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
class ServerType extends ConfigEntityBundleBase implements ServerTypeInterface {

  /**
   * The Server type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Server type label.
   *
   * @var string
   */
  protected $label;

}
