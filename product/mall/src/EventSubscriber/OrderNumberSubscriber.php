<?php

namespace Drupal\mall\EventSubscriber;

use Drupal\commerce_order\EventSubscriber\OrderNumberSubscriber as OrderNumberSubscriberBase;
use Drupal\state_machine\Event\WorkflowTransitionEvent;

/**
 * Extends from Commerce OrderNumberSubscriber
 */
class OrderNumberSubscriber extends OrderNumberSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [
      'mall.place.pre_transition' => ['setOrderNumber', -10],
    ];
    return $events;
  }

  /**
   * Sets the order number to the order ID.
   *
   * Skipped if the order number has already been set.
   *
   * @param \Drupal\state_machine\Event\WorkflowTransitionEvent $event
   *   The transition event.
   */
  public function setOrderNumber(WorkflowTransitionEvent $event) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $event->getEntity();
    if (!$order->getOrderNumber()) {
      // TODO Add customize order number
      $order->setOrderNumber('ul-'.$order->id());
    }
  }

}
