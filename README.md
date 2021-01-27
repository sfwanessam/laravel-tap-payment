# laravel-tap-payment
Tap Payment SDK Package for PHP Laravel

## Installation

Use the package manager [composer](https://getcomposer.org/) to install foobar.

```bash
composer require essam/laravel-tap-payment
```

# Usage
### Create Charge Ttransaction
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);

$TapPay->card([
   'number' => '5123450000000008',
   'exp_month' => 12,
   'exp_year' => 21,
   'cvc' => 124,
]);

return $TapPay->charge([
        'amount' => 200,
        'currency' => 'AED',
        'threeDSecure' => 'true',
        'description' => 'test description',
        'statement_descriptor' => 'sample',
        'customer' => [
           'first_name' => 'customer',
           'email' => 'customer@gmail.com',
        ],
        'post' => [
           'url' => null
        ],
        'redirect' => [
           'url' => route('account.check_payment')
        ]
   ]);
```
If the information is correct, you will be directed to the payment page

### Get Charge By Charge id
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);
$Charge =  $TapPay->getCharge('chg_TS021620210347u9B22301284');
```

### Get Charges List
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);

$ChargesList = $TapPay->chargesList([
'period' => [
  'date' => [
      'from' => Date('Y-m-d H:i:s'),
      'to' => Date('Y-m-d H:i:s'),
     ]
  ],
  'status' => 'INITIATED',
  'limit' => 30
]);
```

### Create Refund Ttransaction
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);

$Refund = $TapPay->refund([
    'charge_id' => 'chg_TS021620210347u9B22301284',
     'amount' => 2,
     'currency' => 'KWD',
     'reason' => 'type the refund reason',
     'post' => [
        'url' => 'http://post_after_refund_page.php'
   ]);
```

### get Refund By Refund id
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);

$Refund = $TapPay->getRefund($refund_id);
```

### get Refunds List
```php
$TapPay = new Payment(['secret_api_Key'=>'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ']);

$RefundList = $TapPay->refundList([
'period' => [
  'date' => [
      'from' => Date('Y-m-d H:i:s'),
      'to' => Date('Y-m-d H:i:s'),
     ]
  ],
  'limit' => 30
]);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
