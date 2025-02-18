<?php
return [
    'default_base_url'  => env('EAZYPAY_DEFAULT_BASE_URL', 'https://eazypay.icicibank.com/EazyPG'),
    'merchant_id'       => env('EAZYPAY_MERCHANT_ID'),
    'encryption_key'    => env('EAZYPAY_ENCRYPTION_KEY'),
    'return_url'        => env('EAZYPAY_RETURN_URL'),

    // The sub-merchant ID is always the same as the merchant ID. Change the value only if needed.
    'sub_merchant_id'   => env('EAZYPAY_SUB_MERCHANT_ID', env('EAZYPAY_MERCHANT_ID')),

    /*
    |------------------------------------------------------------------------------------
    | Reference Number
    |------------------------------------------------------------------------------------
    |
    | Do not confuse the Reference Number (`merchant_reference_no`) with other reference numbers:
    |   - One `reference_no` is a mandatory field in the URL, representing the order ID or
    |     another unique identifier.
    |   - The "Reference No" field in the URL refers to the encrypted `merchant_reference_no`,
    |     which corresponds to the `merchant_id` defined in this config file.
    */

    // In URL encryption, the "Reference No" is the same as the merchant ID. Change the value only if needed.
    'merchant_reference_no' => env('EAZYPAY_MERCHANT_ID'),

    // The `paymode` value must be set to 9 as per ICICI's payment integration documentation.
    'paymode'           => env('EAZYPAY_PAYMODE', 9),
];
