<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\ProductPricing;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class OfferCustomerType
{
    /**
     * Possible values of this enum.
     */
    public const B2_C = 'B2C';

    public const B2_B = 'B2B';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues() : array
    {
        return [
            self::B2_C,
            self::B2_B,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}