<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\system\Plugin\Block\SystemBrandingBlock;

/**
 * Provides a 'LogoGroup' block.
 *
 * @Block(
 *  id = "logo_group",
 *  admin_label = @Translation("Logo group"),
 * )
 */
class LogoGroup extends SystemBrandingBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();

    $build['logo_group']['#markup'] = 'Implement LogoGroup.';

    return $build;
  }

}
