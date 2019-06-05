<?php

namespace Drupal\server;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\machine_room\Entity\RoomInterface;

class ServerStorage extends SqlContentEntityStorage implements ServerStorageInterface {

  /**
   * {@inheritDoc}
   */
  public function getServersByRoom(RoomInterface $room) {

    $query = $this->database->select('server_field_data', 'sfd');
    $query->addField('sfd', ['name']);
    $query->leftJoin('seat_field_data', 'seat', 'sfd.seat = seat.id');
    $query->leftJoin('cabinet_field_data', 'cabinet', 'seat.cabinet=cabinet.id');
    $query->condition('cabinet.room', $room->id());
    $query->groupBy('sfd.name');

    $result = $query->execute();

    return $result;
  }

}
