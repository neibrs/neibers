<?php

namespace Drupal\neibers_mall\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'MallFooterNewTopBlock' block.
 *
 * @Block(
 *  id = "mall_footer_new_top_block",
 *  admin_label = @Translation("Mall footer new top block"),
 * )
 */
class MallFooterNewTopBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['service_telephone' => ''];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['service_telephone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Service telephone'),
      '#default_value' => $this->configuration['service_telephone'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['service_telephone'] = $form_state->getValue('service_telephone');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['service_telephone']['#markup'] = $this->configuration['service_telephone'];

    return $build;
  }

}
