<?php

namespace Drupal\neibers_cabinet\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Seat.
 */
class SeatViewsData extends EntityViewsData {

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
