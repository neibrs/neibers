<?php

namespace Drupal\neibers_recurring\EventSubscriber;

use Drupal\commerce_recurring\EventSubscriber\OrderSubscriber as OrderSubscriberBase;

class OrderSubscriber extends OrderSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['neibers_mall.place.pre_transition'] = 'onPlace';
    $events['neibers_mall.cancel.pre_transition'] = 'onCancel';
    return $events;
  }

}