<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\VendorOrders;

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
class Order implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    public const PURCHASE_ORDER_STATE__NEW = 'New';

    public const PURCHASE_ORDER_STATE_ACKNOWLEDGED = 'Acknowledged';

    public const PURCHASE_ORDER_STATE_CLOSED = 'Closed';

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'Order';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'purchase_order_number' => 'string',
        'purchase_order_state' => 'string',
        'order_details' => '\AmazonPHP\SellingPartner\Model\VendorOrders\OrderDetails',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'purchase_order_number' => null,
        'purchase_order_state' => null,
        'order_details' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'purchase_order_number' => 'purchaseOrderNumber',
        'purchase_order_state' => 'purchaseOrderState',
        'order_details' => 'orderDetails',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'purchase_order_number' => 'setPurchaseOrderNumber',
        'purchase_order_state' => 'setPurchaseOrderState',
        'order_details' => 'setOrderDetails',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'purchase_order_number' => 'getPurchaseOrderNumber',
        'purchase_order_state' => 'getPurchaseOrderState',
        'order_details' => 'getOrderDetails',
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
        $this->container['purchase_order_number'] = $data['purchase_order_number'] ?? null;
        $this->container['purchase_order_state'] = $data['purchase_order_state'] ?? null;
        $this->container['order_details'] = $data['order_details'] ?? null;
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
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public function getPurchaseOrderStateAllowableValues() : array
    {
        return [
            self::PURCHASE_ORDER_STATE__NEW,
            self::PURCHASE_ORDER_STATE_ACKNOWLEDGED,
            self::PURCHASE_ORDER_STATE_CLOSED,
        ];
    }

    /**
     * Validate all properties.
     *
     * @throws AssertionException
     */
    public function validate() : void
    {
        if ($this->container['purchase_order_number'] === null) {
            throw new AssertionException("'purchase_order_number' can't be null");
        }

        if ($this->container['purchase_order_state'] === null) {
            throw new AssertionException("'purchase_order_state' can't be null");
        }

        $allowedValues = $this->getPurchaseOrderStateAllowableValues();

        if (null !== $this->container['purchase_order_state'] && !\in_array($this->container['purchase_order_state'], $allowedValues, true)) {
            throw new AssertionException(
                \sprintf(
                    "invalid value '%s' for 'purchase_order_state', must be one of '%s'",
                    $this->container['purchase_order_state'],
                    \implode("', '", $allowedValues)
                )
            );
        }

        if ($this->container['order_details'] !== null) {
            $this->container['order_details']->validate();
        }
    }

    /**
     * Gets purchase_order_number.
     */
    public function getPurchaseOrderNumber() : string
    {
        return $this->container['purchase_order_number'];
    }

    /**
     * Sets purchase_order_number.
     *
     * @param string $purchase_order_number The purchase order number for this order. Formatting Notes: 8-character alpha-numeric code.
     */
    public function setPurchaseOrderNumber(string $purchase_order_number) : self
    {
        $this->container['purchase_order_number'] = $purchase_order_number;

        return $this;
    }

    /**
     * Gets purchase_order_state.
     */
    public function getPurchaseOrderState() : string
    {
        return $this->container['purchase_order_state'];
    }

    /**
     * Sets purchase_order_state.
     *
     * @param string $purchase_order_state this field will contain the current state of the purchase order
     */
    public function setPurchaseOrderState(string $purchase_order_state) : self
    {
        $this->container['purchase_order_state'] = $purchase_order_state;

        return $this;
    }

    /**
     * Gets order_details.
     */
    public function getOrderDetails() : ?OrderDetails
    {
        return $this->container['order_details'];
    }

    /**
     * Sets order_details.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\VendorOrders\OrderDetails $order_details order_details
     */
    public function setOrderDetails(?OrderDetails $order_details) : self
    {
        $this->container['order_details'] = $order_details;

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