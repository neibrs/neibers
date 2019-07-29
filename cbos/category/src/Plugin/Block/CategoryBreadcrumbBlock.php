<?php

namespace Drupal\neibers_category\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CategoryBreadcrumbBlock' block.
 *
 * @Block(
 *  id = "category_breadcrumb_block",
 *  admin_label = @Translation("Category breadcrumb block"),
 * )
 */
class CategoryBreadcrumbBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['category_breadcrumb_block']['#markup'] = 'Implement CategoryBreadcrumbBlock.';

    return $build;
  }

}
