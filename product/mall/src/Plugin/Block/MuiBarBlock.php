<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'MuiBarBlock' block.
 *
 * @Block(
 *  id = "mui_bar_block",
 *  admin_label = @Translation("Mui bar block"),
 * )
 */
class MuiBarBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['mui_bar_block']['#markup'] = 'Implement MuiBarBlock.';

    return $build;
  }

}
