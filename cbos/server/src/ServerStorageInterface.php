<?php

namespace Drupal\server;

use Drupal\machine_room\Entity\RoomInterface;

interface ServerStorageInterface {

  /**
   * @param \Drupal\machine_room\Entity\RoomInterface $room
   *
   * @return \Drupal\server\Entity\ServerInterface[]
   */
  public function getServersByRoom(RoomInterface $room);

}
