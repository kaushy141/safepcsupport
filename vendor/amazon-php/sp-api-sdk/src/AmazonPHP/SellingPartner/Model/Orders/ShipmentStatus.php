<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\Orders;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class ShipmentStatus
{
    /**
     * Possible values of this enum.
     */
    public const READY_FOR_PICKUP = 'ReadyForPickup';

    public const PICKED_UP = 'PickedUp';

    public const REFUSED_PICKUP = 'RefusedPickup';

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
            self::READY_FOR_PICKUP,
            self::PICKED_UP,
            self::REFUSED_PICKUP,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}