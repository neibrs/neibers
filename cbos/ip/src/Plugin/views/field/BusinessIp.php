<?php

namespace Drupal\neibers_ip\Plugin\views\field;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGenerator;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to provide a list of business ip for the same administer ip with the commerce order.
 * like as view ips_order_form
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("business_ips")
 */
class BusinessIp extends PrerenderList {
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

    $this->additional_fields['order_id'] = ['table' => 'neibers_ip_field_data', 'field' => 'order_id'];
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

    $ip_storage = $this->entityTypeManager->getStorage('neibers_ip');

    foreach ($values as $result) {
      $id = $this->getValue($result);
      $entity = $this->getEntity($result);
      $seat_id = $entity->seat->target_id;
      $ips = $ip_storage->loadByProperties([
        'order_id' => $id,
        'seat'     => $seat_id,
        'type' => 'onet',
        'state' => 'used',
      ]);
      foreach ($ips as $ip) {
        $this->items[$id][$ip->id()]['ip'] = $ip->label();//Link::createFromRoute($this->t('add'), 'entity.neibers_ip.collection')->toString();
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