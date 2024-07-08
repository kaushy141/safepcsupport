<?php
/**
 * FulfillmentOrderStatus
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner APIs for Fulfillment Outbound
 *
 * The Selling Partner API for Fulfillment Outbound lets you create applications that help a seller fulfill Multi-Channel Fulfillment orders using their inventory in Amazon's fulfillment network. You can get information on both potential and existing fulfillment orders.
 *
 * The version of the OpenAPI document: 2020-07-01
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\FbaOutboundV20200701;
use ArrayAccess;

use SellingPartnerApi\Model\ModelInterface;

/**
 * FulfillmentOrderStatus Class Doc Comment
 *
 * @category Class
 * @description The current status of the fulfillment order.
 * @package  SellingPartnerApi
 * @group 
 */
class FulfillmentOrderStatus
{
    public $value;

    /**
     * Possible values of this enum
     */
    const _NEW = 'New';
    const RECEIVED = 'Received';
    const PLANNING = 'Planning';
    const PROCESSING = 'Processing';
    const CANCELLED = 'Cancelled';
    const COMPLETE = 'Complete';
    const COMPLETE_PARTIALLED = 'CompletePartialled';
    const UNFULFILLABLE = 'Unfulfillable';
    const INVALID = 'Invalid';
    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        $baseVals = [
            self::_NEW,
            self::RECEIVED,
            self::PLANNING,
            self::PROCESSING,
            self::CANCELLED,
            self::COMPLETE,
            self::COMPLETE_PARTIALLED,
            self::UNFULFILLABLE,
            self::INVALID,
        ];
        // This is necessary because Amazon does not consistently capitalize their
        // enum values, so we do case-insensitive enum value validation in ObjectSerializer
        $ucVals = array_map(function ($val) { return strtoupper($val); }, $baseVals);
        return array_merge($baseVals, $ucVals);
    }

    public function __construct($value)
    {
        if (is_null($value) || !in_array($value, self::getAllowableEnumValues(), true)) {
            throw new \InvalidArgumentException(sprintf("Invalid value %s for enum 'FulfillmentOrderStatus', must be one of '%s'", $value, implode("', '", self::getAllowableEnumValues())));
        }

        $this->value = $value;
    }

    /**
     * Convert the enum value to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}


