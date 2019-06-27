<?php

namespace Drupal\neibers_alert\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the Alert type entity.
 *
 * @ConfigEntityType(
 *   id = "neibers_alert_type",
 *   label = @Translation("Alert type"),
 *   label_collection = @Translation("Alert types"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\neibers_alert\AlertTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\neibers_alert\Form\AlertTypeForm",
 *       "edit" = "Drupal\neibers_alert\Form\AlertTypeForm",
 *       "delete" = "Drupal\eabax_core\Form\BundleDeleteForm",
 *     },
 *     "access" = "Drupal\neibers_alert\AlertTypeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_alert\AlertTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer neibers_alerts",
 *   bundle_of = "neibers_alert",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/alert/type/add",
 *     "edit-form" = "/alert/type/{neibers_alert_type}/edit",
 *     "delete-form" = "/alert/type/{neibers_alert_type}/delete",
 *     "collection" = "/alert/type",
 *   }
 * )
 */
class AlertType extends ConfigEntityBundleBase implements AlertTypeInterface {

  /**
   * The Alert type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Alert type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this node type.
   *
   * @var string
   */
  protected $description;

  protected $color;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function getColor() {
    return $this->color;
  }

  /**
   * {@inheritdoc}
   */
  public function postCreate(EntityStorageInterface $storage) {
    $color_palette = \Drupal::service('color_icon.manager')->getColorPalette();
    $this->color = $color_palette[array_rand($color_palette)];
  }

}
