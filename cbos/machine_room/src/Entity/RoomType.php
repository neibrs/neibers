<?php

namespace Drupal\machine_room\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Machine Room type entity.
 *
 * @ConfigEntityType(
 *   id = "room_type",
 *   label = @Translation("Machine Room type"),
 *   label_collection = @Translation("Machine Room type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\machine_room\RoomTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\machine_room\Form\RoomTypeForm",
 *       "edit" = "Drupal\machine_room\Form\RoomTypeForm",
 *       "delete" = "Drupal\machine_room\Form\RoomTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\machine_room\RoomTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer machine room",
 *   bundle_of = "room",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/room/type/{room_type}",
 *     "add-form" = "/room/type/add",
 *     "edit-form" = "/room/type/{room_type}/edit",
 *     "delete-form" = "/room/type/{room_type}/delete",
 *     "collection" = "/room/type"
 *   }
 * )
 */
class RoomType extends ConfigEntityBundleBase implements RoomTypeInterface {

  /**
   * The Machine Room type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Machine Room type label.
   *
   * @var string
   */
  protected $label;

}
