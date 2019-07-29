<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'NavigateBlock' block.
 *
 * @Block(
 *  id = "navigate_block",
 *  admin_label = @Translation("Navigate block"),
 * )
 */
class NavigateBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['navigate_block']['#markup'] = 'Implement NavigateBlock.';

    return $build;
  }

}
