<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\ProductFees;

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
class FeesEstimateByIdRequest implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'FeesEstimateByIdRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'fees_estimate_request' => '\AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateRequest',
        'id_type' => '\AmazonPHP\SellingPartner\Model\ProductFees\IdType',
        'id_value' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'fees_estimate_request' => null,
        'id_type' => null,
        'id_value' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'fees_estimate_request' => 'FeesEstimateRequest',
        'id_type' => 'IdType',
        'id_value' => 'IdValue',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'fees_estimate_request' => 'setFeesEstimateRequest',
        'id_type' => 'setIdType',
        'id_value' => 'setIdValue',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'fees_estimate_request' => 'getFeesEstimateRequest',
        'id_type' => 'getIdType',
        'id_value' => 'getIdValue',
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
        $this->container['fees_estimate_request'] = $data['fees_estimate_request'] ?? null;
        $this->container['id_type'] = $data['id_type'] ?? null;
        $this->container['id_value'] = $data['id_value'] ?? null;
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
        if ($this->container['fees_estimate_request'] !== null) {
            $this->container['fees_estimate_request']->validate();
        }

        if ($this->container['id_type'] === null) {
            throw new AssertionException("'id_type' can't be null");
        }

        if ($this->container['id_value'] === null) {
            throw new AssertionException("'id_value' can't be null");
        }
    }

    /**
     * Gets fees_estimate_request.
     */
    public function getFeesEstimateRequest() : ?FeesEstimateRequest
    {
        return $this->container['fees_estimate_request'];
    }

    /**
     * Sets fees_estimate_request.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateRequest $fees_estimate_request fees_estimate_request
     */
    public function setFeesEstimateRequest(?FeesEstimateRequest $fees_estimate_request) : self
    {
        $this->container['fees_estimate_request'] = $fees_estimate_request;

        return $this;
    }

    /**
     * Gets id_type.
     */
    public function getIdType() : IdType
    {
        return $this->container['id_type'];
    }

    /**
     * Sets id_type.
     *
     * @param \AmazonPHP\SellingPartner\Model\ProductFees\IdType $id_type id_type
     */
    public function setIdType(IdType $id_type) : self
    {
        $this->container['id_type'] = $id_type;

        return $this;
    }

    /**
     * Gets id_value.
     */
    public function getIdValue() : string
    {
        return $this->container['id_value'];
    }

    /**
     * Sets id_value.
     *
     * @param string $id_value the item identifier
     */
    public function setIdValue(string $id_value) : self
    {
        $this->container['id_value'] = $id_value;

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