<?php

namespace Drupal\neibers_hardware\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Hardware.
 */
class HardwareViewsData extends EntityViewsData {

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
