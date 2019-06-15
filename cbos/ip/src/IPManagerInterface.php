<?php

namespace Drupal\ip;

use Drupal\Core\Entity\EntityInterface;

interface IPManagerInterface {

  /**
   * @return array
   */
  public function buildOperations(EntityInterface $entity);

}
