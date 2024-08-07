<?php
/**
 * PackageDocumentDetail
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Amazon Shipping API
 *
 * The Amazon Shipping API is designed to support outbound shipping use cases both for orders originating on Amazon-owned marketplaces as well as external channels/marketplaces. With these APIs, you can request shipping rates, create shipments, cancel shipments, and track shipments.
 *
 * The version of the OpenAPI document: v2
 * Contact: swa-api-core@amazon.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\ShippingV2;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * PackageDocumentDetail Class Doc Comment
 *
 * @category Class
 * @description The post-purchase details of a package that will be shipped using a shipping service.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class PackageDocumentDetail extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'PackageDocumentDetail';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'package_client_reference_id' => 'string',
        'package_documents' => '\SellingPartnerApi\Model\ShippingV2\PackageDocument[]',
        'tracking_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'package_client_reference_id' => null,
        'package_documents' => null,
        'tracking_id' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'package_client_reference_id' => 'packageClientReferenceId',
        'package_documents' => 'packageDocuments',
        'tracking_id' => 'trackingId'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'package_client_reference_id' => 'setPackageClientReferenceId',
        'package_documents' => 'setPackageDocuments',
        'tracking_id' => 'setTrackingId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'package_client_reference_id' => 'getPackageClientReferenceId',
        'package_documents' => 'getPackageDocuments',
        'tracking_id' => 'getTrackingId'
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
        $this->container['package_client_reference_id'] = $data['package_client_reference_id'] ?? null;
        $this->container['package_documents'] = $data['package_documents'] ?? null;
        $this->container['tracking_id'] = $data['tracking_id'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['package_client_reference_id'] === null) {
            $invalidProperties[] = "'package_client_reference_id' can't be null";
        }
        if ($this->container['package_documents'] === null) {
            $invalidProperties[] = "'package_documents' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets package_client_reference_id
     *
     * @return string
     */
    public function getPackageClientReferenceId()
    {
        return $this->container['package_client_reference_id'];
    }

    /**
     * Sets package_client_reference_id
     *
     * @param string $package_client_reference_id A client provided unique identifier for a package being shipped. This value should be saved by the client to pass as a parameter to the getShipmentDocuments operation.
     *
     * @return self
     */
    public function setPackageClientReferenceId($package_client_reference_id)
    {
        $this->container['package_client_reference_id'] = $package_client_reference_id;

        return $this;
    }
    /**
     * Gets package_documents
     *
     * @return \SellingPartnerApi\Model\ShippingV2\PackageDocument[]
     */
    public function getPackageDocuments()
    {
        return $this->container['package_documents'];
    }

    /**
     * Sets package_documents
     *
     * @param \SellingPartnerApi\Model\ShippingV2\PackageDocument[] $package_documents A list of documents related to a package.
     *
     * @return self
     */
    public function setPackageDocuments($package_documents)
    {
        $this->container['package_documents'] = $package_documents;

        return $this;
    }
    /**
     * Gets tracking_id
     *
     * @return string|null
     */
    public function getTrackingId()
    {
        return $this->container['tracking_id'];
    }

    /**
     * Sets tracking_id
     *
     * @param string|null $tracking_id The carrier generated identifier for a package in a purchased shipment.
     *
     * @return self
     */
    public function setTrackingId($tracking_id)
    {
        $this->container['tracking_id'] = $tracking_id;

        return $this;
    }
}


