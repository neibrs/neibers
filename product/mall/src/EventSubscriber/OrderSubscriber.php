<?php

namespace Drupal\neibers_mall\EventSubscriber;

use Drupal\commerce_recurring\EventSubscriber\OrderSubscriber as OrderSubscriberBase;

class OrderSubscriber extends OrderSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // TODO neibers_recurring is not depend on mall module.
    $events['neibers_mall.place.pre_transition'] = 'onPlace';
    $events['neibers_mall.cancel.pre_transition'] = 'onCancel';
    return $events;
  }

}
