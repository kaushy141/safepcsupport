<?php
/**
 * OrderScenarioRequest
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Vendor Direct Fulfillment Sandbox Test Data
 *
 * The Selling Partner API for Vendor Direct Fulfillment Sandbox Test Data provides programmatic access to vendor direct fulfillment sandbox test data.
 *
 * The version of the OpenAPI document: 2021-10-28
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * OrderScenarioRequest Class Doc Comment
 *
 * @category Class
 * @description The party identifiers required to generate the test data.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class OrderScenarioRequest extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'OrderScenarioRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'selling_party' => '\SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification',
        'ship_from_party' => '\SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'selling_party' => null,
        'ship_from_party' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'selling_party' => 'sellingParty',
        'ship_from_party' => 'shipFromParty'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'selling_party' => 'setSellingParty',
        'ship_from_party' => 'setShipFromParty'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'selling_party' => 'getSellingParty',
        'ship_from_party' => 'getShipFromParty'
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
        $this->container['selling_party'] = $data['selling_party'] ?? null;
        $this->container['ship_from_party'] = $data['ship_from_party'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['selling_party'] === null) {
            $invalidProperties[] = "'selling_party' can't be null";
        }
        if ($this->container['ship_from_party'] === null) {
            $invalidProperties[] = "'ship_from_party' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets selling_party
     *
     * @return \SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification
     */
    public function getSellingParty()
    {
        return $this->container['selling_party'];
    }

    /**
     * Sets selling_party
     *
     * @param \SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification $selling_party selling_party
     *
     * @return self
     */
    public function setSellingParty($selling_party)
    {
        $this->container['selling_party'] = $selling_party;

        return $this;
    }
    /**
     * Gets ship_from_party
     *
     * @return \SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification
     */
    public function getShipFromParty()
    {
        return $this->container['ship_from_party'];
    }

    /**
     * Sets ship_from_party
     *
     * @param \SellingPartnerApi\Model\VendorDirectFulfillmentSandboxV20211028\PartyIdentification $ship_from_party ship_from_party
     *
     * @return self
     */
    public function setShipFromParty($ship_from_party)
    {
        $this->container['ship_from_party'] = $ship_from_party;

        return $this;
    }
}

