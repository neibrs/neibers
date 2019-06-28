<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'AdminIconBlock' block.
 *
 * @Block(
 *  id = "admin_icon_block",
 *  admin_label = @Translation("Admin icon block"),
 * )
 */
class AdminIconBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['admin_icon_block']['#markup'] = 'Implement AdminIconBlock.';

    return $build;
  }

}
