<?php

namespace Drupal\neibers_cabinet\Plugin\EntityReferenceSelection;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

/**
 * @EntityReferenceSelection(
 *   id = "default:neibers_seat",
 *   label = @Translation("Seat selection"),
 *   entity_types = {"neibers_seat"},
 *   group = "default",
 *   weight = 1
 * )
 */
class SeatSelection extends DefaultSelection {

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
        . '-' . $entity->get('cabinet')->entity->label() . '-' . $entity->get('cabinet')->entity->get('room')->entity->label());
    }

    return $options;
  }

}
