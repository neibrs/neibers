<?php

namespace Drupal\ip\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the IP type entity.
 *
 * @ConfigEntityType(
 *   id = "ip_type",
 *   label = @Translation("IP type"),
 *   label_collection = @Translation("IP type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ip\IPTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ip\Form\IPTypeForm",
 *       "edit" = "Drupal\ip\Form\IPTypeForm",
 *       "delete" = "Drupal\ip\Form\IPTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ip\IPTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ip",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/ip/type/{ip_type}",
 *     "add-form" = "/ip/type/add",
 *     "edit-form" = "/ip/type/{ip_type}/edit",
 *     "delete-form" = "/ip/type/{ip_type}/delete",
 *     "collection" = "/ip/type"
 *   }
 * )
 */
class IPType extends ConfigEntityBundleBase implements IPTypeInterface {

  /**
   * The IP type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The IP type label.
   *
   * @var string
   */
  protected $label;

}
