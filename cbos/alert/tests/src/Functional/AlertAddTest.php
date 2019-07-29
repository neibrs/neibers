<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for neibers_alert add form.
 *
 * @group neibers_alert
 */
class AlertAddTest extends AlertTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block'];

  /**
   * Tests add form.
   */
  public function testAddForm() {
    $this->drupalPlaceBlock('local_actions_block');

    $user = $this->drupalCreateUser([
      'add neibers_alerts',
      'view neibers_alerts',
    ]);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    $this->drupalGet(Url::fromRoute('entity.neibers_alert.collection'));
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists(t('Add Alert'));

    $this->clickLink(t('Add Alert'));
    $assert_session->statusCodeEquals(200);

    $edit = [
      'name[0][value]' => $this->randomMachineName(),
    ];
    $this->drupalPostForm(NULL, $edit, t('Save'));
    $assert_session->statusCodeEquals(200);
    $assert_session->responseContains(t('Created the %label Alert.', [
      '%label' => $edit['name[0][value]'],
    ]));
  }

}
