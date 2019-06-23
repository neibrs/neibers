<?php

namespace Drupal\neibers_room\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Room type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_room_type",
 *   label = @Translation("Room type"),
 *   label_collection = @Translation("Room type"),
 *   handlers = {
 *     "access" = "Drupal\neibers_room\RoomTypeAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_room\RoomTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_room\Form\RoomTypeForm",
 *       "edit" = "Drupal\neibers_room\Form\RoomTypeForm",
 *       "delete" = "Drupal\neibers_room\Form\RoomTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_room\RoomTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer room",
 *   bundle_of = "neibers_room",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/room/type/{neibers_room_type}",
 *     "add-form" = "/room/type/add",
 *     "edit-form" = "/room/type/{neibers_room_type}/edit",
 *     "delete-form" = "/room/type/{neibers_room_type}/delete",
 *     "collection" = "/room/type"
 *   }
 * )
 */
class RoomType extends ConfigEntityBundleBase implements RoomTypeInterface {

  /**
   * The Room type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Room type label.
   *
   * @var string
   */
  protected $label;

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    $locked = \Drupal::state()->get('neibers_room.type.locked');
    return isset($locked[$this->id()]) ? $locked[$this->id()] : FALSE;
  }

}
