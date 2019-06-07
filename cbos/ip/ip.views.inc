<?php


/**
 * Implements hook_views_data_alter().
 */
function ip_views_data_alter(array &$data) {
  // TODO add views data field business ip for commerce order.
  $data['commerce_order']['ip'] = [
    'title' => t('B-IP'),
    'help' => t('The business ip in order'),
//    'field' => [
//      'id' => 'field',
//      'default_formatter' => 'entity_reference_label',
//      'field_name' => 'ip',
//    ],
  ];

  return $data;
}