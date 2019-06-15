<?php

namespace Drupal\ip\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the IP entity.
 *
 * @ingroup ip
 *
 * @ContentEntityType(
 *   id = "ip",
 *   label = @Translation("IP"),
 *   label_collection = @Translation("IP"),
 *   bundle_label = @Translation("IP type"),
 *   handlers = {
 *     "storage" = "Drupal\ip\IPStorage",
 *     "view_builder" = "Drupal\ip\IPViewBuilder",
 *     "list_builder" = "Drupal\ip\IPListBuilder",
 *     "views_data" = "Drupal\ip\Entity\IPViewsData",
 *     "translation" = "Drupal\ip\IPTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\ip\Form\IPForm",
 *       "add" = "Drupal\ip\Form\IPForm",
 *       "edit" = "Drupal\ip\Form\IPForm",
 *       "delete" = "Drupal\ip\Form\IPDeleteForm",
 *     },
 *     "access" = "Drupal\ip\IPAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\ip\IPHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "ip",
 *   data_table = "ip_field_data",
 *   revision_table = "ip_revision",
 *   revision_data_table = "ip_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer ip",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/ip/{ip}",
 *     "add-page" = "/ip/add",
 *     "add-form" = "/ip/add/{ip_type}",
 *     "edit-form" = "/ip/{ip}/edit",
 *     "delete-form" = "/ip/{ip}/delete",
 *     "version-history" = "/ip/{ip}/revisions",
 *     "revision" = "/ip/{ip}/revisions/{ip_revision}/view",
 *     "revision_revert" = "/ip/{ip}/revisions/{ip_revision}/revert",
 *     "revision_delete" = "/ip/{ip}/revisions/{ip_revision}/delete",
 *     "translation_revert" = "/ip/{ip}/revisions/{ip_revision}/revert/{langcode}",
 *     "collection" = "/ip",
 *   },
 *   bundle_entity_type = "ip_type",
 *   field_ui_base_route = "entity.ip_type.edit_form"
 * )
 */
class IP extends RevisionableContentEntityBase implements IPInterface {

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
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the ip owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }

    // TODO polish
    $route_match = \Drupal::routeMatch();
    if ($route_match->getRouteName() == 'eabax_workflows.apply_transition') {
      $route_parameters = $route_match->getRawParameters();
      // state workflow transition is stop.
      if ($route_parameters->get('transition_id') == 'stop') {
        // Stop use ip.
        if ($this->type->entity->id() == 'inet') {
          $bips = $this->entityTypeManager()->getStorage('ip')->loadByProperties([
            'type' => 'onet',
            'order_id' => $this->order_id->target_id,
          ]);
          foreach ($bips as $bip) {
            $bip = $this->unbindOnet($bip);
            $bip->save();
          }
          // stop administer ip for order.
          $this->unbindInet($this);
        }
        if ($this->type->entity->id() == 'onet') {
          $this->unbindOnet($this);
        }
      }
    }
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

    $fields['type']
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the IP entity.'))
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
      ->setDescription(t('The name of the IP entity.'))
      ->addConstraint('UniqueField')
      ->setRevisionable(TRUE)
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['hardware'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hardware'))
      ->setSetting('target_type', 'neibers_hardware')
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 6,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'Which belong to hardware.',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['seat'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Cabinet Seat'))
      ->setSetting('target_type', 'seat')
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 6,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'IP belong to seat.',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['order_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Order'))
      ->setDescription(t('The related order number.'))
      ->setSetting('target_type', 'commerce_order')
      // TODO need confirm the readonly.
      ->setReadOnly(TRUE)
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 6,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'IP belong to Order.',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the IP is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['state'] = BaseFieldDefinition::create('entity_status')
      ->setLabel(t('State'))
      ->setRequired(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('workflow_type', 'ip_state')
      ->setSetting('workflow', 'default_ip_state')
      ->setDefaultValue('free')
      ->setDisplayOptions('view', [
        'type' => 'list_default',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
        'disable' => TRUE,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function unbindOnet(IPInterface $ip) {
    // Need use the workflow transition to be free.
    $ip->state->value        = 'free';
    $ip->hardware->target_id   = 0;
    $ip->seat->target_id     = 0;
    $ip->order_id->target_id = 0;
    $ip->user_id->target_id  = 1;

    return $ip;
  }

  /**
   * {@inheritdoc}
   */
  public function unbindInet(IPInterface $ip) {
    $ip->user_id->target_id = 1;
    $ip->order_id->target_id = 0;

    return $ip;
  }

}
