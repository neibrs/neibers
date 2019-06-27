<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for neibers_alert delete.
 *
 * @group neibers_alert
 */
class AlertDeleteTest extends AlertTestBase {

  /**
   * Tests neibers_alert delete.
   */
  public function testDelete() {
    $neibers_alert = $this->createAlert();

    $user = $this->drupalCreateUser([
      'delete neibers_alerts',
      'edit neibers_alerts',
      'view neibers_alerts',
    ]);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    $this->drupalGet(Url::fromRoute('entity.neibers_alert.edit_form', [
      'neibers_alert' => $neibers_alert->id(),
    ]));
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists(t('Delete'));

    $this->clickLink(t('Delete'));
    $assert_session->statusCodeEquals(200);

    $this->drupalPostForm(NULL, [], t('Delete'));
    $assert_session->responseContains(t('The @entity-type %label has been deleted.', [
      '@entity-type' => t('Alert'),
      '%label' => $neibers_alert->label(),
    ]));
  }

}
