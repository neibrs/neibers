<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityInterface;

interface IpManagerInterface {

  /**
   * @return []
   */
  public function buildOperations(EntityInterface $entity);
}