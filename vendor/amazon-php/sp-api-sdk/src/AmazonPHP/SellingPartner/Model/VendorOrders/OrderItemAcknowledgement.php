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
class OrderItemAcknowledgement implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    public const ACKNOWLEDGEMENT_CODE_ACCEPTED = 'Accepted';

    public const ACKNOWLEDGEMENT_CODE_BACKORDERED = 'Backordered';

    public const ACKNOWLEDGEMENT_CODE_REJECTED = 'Rejected';

    public const REJECTION_REASON_TEMPORARILY_UNAVAILABLE = 'TemporarilyUnavailable';

    public const REJECTION_REASON_INVALID_PRODUCT_IDENTIFIER = 'InvalidProductIdentifier';

    public const REJECTION_REASON_OBSOLETE_PRODUCT = 'ObsoleteProduct';

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'OrderItemAcknowledgement';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'acknowledgement_code' => 'string',
        'acknowledged_quantity' => '\AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity',
        'scheduled_ship_date' => '\DateTimeInterface',
        'scheduled_delivery_date' => '\DateTimeInterface',
        'rejection_reason' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'acknowledgement_code' => null,
        'acknowledged_quantity' => null,
        'scheduled_ship_date' => 'date-time',
        'scheduled_delivery_date' => 'date-time',
        'rejection_reason' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'acknowledgement_code' => 'acknowledgementCode',
        'acknowledged_quantity' => 'acknowledgedQuantity',
        'scheduled_ship_date' => 'scheduledShipDate',
        'scheduled_delivery_date' => 'scheduledDeliveryDate',
        'rejection_reason' => 'rejectionReason',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'acknowledgement_code' => 'setAcknowledgementCode',
        'acknowledged_quantity' => 'setAcknowledgedQuantity',
        'scheduled_ship_date' => 'setScheduledShipDate',
        'scheduled_delivery_date' => 'setScheduledDeliveryDate',
        'rejection_reason' => 'setRejectionReason',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'acknowledgement_code' => 'getAcknowledgementCode',
        'acknowledged_quantity' => 'getAcknowledgedQuantity',
        'scheduled_ship_date' => 'getScheduledShipDate',
        'scheduled_delivery_date' => 'getScheduledDeliveryDate',
        'rejection_reason' => 'getRejectionReason',
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
        $this->container['acknowledgement_code'] = $data['acknowledgement_code'] ?? null;
        $this->container['acknowledged_quantity'] = $data['acknowledged_quantity'] ?? null;
        $this->container['scheduled_ship_date'] = $data['scheduled_ship_date'] ?? null;
        $this->container['scheduled_delivery_date'] = $data['scheduled_delivery_date'] ?? null;
        $this->container['rejection_reason'] = $data['rejection_reason'] ?? null;
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
    public function getAcknowledgementCodeAllowableValues() : array
    {
        return [
            self::ACKNOWLEDGEMENT_CODE_ACCEPTED,
            self::ACKNOWLEDGEMENT_CODE_BACKORDERED,
            self::ACKNOWLEDGEMENT_CODE_REJECTED,
        ];
    }

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public function getRejectionReasonAllowableValues() : array
    {
        return [
            self::REJECTION_REASON_TEMPORARILY_UNAVAILABLE,
            self::REJECTION_REASON_INVALID_PRODUCT_IDENTIFIER,
            self::REJECTION_REASON_OBSOLETE_PRODUCT,
        ];
    }

    /**
     * Validate all properties.
     *
     * @throws AssertionException
     */
    public function validate() : void
    {
        if ($this->container['acknowledgement_code'] === null) {
            throw new AssertionException("'acknowledgement_code' can't be null");
        }

        $allowedValues = $this->getAcknowledgementCodeAllowableValues();

        if (null !== $this->container['acknowledgement_code'] && !\in_array($this->container['acknowledgement_code'], $allowedValues, true)) {
            throw new AssertionException(
                \sprintf(
                    "invalid value '%s' for 'acknowledgement_code', must be one of '%s'",
                    $this->container['acknowledgement_code'],
                    \implode("', '", $allowedValues)
                )
            );
        }

        if ($this->container['acknowledged_quantity'] === null) {
            throw new AssertionException("'acknowledged_quantity' can't be null");
        }

        $this->container['acknowledged_quantity']->validate();

        $allowedValues = $this->getRejectionReasonAllowableValues();

        if (null !== $this->container['rejection_reason'] && !\in_array($this->container['rejection_reason'], $allowedValues, true)) {
            throw new AssertionException(
                \sprintf(
                    "invalid value '%s' for 'rejection_reason', must be one of '%s'",
                    $this->container['rejection_reason'],
                    \implode("', '", $allowedValues)
                )
            );
        }
    }

    /**
     * Gets acknowledgement_code.
     */
    public function getAcknowledgementCode() : string
    {
        return $this->container['acknowledgement_code'];
    }

    /**
     * Sets acknowledgement_code.
     *
     * @param string $acknowledgement_code this indicates the acknowledgement code
     */
    public function setAcknowledgementCode(string $acknowledgement_code) : self
    {
        $this->container['acknowledgement_code'] = $acknowledgement_code;

        return $this;
    }

    /**
     * Gets acknowledged_quantity.
     */
    public function getAcknowledgedQuantity() : ItemQuantity
    {
        return $this->container['acknowledged_quantity'];
    }

    /**
     * Sets acknowledged_quantity.
     *
     * @param \AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity $acknowledged_quantity acknowledged_quantity
     */
    public function setAcknowledgedQuantity(ItemQuantity $acknowledged_quantity) : self
    {
        $this->container['acknowledged_quantity'] = $acknowledged_quantity;

        return $this;
    }

    /**
     * Gets scheduled_ship_date.
     */
    public function getScheduledShipDate() : ?\DateTimeInterface
    {
        return $this->container['scheduled_ship_date'];
    }

    /**
     * Sets scheduled_ship_date.
     *
     * @param null|\DateTimeInterface $scheduled_ship_date Estimated ship date per line item. Must be in ISO-8601 date/time format.
     */
    public function setScheduledShipDate(?\DateTimeInterface $scheduled_ship_date) : self
    {
        $this->container['scheduled_ship_date'] = $scheduled_ship_date;

        return $this;
    }

    /**
     * Gets scheduled_delivery_date.
     */
    public function getScheduledDeliveryDate() : ?\DateTimeInterface
    {
        return $this->container['scheduled_delivery_date'];
    }

    /**
     * Sets scheduled_delivery_date.
     *
     * @param null|\DateTimeInterface $scheduled_delivery_date Estimated delivery date per line item. Must be in ISO-8601 date/time format.
     */
    public function setScheduledDeliveryDate(?\DateTimeInterface $scheduled_delivery_date) : self
    {
        $this->container['scheduled_delivery_date'] = $scheduled_delivery_date;

        return $this;
    }

    /**
     * Gets rejection_reason.
     */
    public function getRejectionReason() : ?string
    {
        return $this->container['rejection_reason'];
    }

    /**
     * Sets rejection_reason.
     *
     * @param null|string $rejection_reason indicates the reason for rejection
     */
    public function setRejectionReason(?string $rejection_reason) : self
    {
        $this->container['rejection_reason'] = $rejection_reason;

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