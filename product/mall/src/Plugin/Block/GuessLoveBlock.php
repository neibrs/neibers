<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'GuessLoveBlock' block.
 *
 * @Block(
 *  id = "guess_love_block",
 *  admin_label = @Translation("Guess love block"),
 * )
 */
class GuessLoveBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['guess_love_block']['#markup'] = 'Implement GuessLoveBlock.';

    return $build;
  }

}
