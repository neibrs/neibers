<?php


/**
 * Implements hook_views_data_alter().
 */
function ip_views_data_alter(array &$data) {
  // TODO add views data field business ip for commerce order.
  $data['commerce_order']['ip'] = [
    'title' => t('Business IP'),
    'help' => t('The business ip in order'),
    'field' => [
      'id' => 'bips',
    ],
  ];

  return $data;
}