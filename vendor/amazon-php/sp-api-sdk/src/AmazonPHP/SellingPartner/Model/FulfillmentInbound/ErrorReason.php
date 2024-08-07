<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FulfillmentInbound;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class ErrorReason
{
    /**
     * Possible values of this enum.
     */
    public const DOES_NOT_EXIST = 'DoesNotExist';

    public const INVALID_ASIN = 'InvalidASIN';

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
            self::DOES_NOT_EXIST,
            self::INVALID_ASIN,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
