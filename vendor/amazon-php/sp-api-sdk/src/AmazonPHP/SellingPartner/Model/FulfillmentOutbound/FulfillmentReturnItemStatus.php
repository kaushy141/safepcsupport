<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FulfillmentOutbound;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class FulfillmentReturnItemStatus
{
    /**
     * Possible values of this enum.
     */
    public const _NEW = 'New';

    public const PROCESSED = 'Processed';

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
            self::_NEW,
            self::PROCESSED,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
