<?php

namespace Drupal\neibers_hardware;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Hardware.
 *
 * @ingroup neibers_hardware
 */
class HardwareListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Hardware ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\neibers_hardware\Entity\Hardware */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.neibers_hardware.edit_form',
      ['neibers_hardware' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
