<?php
/**
 * ItemDelivery
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
 * ItemDelivery Class Doc Comment
 *
 * @category Class
 * @description Delivery information for the item.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ItemDelivery extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ItemDelivery';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'estimated_delivery_date' => 'string',
        'item_delivery_promise' => '\SellingPartnerApi\Model\ServiceV1\ItemDeliveryPromise'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'estimated_delivery_date' => null,
        'item_delivery_promise' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'estimated_delivery_date' => 'estimatedDeliveryDate',
        'item_delivery_promise' => 'itemDeliveryPromise'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'estimated_delivery_date' => 'setEstimatedDeliveryDate',
        'item_delivery_promise' => 'setItemDeliveryPromise'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'estimated_delivery_date' => 'getEstimatedDeliveryDate',
        'item_delivery_promise' => 'getItemDeliveryPromise'
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
        $this->container['estimated_delivery_date'] = $data['estimated_delivery_date'] ?? null;
        $this->container['item_delivery_promise'] = $data['item_delivery_promise'] ?? null;
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
     * Gets estimated_delivery_date
     *
     * @return string|null
     */
    public function getEstimatedDeliveryDate()
    {
        return $this->container['estimated_delivery_date'];
    }

    /**
     * Sets estimated_delivery_date
     *
     * @param string|null $estimated_delivery_date The date and time of the latest Estimated Delivery Date (EDD) of all the items with an EDD. In ISO 8601 format.
     *
     * @return self
     */
    public function setEstimatedDeliveryDate($estimated_delivery_date)
    {
        $this->container['estimated_delivery_date'] = $estimated_delivery_date;

        return $this;
    }
    /**
     * Gets item_delivery_promise
     *
     * @return \SellingPartnerApi\Model\ServiceV1\ItemDeliveryPromise|null
     */
    public function getItemDeliveryPromise()
    {
        return $this->container['item_delivery_promise'];
    }

    /**
     * Sets item_delivery_promise
     *
     * @param \SellingPartnerApi\Model\ServiceV1\ItemDeliveryPromise|null $item_delivery_promise item_delivery_promise
     *
     * @return self
     */
    public function setItemDeliveryPromise($item_delivery_promise)
    {
        $this->container['item_delivery_promise'] = $item_delivery_promise;

        return $this;
    }
}


