<?php

namespace Drupal\neibers_fitting\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Fitting.
 */
class FittingViewsData extends EntityViewsData {

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
