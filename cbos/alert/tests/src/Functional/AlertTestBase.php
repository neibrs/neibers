<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\neibers_alert\Entity\Alert;
use Drupal\neibers_alert\Entity\AlertType;
use Drupal\Tests\BrowserTestBase;

abstract class AlertTestBase extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['neibers_alert'];

  /**
   * @var \Drupal\neibers_alert\Entity\AlertTypeInterface
   */
  protected $alertType;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->alertType = $this->createAlertType();
  }

  protected function createAlertType(array $settings = []) {
    $settings += [
      'id' => strtolower($this->randomMachineName()),
      'label' => $this->randomMachineName(),
    ];
    $neibers_alert_type = AlertType::create($settings);
    $neibers_alert_type->save();

    return $neibers_alert_type;
  }

  /**
   * @param array $settings
   * @return \Drupal\neibers_alert\Entity\AlertInterface
   */
  protected function createAlert(array $settings = []) {
    $settings += [
      'name' => $this->randomMachineName(),
      'type' => $this->alertType->id(),
    ];
    $neibers_alert = Alert::create($settings);
    $neibers_alert->save();

    return $neibers_alert;
  }

}
