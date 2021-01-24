<?php
namespace Essam\TapPayment;

interface Tap
{

  public function charge($data);

  public function getCharge($charge_id);

  public function chargesList($options);

  public function refund($data = []);

  public function getRefund($refund_id);

  public function refundList($options);

}
