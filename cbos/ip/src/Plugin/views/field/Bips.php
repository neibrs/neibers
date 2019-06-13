<?php

namespace Drupal\ip\Plugin\views\field;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to provide a list of business ip.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bips")
 */
class Bips extends PrerenderList {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);

    $this->additional_fields['order_id'] = ['table' => 'commerce_order', 'field' => 'order_id'];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->addAdditionalFields();
    $this->field_alias = $this->aliases['order_id'];
  }

  /**
   * {@inheritdoc}
   */
  public function preRender(&$values) {
    $this->items = [];

    $ip_storage = $this->entityTypeManager->getStorage('ip');

    foreach ($values as $result) {
      $id = $this->getValue($result);
      $ips = $ip_storage->loadByProperties([
        'order_id' => $id,
        'type' => 'onet',
        'state' => 'used',
      ]);

      foreach ($ips as $ip) {
        $this->items[$id][$ip->id()]['ip'] = $ip->label();
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function render_item($count, $item) {
    return $item['ip'];
  }

}
