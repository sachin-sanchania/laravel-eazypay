# Laravel Eazypay Integration
This package provides a seamless integration with ICICI Bank's EazyPay payment gateway for Laravel applications.  It simplifies the process of generating checksums, processing payments, and handling responses within your Laravel projects.

## What is a Eazypay?

Eazypay is a first of its kind secure payment service by ICICI Bank in India. It enables institutions to collect money from their customers through multiple payment modes. ICICI Bank is the first and only bank to offer such a payment service in India.

## Installation

You can install the package via composer:

```shell
$ composer require sachin-sanchania/laravel-eazypay
```
## Configuration

Configuration was designed to be as flexible.
global configuration can be set in the `app/config/eazypay.php` file.

Make sure you have all the detail which needs to use in configuration file.
Must read instruction in `app/config/eazypay.php` file. You need to configure your .env file

```<?php
EAZYPAY_MERCHANT_ID=your_merchant_id // 6 digit Eazypay ICID shared by ICICI Bank
EAZYPAY_ENCRYPTION_KEY=your_encryption_key // AES Key shared by ICICI Bank
EAZYPAY_RETURN_URL=your_return_url // Return URL configured while merchant registration in eazypay. Transaction response is sent to this URL. 
EAZYPAY_SUB_MERCHANT_ID=xxxxxxxx // A numeric value that can be customized by the merchant andused to differentiate between internal business units of the mer-chant (if applicable).
EAZYPAY_PAYMODE=9 // (Optional | Default=9) Cash=0,Cheque=1,NEFT/RTGS=2,NetBanking=3,DebitCard=4,CreditCard=5 and UPI = 6 and All=9
EAZYPAY_DEFAULT_BASE_URL=xxxxxx // (Optional | Default=https://eazypay.icicibank.com/EazyPG?) For UAT set this url : https://eazypayuat.icicibank.com/EazyPG
```
In command line paste this command for clearing cache:
```shell
php artisan optimize:clear
```

Finally, from the command line again, publish the default configuration file:
```shell
php artisan vendor:publish --provider="SachinSanchania\Eazypay\EazypayServiceProvider"
```

## Usage

Import the package in your controller and send request with required parameters.

```php
use SachinSanchania\Eazypay\Eazypay;

class PaymentController extends Controller
{ 
    public function payment()
    {
        $amount        = 1000;
        $referenceNo   = 1; // Stands for order ID or any related database identifier
        $optionalField = '10|10|10|10'; // Optional, must be in pipe (`|`) delimiter format as per ICICI's documentation

        $eazypay    = new Eazypay();
        $paymentUrl = $eazypay->getPaymentUrl($amount, $referenceNo, $optionalField);

        return redirect()->to($paymentUrl); // Redirects the user to ICICI Eazypay payment gateway
    }
}
```

After payment completion, ICICI will redirect the user to the return URL. You can capture the response like this:

```shell
public function paymentResponse(Request $request)
{
    $response = $request->all();

    // Validate and process payment response
    if (isset($response['status']) && $response['status'] == 'success') {
        // Payment successful, update order status
    } else {
        // Payment failed or pending, handle accordingly
    }

    return view('payment.status', compact('response'));
}

```

Thanks for your support! ❤️ If you have any doubts, feel free to start a discussion. 😊

For more details, visit the official [ICICI Eazypay website](https://eazypay.icicibank.com/homePage).
