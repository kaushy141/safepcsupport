<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\MerchantFulfillment;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class InputTargetType
{
    /**
     * Possible values of this enum.
     */
    public const SHIPMENT_LEVEL = 'SHIPMENT_LEVEL';

    public const ITEM_LEVEL = 'ITEM_LEVEL';

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
            self::SHIPMENT_LEVEL,
            self::ITEM_LEVEL,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
