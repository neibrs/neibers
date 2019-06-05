<?php

namespace Drupal\machine_room\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Machine Room.
 */
class RoomViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
