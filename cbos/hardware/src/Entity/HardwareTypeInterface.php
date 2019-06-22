<?php

namespace Drupal\neibers_hardware\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Hardware type entities.
 */
interface HardwareTypeInterface extends ConfigEntityInterface {

  /**
   * Determines if this menu is locked.
   *
   * @return bool
   *   TRUE if the hardware is locked, FALSE otherwise.
   */
  public function isLocked();
}
