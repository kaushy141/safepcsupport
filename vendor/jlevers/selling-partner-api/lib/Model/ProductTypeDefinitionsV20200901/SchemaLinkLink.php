<?php
/**
 * SchemaLinkLink
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Product Type Definitions
 *
 * The Selling Partner API for Product Type Definitions provides programmatic access to attribute and data requirements for product types in the Amazon catalog. Use this API to return the JSON Schema for a product type that you can then use with other Selling Partner APIs, such as the Selling Partner API for Listings Items, the Selling Partner API for Catalog Items, and the Selling Partner API for Feeds (for JSON-based listing feeds). For more information, see the [Product Type Definitions API Use Case Guide](https://developer-docs.amazon.com/sp-api/docs/product-type-api-use-case-guide).
 *
 * The version of the OpenAPI document: 2020-09-01
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\ProductTypeDefinitionsV20200901;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * SchemaLinkLink Class Doc Comment
 *
 * @category Class
 * @description Link to retrieve the schema.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class SchemaLinkLink extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'SchemaLink_link';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'resource' => 'string',
        'verb' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'resource' => null,
        'verb' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'resource' => 'resource',
        'verb' => 'verb'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'resource' => 'setResource',
        'verb' => 'setVerb'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'resource' => 'getResource',
        'verb' => 'getVerb'
    ];



    const VERB_GET = 'GET';
    
    

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getVerbAllowableValues()
    {
        $baseVals = [
            self::VERB_GET,
        ];

        // This is necessary because Amazon does not consistently capitalize their
        // enum values, so we do case-insensitive enum value validation in ObjectSerializer
        return array_map(function ($val) { return strtoupper($val); }, $baseVals);
    }
    
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
        $this->container['resource'] = $data['resource'] ?? null;
        $this->container['verb'] = $data['verb'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['resource'] === null) {
            $invalidProperties[] = "'resource' can't be null";
        }
        if ($this->container['verb'] === null) {
            $invalidProperties[] = "'verb' can't be null";
        }
        $allowedValues = $this->getVerbAllowableValues();
        if (
            !is_null($this->container['verb']) &&
            !in_array(strtoupper($this->container['verb']), $allowedValues, true)
        ) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'verb', must be one of '%s'",
                $this->container['verb'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }


    /**
     * Gets resource
     *
     * @return string
     */
    public function getResource()
    {
        return $this->container['resource'];
    }

    /**
     * Sets resource
     *
     * @param string $resource URI resource for the link.
     *
     * @return self
     */
    public function setResource($resource)
    {
        $this->container['resource'] = $resource;

        return $this;
    }
    /**
     * Gets verb
     *
     * @return string
     */
    public function getVerb()
    {
        return $this->container['verb'];
    }

    /**
     * Sets verb
     *
     * @param string $verb HTTP method for the link operation.
     *
     * @return self
     */
    public function setVerb($verb)
    {
        $allowedValues = $this->getVerbAllowableValues();
        if (!in_array(strtoupper($verb), $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'verb', must be one of '%s'",
                    $verb,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['verb'] = $verb;

        return $this;
    }
}


