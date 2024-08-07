<?php
/**
 * Rate
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Shipping
 *
 * Provides programmatic access to Amazon Shipping APIs.  **Note:** If you are new to the Amazon Shipping API, refer to the latest version of <a href=\"https://developer-docs.amazon.com/amazon-shipping/docs/shipping-api-v2-reference\">Amazon Shipping API (v2)</a> on the <a href=\"https://developer-docs.amazon.com/amazon-shipping/\">Amazon Shipping Developer Documentation</a> site.
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

namespace SellingPartnerApi\Model\ShippingV1;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * Rate Class Doc Comment
 *
 * @category Class
 * @description The available rate that can be used to send the shipment
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class Rate extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Rate';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'rate_id' => 'string',
        'total_charge' => '\SellingPartnerApi\Model\ShippingV1\Currency',
        'billed_weight' => '\SellingPartnerApi\Model\ShippingV1\Weight',
        'expiration_time' => 'string',
        'service_type' => '\SellingPartnerApi\Model\ShippingV1\ServiceType',
        'promise' => '\SellingPartnerApi\Model\ShippingV1\ShippingPromiseSet'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'rate_id' => null,
        'total_charge' => null,
        'billed_weight' => null,
        'expiration_time' => null,
        'service_type' => null,
        'promise' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'rate_id' => 'rateId',
        'total_charge' => 'totalCharge',
        'billed_weight' => 'billedWeight',
        'expiration_time' => 'expirationTime',
        'service_type' => 'serviceType',
        'promise' => 'promise'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'rate_id' => 'setRateId',
        'total_charge' => 'setTotalCharge',
        'billed_weight' => 'setBilledWeight',
        'expiration_time' => 'setExpirationTime',
        'service_type' => 'setServiceType',
        'promise' => 'setPromise'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'rate_id' => 'getRateId',
        'total_charge' => 'getTotalCharge',
        'billed_weight' => 'getBilledWeight',
        'expiration_time' => 'getExpirationTime',
        'service_type' => 'getServiceType',
        'promise' => 'getPromise'
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
        $this->container['rate_id'] = $data['rate_id'] ?? null;
        $this->container['total_charge'] = $data['total_charge'] ?? null;
        $this->container['billed_weight'] = $data['billed_weight'] ?? null;
        $this->container['expiration_time'] = $data['expiration_time'] ?? null;
        $this->container['service_type'] = $data['service_type'] ?? null;
        $this->container['promise'] = $data['promise'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        return $invalidProperties;
    }


    /**
     * Gets rate_id
     *
     * @return string|null
     */
    public function getRateId()
    {
        return $this->container['rate_id'];
    }

    /**
     * Sets rate_id
     *
     * @param string|null $rate_id An identifier for the rate.
     *
     * @return self
     */
    public function setRateId($rate_id)
    {
        $this->container['rate_id'] = $rate_id;

        return $this;
    }
    /**
     * Gets total_charge
     *
     * @return \SellingPartnerApi\Model\ShippingV1\Currency|null
     */
    public function getTotalCharge()
    {
        return $this->container['total_charge'];
    }

    /**
     * Sets total_charge
     *
     * @param \SellingPartnerApi\Model\ShippingV1\Currency|null $total_charge total_charge
     *
     * @return self
     */
    public function setTotalCharge($total_charge)
    {
        $this->container['total_charge'] = $total_charge;

        return $this;
    }
    /**
     * Gets billed_weight
     *
     * @return \SellingPartnerApi\Model\ShippingV1\Weight|null
     */
    public function getBilledWeight()
    {
        return $this->container['billed_weight'];
    }

    /**
     * Sets billed_weight
     *
     * @param \SellingPartnerApi\Model\ShippingV1\Weight|null $billed_weight billed_weight
     *
     * @return self
     */
    public function setBilledWeight($billed_weight)
    {
        $this->container['billed_weight'] = $billed_weight;

        return $this;
    }
    /**
     * Gets expiration_time
     *
     * @return string|null
     */
    public function getExpirationTime()
    {
        return $this->container['expiration_time'];
    }

    /**
     * Sets expiration_time
     *
     * @param string|null $expiration_time The time after which the offering will expire.
     *
     * @return self
     */
    public function setExpirationTime($expiration_time)
    {
        $this->container['expiration_time'] = $expiration_time;

        return $this;
    }
    /**
     * Gets service_type
     *
     * @return \SellingPartnerApi\Model\ShippingV1\ServiceType|null
     */
    public function getServiceType()
    {
        return $this->container['service_type'];
    }

    /**
     * Sets service_type
     *
     * @param \SellingPartnerApi\Model\ShippingV1\ServiceType|null $service_type service_type
     *
     * @return self
     */
    public function setServiceType($service_type)
    {
        $this->container['service_type'] = $service_type;

        return $this;
    }
    /**
     * Gets promise
     *
     * @return \SellingPartnerApi\Model\ShippingV1\ShippingPromiseSet|null
     */
    public function getPromise()
    {
        return $this->container['promise'];
    }

    /**
     * Sets promise
     *
     * @param \SellingPartnerApi\Model\ShippingV1\ShippingPromiseSet|null $promise promise
     *
     * @return self
     */
    public function setPromise($promise)
    {
        $this->container['promise'] = $promise;

        return $this;
    }
}


