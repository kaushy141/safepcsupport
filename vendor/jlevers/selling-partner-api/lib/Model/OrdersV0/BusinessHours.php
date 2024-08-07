<?php
/**
 * BusinessHours
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
 * BusinessHours Class Doc Comment
 *
 * @category Class
 * @description Business days and hours when the destination is open for deliveries.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class BusinessHours extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'BusinessHours';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'day_of_week' => 'string',
        'open_intervals' => '\SellingPartnerApi\Model\OrdersV0\OpenInterval[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'day_of_week' => null,
        'open_intervals' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'day_of_week' => 'DayOfWeek',
        'open_intervals' => 'OpenIntervals'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'day_of_week' => 'setDayOfWeek',
        'open_intervals' => 'setOpenIntervals'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'day_of_week' => 'getDayOfWeek',
        'open_intervals' => 'getOpenIntervals'
    ];



    const DAY_OF_WEEK_SUN = 'SUN';
    const DAY_OF_WEEK_MON = 'MON';
    const DAY_OF_WEEK_TUE = 'TUE';
    const DAY_OF_WEEK_WED = 'WED';
    const DAY_OF_WEEK_THU = 'THU';
    const DAY_OF_WEEK_FRI = 'FRI';
    const DAY_OF_WEEK_SAT = 'SAT';
    
    

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getDayOfWeekAllowableValues()
    {
        $baseVals = [
            self::DAY_OF_WEEK_SUN,
            self::DAY_OF_WEEK_MON,
            self::DAY_OF_WEEK_TUE,
            self::DAY_OF_WEEK_WED,
            self::DAY_OF_WEEK_THU,
            self::DAY_OF_WEEK_FRI,
            self::DAY_OF_WEEK_SAT,
        ];

        // This is necessary because Amazon does not consistently capitalize their
        // enum values, so we do case-insensitive enum value validation in ObjectSerializer
        return array_map(function ($val) { return strtoupper($val); }, $baseVals);
    }
    
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
        $this->container['day_of_week'] = $data['day_of_week'] ?? null;
        $this->container['open_intervals'] = $data['open_intervals'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        $allowedValues = $this->getDayOfWeekAllowableValues();
        if (
            !is_null($this->container['day_of_week']) &&
            !in_array(strtoupper($this->container['day_of_week']), $allowedValues, true)
        ) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'day_of_week', must be one of '%s'",
                $this->container['day_of_week'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }


    /**
     * Gets day_of_week
     *
     * @return string|null
     */
    public function getDayOfWeek()
    {
        return $this->container['day_of_week'];
    }

    /**
     * Sets day_of_week
     *
     * @param string|null $day_of_week Day of the week.
     *
     * @return self
     */
    public function setDayOfWeek($day_of_week)
    {
        $allowedValues = $this->getDayOfWeekAllowableValues();
        if (!is_null($day_of_week) &&!in_array(strtoupper($day_of_week), $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'day_of_week', must be one of '%s'",
                    $day_of_week,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['day_of_week'] = $day_of_week;

        return $this;
    }
    /**
     * Gets open_intervals
     *
     * @return \SellingPartnerApi\Model\OrdersV0\OpenInterval[]|null
     */
    public function getOpenIntervals()
    {
        return $this->container['open_intervals'];
    }

    /**
     * Sets open_intervals
     *
     * @param \SellingPartnerApi\Model\OrdersV0\OpenInterval[]|null $open_intervals Time window during the day when the business is open.
     *
     * @return self
     */
    public function setOpenIntervals($open_intervals)
    {
        $this->container['open_intervals'] = $open_intervals;

        return $this;
    }
}


