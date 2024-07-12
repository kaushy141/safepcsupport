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
class OrderItemStatusAcknowledgementStatus implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    public const CONFIRMATION_STATUS_ACCEPTED = 'ACCEPTED';

    public const CONFIRMATION_STATUS_PARTIALLY_ACCEPTED = 'PARTIALLY_ACCEPTED';

    public const CONFIRMATION_STATUS_REJECTED = 'REJECTED';

    public const CONFIRMATION_STATUS_UNCONFIRMED = 'UNCONFIRMED';

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'OrderItemStatus_acknowledgementStatus';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'confirmation_status' => 'string',
        'accepted_quantity' => '\AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity',
        'rejected_quantity' => '\AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity',
        'acknowledgement_status_details' => '\AmazonPHP\SellingPartner\Model\VendorOrders\AcknowledgementStatusDetails[]',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'confirmation_status' => null,
        'accepted_quantity' => null,
        'rejected_quantity' => null,
        'acknowledgement_status_details' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'confirmation_status' => 'confirmationStatus',
        'accepted_quantity' => 'acceptedQuantity',
        'rejected_quantity' => 'rejectedQuantity',
        'acknowledgement_status_details' => 'acknowledgementStatusDetails',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'confirmation_status' => 'setConfirmationStatus',
        'accepted_quantity' => 'setAcceptedQuantity',
        'rejected_quantity' => 'setRejectedQuantity',
        'acknowledgement_status_details' => 'setAcknowledgementStatusDetails',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'confirmation_status' => 'getConfirmationStatus',
        'accepted_quantity' => 'getAcceptedQuantity',
        'rejected_quantity' => 'getRejectedQuantity',
        'acknowledgement_status_details' => 'getAcknowledgementStatusDetails',
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
        $this->container['confirmation_status'] = $data['confirmation_status'] ?? null;
        $this->container['accepted_quantity'] = $data['accepted_quantity'] ?? null;
        $this->container['rejected_quantity'] = $data['rejected_quantity'] ?? null;
        $this->container['acknowledgement_status_details'] = $data['acknowledgement_status_details'] ?? null;
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
    public function getConfirmationStatusAllowableValues() : array
    {
        return [
            self::CONFIRMATION_STATUS_ACCEPTED,
            self::CONFIRMATION_STATUS_PARTIALLY_ACCEPTED,
            self::CONFIRMATION_STATUS_REJECTED,
            self::CONFIRMATION_STATUS_UNCONFIRMED,
        ];
    }

    /**
     * Validate all properties.
     *
     * @throws AssertionException
     */
    public function validate() : void
    {
        $allowedValues = $this->getConfirmationStatusAllowableValues();

        if (null !== $this->container['confirmation_status'] && !\in_array($this->container['confirmation_status'], $allowedValues, true)) {
            throw new AssertionException(
                \sprintf(
                    "invalid value '%s' for 'confirmation_status', must be one of '%s'",
                    $this->container['confirmation_status'],
                    \implode("', '", $allowedValues)
                )
            );
        }

        if ($this->container['accepted_quantity'] !== null) {
            $this->container['accepted_quantity']->validate();
        }

        if ($this->container['rejected_quantity'] !== null) {
            $this->container['rejected_quantity']->validate();
        }
    }

    /**
     * Gets confirmation_status.
     */
    public function getConfirmationStatus() : ?string
    {
        return $this->container['confirmation_status'];
    }

    /**
     * Sets confirmation_status.
     *
     * @param null|string $confirmation_status confirmation status of line item
     */
    public function setConfirmationStatus(?string $confirmation_status) : self
    {
        $this->container['confirmation_status'] = $confirmation_status;

        return $this;
    }

    /**
     * Gets accepted_quantity.
     */
    public function getAcceptedQuantity() : ?ItemQuantity
    {
        return $this->container['accepted_quantity'];
    }

    /**
     * Sets accepted_quantity.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity $accepted_quantity accepted_quantity
     */
    public function setAcceptedQuantity(?ItemQuantity $accepted_quantity) : self
    {
        $this->container['accepted_quantity'] = $accepted_quantity;

        return $this;
    }

    /**
     * Gets rejected_quantity.
     */
    public function getRejectedQuantity() : ?ItemQuantity
    {
        return $this->container['rejected_quantity'];
    }

    /**
     * Sets rejected_quantity.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\VendorOrders\ItemQuantity $rejected_quantity rejected_quantity
     */
    public function setRejectedQuantity(?ItemQuantity $rejected_quantity) : self
    {
        $this->container['rejected_quantity'] = $rejected_quantity;

        return $this;
    }

    /**
     * Gets acknowledgement_status_details.
     *
     * @return null|\AmazonPHP\SellingPartner\Model\VendorOrders\AcknowledgementStatusDetails[]
     */
    public function getAcknowledgementStatusDetails() : ?array
    {
        return $this->container['acknowledgement_status_details'];
    }

    /**
     * Sets acknowledgement_status_details.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\VendorOrders\AcknowledgementStatusDetails[] $acknowledgement_status_details details of item quantity confirmed
     */
    public function setAcknowledgementStatusDetails(?array $acknowledgement_status_details) : self
    {
        $this->container['acknowledgement_status_details'] = $acknowledgement_status_details;

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