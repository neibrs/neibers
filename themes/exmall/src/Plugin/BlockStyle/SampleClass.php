<?php

namespace Drupal\exmall\Plugin\BlockStyle;

use Drupal\block_style_plugins\Plugin\BlockStyleBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @BlockStyle(
 *  id = "sample_class",
 *  label = @Translation("Sample Class"),
 * )
 */
class SampleClass extends BlockStyleBase {

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration() {
    return [
      'sample_class' => '',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['sample_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add custom class to this block'),
      '#description' => '',
      '#default_value' => $this->configuration['sample_class'],
    ];

    return $form;
  }

}
