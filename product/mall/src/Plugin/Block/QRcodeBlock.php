<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'QRcodeBlock' block.
 *
 * @Block(
 *  id = "qrcode_block",
 *  admin_label = @Translation("Qrcode block"),
 * )
 */
class QRcodeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['qrcode_block']['#markup'] = 'Implement QRcodeBlock.';

    return $build;
  }

}
