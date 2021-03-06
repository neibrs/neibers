<?php

/**
 * @file
 */

/**
 * Implements hook_views_data_alter().
 * Display on user order detail page, and order admin page(commerce_order_collection)
 */
function neibers_ip_views_data_alter(array &$data) {

  $data['commerce_order']['ip'] = [
    'title' => t('Order business IP'),
    'help' => t('The business ip in user\'s order and commerce order list.'),
    'field' => [
      'id' => 'bips',
    ],
  ];

  $data['commerce_order']['mip'] = [
    'title' => t('Order administer IP'),
    'help' => t('The administer ip in commerce order list'),
    'field' => [
      'id' => 'mips',
    ],
  ];

  return $data;
}
