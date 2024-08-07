<?php
/**
 * ShipmentStatusDetails
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Retail Procurement Shipments
 *
 * The Selling Partner API for Retail Procurement Shipments provides programmatic access to retail shipping data for vendors.
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

namespace SellingPartnerApi\Model\VendorShippingV1;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * ShipmentStatusDetails Class Doc Comment
 *
 * @category Class
 * @description Shipment Status details.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ShipmentStatusDetails extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ShipmentStatusDetails';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'shipment_status' => 'string',
        'shipment_status_date' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'shipment_status' => null,
        'shipment_status_date' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'shipment_status' => 'shipmentStatus',
        'shipment_status_date' => 'shipmentStatusDate'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'shipment_status' => 'setShipmentStatus',
        'shipment_status_date' => 'setShipmentStatusDate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'shipment_status' => 'getShipmentStatus',
        'shipment_status_date' => 'getShipmentStatusDate'
    ];



    const SHIPMENT_STATUS_CREATED = 'Created';
    const SHIPMENT_STATUS_TRANSPORTATION_REQUESTED = 'TransportationRequested';
    const SHIPMENT_STATUS_CARRIER_ASSIGNED = 'CarrierAssigned';
    const SHIPMENT_STATUS_SHIPPED = 'Shipped';
    
    

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getShipmentStatusAllowableValues()
    {
        $baseVals = [
            self::SHIPMENT_STATUS_CREATED,
            self::SHIPMENT_STATUS_TRANSPORTATION_REQUESTED,
            self::SHIPMENT_STATUS_CARRIER_ASSIGNED,
            self::SHIPMENT_STATUS_SHIPPED,
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
        $this->container['shipment_status'] = $data['shipment_status'] ?? null;
        $this->container['shipment_status_date'] = $data['shipment_status_date'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        $allowedValues = $this->getShipmentStatusAllowableValues();
        if (
            !is_null($this->container['shipment_status']) &&
            !in_array(strtoupper($this->container['shipment_status']), $allowedValues, true)
        ) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'shipment_status', must be one of '%s'",
                $this->container['shipment_status'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }


    /**
     * Gets shipment_status
     *
     * @return string|null
     */
    public function getShipmentStatus()
    {
        return $this->container['shipment_status'];
    }

    /**
     * Sets shipment_status
     *
     * @param string|null $shipment_status Current status of the shipment on whether it is picked up or scheduled.
     *
     * @return self
     */
    public function setShipmentStatus($shipment_status)
    {
        $allowedValues = $this->getShipmentStatusAllowableValues();
        if (!is_null($shipment_status) &&!in_array(strtoupper($shipment_status), $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'shipment_status', must be one of '%s'",
                    $shipment_status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['shipment_status'] = $shipment_status;

        return $this;
    }
    /**
     * Gets shipment_status_date
     *
     * @return string|null
     */
    public function getShipmentStatusDate()
    {
        return $this->container['shipment_status_date'];
    }

    /**
     * Sets shipment_status_date
     *
     * @param string|null $shipment_status_date Date and time on last status update received for the shipment
     *
     * @return self
     */
    public function setShipmentStatusDate($shipment_status_date)
    {
        $this->container['shipment_status_date'] = $shipment_status_date;

        return $this;
    }
}


