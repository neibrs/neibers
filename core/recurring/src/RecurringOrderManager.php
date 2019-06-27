<?php

namespace Drupal\neibers_recurring;

use Drupal\commerce_recurring\RecurringOrderManager as RecurringOrderManagerBase;
use Drupal\commerce_recurring\Entity\SubscriptionInterface;
use Drupal\commerce_recurring\BillingPeriod;

class RecurringOrderManager extends RecurringOrderManagerBase {
  /**
   * {@inheritdoc}
   */
  protected function createOrder(SubscriptionInterface $subscription, BillingPeriod $billing_period) {
    $payment_method = $subscription->getPaymentMethod();
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $this->entityTypeManager->getStorage('commerce_order')->create([
      'type' => 'recurring',
      'store_id' => $subscription->getStoreId(),
      'uid' => $subscription->getCustomerId(),
      'billing_profile' => $payment_method ? $payment_method->getBillingProfile() : NULL,
      'payment_method' => $payment_method,
      'payment_gateway' => $payment_method ? $payment_method->getPaymentGatewayId() : NULL,
      'billing_period' => $billing_period,
      'billing_schedule' => $subscription->getBillingSchedule(),
      'initial_order' => $subscription->getInitialOrderId(),
    ]);

    return $order;
  }
}