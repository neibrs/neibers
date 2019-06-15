<?php

namespace Drupal\neibers_hardware;

use Drupal\machine_room\Entity\RoomInterface;

interface ServerStorageInterface {

  /**
   * @param \Drupal\machine_room\Entity\RoomInterface $room
   *
   * @return \Drupal\neibers_hardware\Entity\ServerInterface[]
   */
  public function getServersByRoom(RoomInterface $room);

}
