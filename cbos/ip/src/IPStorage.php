<?php

namespace Drupal\ip;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\ip\Entity\IPInterface;
use Drupal\server\Entity\ServerInterface;

/**
 * Defines the storage handler class for IP.
 *
 * This extends the base storage class, adding required special handling for
 * IP.
 *
 * @ingroup ip
 */
class IPStorage extends SqlContentEntityStorage implements IPStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(IPInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {ip_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {ip_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(IPInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {ip_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('ip_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function getInetsByOrder(OrderInterface $order) {
    $inets = $this->loadByProperties([
      'order_id' => $order->id(),
      'type' => 'inet',
    ]);

    return $inets;
  }

  public function getOnetsByOrder(OrderInterface $order) {
    $onets = $this->loadByProperties([
      'order_id' => $order->id(),
      'type' => 'onet',
    ]);

    return $onets;
  }

  /**
   * {@inheritDoc}
   */
  public function getOnetsByInet(IPInterface $ip) {
    /** @var \Drupal\ip\Entity\IPInterface[] $onets */
    $onets = $this->loadByProperties([
      'type' => 'onet',
      'seat' => $ip->get('seat')->target_id,
      'server' => $ip->get('server')->target_id,
    ]);

    return $onets;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableInetByServer(ServerInterface $server) {
    $ips = $this->loadByProperties([
      'type' => 'inet',
      'server' => $server->id(),
      'state' => 'free',
      // Add user_id condition for none client
    ]);

    return $ips;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableOnets() {
    $ips = $this->loadByProperties([
      'type' => 'onet',
      'state' => 'free',
      // Add user_id condition for none client
    ]);

    return $ips;
  }

}
