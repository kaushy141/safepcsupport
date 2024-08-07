<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FBASmallAndLight;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class SmallAndLightEligibilityStatus
{
    /**
     * Possible values of this enum.
     */
    public const ELIGIBLE = 'ELIGIBLE';

    public const NOT_ELIGIBLE = 'NOT_ELIGIBLE';

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
            self::ELIGIBLE,
            self::NOT_ELIGIBLE,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
