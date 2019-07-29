<?php

namespace Drupal\neibers_seat\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Seat type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_seat_type",
 *   label = @Translation("Seat type"),
 *   label_collection = @Translation("Seat type"),
 *   handlers = {
 *     "access" = "Drupal\neibers_seat\SeatTypeAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_seat\SeatTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_seat\Form\SeatTypeForm",
 *       "edit" = "Drupal\neibers_seat\Form\SeatTypeForm",
 *       "delete" = "Drupal\neibers_seat\Form\SeatTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_seat\SeatTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "neibers_seat",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/nidc/seat/type/{neibers_seat_type}",
 *     "add-form" = "/admin/config/nidc/seat/type/add",
 *     "edit-form" = "/admin/config/nidc/seat/type/{neibers_seat_type}/edit",
 *     "delete-form" = "/admin/config/nidc/seat/type/{neibers_seat_type}/delete",
 *     "collection" = "/admin/config/nidc/seat/type"
 *   }
 * )
 */
class SeatType extends ConfigEntityBundleBase implements SeatTypeInterface {

  /**
   * The Seat type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Seat type label.
   *
   * @var string
   */
  protected $label;

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    $locked = \Drupal::state()->get('neibers_seat.type.locked');
    return isset($locked[$this->id()]) ? $locked[$this->id()] : FALSE;
  }
}
