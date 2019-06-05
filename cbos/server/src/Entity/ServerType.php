<?php

namespace Drupal\server\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Server type entity.
 *
 * @ConfigEntityType(
 *   id = "server_type",
 *   label = @Translation("Server type"),
 *   label_collection = @Translation("Server type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\server\ServerTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\server\Form\ServerTypeForm",
 *       "edit" = "Drupal\server\Form\ServerTypeForm",
 *       "delete" = "Drupal\server\Form\ServerTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\server\ServerTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "server",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/server/type/{server_type}",
 *     "add-form" = "/server/type/add",
 *     "edit-form" = "/server/type/{server_type}/edit",
 *     "delete-form" = "/server/type/{server_type}/delete",
 *     "collection" = "/server/type"
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
