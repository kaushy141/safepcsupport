<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FulfillmentInbound;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class Condition
{
    /**
     * Possible values of this enum.
     */
    public const NEW_ITEM = 'NewItem';

    public const NEW_WITH_WARRANTY = 'NewWithWarranty';

    public const NEW_OEM = 'NewOEM';

    public const NEW_OPEN_BOX = 'NewOpenBox';

    public const USED_LIKE_NEW = 'UsedLikeNew';

    public const USED_VERY_GOOD = 'UsedVeryGood';

    public const USED_GOOD = 'UsedGood';

    public const USED_ACCEPTABLE = 'UsedAcceptable';

    public const USED_POOR = 'UsedPoor';

    public const USED_REFURBISHED = 'UsedRefurbished';

    public const COLLECTIBLE_LIKE_NEW = 'CollectibleLikeNew';

    public const COLLECTIBLE_VERY_GOOD = 'CollectibleVeryGood';

    public const COLLECTIBLE_GOOD = 'CollectibleGood';

    public const COLLECTIBLE_ACCEPTABLE = 'CollectibleAcceptable';

    public const COLLECTIBLE_POOR = 'CollectiblePoor';

    public const REFURBISHED_WITH_WARRANTY = 'RefurbishedWithWarranty';

    public const REFURBISHED = 'Refurbished';

    public const CLUB = 'Club';

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
            self::NEW_ITEM,
            self::NEW_WITH_WARRANTY,
            self::NEW_OEM,
            self::NEW_OPEN_BOX,
            self::USED_LIKE_NEW,
            self::USED_VERY_GOOD,
            self::USED_GOOD,
            self::USED_ACCEPTABLE,
            self::USED_POOR,
            self::USED_REFURBISHED,
            self::COLLECTIBLE_LIKE_NEW,
            self::COLLECTIBLE_VERY_GOOD,
            self::COLLECTIBLE_GOOD,
            self::COLLECTIBLE_ACCEPTABLE,
            self::COLLECTIBLE_POOR,
            self::REFURBISHED_WITH_WARRANTY,
            self::REFURBISHED,
            self::CLUB,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
