<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\MerchantFulfillment;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class LabelFormat
{
    /**
     * Possible values of this enum.
     */
    public const PDF = 'PDF';

    public const PNG = 'PNG';

    public const ZPL203 = 'ZPL203';

    public const ZPL300 = 'ZPL300';

    public const SHIPPING_SERVICE_DEFAULT = 'ShippingServiceDefault';

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
            self::PDF,
            self::PNG,
            self::ZPL203,
            self::ZPL300,
            self::SHIPPING_SERVICE_DEFAULT,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
