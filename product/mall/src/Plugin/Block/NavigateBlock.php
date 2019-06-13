<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'NavigateBlock' block.
 *
 * @Block(
 *  id = "navigate_block",
 *  admin_label = @Translation("Navigate block for exsen theme"),
 * )
 */
class NavigateBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
 * @var \Drupal\Core\Entity\EntityTypeManagerInterface  */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
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
  public function build() {
    $build = $items = [];

    /** @var \Drupal\system\MenuInterface[] $menus */
    $menus = \Drupal::service('menu_plus.manager')->getMenuPlus();

    foreach ($menus as $menu) {
      $items[$menu->id()] = [
        'menu' => $menu->id(),
        'title' => $menu->label(),
      ];
    }

    $build['navigate_block'] = [
      '#theme' => 'navigate_item_list',
      '#items' => $items,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $menu_type = $this->entityTypeManager->getDefinition('menu');

    return Cache::mergeTags(parent::getCacheTags(), $menu_type->getListCacheTags());
  }

}
