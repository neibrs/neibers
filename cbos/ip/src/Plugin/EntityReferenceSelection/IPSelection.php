<?php

namespace Drupal\neibers_ip\Plugin\EntityReferenceSelection;

use Drupal\entity_plus\Plugin\EntityReferenceSelection\DefaultSelection;

/**
 * Provides specific access control for the user entity type.
 *
 * @EntityReferenceSelection(
 *   id = "default:neibers_ip",
 *   label = @Translation("IP selection"),
 *   entity_types = {"neibers_ip"},
 *   group = "default",
 *   weight = 1
 * )
 */
class IPSelection extends DefaultSelection {

}