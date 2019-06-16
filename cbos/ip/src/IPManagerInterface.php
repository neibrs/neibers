<?php

namespace Drupal\neibers_ip;

use Drupal\Core\Entity\EntityInterface;

interface IPManagerInterface {

  /**
   * @return array
   */
  public function buildOperations(EntityInterface $entity);

}
