<?php

namespace Drupal\neibers_category\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CategoryBlock' block.
 *
 * @Block(
 *  id = "category_block",
 *  admin_label = @Translation("Category block"),
 * )
 */
class CategoryBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['category_block']['#markup'] = 'Implement CategoryBlock.';

    return $build;
  }

}
