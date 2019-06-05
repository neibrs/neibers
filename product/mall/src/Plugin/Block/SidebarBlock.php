<?php

namespace Drupal\mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\menu_plus\MenuPlusManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'SidebarBlock' block.
 *
 * @Block(
 *  id = "leftside_block",
 *  admin_label = @Translation("Sidebar block"),
 * )
 */
class SidebarBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
 * @var \Drupal\menu_plus\MenuPlusManagerInterface  */
  protected $menuPlusManager;

  /**
 * @var \Drupal\Core\Entity\EntityTypeManagerInterface  */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, MenuLinkTreeInterface $menu_link_tree, MenuPlusManagerInterface $menu_plus_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
    $this->menuLinkTree = $menu_link_tree;
    $this->menuPlusManager = $menu_plus_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('menu.link_tree'),
      $container->get('menu_plus.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = $tree = [];

    /** @var \Drupal\system\MenuInterface[] $menus */
    $menus = $this->menuPlusManager->getMenuPlus();

    foreach ($menus as $menu) {
      $items = $this->buildMenu($menu->id())['#items'];
      $items = is_null($items) ? [] : $items;
      $tree[$menu->id()]['items'] = $items;
      $tree[$menu->id()]['active'] = $this->getActiveTag($items);
    }

    $build['sidebar_block'] = [
      '#theme' => 'sidebar_item_list',
      '#items' => $tree,
    ];

    return $build;
  }

  protected function getActiveTag(array $items) {
    $tag = FALSE;

    foreach ($items as $item) {
      if ($item['in_active_trail']) {
        $tag = TRUE;
        break;
      }
    }

    return $tag;
  }

  protected function buildMenu($menu_id) {
    $parameters = $this->menuLinkTree->getCurrentRouteMenuTreeParameters($menu_id);

    // Build the whole menu tree
    $parameters->expandedParents = [];

    $tree = $this->menuLinkTree->load($menu_id, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuLinkTree->transform($tree, $manipulators);

    $build = $this->menuLinkTree->build($tree);

    return $build;
  }

  /**
   * {@inheritDoc}
   * TODO
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
