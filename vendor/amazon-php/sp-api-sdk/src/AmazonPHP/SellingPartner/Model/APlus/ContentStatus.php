<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\APlus;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class ContentStatus
{
    /**
     * Possible values of this enum.
     */
    public const APPROVED = 'APPROVED';

    public const DRAFT = 'DRAFT';

    public const REJECTED = 'REJECTED';

    public const SUBMITTED = 'SUBMITTED';

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
            self::APPROVED,
            self::DRAFT,
            self::REJECTED,
            self::SUBMITTED,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}