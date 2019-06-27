<?php

namespace Drupal\Tests\neibers_alert\Functional;

use Drupal\Core\Url;

/**
 * Simple test for neibers_alert_type delete.
 *
 * @group neibers_alert
 */
class AlertTypeDeleteTest extends AlertTestBase {

  /**
   * Tests neibers_alert_type delete.
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testDelete() {
    // Prepare data
    $type_no_data = $this->createAlertType();
    $type_has_data = $this->createAlertType();
    $this->createAlert([
      'type' => $type_has_data->id(),
    ]);

    $user = $this->drupalCreateUser([
      'administer neibers_alerts',
    ]);
    $this->drupalLogin($user);

    $assert_session = $this->assertSession();

    // Tests delete type with no data
    $this->drupalGet(Url::fromRoute('entity.neibers_alert_type.edit_form', [
      'neibers_alert_type' => $type_no_data->id(),
    ]));
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists(t('Delete'));

    $this->clickLink(t('Delete'));
    $assert_session->statusCodeEquals(200);

    $this->drupalPostForm(NULL, [], t('Delete'));
    $assert_session->responseContains(t('The @entity-type %label has been deleted.', [
      '@entity-type' => t('neibers_alert type'),
      '%label' => $type_no_data->label(),
    ]));
    // Tests delete type with data
    $this->drupalGet(Url::fromRoute('entity.neibers_alert_type.delete_form', [
      'neibers_alert_type' => $type_has_data->id(),
    ]));
    $assert_session->responseContains(t('You can not remove %type until you have removed all of the %type %entity.', [
      '%type' => $type_has_data->label(),
      '%entity' => t('Alert'),
    ]));
  }

}
