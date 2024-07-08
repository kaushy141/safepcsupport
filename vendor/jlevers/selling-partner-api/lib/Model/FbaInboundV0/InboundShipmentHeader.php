<?php
/**
 * InboundShipmentHeader
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Fulfillment Inbound
 *
 * The Selling Partner API for Fulfillment Inbound lets you create applications that create and update inbound shipments of inventory to Amazon's fulfillment network.
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

namespace SellingPartnerApi\Model\FbaInboundV0;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * InboundShipmentHeader Class Doc Comment
 *
 * @category Class
 * @description Inbound shipment information used to create and update inbound shipments.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class InboundShipmentHeader extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'InboundShipmentHeader';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'shipment_name' => 'string',
        'ship_from_address' => '\SellingPartnerApi\Model\FbaInboundV0\Address',
        'destination_fulfillment_center_id' => 'string',
        'are_cases_required' => 'bool',
        'shipment_status' => '\SellingPartnerApi\Model\FbaInboundV0\ShipmentStatus',
        'label_prep_preference' => '\SellingPartnerApi\Model\FbaInboundV0\LabelPrepPreference',
        'intended_box_contents_source' => '\SellingPartnerApi\Model\FbaInboundV0\IntendedBoxContentsSource'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'shipment_name' => null,
        'ship_from_address' => null,
        'destination_fulfillment_center_id' => null,
        'are_cases_required' => null,
        'shipment_status' => null,
        'label_prep_preference' => null,
        'intended_box_contents_source' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'shipment_name' => 'ShipmentName',
        'ship_from_address' => 'ShipFromAddress',
        'destination_fulfillment_center_id' => 'DestinationFulfillmentCenterId',
        'are_cases_required' => 'AreCasesRequired',
        'shipment_status' => 'ShipmentStatus',
        'label_prep_preference' => 'LabelPrepPreference',
        'intended_box_contents_source' => 'IntendedBoxContentsSource'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'shipment_name' => 'setShipmentName',
        'ship_from_address' => 'setShipFromAddress',
        'destination_fulfillment_center_id' => 'setDestinationFulfillmentCenterId',
        'are_cases_required' => 'setAreCasesRequired',
        'shipment_status' => 'setShipmentStatus',
        'label_prep_preference' => 'setLabelPrepPreference',
        'intended_box_contents_source' => 'setIntendedBoxContentsSource'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'shipment_name' => 'getShipmentName',
        'ship_from_address' => 'getShipFromAddress',
        'destination_fulfillment_center_id' => 'getDestinationFulfillmentCenterId',
        'are_cases_required' => 'getAreCasesRequired',
        'shipment_status' => 'getShipmentStatus',
        'label_prep_preference' => 'getLabelPrepPreference',
        'intended_box_contents_source' => 'getIntendedBoxContentsSource'
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
        $this->container['shipment_name'] = $data['shipment_name'] ?? null;
        $this->container['ship_from_address'] = $data['ship_from_address'] ?? null;
        $this->container['destination_fulfillment_center_id'] = $data['destination_fulfillment_center_id'] ?? null;
        $this->container['are_cases_required'] = $data['are_cases_required'] ?? null;
        $this->container['shipment_status'] = $data['shipment_status'] ?? null;
        $this->container['label_prep_preference'] = $data['label_prep_preference'] ?? null;
        $this->container['intended_box_contents_source'] = $data['intended_box_contents_source'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['shipment_name'] === null) {
            $invalidProperties[] = "'shipment_name' can't be null";
        }
        if ($this->container['ship_from_address'] === null) {
            $invalidProperties[] = "'ship_from_address' can't be null";
        }
        if ($this->container['destination_fulfillment_center_id'] === null) {
            $invalidProperties[] = "'destination_fulfillment_center_id' can't be null";
        }
        if ($this->container['shipment_status'] === null) {
            $invalidProperties[] = "'shipment_status' can't be null";
        }
        if ($this->container['label_prep_preference'] === null) {
            $invalidProperties[] = "'label_prep_preference' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets shipment_name
     *
     * @return string
     */
    public function getShipmentName()
    {
        return $this->container['shipment_name'];
    }

    /**
     * Sets shipment_name
     *
     * @param string $shipment_name The name for the shipment. Use a naming convention that helps distinguish between shipments over time, such as the date the shipment was created.
     *
     * @return self
     */
    public function setShipmentName($shipment_name)
    {
        $this->container['shipment_name'] = $shipment_name;

        return $this;
    }
    /**
     * Gets ship_from_address
     *
     * @return \SellingPartnerApi\Model\FbaInboundV0\Address
     */
    public function getShipFromAddress()
    {
        return $this->container['ship_from_address'];
    }

    /**
     * Sets ship_from_address
     *
     * @param \SellingPartnerApi\Model\FbaInboundV0\Address $ship_from_address ship_from_address
     *
     * @return self
     */
    public function setShipFromAddress($ship_from_address)
    {
        $this->container['ship_from_address'] = $ship_from_address;

        return $this;
    }
    /**
     * Gets destination_fulfillment_center_id
     *
     * @return string
     */
    public function getDestinationFulfillmentCenterId()
    {
        return $this->container['destination_fulfillment_center_id'];
    }

    /**
     * Sets destination_fulfillment_center_id
     *
     * @param string $destination_fulfillment_center_id The identifier for the fulfillment center to which the shipment will be shipped. Get this value from the InboundShipmentPlan object in the response returned by the createInboundShipmentPlan operation.
     *
     * @return self
     */
    public function setDestinationFulfillmentCenterId($destination_fulfillment_center_id)
    {
        $this->container['destination_fulfillment_center_id'] = $destination_fulfillment_center_id;

        return $this;
    }
    /**
     * Gets are_cases_required
     *
     * @return bool|null
     */
    public function getAreCasesRequired()
    {
        return $this->container['are_cases_required'];
    }

    /**
     * Sets are_cases_required
     *
     * @param bool|null $are_cases_required Indicates whether or not an inbound shipment contains case-packed boxes. Note: A shipment must contain either all case-packed boxes or all individually packed boxes. Possible values: true - All boxes in the shipment must be case packed. false - All boxes in the shipment must be individually packed. Note: If AreCasesRequired = true for an inbound shipment, then the value of QuantityInCase must be greater than zero for every item in the shipment. Otherwise the service returns an error.
     *
     * @return self
     */
    public function setAreCasesRequired($are_cases_required)
    {
        $this->container['are_cases_required'] = $are_cases_required;

        return $this;
    }
    /**
     * Gets shipment_status
     *
     * @return \SellingPartnerApi\Model\FbaInboundV0\ShipmentStatus
     */
    public function getShipmentStatus()
    {
        return $this->container['shipment_status'];
    }

    /**
     * Sets shipment_status
     *
     * @param \SellingPartnerApi\Model\FbaInboundV0\ShipmentStatus $shipment_status shipment_status
     *
     * @return self
     */
    public function setShipmentStatus($shipment_status)
    {
        $this->container['shipment_status'] = $shipment_status;

        return $this;
    }
    /**
     * Gets label_prep_preference
     *
     * @return \SellingPartnerApi\Model\FbaInboundV0\LabelPrepPreference
     */
    public function getLabelPrepPreference()
    {
        return $this->container['label_prep_preference'];
    }

    /**
     * Sets label_prep_preference
     *
     * @param \SellingPartnerApi\Model\FbaInboundV0\LabelPrepPreference $label_prep_preference label_prep_preference
     *
     * @return self
     */
    public function setLabelPrepPreference($label_prep_preference)
    {
        $this->container['label_prep_preference'] = $label_prep_preference;

        return $this;
    }
    /**
     * Gets intended_box_contents_source
     *
     * @return \SellingPartnerApi\Model\FbaInboundV0\IntendedBoxContentsSource|null
     */
    public function getIntendedBoxContentsSource()
    {
        return $this->container['intended_box_contents_source'];
    }

    /**
     * Sets intended_box_contents_source
     *
     * @param \SellingPartnerApi\Model\FbaInboundV0\IntendedBoxContentsSource|null $intended_box_contents_source intended_box_contents_source
     *
     * @return self
     */
    public function setIntendedBoxContentsSource($intended_box_contents_source)
    {
        $this->container['intended_box_contents_source'] = $intended_box_contents_source;

        return $this;
    }
}


