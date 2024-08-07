<?php
/**
 * RescheduleAppointmentRequest
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
 * RescheduleAppointmentRequest Class Doc Comment
 *
 * @category Class
 * @description Input for rescheduled appointment operation.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class RescheduleAppointmentRequest extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RescheduleAppointmentRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'appointment_time' => '\SellingPartnerApi\Model\ServiceV1\AppointmentTimeInput',
        'reschedule_reason_code' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'appointment_time' => null,
        'reschedule_reason_code' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'appointment_time' => 'appointmentTime',
        'reschedule_reason_code' => 'rescheduleReasonCode'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'appointment_time' => 'setAppointmentTime',
        'reschedule_reason_code' => 'setRescheduleReasonCode'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'appointment_time' => 'getAppointmentTime',
        'reschedule_reason_code' => 'getRescheduleReasonCode'
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
        $this->container['appointment_time'] = $data['appointment_time'] ?? null;
        $this->container['reschedule_reason_code'] = $data['reschedule_reason_code'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['appointment_time'] === null) {
            $invalidProperties[] = "'appointment_time' can't be null";
        }
        if ($this->container['reschedule_reason_code'] === null) {
            $invalidProperties[] = "'reschedule_reason_code' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets appointment_time
     *
     * @return \SellingPartnerApi\Model\ServiceV1\AppointmentTimeInput
     */
    public function getAppointmentTime()
    {
        return $this->container['appointment_time'];
    }

    /**
     * Sets appointment_time
     *
     * @param \SellingPartnerApi\Model\ServiceV1\AppointmentTimeInput $appointment_time appointment_time
     *
     * @return self
     */
    public function setAppointmentTime($appointment_time)
    {
        $this->container['appointment_time'] = $appointment_time;

        return $this;
    }
    /**
     * Gets reschedule_reason_code
     *
     * @return string
     */
    public function getRescheduleReasonCode()
    {
        return $this->container['reschedule_reason_code'];
    }

    /**
     * Sets reschedule_reason_code
     *
     * @param string $reschedule_reason_code The appointment reschedule reason code.
     *
     * @return self
     */
    public function setRescheduleReasonCode($reschedule_reason_code)
    {
        $this->container['reschedule_reason_code'] = $reschedule_reason_code;

        return $this;
    }
}


