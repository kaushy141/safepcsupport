<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\Orders;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class VerificationStatus
{
    /**
     * Possible values of this enum.
     */
    public const PENDING = 'Pending';

    public const APPROVED = 'Approved';

    public const REJECTED = 'Rejected';

    public const EXPIRED = 'Expired';

    public const CANCELLED = 'Cancelled';

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
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::EXPIRED,
            self::CANCELLED,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}