<?php


/**
 * Implements hook_views_data_alter().
 */
function ip_views_data_alter(array &$data) {

  $data['commerce_order']['ip'] = [
    'title' => t('Business IP'),
    'help' => t('The business ip in order'),
    'field' => [
      'id' => 'bips',
    ],
  ];

  $data['commerce_order']['mip'] = [
    'title' => t('Administer IP'),
    'help' => t('The administer ip in order'),
    'field' => [
      'id' => 'mips',
    ],
  ];

  return $data;
}