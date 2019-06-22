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
 *     "canonical" = "/seat/type/{neibers_seat_type}",
 *     "add-form" = "/seat/type/add",
 *     "edit-form" = "/seat/type/{neibers_seat_type}/edit",
 *     "delete-form" = "/seat/type/{neibers_seat_type}/delete",
 *     "collection" = "/seat/type"
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

}
