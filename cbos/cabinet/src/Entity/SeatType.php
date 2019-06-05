<?php

namespace Drupal\cabinet\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Seat type entity.
 *
 * @ConfigEntityType(
 *   id = "seat_type",
 *   label = @Translation("Seat type"),
 *   label_collection = @Translation("Seat type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cabinet\SeatTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\cabinet\Form\SeatTypeForm",
 *       "edit" = "Drupal\cabinet\Form\SeatTypeForm",
 *       "delete" = "Drupal\cabinet\Form\SeatTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cabinet\SeatTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seat_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "seat",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/seat/type/{seat_type}",
 *     "add-form" = "/seat/type/add",
 *     "edit-form" = "/seat/type/{seat_type}/edit",
 *     "delete-form" = "/seat/type/{seat_type}/delete",
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
