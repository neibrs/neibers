<?php

namespace Drupal\neibers_alert;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for nodes.
 *
 * This extends the base storage class, adding required special handling for
 * neibers_alert entities.
 */
class AlertStorage extends SqlContentEntityStorage implements AlertStorageInterface {

}
