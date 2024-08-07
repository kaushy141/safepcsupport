<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\APlus;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class ColorType
{
    /**
     * Possible values of this enum.
     */
    public const DARK = 'DARK';

    public const LIGHT = 'LIGHT';

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
            self::DARK,
            self::LIGHT,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
