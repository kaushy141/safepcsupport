<?php
/**
 * ListHandoverSlotsResponse
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Easy Ship
 *
 * The Selling Partner API for Easy Ship helps you build applications that help sellers manage and ship Amazon Easy Ship orders. Your Easy Ship applications can: * Get available time slots for packages to be scheduled for delivery. * Schedule, reschedule, and cancel Easy Ship orders. * Print labels, invoices, and warranties. See the [Marketplace Support Table](https://developer-docs.amazon.com/sp-api/docs/easyship-api-v2022-03-23-use-case-guide#marketplace-support-table) for the differences in Easy Ship operations by marketplace.
 *
 * The version of the OpenAPI document: 2022-03-23
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\EasyShipV20220323;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * ListHandoverSlotsResponse Class Doc Comment
 *
 * @category Class
 * @description The response schema for the `listHandoverSlots` operation.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ListHandoverSlotsResponse extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ListHandoverSlotsResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'amazon_order_id' => 'string',
        'time_slots' => '\SellingPartnerApi\Model\EasyShipV20220323\TimeSlot[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'amazon_order_id' => null,
        'time_slots' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'headers' => 'headers',
        'amazon_order_id' => 'amazonOrderId',
        'time_slots' => 'timeSlots'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'headers' => 'setHeaders',
        'amazon_order_id' => 'setAmazonOrderId',
        'time_slots' => 'setTimeSlots'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'headers' => 'getHeaders',
        'amazon_order_id' => 'getAmazonOrderId',
        'time_slots' => 'getTimeSlots'
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
        $this->container['amazon_order_id'] = $data['amazon_order_id'] ?? null;
        $this->container['time_slots'] = $data['time_slots'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['amazon_order_id'] === null) {
            $invalidProperties[] = "'amazon_order_id' can't be null";
        }
        if ($this->container['time_slots'] === null) {
            $invalidProperties[] = "'time_slots' can't be null";
        }
        if ((count($this->container['time_slots']) > 500)) {
            $invalidProperties[] = "invalid value for 'time_slots', number of items must be less than or equal to 500.";
        }

        if ((count($this->container['time_slots']) < 1)) {
            $invalidProperties[] = "invalid value for 'time_slots', number of items must be greater than or equal to 1.";
        }

        return $invalidProperties;
    }

    /**
     * Gets API response headers
     *
     * @return array[string]
     */
    public function getHeaders()
    {
        return $this->container['headers'];
    }

    /**
     * Sets API response headers (only relevant to response models)
     *
     * @param array[string => string] $headers Associative array of response headers.
     *
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->container['headers'] = $headers;
        return $this;
    }

    /**
     * Gets amazon_order_id
     *
     * @return string
     */
    public function getAmazonOrderId()
    {
        return $this->container['amazon_order_id'];
    }

    /**
     * Sets amazon_order_id
     *
     * @param string $amazon_order_id An Amazon-defined order identifier. Identifies the order that the seller wants to deliver using Amazon Easy Ship.
     *
     * @return self
     */
    public function setAmazonOrderId($amazon_order_id)
    {
        $this->container['amazon_order_id'] = $amazon_order_id;

        return $this;
    }
    /**
     * Gets time_slots
     *
     * @return \SellingPartnerApi\Model\EasyShipV20220323\TimeSlot[]
     */
    public function getTimeSlots()
    {
        return $this->container['time_slots'];
    }

    /**
     * Sets time_slots
     *
     * @param \SellingPartnerApi\Model\EasyShipV20220323\TimeSlot[] $time_slots A list of time slots.
     *
     * @return self
     */
    public function setTimeSlots($time_slots)
    {

        if ((count($time_slots) > 500)) {
            throw new \InvalidArgumentException('invalid value for $time_slots when calling ListHandoverSlotsResponse., number of items must be less than or equal to 500.');
        }
        if ((count($time_slots) < 1)) {
            throw new \InvalidArgumentException('invalid length for $time_slots when calling ListHandoverSlotsResponse., number of items must be greater than or equal to 1.');
        }
        $this->container['time_slots'] = $time_slots;

        return $this;
    }
}


