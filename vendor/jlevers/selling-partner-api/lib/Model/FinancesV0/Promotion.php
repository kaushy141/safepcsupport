<?php
/**
 * Promotion
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Finances
 *
 * The Selling Partner API for Finances helps you obtain financial information relevant to a seller's business. You can obtain financial events for a given order, financial event group, or date range without having to wait until a statement period closes. You can also obtain financial event groups for a given date range.
 *
 * The version of the OpenAPI document: v0
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\FinancesV0;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * Promotion Class Doc Comment
 *
 * @category Class
 * @description A promotion applied to an item.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class Promotion extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Promotion';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'promotion_type' => 'string',
        'promotion_id' => 'string',
        'promotion_amount' => '\SellingPartnerApi\Model\FinancesV0\Currency'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'promotion_type' => null,
        'promotion_id' => null,
        'promotion_amount' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'promotion_type' => 'PromotionType',
        'promotion_id' => 'PromotionId',
        'promotion_amount' => 'PromotionAmount'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'promotion_type' => 'setPromotionType',
        'promotion_id' => 'setPromotionId',
        'promotion_amount' => 'setPromotionAmount'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'promotion_type' => 'getPromotionType',
        'promotion_id' => 'getPromotionId',
        'promotion_amount' => 'getPromotionAmount'
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
        $this->container['promotion_type'] = $data['promotion_type'] ?? null;
        $this->container['promotion_id'] = $data['promotion_id'] ?? null;
        $this->container['promotion_amount'] = $data['promotion_amount'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        return $invalidProperties;
    }


    /**
     * Gets promotion_type
     *
     * @return string|null
     */
    public function getPromotionType()
    {
        return $this->container['promotion_type'];
    }

    /**
     * Sets promotion_type
     *
     * @param string|null $promotion_type The type of promotion.
     *
     * @return self
     */
    public function setPromotionType($promotion_type)
    {
        $this->container['promotion_type'] = $promotion_type;

        return $this;
    }
    /**
     * Gets promotion_id
     *
     * @return string|null
     */
    public function getPromotionId()
    {
        return $this->container['promotion_id'];
    }

    /**
     * Sets promotion_id
     *
     * @param string|null $promotion_id The seller-specified identifier for the promotion.
     *
     * @return self
     */
    public function setPromotionId($promotion_id)
    {
        $this->container['promotion_id'] = $promotion_id;

        return $this;
    }
    /**
     * Gets promotion_amount
     *
     * @return \SellingPartnerApi\Model\FinancesV0\Currency|null
     */
    public function getPromotionAmount()
    {
        return $this->container['promotion_amount'];
    }

    /**
     * Sets promotion_amount
     *
     * @param \SellingPartnerApi\Model\FinancesV0\Currency|null $promotion_amount promotion_amount
     *
     * @return self
     */
    public function setPromotionAmount($promotion_amount)
    {
        $this->container['promotion_amount'] = $promotion_amount;

        return $this;
    }
}


