<?php
/**
 * GetAdditionalSellerInputsRequest
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Merchant Fulfillment
 *
 * The Selling Partner API for Merchant Fulfillment helps you build applications that let sellers purchase shipping for non-Prime and Prime orders using Amazon's Buy Shipping Services.
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

namespace SellingPartnerApi\Model\MerchantFulfillmentV0;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * GetAdditionalSellerInputsRequest Class Doc Comment
 *
 * @category Class
 * @description Request schema.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class GetAdditionalSellerInputsRequest extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'GetAdditionalSellerInputsRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'shipping_service_id' => 'string',
        'ship_from_address' => '\SellingPartnerApi\Model\MerchantFulfillmentV0\Address',
        'order_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'shipping_service_id' => null,
        'ship_from_address' => null,
        'order_id' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'shipping_service_id' => 'ShippingServiceId',
        'ship_from_address' => 'ShipFromAddress',
        'order_id' => 'OrderId'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'shipping_service_id' => 'setShippingServiceId',
        'ship_from_address' => 'setShipFromAddress',
        'order_id' => 'setOrderId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'shipping_service_id' => 'getShippingServiceId',
        'ship_from_address' => 'getShipFromAddress',
        'order_id' => 'getOrderId'
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
        $this->container['shipping_service_id'] = $data['shipping_service_id'] ?? null;
        $this->container['ship_from_address'] = $data['ship_from_address'] ?? null;
        $this->container['order_id'] = $data['order_id'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['shipping_service_id'] === null) {
            $invalidProperties[] = "'shipping_service_id' can't be null";
        }
        if ($this->container['ship_from_address'] === null) {
            $invalidProperties[] = "'ship_from_address' can't be null";
        }
        if ($this->container['order_id'] === null) {
            $invalidProperties[] = "'order_id' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets shipping_service_id
     *
     * @return string
     */
    public function getShippingServiceId()
    {
        return $this->container['shipping_service_id'];
    }

    /**
     * Sets shipping_service_id
     *
     * @param string $shipping_service_id An Amazon-defined shipping service identifier.
     *
     * @return self
     */
    public function setShippingServiceId($shipping_service_id)
    {
        $this->container['shipping_service_id'] = $shipping_service_id;

        return $this;
    }
    /**
     * Gets ship_from_address
     *
     * @return \SellingPartnerApi\Model\MerchantFulfillmentV0\Address
     */
    public function getShipFromAddress()
    {
        return $this->container['ship_from_address'];
    }

    /**
     * Sets ship_from_address
     *
     * @param \SellingPartnerApi\Model\MerchantFulfillmentV0\Address $ship_from_address ship_from_address
     *
     * @return self
     */
    public function setShipFromAddress($ship_from_address)
    {
        $this->container['ship_from_address'] = $ship_from_address;

        return $this;
    }
    /**
     * Gets order_id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->container['order_id'];
    }

    /**
     * Sets order_id
     *
     * @param string $order_id An Amazon-defined order identifier, in 3-7-7 format.
     *
     * @return self
     */
    public function setOrderId($order_id)
    {
        $this->container['order_id'] = $order_id;

        return $this;
    }
}


