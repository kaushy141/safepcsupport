<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\ProductFees;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class OptionalFulfillmentProgram
{
    /**
     * Possible values of this enum.
     */
    public const CORE = 'FBA_CORE';

    public const SNL = 'FBA_SNL';

    public const EFN = 'FBA_EFN';

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
            self::CORE,
            self::SNL,
            self::EFN,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
