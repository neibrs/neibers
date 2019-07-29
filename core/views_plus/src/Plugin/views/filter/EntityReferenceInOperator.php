<?php

namespace Drupal\views_plus\Plugin\views\filter;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\FieldAPIHandlerTrait;
use Drupal\views\Plugin\views\filter\InOperator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ViewsFilter("entity_reference_in_operator")
 */
class EntityReferenceInOperator extends InOperator {

  use FieldAPIHandlerTrait;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['properties']['default'] = [];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getValueOptions() {
    if (!isset($this->valueOptions)) {
      $entity_type_id = $this->getFieldDefinition()->getItemDefinition()->getSetting('target_type');

      if (!empty($this->options['properties'])) {
        $entities = $this->getEntityManager()->getStorage($entity_type_id)->loadByProperties($this->options['properties']);
      }
      else {
        $entities = $this->getEntityManager()->getStorage($entity_type_id)->loadMultiple();
      }
      $options = array_map(function ($entity) {
        return $entity->label();
      }, $entities);
      asort($options);

      $this->valueOptions = $options;
    }

    return $this->valueOptions;
  }

}
