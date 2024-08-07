<?php
/**
 * Item
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Amazon Shipping API
 *
 * The Amazon Shipping API is designed to support outbound shipping use cases both for orders originating on Amazon-owned marketplaces as well as external channels/marketplaces. With these APIs, you can request shipping rates, create shipments, cancel shipments, and track shipments.
 *
 * The version of the OpenAPI document: v2
 * Contact: swa-api-core@amazon.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\ShippingV2;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * Item Class Doc Comment
 *
 * @category Class
 * @description An item in a package.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class Item extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Item';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'item_value' => '\SellingPartnerApi\Model\ShippingV2\Currency',
        'description' => 'string',
        'item_identifier' => 'string',
        'quantity' => 'int',
        'weight' => '\SellingPartnerApi\Model\ShippingV2\Weight',
        'is_hazmat' => 'bool',
        'product_type' => 'string',
        'invoice_details' => '\SellingPartnerApi\Model\ShippingV2\InvoiceDetails',
        'serial_numbers' => 'string[]',
        'direct_fulfillment_item_identifiers' => '\SellingPartnerApi\Model\ShippingV2\DirectFulfillmentItemIdentifiers'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'item_value' => null,
        'description' => null,
        'item_identifier' => null,
        'quantity' => null,
        'weight' => null,
        'is_hazmat' => null,
        'product_type' => null,
        'invoice_details' => null,
        'serial_numbers' => null,
        'direct_fulfillment_item_identifiers' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'item_value' => 'itemValue',
        'description' => 'description',
        'item_identifier' => 'itemIdentifier',
        'quantity' => 'quantity',
        'weight' => 'weight',
        'is_hazmat' => 'isHazmat',
        'product_type' => 'productType',
        'invoice_details' => 'invoiceDetails',
        'serial_numbers' => 'serialNumbers',
        'direct_fulfillment_item_identifiers' => 'directFulfillmentItemIdentifiers'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'item_value' => 'setItemValue',
        'description' => 'setDescription',
        'item_identifier' => 'setItemIdentifier',
        'quantity' => 'setQuantity',
        'weight' => 'setWeight',
        'is_hazmat' => 'setIsHazmat',
        'product_type' => 'setProductType',
        'invoice_details' => 'setInvoiceDetails',
        'serial_numbers' => 'setSerialNumbers',
        'direct_fulfillment_item_identifiers' => 'setDirectFulfillmentItemIdentifiers'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'item_value' => 'getItemValue',
        'description' => 'getDescription',
        'item_identifier' => 'getItemIdentifier',
        'quantity' => 'getQuantity',
        'weight' => 'getWeight',
        'is_hazmat' => 'getIsHazmat',
        'product_type' => 'getProductType',
        'invoice_details' => 'getInvoiceDetails',
        'serial_numbers' => 'getSerialNumbers',
        'direct_fulfillment_item_identifiers' => 'getDirectFulfillmentItemIdentifiers'
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
        $this->container['item_value'] = $data['item_value'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['item_identifier'] = $data['item_identifier'] ?? null;
        $this->container['quantity'] = $data['quantity'] ?? null;
        $this->container['weight'] = $data['weight'] ?? null;
        $this->container['is_hazmat'] = $data['is_hazmat'] ?? null;
        $this->container['product_type'] = $data['product_type'] ?? null;
        $this->container['invoice_details'] = $data['invoice_details'] ?? null;
        $this->container['serial_numbers'] = $data['serial_numbers'] ?? null;
        $this->container['direct_fulfillment_item_identifiers'] = $data['direct_fulfillment_item_identifiers'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['quantity'] === null) {
            $invalidProperties[] = "'quantity' can't be null";
        }
        if ($this->container['weight'] === null) {
            $invalidProperties[] = "'weight' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets item_value
     *
     * @return \SellingPartnerApi\Model\ShippingV2\Currency|null
     */
    public function getItemValue()
    {
        return $this->container['item_value'];
    }

    /**
     * Sets item_value
     *
     * @param \SellingPartnerApi\Model\ShippingV2\Currency|null $item_value item_value
     *
     * @return self
     */
    public function setItemValue($item_value)
    {
        $this->container['item_value'] = $item_value;

        return $this;
    }
    /**
     * Gets description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     *
     * @param string|null $description The product description of the item.
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }
    /**
     * Gets item_identifier
     *
     * @return string|null
     */
    public function getItemIdentifier()
    {
        return $this->container['item_identifier'];
    }

    /**
     * Sets item_identifier
     *
     * @param string|null $item_identifier A unique identifier for an item provided by the client. Should be the order item identifier for the item if this item is associated with an Amazon order. If the item is part of an external (non-Amazon) order, this field can be left blank.
     *
     * @return self
     */
    public function setItemIdentifier($item_identifier)
    {
        $this->container['item_identifier'] = $item_identifier;

        return $this;
    }
    /**
     * Gets quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->container['quantity'];
    }

    /**
     * Sets quantity
     *
     * @param int $quantity The number of units. This value is required.
     *
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->container['quantity'] = $quantity;

        return $this;
    }
    /**
     * Gets weight
     *
     * @return \SellingPartnerApi\Model\ShippingV2\Weight
     */
    public function getWeight()
    {
        return $this->container['weight'];
    }

    /**
     * Sets weight
     *
     * @param \SellingPartnerApi\Model\ShippingV2\Weight $weight weight
     *
     * @return self
     */
    public function setWeight($weight)
    {
        $this->container['weight'] = $weight;

        return $this;
    }
    /**
     * Gets is_hazmat
     *
     * @return bool|null
     */
    public function getIsHazmat()
    {
        return $this->container['is_hazmat'];
    }

    /**
     * Sets is_hazmat
     *
     * @param bool|null $is_hazmat When true, the item qualifies as hazardous materials (hazmat). Defaults to false.
     *
     * @return self
     */
    public function setIsHazmat($is_hazmat)
    {
        $this->container['is_hazmat'] = $is_hazmat;

        return $this;
    }
    /**
     * Gets product_type
     *
     * @return string|null
     */
    public function getProductType()
    {
        return $this->container['product_type'];
    }

    /**
     * Sets product_type
     *
     * @param string|null $product_type The product type of the item.
     *
     * @return self
     */
    public function setProductType($product_type)
    {
        $this->container['product_type'] = $product_type;

        return $this;
    }
    /**
     * Gets invoice_details
     *
     * @return \SellingPartnerApi\Model\ShippingV2\InvoiceDetails|null
     */
    public function getInvoiceDetails()
    {
        return $this->container['invoice_details'];
    }

    /**
     * Sets invoice_details
     *
     * @param \SellingPartnerApi\Model\ShippingV2\InvoiceDetails|null $invoice_details invoice_details
     *
     * @return self
     */
    public function setInvoiceDetails($invoice_details)
    {
        $this->container['invoice_details'] = $invoice_details;

        return $this;
    }
    /**
     * Gets serial_numbers
     *
     * @return string[]|null
     */
    public function getSerialNumbers()
    {
        return $this->container['serial_numbers'];
    }

    /**
     * Sets serial_numbers
     *
     * @param string[]|null $serial_numbers A list of unique serial numbers in an Amazon package that can be used to guarantee non-fraudulent items. The number of serial numbers in the list must be less than or equal to the quantity of items being shipped. Only applicable when channel source is Amazon.
     *
     * @return self
     */
    public function setSerialNumbers($serial_numbers)
    {
        $this->container['serial_numbers'] = $serial_numbers;

        return $this;
    }
    /**
     * Gets direct_fulfillment_item_identifiers
     *
     * @return \SellingPartnerApi\Model\ShippingV2\DirectFulfillmentItemIdentifiers|null
     */
    public function getDirectFulfillmentItemIdentifiers()
    {
        return $this->container['direct_fulfillment_item_identifiers'];
    }

    /**
     * Sets direct_fulfillment_item_identifiers
     *
     * @param \SellingPartnerApi\Model\ShippingV2\DirectFulfillmentItemIdentifiers|null $direct_fulfillment_item_identifiers direct_fulfillment_item_identifiers
     *
     * @return self
     */
    public function setDirectFulfillmentItemIdentifiers($direct_fulfillment_item_identifiers)
    {
        $this->container['direct_fulfillment_item_identifiers'] = $direct_fulfillment_item_identifiers;

        return $this;
    }
}


