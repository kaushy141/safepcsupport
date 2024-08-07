<?php
/**
 * ListOfferMetricsRequestPagination
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Replenishment
 *
 * The Selling Partner API for Replenishment (Replenishment API) provides programmatic access to replenishment program metrics and offers. These programs provide recurring delivery (automatic or manual) of any replenishable item at a frequency chosen by the customer.
 *
 * The version of the OpenAPI document: 2022-11-07
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\ReplenishmentV20221107;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * ListOfferMetricsRequestPagination Class Doc Comment
 *
 * @category Class
 * @description Use these parameters to paginate through the response.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ListOfferMetricsRequestPagination extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ListOfferMetricsRequestPagination';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'limit' => 'int',
        'offset' => 'int'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'limit' => 'int64',
        'offset' => 'int64'
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'limit' => 'limit',
        'offset' => 'offset'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'limit' => 'setLimit',
        'offset' => 'setOffset'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'limit' => 'getLimit',
        'offset' => 'getOffset'
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
        $this->container['limit'] = $data['limit'] ?? null;
        $this->container['offset'] = $data['offset'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['limit'] === null) {
            $invalidProperties[] = "'limit' can't be null";
        }
        if (($this->container['limit'] > 500)) {
            $invalidProperties[] = "invalid value for 'limit', must be smaller than or equal to 500.";
        }

        if (($this->container['limit'] < 1)) {
            $invalidProperties[] = "invalid value for 'limit', must be bigger than or equal to 1.";
        }

        if ($this->container['offset'] === null) {
            $invalidProperties[] = "'offset' can't be null";
        }
        if (($this->container['offset'] > 9000)) {
            $invalidProperties[] = "invalid value for 'offset', must be smaller than or equal to 9000.";
        }

        if (($this->container['offset'] < 0)) {
            $invalidProperties[] = "invalid value for 'offset', must be bigger than or equal to 0.";
        }

        return $invalidProperties;
    }


    /**
     * Gets limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->container['limit'];
    }

    /**
     * Sets limit
     *
     * @param int $limit The maximum number of results to return in the response.
     *
     * @return self
     */
    public function setLimit($limit)
    {

        if (($limit > 500)) {
            throw new \InvalidArgumentException('invalid value for $limit when calling ListOfferMetricsRequestPagination., must be smaller than or equal to 500.');
        }
        if (($limit < 1)) {
            throw new \InvalidArgumentException('invalid value for $limit when calling ListOfferMetricsRequestPagination., must be bigger than or equal to 1.');
        }

        $this->container['limit'] = $limit;

        return $this;
    }
    /**
     * Gets offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->container['offset'];
    }

    /**
     * Sets offset
     *
     * @param int $offset The offset from which to retrieve the number of results specified by the `limit` value. The first result is at offset 0.
     *
     * @return self
     */
    public function setOffset($offset)
    {

        if (($offset > 9000)) {
            throw new \InvalidArgumentException('invalid value for $offset when calling ListOfferMetricsRequestPagination., must be smaller than or equal to 9000.');
        }
        if (($offset < 0)) {
            throw new \InvalidArgumentException('invalid value for $offset when calling ListOfferMetricsRequestPagination., must be bigger than or equal to 0.');
        }

        $this->container['offset'] = $offset;

        return $this;
    }
}


