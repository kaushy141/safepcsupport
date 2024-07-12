<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\FBAInventory;

use AmazonPHP\SellingPartner\Exception\AssertionException;
use AmazonPHP\SellingPartner\ModelInterface;
use AmazonPHP\SellingPartner\ObjectSerializer;

/**
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh.
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class InventorySummary implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'InventorySummary';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'asin' => 'string',
        'fn_sku' => 'string',
        'seller_sku' => 'string',
        'condition' => 'string',
        'inventory_details' => '\AmazonPHP\SellingPartner\Model\FBAInventory\InventoryDetails',
        'last_updated_time' => '\DateTimeInterface',
        'product_name' => 'string',
        'total_quantity' => 'int',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'asin' => null,
        'fn_sku' => null,
        'seller_sku' => null,
        'condition' => null,
        'inventory_details' => null,
        'last_updated_time' => 'date-time',
        'product_name' => null,
        'total_quantity' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'asin' => 'asin',
        'fn_sku' => 'fnSku',
        'seller_sku' => 'sellerSku',
        'condition' => 'condition',
        'inventory_details' => 'inventoryDetails',
        'last_updated_time' => 'lastUpdatedTime',
        'product_name' => 'productName',
        'total_quantity' => 'totalQuantity',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'asin' => 'setAsin',
        'fn_sku' => 'setFnSku',
        'seller_sku' => 'setSellerSku',
        'condition' => 'setCondition',
        'inventory_details' => 'setInventoryDetails',
        'last_updated_time' => 'setLastUpdatedTime',
        'product_name' => 'setProductName',
        'total_quantity' => 'setTotalQuantity',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'asin' => 'getAsin',
        'fn_sku' => 'getFnSku',
        'seller_sku' => 'getSellerSku',
        'condition' => 'getCondition',
        'inventory_details' => 'getInventoryDetails',
        'last_updated_time' => 'getLastUpdatedTime',
        'product_name' => 'getProductName',
        'total_quantity' => 'getTotalQuantity',
    ];

    /**
     * Associative array for storing property values.
     *
     * @var mixed[]
     */
    protected array $container = [];

    /**
     * Constructor.
     *
     * @param null|mixed[] $data Associated array of property values
     *                           initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['asin'] = $data['asin'] ?? null;
        $this->container['fn_sku'] = $data['fn_sku'] ?? null;
        $this->container['seller_sku'] = $data['seller_sku'] ?? null;
        $this->container['condition'] = $data['condition'] ?? null;
        $this->container['inventory_details'] = $data['inventory_details'] ?? null;
        $this->container['last_updated_time'] = $data['last_updated_time'] ?? null;
        $this->container['product_name'] = $data['product_name'] ?? null;
        $this->container['total_quantity'] = $data['total_quantity'] ?? null;
    }

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @return string[]
     */
    public static function openAPITypes() : array
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @return null[]|string[]
     */
    public static function openAPIFormats() : array
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @return string[]
     */
    public static function attributeMap() : array
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @return string[]
     */
    public static function setters() : array
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @return string[]
     */
    public static function getters() : array
    {
        return self::$getters;
    }

    /**
     * Gets the string presentation of the object.
     */
    public function __toString() : string
    {
        return \json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * The original name of the model.
     */
    public function getModelName() : string
    {
        return self::$openAPIModelName;
    }

    /**
     * Validate all properties.
     *
     * @throws AssertionException
     */
    public function validate() : void
    {
        if ($this->container['inventory_details'] !== null) {
            $this->container['inventory_details']->validate();
        }
    }

    /**
     * Gets asin.
     */
    public function getAsin() : ?string
    {
        return $this->container['asin'];
    }

    /**
     * Sets asin.
     *
     * @param null|string $asin the Amazon Standard Identification Number (ASIN) of an item
     */
    public function setAsin(?string $asin) : self
    {
        $this->container['asin'] = $asin;

        return $this;
    }

    /**
     * Gets fn_sku.
     */
    public function getFnSku() : ?string
    {
        return $this->container['fn_sku'];
    }

    /**
     * Sets fn_sku.
     *
     * @param null|string $fn_sku amazon's fulfillment network SKU identifier
     */
    public function setFnSku(?string $fn_sku) : self
    {
        $this->container['fn_sku'] = $fn_sku;

        return $this;
    }

    /**
     * Gets seller_sku.
     */
    public function getSellerSku() : ?string
    {
        return $this->container['seller_sku'];
    }

    /**
     * Sets seller_sku.
     *
     * @param null|string $seller_sku the seller SKU of the item
     */
    public function setSellerSku(?string $seller_sku) : self
    {
        $this->container['seller_sku'] = $seller_sku;

        return $this;
    }

    /**
     * Gets condition.
     */
    public function getCondition() : ?string
    {
        return $this->container['condition'];
    }

    /**
     * Sets condition.
     *
     * @param null|string $condition the condition of the item as described by the seller (for example, New Item)
     */
    public function setCondition(?string $condition) : self
    {
        $this->container['condition'] = $condition;

        return $this;
    }

    /**
     * Gets inventory_details.
     */
    public function getInventoryDetails() : ?InventoryDetails
    {
        return $this->container['inventory_details'];
    }

    /**
     * Sets inventory_details.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\FBAInventory\InventoryDetails $inventory_details inventory_details
     */
    public function setInventoryDetails(?InventoryDetails $inventory_details) : self
    {
        $this->container['inventory_details'] = $inventory_details;

        return $this;
    }

    /**
     * Gets last_updated_time.
     */
    public function getLastUpdatedTime() : ?\DateTimeInterface
    {
        return $this->container['last_updated_time'];
    }

    /**
     * Sets last_updated_time.
     *
     * @param null|\DateTimeInterface $last_updated_time the date and time that any quantity was last updated
     */
    public function setLastUpdatedTime(?\DateTimeInterface $last_updated_time) : self
    {
        $this->container['last_updated_time'] = $last_updated_time;

        return $this;
    }

    /**
     * Gets product_name.
     */
    public function getProductName() : ?string
    {
        return $this->container['product_name'];
    }

    /**
     * Sets product_name.
     *
     * @param null|string $product_name the localized language product title of the item within the specific marketplace
     */
    public function setProductName(?string $product_name) : self
    {
        $this->container['product_name'] = $product_name;

        return $this;
    }

    /**
     * Gets total_quantity.
     */
    public function getTotalQuantity() : ?int
    {
        return $this->container['total_quantity'];
    }

    /**
     * Sets total_quantity.
     *
     * @param null|int $total_quantity the total number of units in an inbound shipment or in Amazon fulfillment centers
     */
    public function setTotalQuantity(?int $total_quantity) : self
    {
        $this->container['total_quantity'] = $total_quantity;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @return null|mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     */
    public function offsetSet($offset, $value) : void
    {
        if (null === $offset) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     */
    public function offsetUnset($offset) : void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed returns data which can be serialized by json_encode(), which is a value
     *               of any type other than a resource
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : string
    {
        return \json_encode(ObjectSerializer::sanitizeForSerialization($this), JSON_THROW_ON_ERROR);
    }

    /**
     * Gets a header-safe presentation of the object.
     */
    public function toHeaderValue() : string
    {
        return \json_encode(ObjectSerializer::sanitizeForSerialization($this), JSON_THROW_ON_ERROR);
    }
}