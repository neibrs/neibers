<?php

namespace Drupal\menu_plus;

interface MenuPlusManagerInterface {

  /**
   * @return \Drupal\system\MenuInterface[]
   */
  public function getMenuPlus();

}
