<?php

namespace Drupal\neibers_fitting;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Fitting.
 *
 * @ingroup neibers_fitting
 */
class FittingListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Fitting ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\neibers_fitting\Entity\Fitting */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.neibers_fitting.edit_form',
      ['neibers_fitting' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
