<?php
/**
 * SubmitShipmentStatusUpdatesRequest
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Direct Fulfillment Shipping
 *
 * The Selling Partner API for Direct Fulfillment Shipping provides programmatic access to a direct fulfillment vendor's shipping data.
 *
 * The version of the OpenAPI document: 2021-12-28
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\VendorDirectFulfillmentShippingV20211228;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * SubmitShipmentStatusUpdatesRequest Class Doc Comment
 *
 * @category Class
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class SubmitShipmentStatusUpdatesRequest extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'SubmitShipmentStatusUpdatesRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'shipment_status_updates' => '\SellingPartnerApi\Model\VendorDirectFulfillmentShippingV20211228\ShipmentStatusUpdate[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'shipment_status_updates' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'shipment_status_updates' => 'shipmentStatusUpdates'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'shipment_status_updates' => 'setShipmentStatusUpdates'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'shipment_status_updates' => 'getShipmentStatusUpdates'
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
        $this->container['shipment_status_updates'] = $data['shipment_status_updates'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if (!is_null($this->container['shipment_status_updates']) && (count($this->container['shipment_status_updates']) < 1)) {
            $invalidProperties[] = "invalid value for 'shipment_status_updates', number of items must be greater than or equal to 1.";
        }

        return $invalidProperties;
    }


    /**
     * Gets shipment_status_updates
     *
     * @return \SellingPartnerApi\Model\VendorDirectFulfillmentShippingV20211228\ShipmentStatusUpdate[]|null
     */
    public function getShipmentStatusUpdates()
    {
        return $this->container['shipment_status_updates'];
    }

    /**
     * Sets shipment_status_updates
     *
     * @param \SellingPartnerApi\Model\VendorDirectFulfillmentShippingV20211228\ShipmentStatusUpdate[]|null $shipment_status_updates shipment_status_updates
     *
     * @return self
     */
    public function setShipmentStatusUpdates($shipment_status_updates)
    {


        if (!is_null($shipment_status_updates) && (count($shipment_status_updates) < 1)) {
            throw new \InvalidArgumentException('invalid length for $shipment_status_updates when calling SubmitShipmentStatusUpdatesRequest., number of items must be greater than or equal to 1.');
        }
        $this->container['shipment_status_updates'] = $shipment_status_updates;

        return $this;
    }
}


