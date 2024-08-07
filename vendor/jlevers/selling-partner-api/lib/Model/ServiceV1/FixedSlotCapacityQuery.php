<?php
/**
 * FixedSlotCapacityQuery
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Services
 *
 * With the Services API, you can build applications that help service providers get and modify their service orders and manage their resources.
 *
 * The version of the OpenAPI document: v1
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\ServiceV1;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * FixedSlotCapacityQuery Class Doc Comment
 *
 * @category Class
 * @description Request schema for the `getFixedSlotCapacity` operation. This schema is used to define the time range, capacity types and slot duration which are being queried.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class FixedSlotCapacityQuery extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'FixedSlotCapacityQuery';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'capacity_types' => '\SellingPartnerApi\Model\ServiceV1\CapacityType[]',
        'slot_duration' => 'float',
        'start_date_time' => 'string',
        'end_date_time' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'capacity_types' => null,
        'slot_duration' => 'int32',
        'start_date_time' => null,
        'end_date_time' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'capacity_types' => 'capacityTypes',
        'slot_duration' => 'slotDuration',
        'start_date_time' => 'startDateTime',
        'end_date_time' => 'endDateTime'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'capacity_types' => 'setCapacityTypes',
        'slot_duration' => 'setSlotDuration',
        'start_date_time' => 'setStartDateTime',
        'end_date_time' => 'setEndDateTime'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'capacity_types' => 'getCapacityTypes',
        'slot_duration' => 'getSlotDuration',
        'start_date_time' => 'getStartDateTime',
        'end_date_time' => 'getEndDateTime'
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
        $this->container['capacity_types'] = $data['capacity_types'] ?? null;
        $this->container['slot_duration'] = $data['slot_duration'] ?? null;
        $this->container['start_date_time'] = $data['start_date_time'] ?? null;
        $this->container['end_date_time'] = $data['end_date_time'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['start_date_time'] === null) {
            $invalidProperties[] = "'start_date_time' can't be null";
        }
        if ($this->container['end_date_time'] === null) {
            $invalidProperties[] = "'end_date_time' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets capacity_types
     *
     * @return \SellingPartnerApi\Model\ServiceV1\CapacityType[]|null
     */
    public function getCapacityTypes()
    {
        return $this->container['capacity_types'];
    }

    /**
     * Sets capacity_types
     *
     * @param \SellingPartnerApi\Model\ServiceV1\CapacityType[]|null $capacity_types An array of capacity types which are being requested. Default value is `[SCHEDULED_CAPACITY]`.
     *
     * @return self
     */
    public function setCapacityTypes($capacity_types)
    {
        $this->container['capacity_types'] = $capacity_types;

        return $this;
    }
    /**
     * Gets slot_duration
     *
     * @return float|null
     */
    public function getSlotDuration()
    {
        return $this->container['slot_duration'];
    }

    /**
     * Sets slot_duration
     *
     * @param float|null $slot_duration Size in which slots are being requested. This value should be a multiple of 5 and fall in the range: 5 <= `slotDuration` <= 360.
     *
     * @return self
     */
    public function setSlotDuration($slot_duration)
    {


        $this->container['slot_duration'] = $slot_duration;

        return $this;
    }
    /**
     * Gets start_date_time
     *
     * @return string
     */
    public function getStartDateTime()
    {
        return $this->container['start_date_time'];
    }

    /**
     * Sets start_date_time
     *
     * @param string $start_date_time Start date time from which the capacity slots are being requested in ISO 8601 format.
     *
     * @return self
     */
    public function setStartDateTime($start_date_time)
    {
        $this->container['start_date_time'] = $start_date_time;

        return $this;
    }
    /**
     * Gets end_date_time
     *
     * @return string
     */
    public function getEndDateTime()
    {
        return $this->container['end_date_time'];
    }

    /**
     * Sets end_date_time
     *
     * @param string $end_date_time End date time up to which the capacity slots are being requested in ISO 8601 format.
     *
     * @return self
     */
    public function setEndDateTime($end_date_time)
    {
        $this->container['end_date_time'] = $end_date_time;

        return $this;
    }
}


