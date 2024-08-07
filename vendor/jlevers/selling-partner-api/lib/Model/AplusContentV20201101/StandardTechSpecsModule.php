<?php
/**
 * StandardTechSpecsModule
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
 * StandardTechSpecsModule Class Doc Comment
 *
 * @category Class
 * @description The standard table of technical feature names and definitions.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class StandardTechSpecsModule extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'StandardTechSpecsModule';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'headline' => '\SellingPartnerApi\Model\AplusContentV20201101\TextComponent',
        'specification_list' => '\SellingPartnerApi\Model\AplusContentV20201101\StandardTextPairBlock[]',
        'table_count' => 'int'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'headline' => null,
        'specification_list' => null,
        'table_count' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'headline' => 'headline',
        'specification_list' => 'specificationList',
        'table_count' => 'tableCount'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'headline' => 'setHeadline',
        'specification_list' => 'setSpecificationList',
        'table_count' => 'setTableCount'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'headline' => 'getHeadline',
        'specification_list' => 'getSpecificationList',
        'table_count' => 'getTableCount'
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
        $this->container['headline'] = $data['headline'] ?? null;
        $this->container['specification_list'] = $data['specification_list'] ?? null;
        $this->container['table_count'] = $data['table_count'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['specification_list'] === null) {
            $invalidProperties[] = "'specification_list' can't be null";
        }
        if ((count($this->container['specification_list']) > 16)) {
            $invalidProperties[] = "invalid value for 'specification_list', number of items must be less than or equal to 16.";
        }

        if ((count($this->container['specification_list']) < 4)) {
            $invalidProperties[] = "invalid value for 'specification_list', number of items must be greater than or equal to 4.";
        }

        if (!is_null($this->container['table_count']) && ($this->container['table_count'] > 2)) {
            $invalidProperties[] = "invalid value for 'table_count', must be smaller than or equal to 2.";
        }

        if (!is_null($this->container['table_count']) && ($this->container['table_count'] < 1)) {
            $invalidProperties[] = "invalid value for 'table_count', must be bigger than or equal to 1.";
        }

        return $invalidProperties;
    }


    /**
     * Gets headline
     *
     * @return \SellingPartnerApi\Model\AplusContentV20201101\TextComponent|null
     */
    public function getHeadline()
    {
        return $this->container['headline'];
    }

    /**
     * Sets headline
     *
     * @param \SellingPartnerApi\Model\AplusContentV20201101\TextComponent|null $headline headline
     *
     * @return self
     */
    public function setHeadline($headline)
    {
        $this->container['headline'] = $headline;

        return $this;
    }
    /**
     * Gets specification_list
     *
     * @return \SellingPartnerApi\Model\AplusContentV20201101\StandardTextPairBlock[]
     */
    public function getSpecificationList()
    {
        return $this->container['specification_list'];
    }

    /**
     * Sets specification_list
     *
     * @param \SellingPartnerApi\Model\AplusContentV20201101\StandardTextPairBlock[] $specification_list The specification list.
     *
     * @return self
     */
    public function setSpecificationList($specification_list)
    {

        if ((count($specification_list) > 16)) {
            throw new \InvalidArgumentException('invalid value for $specification_list when calling StandardTechSpecsModule., number of items must be less than or equal to 16.');
        }
        if ((count($specification_list) < 4)) {
            throw new \InvalidArgumentException('invalid length for $specification_list when calling StandardTechSpecsModule., number of items must be greater than or equal to 4.');
        }
        $this->container['specification_list'] = $specification_list;

        return $this;
    }
    /**
     * Gets table_count
     *
     * @return int|null
     */
    public function getTableCount()
    {
        return $this->container['table_count'];
    }

    /**
     * Sets table_count
     *
     * @param int|null $table_count The number of tables to present. Features are evenly divided between the tables.
     *
     * @return self
     */
    public function setTableCount($table_count)
    {

        if (!is_null($table_count) && ($table_count > 2)) {
            throw new \InvalidArgumentException('invalid value for $table_count when calling StandardTechSpecsModule., must be smaller than or equal to 2.');
        }
        if (!is_null($table_count) && ($table_count < 1)) {
            throw new \InvalidArgumentException('invalid value for $table_count when calling StandardTechSpecsModule., must be bigger than or equal to 1.');
        }

        $this->container['table_count'] = $table_count;

        return $this;
    }
}


