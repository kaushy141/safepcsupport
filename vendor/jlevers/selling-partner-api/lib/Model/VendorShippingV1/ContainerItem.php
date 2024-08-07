<?php
/**
 * ContainerItem
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
 * ContainerItem Class Doc Comment
 *
 * @category Class
 * @description Carton/Pallet level details for the item.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ContainerItem extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ContainerItem';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'item_reference' => 'string',
        'shipped_quantity' => '\SellingPartnerApi\Model\VendorShippingV1\ItemQuantity',
        'item_details' => '\SellingPartnerApi\Model\VendorShippingV1\ItemDetails'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'item_reference' => null,
        'shipped_quantity' => null,
        'item_details' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'item_reference' => 'itemReference',
        'shipped_quantity' => 'shippedQuantity',
        'item_details' => 'itemDetails'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'item_reference' => 'setItemReference',
        'shipped_quantity' => 'setShippedQuantity',
        'item_details' => 'setItemDetails'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'item_reference' => 'getItemReference',
        'shipped_quantity' => 'getShippedQuantity',
        'item_details' => 'getItemDetails'
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
        $this->container['item_reference'] = $data['item_reference'] ?? null;
        $this->container['shipped_quantity'] = $data['shipped_quantity'] ?? null;
        $this->container['item_details'] = $data['item_details'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['item_reference'] === null) {
            $invalidProperties[] = "'item_reference' can't be null";
        }
        if ($this->container['shipped_quantity'] === null) {
            $invalidProperties[] = "'shipped_quantity' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets item_reference
     *
     * @return string
     */
    public function getItemReference()
    {
        return $this->container['item_reference'];
    }

    /**
     * Sets item_reference
     *
     * @param string $item_reference The reference number for the item. Please provide the itemSequenceNumber from the 'items' segment to refer to that item's details here.
     *
     * @return self
     */
    public function setItemReference($item_reference)
    {
        $this->container['item_reference'] = $item_reference;

        return $this;
    }
    /**
     * Gets shipped_quantity
     *
     * @return \SellingPartnerApi\Model\VendorShippingV1\ItemQuantity
     */
    public function getShippedQuantity()
    {
        return $this->container['shipped_quantity'];
    }

    /**
     * Sets shipped_quantity
     *
     * @param \SellingPartnerApi\Model\VendorShippingV1\ItemQuantity $shipped_quantity shipped_quantity
     *
     * @return self
     */
    public function setShippedQuantity($shipped_quantity)
    {
        $this->container['shipped_quantity'] = $shipped_quantity;

        return $this;
    }
    /**
     * Gets item_details
     *
     * @return \SellingPartnerApi\Model\VendorShippingV1\ItemDetails|null
     */
    public function getItemDetails()
    {
        return $this->container['item_details'];
    }

    /**
     * Sets item_details
     *
     * @param \SellingPartnerApi\Model\VendorShippingV1\ItemDetails|null $item_details item_details
     *
     * @return self
     */
    public function setItemDetails($item_details)
    {
        $this->container['item_details'] = $item_details;

        return $this;
    }
}


