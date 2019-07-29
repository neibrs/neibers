<?php

namespace Drupal\entity_plus\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * @Block(
 *   id = "entity_operations_menu",
 *   admin_label = @Translation("Entity operations menu")
 * )
 */
class EntityOperationsMenuBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'entity_type_id' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['entity_type_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Entity type ID'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['entity_type_id'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['entity_type_id'] = $form_state->getValue('entity_type_id');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_match = \Drupal::routeMatch();

    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $route_match->getParameter($this->configuration['entity_type_id']);
    if (!$entity) {
      return [];
    }

    if (!is_object($entity)) {
      $entity = \Drupal::entityTypeManager()
        ->getStorage($this->configuration['entity_type_id'])
        ->load($entity);
    }

    $operations = [];

    if ($entity->hasLinkTemplate('canonical')) {
      $operations['view'] = [
        'title' => $this->t('@entity information', ['@entity' => $entity->getEntityType()->getLabel()]),
        'url' => $entity->toUrl(),
      ];
    }

    $operations += \Drupal::entityTypeManager()
      ->getListBuilder($this->configuration['entity_type_id'])
      ->getOperations($entity);

    $language = \Drupal::languageManager()->getCurrentLanguage();
    foreach ($operations as &$operation) {
      if (isset($operation['url']) && $link_language = $operation['url']->getOption('language')) {
        if ($link_language->getId() != LanguageInterface::LANGCODE_NOT_SPECIFIED) {
          $operation['url']->setOption('language', $language);
        }
      }
    }

    $build['menu'] = [
      '#theme' => 'links__nav_tabs',
      '#links' => $operations,
    ];
    $build['menu']['#attached']['library'][] = 'entity_plus/local_actions';

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeTags(parent::getCacheContexts(), ['languages']);
  }

}
