<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FulfillmentInbound;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 */
class PrepGuidance
{
    /**
     * Possible values of this enum.
     */
    public const CONSULT_HELP_DOCUMENTS = 'ConsultHelpDocuments';

    public const NO_ADDITIONAL_PREP_REQUIRED = 'NoAdditionalPrepRequired';

    public const SEE_PREP_INSTRUCTIONS_LIST = 'SeePrepInstructionsList';

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
            self::CONSULT_HELP_DOCUMENTS,
            self::NO_ADDITIONAL_PREP_REQUIRED,
            self::SEE_PREP_INSTRUCTIONS_LIST,
        ];
    }

    public function toString() : string
    {
        return $this->value;
    }
}
