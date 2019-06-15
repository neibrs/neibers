<?php

namespace Drupal\neibers_hardware;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\machine_room\Entity\RoomInterface;

class HardwareStorage extends SqlContentEntityStorage implements HardwareStorageInterface {

  /**
   * {@inheritDoc}
   */
  public function getHardwaresByRoom(RoomInterface $room) {

    $query = $this->database->select('neibers_hardware_field_data', 'sfd');
    $query->addField('sfd', ['name']);
    $query->leftJoin('neibers_seat_field_data', 'seat', 'sfd.seat = seat.id');
    $query->leftJoin('neibers_cabinet_field_data', 'cabinet', 'seat.cabinet=cabinet.id');
    $query->condition('cabinet.room', $room->id());
    $query->groupBy('sfd.name');

    $result = $query->execute();

    return $result;
  }

}
