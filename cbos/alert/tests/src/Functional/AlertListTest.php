<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for neibers_alert list.
 *
 * @group neibers_alert
 */
class AlertListTest extends AlertTestBase {

  public function testList() {
    $neibers_alert = $this->createAlert();

    $user = $this->drupalCreateUser([
      'view neibers_alerts',
    ]);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    $this->drupalGet(Url::fromRoute('entity.neibers_alert.collection'));
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists($neibers_alert->label());
  }

}
