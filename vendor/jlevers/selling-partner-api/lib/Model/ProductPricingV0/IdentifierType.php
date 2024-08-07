<?php
/**
 * IdentifierType
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Pricing
 *
 * The Selling Partner API for Pricing helps you programmatically retrieve product pricing and offer information for Amazon Marketplace products.
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

namespace SellingPartnerApi\Model\ProductPricingV0;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * IdentifierType Class Doc Comment
 *
 * @category Class
 * @description Specifies the identifiers used to uniquely identify an item.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class IdentifierType extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'IdentifierType';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'marketplace_asin' => '\SellingPartnerApi\Model\ProductPricingV0\ASINIdentifier',
        'sku_identifier' => '\SellingPartnerApi\Model\ProductPricingV0\SellerSKUIdentifier'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'marketplace_asin' => null,
        'sku_identifier' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'marketplace_asin' => 'MarketplaceASIN',
        'sku_identifier' => 'SKUIdentifier'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'marketplace_asin' => 'setMarketplaceAsin',
        'sku_identifier' => 'setSkuIdentifier'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'marketplace_asin' => 'getMarketplaceAsin',
        'sku_identifier' => 'getSkuIdentifier'
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
        $this->container['marketplace_asin'] = $data['marketplace_asin'] ?? null;
        $this->container['sku_identifier'] = $data['sku_identifier'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['marketplace_asin'] === null) {
            $invalidProperties[] = "'marketplace_asin' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets marketplace_asin
     *
     * @return \SellingPartnerApi\Model\ProductPricingV0\ASINIdentifier
     */
    public function getMarketplaceAsin()
    {
        return $this->container['marketplace_asin'];
    }

    /**
     * Sets marketplace_asin
     *
     * @param \SellingPartnerApi\Model\ProductPricingV0\ASINIdentifier $marketplace_asin marketplace_asin
     *
     * @return self
     */
    public function setMarketplaceAsin($marketplace_asin)
    {
        $this->container['marketplace_asin'] = $marketplace_asin;

        return $this;
    }
    /**
     * Gets sku_identifier
     *
     * @return \SellingPartnerApi\Model\ProductPricingV0\SellerSKUIdentifier|null
     */
    public function getSkuIdentifier()
    {
        return $this->container['sku_identifier'];
    }

    /**
     * Sets sku_identifier
     *
     * @param \SellingPartnerApi\Model\ProductPricingV0\SellerSKUIdentifier|null $sku_identifier sku_identifier
     *
     * @return self
     */
    public function setSkuIdentifier($sku_identifier)
    {
        $this->container['sku_identifier'] = $sku_identifier;

        return $this;
    }
}


