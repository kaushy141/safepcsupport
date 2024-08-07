<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\ProductFees;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class IdType
{
    /**
     * Possible values of this enum.
     */
    public const ASIN = 'ASIN';

    public const SELLER_SKU = 'SellerSKU';

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
            self::ASIN,
            self::SELLER_SKU,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
