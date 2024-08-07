<?php
/**
 * RejectionReason
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Orders
 *
 * The Selling Partner API for Orders helps you programmatically retrieve order information. These APIs let you develop fast, flexible, custom applications in areas like order synchronization, order research, and demand-based decision support tools. The Orders API only supports orders that are less than two years old. Orders more than two years old will not show in the API response.
 *
 * The version of the OpenAPI document: v0
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\OrdersV0;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * RejectionReason Class Doc Comment
 *
 * @category Class
 * @description The reason for rejecting the order's regulated information. Not present if the order isn't rejected.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class RejectionReason extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RejectionReason';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'rejection_reason_id' => 'string',
        'rejection_reason_description' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'rejection_reason_id' => null,
        'rejection_reason_description' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'rejection_reason_id' => 'RejectionReasonId',
        'rejection_reason_description' => 'RejectionReasonDescription'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'rejection_reason_id' => 'setRejectionReasonId',
        'rejection_reason_description' => 'setRejectionReasonDescription'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'rejection_reason_id' => 'getRejectionReasonId',
        'rejection_reason_description' => 'getRejectionReasonDescription'
    ];


    
    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['rejection_reason_id'] = $data['rejection_reason_id'] ?? null;
        $this->container['rejection_reason_description'] = $data['rejection_reason_description'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['rejection_reason_id'] === null) {
            $invalidProperties[] = "'rejection_reason_id' can't be null";
        }
        if ($this->container['rejection_reason_description'] === null) {
            $invalidProperties[] = "'rejection_reason_description' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets rejection_reason_id
     *
     * @return string
     */
    public function getRejectionReasonId()
    {
        return $this->container['rejection_reason_id'];
    }

    /**
     * Sets rejection_reason_id
     *
     * @param string $rejection_reason_id The unique identifier for the rejection reason.
     *
     * @return self
     */
    public function setRejectionReasonId($rejection_reason_id)
    {
        $this->container['rejection_reason_id'] = $rejection_reason_id;

        return $this;
    }
    /**
     * Gets rejection_reason_description
     *
     * @return string
     */
    public function getRejectionReasonDescription()
    {
        return $this->container['rejection_reason_description'];
    }

    /**
     * Sets rejection_reason_description
     *
     * @param string $rejection_reason_description The description of this rejection reason.
     *
     * @return self
     */
    public function setRejectionReasonDescription($rejection_reason_description)
    {
        $this->container['rejection_reason_description'] = $rejection_reason_description;

        return $this;
    }
}


