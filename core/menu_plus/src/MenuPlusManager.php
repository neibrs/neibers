<?php

namespace Drupal\menu_plus;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class MenuPlusManager implements MenuPlusManagerInterface {

  /**
 * @var \Drupal\Core\Entity\EntityTypeManagerInterface  */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getMenuPlus() {
    $data = [];

    /** @var \Drupal\system\MenuInterface[] $menus */
    $menus = $this->entityTypeManager->getStorage('menu')->loadMultiple();

    foreach ($menus as $menu) {
      if ($menu->getThirdPartySetting('menu_plus', 'navigate', NULL)) {
        $data[$menu->id()] = $menu;
      }
    }

    return $data;
  }

}
