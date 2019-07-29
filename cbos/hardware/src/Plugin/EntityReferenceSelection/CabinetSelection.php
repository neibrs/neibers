<?php

namespace Drupal\neibers_hardware\Plugin\EntityReferenceSelection;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

/**
 * @EntityReferenceSelection(
 *   id = "default:neibers_hardware",
 *   label = @Translation("Hardware selection"),
 *   entity_types = {"neibers_hardware"},
 *   group = "default",
 *   weight = 1
 * )
 */
class CabinetSelection extends DefaultSelection {

  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $target_type = $this->getConfiguration()['target_type'];

    $query = $this->buildEntityQuery($match, $match_operator);
    if ($limit > 0) {
      $query->range(0, $limit);
    }

    $result = $query->execute();

    if (empty($result)) {
      return [];
    }

    $options = [];
    $entities = $this->entityManager->getStorage($target_type)->loadMultiple($result);
    foreach ($entities as $entity_id => $entity) {
      $bundle = $entity->bundle();
      $options[$bundle][$entity_id] = Html::escape($entity->label()
        . '-' . $entity->get('type')->entity->label());
    }

    return $options;
  }

}
