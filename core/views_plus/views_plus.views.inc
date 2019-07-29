<?php

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\field\FieldStorageConfigInterface;

/**
 * Implements hook_views_data_alter().
 */
function views_plus_views_data_alter(array &$data) {
  $entity_type_manager = \Drupal::entityTypeManager();
  $entity_types = $entity_type_manager->getDefinitions();
  foreach ($entity_types as $entity_type) {
    if ($entity_type instanceof ContentEntityTypeInterface) {
      $field_definitions = \Drupal::entityManager()
        ->getBaseFieldDefinitions($entity_type->id());
      $storage = $entity_type_manager->getStorage($entity_type->id());
      if (!($storage instanceof SqlContentEntityStorage)) {
        continue;
      }
      /** @var \Drupal\Core\Entity\Sql\DefaultTableMapping $table_mapping */
      if ($table_mapping = $storage->getTableMapping($field_definitions)) {
        foreach ($table_mapping->getTableNames() as $table) {
          foreach ($table_mapping->getFieldNames($table) as $field_name) {
            if (isset($data[$table][$field_name]['filter'])) {
              if ($field_definitions[$field_name]->getType() == 'entity_reference' && $data[$table][$field_name]['filter']['id'] == 'numeric') {
                $data[$table][$field_name]['filter']['id'] = 'entity_reference_in_operator';
                $data[$table][$field_name]['filter']['field_name'] = $field_name;
              }
            }
          }
        }
      }
    }
  }
}

/**
 * Implements hook_field_views_data_alter().
 */
function views_plus_field_views_data_alter(array &$data, FieldStorageConfigInterface $field_storage) {
  if ($field_storage->getType() == 'entity_reference') {
    foreach ($data as $table_name => $table_data) {
      foreach ($table_data as $field_name => $field_data) {
        if (isset($field_data['filter']) && $field_name != 'delta') {
          if ($field_data['filter']['id'] == 'numeric') {
            $data[$table_name][$field_name]['filter']['id'] = 'entity_reference_in_operator';
          }
        }
      }
    }
  }
}
