<?php

namespace Drupal\import\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "skip_on_empty_plus"
 * )
 */
class SkipOnEmptyPlus extends ProcessPluginBase {

  public function row($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $invert = $this->configuration['invert'];
    if (!$value && !$invert || $value && $invert) {
      $message = !empty($this->configuration['message']) ? $this->configuration['message'] : '';
      throw new MigrateSkipRowException($message);
    }
    return $value;
  }

  public function process($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $invert = $this->configuration['invert'];
    if (!$value && !$invert || $value && $invert) {
      throw new MigrateSkipProcessException();
    }
    return $value;
  }

}
