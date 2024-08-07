<?php
/**
 * InnerContainersDetails
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Retail Procurement Shipments
 *
 * The Selling Partner API for Retail Procurement Shipments provides programmatic access to retail shipping data for vendors.
 *
 * The version of the OpenAPI document: v1
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\VendorShippingV1;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * InnerContainersDetails Class Doc Comment
 *
 * @category Class
 * @description Details of the innerContainersDetails.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class InnerContainersDetails extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'InnerContainersDetails';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'container_count' => 'int',
        'container_sequence_numbers' => '\SellingPartnerApi\Model\VendorShippingV1\ContainerSequenceNumbers[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'container_count' => null,
        'container_sequence_numbers' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'container_count' => 'containerCount',
        'container_sequence_numbers' => 'containerSequenceNumbers'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'container_count' => 'setContainerCount',
        'container_sequence_numbers' => 'setContainerSequenceNumbers'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'container_count' => 'getContainerCount',
        'container_sequence_numbers' => 'getContainerSequenceNumbers'
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
        $this->container['container_count'] = $data['container_count'] ?? null;
        $this->container['container_sequence_numbers'] = $data['container_sequence_numbers'] ?? null;
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
     * Gets container_count
     *
     * @return int|null
     */
    public function getContainerCount()
    {
        return $this->container['container_count'];
    }

    /**
     * Sets container_count
     *
     * @param int|null $container_count Total containers as part of the shipment
     *
     * @return self
     */
    public function setContainerCount($container_count)
    {
        $this->container['container_count'] = $container_count;

        return $this;
    }
    /**
     * Gets container_sequence_numbers
     *
     * @return \SellingPartnerApi\Model\VendorShippingV1\ContainerSequenceNumbers[]|null
     */
    public function getContainerSequenceNumbers()
    {
        return $this->container['container_sequence_numbers'];
    }

    /**
     * Sets container_sequence_numbers
     *
     * @param \SellingPartnerApi\Model\VendorShippingV1\ContainerSequenceNumbers[]|null $container_sequence_numbers Container sequence numbers that are involved in this shipment.
     *
     * @return self
     */
    public function setContainerSequenceNumbers($container_sequence_numbers)
    {
        $this->container['container_sequence_numbers'] = $container_sequence_numbers;

        return $this;
    }
}


