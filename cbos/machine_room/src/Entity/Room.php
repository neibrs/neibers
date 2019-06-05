<?php

namespace Drupal\machine_room\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Machine Room entity.
 *
 * @ingroup machine_room
 *
 * @ContentEntityType(
 *   id = "room",
 *   label = @Translation("Machine Room"),
 *   label_collection = @Translation("Machine Room"),
 *   bundle_label = @Translation("Machine Room type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\machine_room\RoomListBuilder",
 *     "views_data" = "Drupal\machine_room\Entity\RoomViewsData",
 *     "translation" = "Drupal\machine_room\RoomTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\machine_room\Form\RoomForm",
 *       "add" = "Drupal\machine_room\Form\RoomForm",
 *       "edit" = "Drupal\machine_room\Form\RoomForm",
 *       "delete" = "Drupal\machine_room\Form\RoomDeleteForm",
 *     },
 *     "access" = "Drupal\machine_room\RoomAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\machine_room\RoomHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "room",
 *   data_table = "room_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer machine room",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/room/{room}",
 *     "add-page" = "/room/add",
 *     "add-form" = "/room/add/{room_type}",
 *     "edit-form" = "/room/{room}/edit",
 *     "delete-form" = "/room/{room}/delete",
 *     "collection" = "/room",
 *   },
 *   bundle_entity_type = "room_type",
 *   field_ui_base_route = "entity.room_type.edit_form"
 * )
 */
class Room extends ContentEntityBase implements RoomInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Machine Room entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setDescription(t('The name of the Machine Room entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Machine Room is published.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 25,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['speed_test_address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Speed test ip address'))
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string',
        'weight' => 40,
      ])
      // TODO add view formatter.
      ->setDisplayConfigurable('form', TRUE);

    $fields['x_axis'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('X axis'))
      ->setDescription(t('The total number of statistical x axis cabinet.'))
      ->setSetting('min', 1)
      ->setDefaultValue(10)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['y_axis'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Y axis'))
      ->setDescription(t('The total number of statistical Y axis cabinet.'))
      ->setSetting('min', 1)
      ->setDefaultValue(10)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
