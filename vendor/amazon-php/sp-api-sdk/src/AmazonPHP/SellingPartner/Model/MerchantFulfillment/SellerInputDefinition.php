<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Model\MerchantFulfillment;

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
class SellerInputDefinition implements \ArrayAccess, \JsonSerializable, ModelInterface
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static string $openAPIModelName = 'SellerInputDefinition';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static array $openAPITypes = [
        'is_required' => 'bool',
        'data_type' => 'string',
        'constraints' => '\AmazonPHP\SellingPartner\Model\MerchantFulfillment\Constraint[]',
        'input_display_text' => 'string',
        'input_target' => '\AmazonPHP\SellingPartner\Model\MerchantFulfillment\InputTargetType',
        'stored_value' => '\AmazonPHP\SellingPartner\Model\MerchantFulfillment\AdditionalSellerInput',
        'restricted_set_values' => 'string[]',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static array $openAPIFormats = [
        'is_required' => null,
        'data_type' => null,
        'constraints' => null,
        'input_display_text' => null,
        'input_target' => null,
        'stored_value' => null,
        'restricted_set_values' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static array $attributeMap = [
        'is_required' => 'IsRequired',
        'data_type' => 'DataType',
        'constraints' => 'Constraints',
        'input_display_text' => 'InputDisplayText',
        'input_target' => 'InputTarget',
        'stored_value' => 'StoredValue',
        'restricted_set_values' => 'RestrictedSetValues',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static array $setters = [
        'is_required' => 'setIsRequired',
        'data_type' => 'setDataType',
        'constraints' => 'setConstraints',
        'input_display_text' => 'setInputDisplayText',
        'input_target' => 'setInputTarget',
        'stored_value' => 'setStoredValue',
        'restricted_set_values' => 'setRestrictedSetValues',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static array $getters = [
        'is_required' => 'getIsRequired',
        'data_type' => 'getDataType',
        'constraints' => 'getConstraints',
        'input_display_text' => 'getInputDisplayText',
        'input_target' => 'getInputTarget',
        'stored_value' => 'getStoredValue',
        'restricted_set_values' => 'getRestrictedSetValues',
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
        $this->container['is_required'] = $data['is_required'] ?? null;
        $this->container['data_type'] = $data['data_type'] ?? null;
        $this->container['constraints'] = $data['constraints'] ?? null;
        $this->container['input_display_text'] = $data['input_display_text'] ?? null;
        $this->container['input_target'] = $data['input_target'] ?? null;
        $this->container['stored_value'] = $data['stored_value'] ?? null;
        $this->container['restricted_set_values'] = $data['restricted_set_values'] ?? null;
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
        if ($this->container['is_required'] === null) {
            throw new AssertionException("'is_required' can't be null");
        }

        if ($this->container['data_type'] === null) {
            throw new AssertionException("'data_type' can't be null");
        }

        if ($this->container['constraints'] === null) {
            throw new AssertionException("'constraints' can't be null");
        }

        if ($this->container['input_display_text'] === null) {
            throw new AssertionException("'input_display_text' can't be null");
        }

        if ($this->container['stored_value'] === null) {
            throw new AssertionException("'stored_value' can't be null");
        }

        $this->container['stored_value']->validate();
    }

    /**
     * Gets is_required.
     */
    public function getIsRequired() : bool
    {
        return $this->container['is_required'];
    }

    /**
     * Sets is_required.
     *
     * @param bool $is_required when true, the additional input field is required
     */
    public function setIsRequired(bool $is_required) : self
    {
        $this->container['is_required'] = $is_required;

        return $this;
    }

    /**
     * Gets data_type.
     */
    public function getDataType() : string
    {
        return $this->container['data_type'];
    }

    /**
     * Sets data_type.
     *
     * @param string $data_type the data type of the additional input field
     */
    public function setDataType(string $data_type) : self
    {
        $this->container['data_type'] = $data_type;

        return $this;
    }

    /**
     * Gets constraints.
     *
     * @return \AmazonPHP\SellingPartner\Model\MerchantFulfillment\Constraint[]
     */
    public function getConstraints() : array
    {
        return $this->container['constraints'];
    }

    /**
     * Sets constraints.
     *
     * @param \AmazonPHP\SellingPartner\Model\MerchantFulfillment\Constraint[] $constraints list of constraints
     */
    public function setConstraints(array $constraints) : self
    {
        $this->container['constraints'] = $constraints;

        return $this;
    }

    /**
     * Gets input_display_text.
     */
    public function getInputDisplayText() : string
    {
        return $this->container['input_display_text'];
    }

    /**
     * Sets input_display_text.
     *
     * @param string $input_display_text the display text for the additional input field
     */
    public function setInputDisplayText(string $input_display_text) : self
    {
        $this->container['input_display_text'] = $input_display_text;

        return $this;
    }

    /**
     * Gets input_target.
     */
    public function getInputTarget() : ?InputTargetType
    {
        return $this->container['input_target'];
    }

    /**
     * Sets input_target.
     *
     * @param null|\AmazonPHP\SellingPartner\Model\MerchantFulfillment\InputTargetType $input_target input_target
     */
    public function setInputTarget(?InputTargetType $input_target) : self
    {
        $this->container['input_target'] = $input_target;

        return $this;
    }

    /**
     * Gets stored_value.
     */
    public function getStoredValue() : AdditionalSellerInput
    {
        return $this->container['stored_value'];
    }

    /**
     * Sets stored_value.
     *
     * @param \AmazonPHP\SellingPartner\Model\MerchantFulfillment\AdditionalSellerInput $stored_value stored_value
     */
    public function setStoredValue(AdditionalSellerInput $stored_value) : self
    {
        $this->container['stored_value'] = $stored_value;

        return $this;
    }

    /**
     * Gets restricted_set_values.
     *
     * @return null|string[]
     */
    public function getRestrictedSetValues() : ?array
    {
        return $this->container['restricted_set_values'];
    }

    /**
     * Sets restricted_set_values.
     *
     * @param null|string[] $restricted_set_values the set of fixed values in an additional seller input
     */
    public function setRestrictedSetValues(?array $restricted_set_values) : self
    {
        $this->container['restricted_set_values'] = $restricted_set_values;

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
