<?php

namespace Drupal\eabax_core\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

/**
 * Provides a trait for accessing effective status.
 */
trait EffectiveDatesTrait {

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    $today = new DrupalDateTime('now', DateTimeItemInterface::STORAGE_TIMEZONE);
    $today = $today->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    if (!empty($this->effective_dates->value)) {
      if ($today < $this->effective_dates->value) {
        return FALSE;
      }
    }
    if (!empty($this->effective_dates->end_value)) {
      if ($today > $this->effective_dates->end_value) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $today = new DrupalDateTime('now', DateTimeItemInterface::STORAGE_TIMEZONE);
    if ($published) {
      $this->effective_dates->value = $today->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
      $this->effective_dates->end_value = '';
    }
    else {
      $this->effective_dates->end_value = $today->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    }
    return $this;
  }

}
