<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\APlus;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class DecoratorType
{
    /**
     * Possible values of this enum.
     */
    public const LIST_ITEM = 'LIST_ITEM';

    public const LIST_ORDERED = 'LIST_ORDERED';

    public const LIST_UNORDERED = 'LIST_UNORDERED';

    public const STYLE_BOLD = 'STYLE_BOLD';

    public const STYLE_ITALIC = 'STYLE_ITALIC';

    public const STYLE_LINEBREAK = 'STYLE_LINEBREAK';

    public const STYLE_PARAGRAPH = 'STYLE_PARAGRAPH';

    public const STYLE_UNDERLINE = 'STYLE_UNDERLINE';

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
            self::LIST_ITEM,
            self::LIST_ORDERED,
            self::LIST_UNORDERED,
            self::STYLE_BOLD,
            self::STYLE_ITALIC,
            self::STYLE_LINEBREAK,
            self::STYLE_PARAGRAPH,
            self::STYLE_UNDERLINE,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}