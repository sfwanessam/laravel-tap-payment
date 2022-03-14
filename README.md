

# laravel-tap-payment
Tap Payment SDK Package for PHP Laravel
![New Project (20)](https://user-images.githubusercontent.com/14217354/158107594-cc7d0b4f-2e62-4d27-a09c-75aaae8e1e48.jpg)

## Laravel compatibility

 Laravel      | laravel-tap-payment
:-------------|:----------
 5.6.* - 5.8.* (PHP 7 required) | 0.0.1
 5.6.* - 8.*   (PHP 7 required) | 0.0.2
 5.6.* - 8.*   (PHP 7 required) | 0.0.3

## Installation

Use the package manager [composer](https://getcomposer.org/) to install foobar.

```bash
composer require essam/laravel-tap-payment
```

# Usage
### Create Charge Ttransaction
```php
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);

$redirect = false; // return response as json , you can use it form mobile web view application

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
        'source' => [
          'id' => 'src_card'
        ],
        'post' => [
           'url' => null
        ],
        'redirect' => [
           'url' => url('check_payment.php')
        ]
   ],$redirect);
```
If the information is correct, you will be directed to the payment page

### Get Charge By Charge id
```php
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);
$Charge =  $TapPay->getCharge($charge_id);
```

### Get Charges List
```php
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);

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
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);

$Refund = $TapPay->refund([
    'charge_id' => $charge_id,
     'amount' => 2,
     'currency' => 'AED',
     'reason' => 'type the refund reason',
     'post' => [
        'url' => 'http://post_after_refund_page.php'
   ]);
```

### get Refund By Refund id
```php
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);

$Refund = $TapPay->getRefund($refund_id);
```

### get Refunds List
```php
$TapPay = new Payment(['secret_api_Key'=> $secret_api_Key]);

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

# Some Tutorial to learn How to use this Api
https://medium.com/@sfwanessam9/how-to-use-laravel-tap-payment-sdk-cc9e583f84a4

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)


