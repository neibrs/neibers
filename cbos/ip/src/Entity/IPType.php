<?php

namespace Drupal\neibers_ip\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the IP type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_ip_type",
 *   label = @Translation("IP type"),
 *   handlers = {
 *     "access" = "Drupal\neibers_ip\IPTypeAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_ip\IPTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_ip\Form\IPTypeForm",
 *       "edit" = "Drupal\neibers_ip\Form\IPTypeForm",
 *       "delete" = "Drupal\neibers_ip\Form\IPTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_ip\IPTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "neibers_ip",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/ip/type/{neibers_ip_type}",
 *     "add-form" = "/ip/type/add",
 *     "edit-form" = "/ip/type/{neibers_ip_type}/edit",
 *     "delete-form" = "/ip/type/{neibers_ip_type}/delete",
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
