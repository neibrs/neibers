<?php

namespace Drupal\eabax_core\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @FieldFormatter(
 *   id = "entity_reference_custom",
 *   label = @Translation("Custom"),
 *   description = @Translation("Display the referenced entity."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class EntityReferenceCustom extends EntityReferenceFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, EntityDisplayRepositoryInterface $entity_display_repository) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->entityDisplayRepository = $entity_display_repository;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['view_mode'] = 'default';
    $options['prefix'] = '';
    $options['suffix'] = '';
    $options['field_prefix'] = '';
    $options['field_suffix'] = '';
    $options['label_prefix'] = '';
    $options['label_suffix'] = ': ';
    $options['value_prefix'] = '';
    $options['value_suffix'] = ',';

    return $options;
  }

  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['view_mode'] = [
      '#type' => 'select',
      '#options' => $this->entityDisplayRepository->getViewModeOptions($this->getFieldSetting('target_type')),
      '#title' => t('View mode'),
      '#default_value' => $this->getSetting('view_mode'),
      '#required' => TRUE,
    ];
    $form['prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Prefix'),
      '#default_value' => $this->getSetting('prefix'),
    ];
    $form['suffix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Suffix'),
      '#default_value' => $this->getSetting('suffix'),
    ];
    $form['field_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field prefix'),
      '#default_value' => $this->getSetting('field_prefix'),
    ];
    $form['field_suffix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field suffix'),
      '#default_value' => $this->getSetting('field_suffix'),
    ];
    $form['label_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label prefix'),
      '#default_value' => $this->getSetting('label_prefix'),
    ];
    $form['label_suffix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label suffix'),
      '#default_value' => $this->getSetting('label_suffix'),
    ];
    $form['value_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Value prefix'),
      '#default_value' => $this->getSetting('value_prefix'),
    ];
    $form['value_suffix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Value suffix'),
      '#default_value' => $this->getSetting('value_suffix'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $display = EntityViewDisplay::collectRenderDisplay($entity, $this->getSetting('view_mode'));

      $markup = $this->getSetting('prefix');
      foreach ($display->getComponents() as $key => $component) {
        $baseFieldDefinition = $entity->getFieldDefinitions()[$key];

        $markup .= $this->getSetting('field_prefix');
        $markup .= $this->getSetting('label_prefix');
        $markup .= $baseFieldDefinition->getLabel();
        $markup .= $this->getSetting('label_suffix');
        $markup .= $this->getSetting('value_prefix');
        if ($baseFieldDefinition->getType() == "entity_reference") {
          $markup .= $entity->get($key)->isEmpty() ? '-' : $entity->get($key)->entity->label();
        }
        else {
          $markup .= $entity->get($key)->value;
        }
        $markup .= $this->getSetting('value_suffix');
        $markup .= $this->getSetting('field_suffix');

      }
      $markup .= $this->getSetting('suffix');

      $elements[$delta] = [
        '#markup' => $markup,
      ];
    }

    return $elements;
  }

}
