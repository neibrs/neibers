<?php

namespace Drupal\neibers_alert\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for neibers_alerts.
 */
class AlertViewsData extends EntityViewsData {

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
