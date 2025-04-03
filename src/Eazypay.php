<?php
namespace SachinSanchania\LaravelEazypay;

class Eazypay
{
    protected string $default_base_url;
    protected string $merchant_id;
    protected string $encryption_key;
    protected string $sub_merchant_id;
    protected string $reference_no;
    protected string $paymode;
    protected string $return_url;

    /**
     * Constructor - Initializes payment configurations.
     */
    public function __construct()
    {
        $this->default_base_url         =    config('eazypay.default_base_url');
        $this->merchant_id              =    config('eazypay.merchant_id');
        $this->encryption_key           =    config('eazypay.encryption_key');
        $this->sub_merchant_id          =    config('eazypay.sub_merchant_id');
        $this->merchant_reference_no    =    config('eazypay.merchant_reference_no');
        $this->paymode                  =    config('eazypay.paymode');
        $this->return_url               =    config('eazypay.return_url');
    }

    /**
     * Generates the payment URL with encrypted parameters.
     *
     * @param float $amount
     * @param string $reference_no
     * @param string|null $optionalField
     * @return string
     */
    public function getPaymentUrl($amount, $reference_no, array $customMandatoryFields = [], $optionalField = null)
    {
        return $this->generatePaymentUrl(
            $this->getMandatoryField($amount, $reference_no, $customMandatoryFields),
            $this->getOptionalField($optionalField),
            $this->getAmount($amount),
            $this->getReferenceNo($reference_no)
        );
    }

    /**
     * Constructs the encrypted payment URL.
     *
     * @param string $mandatoryField
     * @param string|null $optionalField
     * @param string $amount
     * @param string $reference_no
     * @return string
     */
    protected function generatePaymentUrl(string $mandatoryField, ?string $optionalField, string $amount, string $reference_no): string
    {
        $params = [
            'merchantid' => $this->merchant_id,
            'mandatory_fields' => $mandatoryField,
            'optional_fields' => $optionalField ?? '',
            'returnurl' => $this->getReturnUrl(),
            'Reference_No' => $reference_no,
            'submerchantid' => $this->getSubMerchantId(),
            'transaction_amount' => $amount,
            'paymode' => $this->getPaymode(),
        ];

        return $this->default_base_url . '?' . http_build_query($params);
    }

    /**
     * Encrypts and formats mandatory fields.
     *
     * @param float $amount
     * @param string $reference_no
     * @return string
     */
    protected function getMandatoryField(float $amount, string $reference_no, array $customFields = []): string
    {
        if (empty($customFields)) {
            $customFields = [$reference_no, $this->sub_merchant_id, $amount];
        }
        return $this->encryptValue(implode('|', $customFields));
    }

    /**
     * Encrypts optional fields if provided.
     *
     * @param string|null $optionalField
     * @return string|null
     */
    protected function getOptionalField(?string $optionalField): ?string
    {
        return $optionalField ? $this->encryptValue($optionalField) : null;
    }

    /**
     * Encrypts the amount.
     *
     * @param float $amount
     * @return string
     */
    protected function getAmount(float $amount): string
    {
        return $this->encryptValue((string) $amount);
    }

    /**
     * Encrypts the return URL.
     *
     * @return string
     */
    protected function getReturnUrl(): string
    {
        return $this->encryptValue($this->return_url);
    }

    /**
     * Encrypts the reference number.
     *
     * @param string $reference_no
     * @return string
     */
    protected function getReferenceNo(string $reference_no): string
    {
        return $this->encryptValue($reference_no);
    }

    /**
     * Encrypts the sub-merchant ID.
     *
     * @return string
     */
    protected function getSubMerchantId(): string
    {
        return $this->encryptValue($this->sub_merchant_id);
    }

    /**
     * Encrypts the payment mode.
     *
     * @return string
     */
    protected function getPaymode(): string
    {
        return $this->encryptValue($this->paymode);
    }

    /**
     * Encrypts the given string using AES-128-ECB encryption.
     *
     * @param string $str
     * @return string
     */
    protected function encryptValue(string $str): string
    {
        $encrypted = openssl_encrypt($str, 'aes-128-ecb', $this->encryption_key, OPENSSL_RAW_DATA);
        return base64_encode($encrypted);
    }
}
