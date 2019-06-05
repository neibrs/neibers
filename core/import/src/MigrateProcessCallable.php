<?php

namespace Drupal\import;

class MigrateProcessCallable {

  public static function excelDateToYmd($value) {
    $time = ((int) $value - 25569) * 24 * 60 * 60;
    return date('Y-m-d', $time);
  }

}
