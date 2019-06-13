<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityInterface;

interface IpManagerInterface {

  /**
   * @return array
   */
  public function buildOperations(EntityInterface $entity);

}
