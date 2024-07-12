<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\APlus;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class AsinBadge
{
    /**
     * Possible values of this enum.
     */
    public const BRAND_NOT_ELIGIBLE = 'BRAND_NOT_ELIGIBLE';

    public const CATALOG_NOT_FOUND = 'CATALOG_NOT_FOUND';

    public const CONTENT_NOT_PUBLISHED = 'CONTENT_NOT_PUBLISHED';

    public const CONTENT_PUBLISHED = 'CONTENT_PUBLISHED';

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
            self::BRAND_NOT_ELIGIBLE,
            self::CATALOG_NOT_FOUND,
            self::CONTENT_NOT_PUBLISHED,
            self::CONTENT_PUBLISHED,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}