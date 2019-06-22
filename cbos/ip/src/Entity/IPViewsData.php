<?php

namespace Drupal\neibers_ip\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for IP.
 */
class IPViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['neibers_ip_field_data']['business_ips'] = [
      'title' => t('Business IP'),
      'field' => [
        'id' => 'business_ips',
      ],
    ];
    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
