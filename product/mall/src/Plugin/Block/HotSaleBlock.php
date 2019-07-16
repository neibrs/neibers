<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HotSaleBlock' block.
 *
 * @Block(
 *  id = "hot_sale_block",
 *  admin_label = @Translation("Hot sale block"),
 * )
 */
class HotSaleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['hot_sale_block']['#markup'] = 'Implement HotSaleBlock.';

    return $build;
  }

}
