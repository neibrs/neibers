<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for list.
 *
 * @group neibers_alert
 */
class AlertViewTest extends AlertTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block'];

  /**
   * Tests canonical page.
   */
  public function testCanonical() {
    $this->drupalPlaceBlock('page_title_block');

    $neibers_alert = $this->createAlert();

    $user = $this->drupalCreateUser(['view neibers_alerts']);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    $this->drupalGet(Url::fromRoute('entity.neibers_alert.canonical', [
      'neibers_alert' => $neibers_alert->id(),
    ]));
    $assert_session->statusCodeEquals(200);
    $assert_session->responseContains($neibers_alert->label());
  }

}
