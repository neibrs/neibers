<?php

namespace Drupal\neibers_ip\Entity;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\neibers_seat\Entity\SeatInterface;
use Drupal\user\UserInterface;

/**
 * Defines the IP entity.
 *
 * @ingroup neibers_ip
 *
 * @ContentEntityType(
 *   id = "neibers_ip",
 *   label = @Translation("IP"),
 *   bundle_label = @Translation("IP type"),
 *   handlers = {
 *     "storage" = "Drupal\neibers_ip\IPStorage",
 *     "list_builder" = "Drupal\neibers_ip\IPListBuilder",
 *     "view_builder" = "Drupal\neibers_ip\IPViewBuilder",
 *     "views_data" = "Drupal\neibers_ip\Entity\IPViewsData",
 *     "translation" = "Drupal\neibers_ip\IPTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\neibers_ip\Form\IPForm",
 *       "add" = "Drupal\neibers_ip\Form\IPForm",
 *       "edit" = "Drupal\neibers_ip\Form\IPForm",
 *       "delete" = "Drupal\neibers_ip\Form\IPDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\neibers_ip\IPHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\neibers_ip\IPAccessControlHandler",
 *   },
 *   base_table = "neibers_ip",
 *   data_table = "neibers_ip_field_data",
 *   revision_table = "neibers_ip_revision",
 *   revision_data_table = "neibers_ip_field_revision",
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
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/ip/{neibers_ip}",
 *     "add-page" = "/admin/nidc/ip/add",
 *     "add-form" = "/admin/nidc/ip/add/{neibers_ip_type}",
 *     "edit-form" = "/admin/nidc/ip/{neibers_ip}/edit",
 *     "delete-form" = "/admin/nidc/ip/{neibers_ip}/delete",
 *     "version-history" = "/admin/nidc/ip/{neibers_ip}/revisions",
 *     "revision" = "/admin/nidc/ip/{neibers_ip}/revisions/{neibers_ip_revision}/view",
 *     "revision_revert" = "/admin/nidc/ip/{neibers_ip}/revisions/{neibers_ip_revision}/revert",
 *     "revision_delete" = "/admin/nidc/ip/{neibers_ip}/revisions/{neibers_ip_revision}/delete",
 *     "translation_revert" = "/admin/nidc/ip/{neibers_ip}/revisions/{neibers_ip_revision}/revert/{langcode}",
 *     "collection" = "/admin/nidc/ip",
 *   },
 *   bundle_entity_type = "neibers_ip_type",
 *   field_ui_base_route = "entity.neibers_ip_type.edit_form"
 * )
 */
class IP extends RevisionableContentEntityBase implements IPInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

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
          $bips = $this->entityTypeManager()->getStorage('neibers_ip')->loadByProperties([
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);


    $fields['type']
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => -10,
      ])
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
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
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

    $fields['seat'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Cabinet Seat'))
      ->setSetting('target_type', 'neibers_seat')
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

    $fields['status']->setDescription(t('A boolean indicating whether the IP is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

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

  /**
   * @description Allocate ip to order
   */
  public function allocateInet(OrderInterface $order) {
    $this->setOrder($order);
    $this->setOwnerId($order->getCustomerId());
    $this->setState('used');

    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function allocateOnet(SeatInterface $seat, OrderInterface $order) {
    $this->setSeat($seat);
    $this->setOrder($order);
    $this->setOwnerId($order->getCustomerId());
    $this->setState('used');

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrder(OrderInterface $order) {
    $this->set('order_id', $order->id());

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrderId() {
    return $this->get('order_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setSeat(SeatInterface $seat) {
    $this->seat->target_id = $seat->id();

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSeat() {
    return $this->seat->entity;
  }

  public function setState($state = '') {
    // TODO state for workflow transition.
    $this->state->value = $state;

    return;
  }
}
