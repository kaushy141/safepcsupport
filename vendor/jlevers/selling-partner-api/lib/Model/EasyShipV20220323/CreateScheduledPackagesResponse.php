<?php
/**
 * CreateScheduledPackagesResponse
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Easy Ship
 *
 * The Selling Partner API for Easy Ship helps you build applications that help sellers manage and ship Amazon Easy Ship orders. Your Easy Ship applications can: * Get available time slots for packages to be scheduled for delivery. * Schedule, reschedule, and cancel Easy Ship orders. * Print labels, invoices, and warranties. See the [Marketplace Support Table](https://developer-docs.amazon.com/sp-api/docs/easyship-api-v2022-03-23-use-case-guide#marketplace-support-table) for the differences in Easy Ship operations by marketplace.
 *
 * The version of the OpenAPI document: 2022-03-23
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\EasyShipV20220323;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * CreateScheduledPackagesResponse Class Doc Comment
 *
 * @category Class
 * @description The response schema for the bulk scheduling API. It returns by the bulk scheduling API containing an array of the scheduled packtages, an optional list of orders we couldn't schedule with the reason, and a pre-signed URL for a ZIP file containing the associated shipping labels plus the documents enabled for your marketplace.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class CreateScheduledPackagesResponse extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CreateScheduledPackagesResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'scheduled_packages' => '\SellingPartnerApi\Model\EasyShipV20220323\Package[]',
        'rejected_orders' => '\SellingPartnerApi\Model\EasyShipV20220323\RejectedOrder[]',
        'printable_documents_url' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'scheduled_packages' => null,
        'rejected_orders' => null,
        'printable_documents_url' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'headers' => 'headers',
        'scheduled_packages' => 'scheduledPackages',
        'rejected_orders' => 'rejectedOrders',
        'printable_documents_url' => 'printableDocumentsUrl'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'headers' => 'setHeaders',
        'scheduled_packages' => 'setScheduledPackages',
        'rejected_orders' => 'setRejectedOrders',
        'printable_documents_url' => 'setPrintableDocumentsUrl'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'headers' => 'getHeaders',
        'scheduled_packages' => 'getScheduledPackages',
        'rejected_orders' => 'getRejectedOrders',
        'printable_documents_url' => 'getPrintableDocumentsUrl'
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
        $this->container['scheduled_packages'] = $data['scheduled_packages'] ?? null;
        $this->container['rejected_orders'] = $data['rejected_orders'] ?? null;
        $this->container['printable_documents_url'] = $data['printable_documents_url'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if (!is_null($this->container['scheduled_packages']) && (count($this->container['scheduled_packages']) > 100)) {
            $invalidProperties[] = "invalid value for 'scheduled_packages', number of items must be less than or equal to 100.";
        }

        return $invalidProperties;
    }

    /**
     * Gets API response headers
     *
     * @return array[string]
     */
    public function getHeaders()
    {
        return $this->container['headers'];
    }

    /**
     * Sets API response headers (only relevant to response models)
     *
     * @param array[string => string] $headers Associative array of response headers.
     *
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->container['headers'] = $headers;
        return $this;
    }

    /**
     * Gets scheduled_packages
     *
     * @return \SellingPartnerApi\Model\EasyShipV20220323\Package[]|null
     */
    public function getScheduledPackages()
    {
        return $this->container['scheduled_packages'];
    }

    /**
     * Sets scheduled_packages
     *
     * @param \SellingPartnerApi\Model\EasyShipV20220323\Package[]|null $scheduled_packages A list of packages. Refer to the `Package` object.
     *
     * @return self
     */
    public function setScheduledPackages($scheduled_packages)
    {

        if (!is_null($scheduled_packages) && (count($scheduled_packages) > 100)) {
            throw new \InvalidArgumentException('invalid value for $scheduled_packages when calling CreateScheduledPackagesResponse., number of items must be less than or equal to 100.');
        }
        $this->container['scheduled_packages'] = $scheduled_packages;

        return $this;
    }
    /**
     * Gets rejected_orders
     *
     * @return \SellingPartnerApi\Model\EasyShipV20220323\RejectedOrder[]|null
     */
    public function getRejectedOrders()
    {
        return $this->container['rejected_orders'];
    }

    /**
     * Sets rejected_orders
     *
     * @param \SellingPartnerApi\Model\EasyShipV20220323\RejectedOrder[]|null $rejected_orders A list of orders we couldn't scheduled on your behalf. Each element contains the reason and details on the error.
     *
     * @return self
     */
    public function setRejectedOrders($rejected_orders)
    {
        $this->container['rejected_orders'] = $rejected_orders;

        return $this;
    }
    /**
     * Gets printable_documents_url
     *
     * @return string|null
     */
    public function getPrintableDocumentsUrl()
    {
        return $this->container['printable_documents_url'];
    }

    /**
     * Sets printable_documents_url
     *
     * @param string|null $printable_documents_url A pre-signed URL for the zip document containing the shipping labels and the documents enabled for your marketplace.
     *
     * @return self
     */
    public function setPrintableDocumentsUrl($printable_documents_url)
    {
        $this->container['printable_documents_url'] = $printable_documents_url;

        return $this;
    }
}


