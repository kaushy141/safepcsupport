<?php
/**
 * StandardComparisonTableModule
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for A+ Content Management
 *
 * With the A+ Content API, you can build applications that help selling partners add rich marketing content to their Amazon product detail pages. A+ content helps selling partners share their brand and product story, which helps buyers make informed purchasing decisions. Selling partners assemble content by choosing from content modules and adding images and text.
 *
 * The version of the OpenAPI document: 2020-11-01
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\AplusContentV20201101;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * StandardComparisonTableModule Class Doc Comment
 *
 * @category Class
 * @description The standard product comparison table.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class StandardComparisonTableModule extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'StandardComparisonTableModule';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'product_columns' => '\SellingPartnerApi\Model\AplusContentV20201101\StandardComparisonProductBlock[]',
        'metric_row_labels' => '\SellingPartnerApi\Model\AplusContentV20201101\PlainTextItem[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'product_columns' => null,
        'metric_row_labels' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'product_columns' => 'productColumns',
        'metric_row_labels' => 'metricRowLabels'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'product_columns' => 'setProductColumns',
        'metric_row_labels' => 'setMetricRowLabels'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'product_columns' => 'getProductColumns',
        'metric_row_labels' => 'getMetricRowLabels'
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
        $this->container['product_columns'] = $data['product_columns'] ?? null;
        $this->container['metric_row_labels'] = $data['metric_row_labels'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if (!is_null($this->container['product_columns']) && (count($this->container['product_columns']) > 6)) {
            $invalidProperties[] = "invalid value for 'product_columns', number of items must be less than or equal to 6.";
        }

        if (!is_null($this->container['product_columns']) && (count($this->container['product_columns']) < 0)) {
            $invalidProperties[] = "invalid value for 'product_columns', number of items must be greater than or equal to 0.";
        }

        if (!is_null($this->container['metric_row_labels']) && (count($this->container['metric_row_labels']) > 10)) {
            $invalidProperties[] = "invalid value for 'metric_row_labels', number of items must be less than or equal to 10.";
        }

        if (!is_null($this->container['metric_row_labels']) && (count($this->container['metric_row_labels']) < 0)) {
            $invalidProperties[] = "invalid value for 'metric_row_labels', number of items must be greater than or equal to 0.";
        }

        return $invalidProperties;
    }


    /**
     * Gets product_columns
     *
     * @return \SellingPartnerApi\Model\AplusContentV20201101\StandardComparisonProductBlock[]|null
     */
    public function getProductColumns()
    {
        return $this->container['product_columns'];
    }

    /**
     * Sets product_columns
     *
     * @param \SellingPartnerApi\Model\AplusContentV20201101\StandardComparisonProductBlock[]|null $product_columns product_columns
     *
     * @return self
     */
    public function setProductColumns($product_columns)
    {

        if (!is_null($product_columns) && (count($product_columns) > 6)) {
            throw new \InvalidArgumentException('invalid value for $product_columns when calling StandardComparisonTableModule., number of items must be less than or equal to 6.');
        }
        if (!is_null($product_columns) && (count($product_columns) < 0)) {
            throw new \InvalidArgumentException('invalid length for $product_columns when calling StandardComparisonTableModule., number of items must be greater than or equal to 0.');
        }
        $this->container['product_columns'] = $product_columns;

        return $this;
    }
    /**
     * Gets metric_row_labels
     *
     * @return \SellingPartnerApi\Model\AplusContentV20201101\PlainTextItem[]|null
     */
    public function getMetricRowLabels()
    {
        return $this->container['metric_row_labels'];
    }

    /**
     * Sets metric_row_labels
     *
     * @param \SellingPartnerApi\Model\AplusContentV20201101\PlainTextItem[]|null $metric_row_labels metric_row_labels
     *
     * @return self
     */
    public function setMetricRowLabels($metric_row_labels)
    {

        if (!is_null($metric_row_labels) && (count($metric_row_labels) > 10)) {
            throw new \InvalidArgumentException('invalid value for $metric_row_labels when calling StandardComparisonTableModule., number of items must be less than or equal to 10.');
        }
        if (!is_null($metric_row_labels) && (count($metric_row_labels) < 0)) {
            throw new \InvalidArgumentException('invalid length for $metric_row_labels when calling StandardComparisonTableModule., number of items must be greater than or equal to 0.');
        }
        $this->container['metric_row_labels'] = $metric_row_labels;

        return $this;
    }
}


