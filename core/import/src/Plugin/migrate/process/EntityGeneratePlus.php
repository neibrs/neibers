<?php

namespace Drupal\import\Plugin\migrate\process;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\migrate_plus\Plugin\migrate\process\EntityGenerate;

/**
 * Add conditions for entity_generate.
 *
 * @MigrateProcessPlugin(
 *   id = "entity_generate_plus"
 * )
 */
class EntityGeneratePlus extends EntityGenerate {

  /**
   * {@inheritdoc}
   */
  protected function query($value) {
    // Entity queries typically are case-insensitive. Therefore, we need to
    // handle case sensitive filtering as a post-query step. By default, it
    // filters case insensitive. Change to true if that is not the desired
    // outcome.
    $ignoreCase = !empty($this->configuration['ignore_case']) ?: FALSE;

    $multiple = is_array($value);

    $query = $this->entityManager->getStorage($this->lookupEntityType)
      ->getQuery()
      ->condition($this->lookupValueKey, $value, $multiple ? 'IN' : NULL);

    if ($this->lookupBundleKey) {
      $query->condition($this->lookupBundleKey, $this->lookupBundle);
    }

    // Plus
    foreach ($this->configuration['conditions'] as $key => $value) {
      $getProcessPlugin = $this->migratePluginManager->createInstance('get', ['source' => $value]);
      $value = $getProcessPlugin->transform(NULL, $this->migrateExecutable, $this->row, $value);
      $query->condition($key, $value);
    }

    $results = $query->execute();

    if (empty($results)) {
      return NULL;
    }

    // By default do a case-sensitive comparison.
    if (!$ignoreCase) {
      // Returns the entity's identifier.
      foreach ($results as $k => $identifier) {
        $entity = $this->entityManager->getStorage($this->lookupEntityType)->load($identifier);
        $result_value = $entity instanceof ConfigEntityInterface ? $entity->get($this->lookupValueKey) : $entity->get($this->lookupValueKey)->value;
        if (($multiple && !in_array($result_value, $value, TRUE)) || (!$multiple && $result_value !== $value)) {
          unset($results[$k]);
        }
      }
    }

    if ($multiple && !empty($this->destinationProperty)) {
      array_walk($results, function (&$value) {
        $value = [$this->destinationProperty => $value];
      });
    }

    return $multiple ? array_values($results) : reset($results);
  }

}
