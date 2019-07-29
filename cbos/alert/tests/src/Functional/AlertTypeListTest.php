<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for neibers_alert_type list.
 *
 * @group neibers_alert
 */
class AlertTypeListTest extends AlertTestBase {

  public function testList() {
    $neibers_alert_type = $this->createAlertType();

    $user = $this->drupalCreateUser([
      'administer neibers_alerts',
    ]);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    $this->drupalGet(Url::fromRoute('entity.neibers_alert_type.collection'));
    $assert_session->statusCodeEquals(200);
    $assert_session->responseContains($neibers_alert_type->label());
  }

}
