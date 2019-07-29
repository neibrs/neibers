<?php

namespace Drupal\neibers_hardware;

use Drupal\neibers_room\Entity\RoomInterface;

interface HardwareStorageInterface {

  /**
   * @param \Drupal\neibers_room\Entity\RoomInterface $room
   *
   * @return \Drupal\neibers_hardware\Entity\HardwareInterface[]
   */
  public function getHardwaresByRoom(RoomInterface $room);

}
