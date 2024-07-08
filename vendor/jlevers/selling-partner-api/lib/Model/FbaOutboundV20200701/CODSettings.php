<?php
/**
 * CODSettings
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner APIs for Fulfillment Outbound
 *
 * The Selling Partner API for Fulfillment Outbound lets you create applications that help a seller fulfill Multi-Channel Fulfillment orders using their inventory in Amazon's fulfillment network. You can get information on both potential and existing fulfillment orders.
 *
 * The version of the OpenAPI document: 2020-07-01
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace SellingPartnerApi\Model\FbaOutboundV20200701;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * CODSettings Class Doc Comment
 *
 * @category Class
 * @description The COD (Cash On Delivery) charges that you associate with a COD fulfillment order.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class CODSettings extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CODSettings';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'is_cod_required' => 'bool',
        'cod_charge' => '\SellingPartnerApi\Model\FbaOutboundV20200701\Money',
        'cod_charge_tax' => '\SellingPartnerApi\Model\FbaOutboundV20200701\Money',
        'shipping_charge' => '\SellingPartnerApi\Model\FbaOutboundV20200701\Money',
        'shipping_charge_tax' => '\SellingPartnerApi\Model\FbaOutboundV20200701\Money'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'is_cod_required' => null,
        'cod_charge' => null,
        'cod_charge_tax' => null,
        'shipping_charge' => null,
        'shipping_charge_tax' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'is_cod_required' => 'isCodRequired',
        'cod_charge' => 'codCharge',
        'cod_charge_tax' => 'codChargeTax',
        'shipping_charge' => 'shippingCharge',
        'shipping_charge_tax' => 'shippingChargeTax'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'is_cod_required' => 'setIsCodRequired',
        'cod_charge' => 'setCodCharge',
        'cod_charge_tax' => 'setCodChargeTax',
        'shipping_charge' => 'setShippingCharge',
        'shipping_charge_tax' => 'setShippingChargeTax'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'is_cod_required' => 'getIsCodRequired',
        'cod_charge' => 'getCodCharge',
        'cod_charge_tax' => 'getCodChargeTax',
        'shipping_charge' => 'getShippingCharge',
        'shipping_charge_tax' => 'getShippingChargeTax'
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
        $this->container['is_cod_required'] = $data['is_cod_required'] ?? null;
        $this->container['cod_charge'] = $data['cod_charge'] ?? null;
        $this->container['cod_charge_tax'] = $data['cod_charge_tax'] ?? null;
        $this->container['shipping_charge'] = $data['shipping_charge'] ?? null;
        $this->container['shipping_charge_tax'] = $data['shipping_charge_tax'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['is_cod_required'] === null) {
            $invalidProperties[] = "'is_cod_required' can't be null";
        }
        return $invalidProperties;
    }


    /**
     * Gets is_cod_required
     *
     * @return bool
     */
    public function getIsCodRequired()
    {
        return $this->container['is_cod_required'];
    }

    /**
     * Sets is_cod_required
     *
     * @param bool $is_cod_required When true, this fulfillment order requires a COD (Cash On Delivery) payment.
     *
     * @return self
     */
    public function setIsCodRequired($is_cod_required)
    {
        $this->container['is_cod_required'] = $is_cod_required;

        return $this;
    }
    /**
     * Gets cod_charge
     *
     * @return \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null
     */
    public function getCodCharge()
    {
        return $this->container['cod_charge'];
    }

    /**
     * Sets cod_charge
     *
     * @param \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null $cod_charge cod_charge
     *
     * @return self
     */
    public function setCodCharge($cod_charge)
    {
        $this->container['cod_charge'] = $cod_charge;

        return $this;
    }
    /**
     * Gets cod_charge_tax
     *
     * @return \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null
     */
    public function getCodChargeTax()
    {
        return $this->container['cod_charge_tax'];
    }

    /**
     * Sets cod_charge_tax
     *
     * @param \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null $cod_charge_tax cod_charge_tax
     *
     * @return self
     */
    public function setCodChargeTax($cod_charge_tax)
    {
        $this->container['cod_charge_tax'] = $cod_charge_tax;

        return $this;
    }
    /**
     * Gets shipping_charge
     *
     * @return \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null
     */
    public function getShippingCharge()
    {
        return $this->container['shipping_charge'];
    }

    /**
     * Sets shipping_charge
     *
     * @param \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null $shipping_charge shipping_charge
     *
     * @return self
     */
    public function setShippingCharge($shipping_charge)
    {
        $this->container['shipping_charge'] = $shipping_charge;

        return $this;
    }
    /**
     * Gets shipping_charge_tax
     *
     * @return \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null
     */
    public function getShippingChargeTax()
    {
        return $this->container['shipping_charge_tax'];
    }

    /**
     * Sets shipping_charge_tax
     *
     * @param \SellingPartnerApi\Model\FbaOutboundV20200701\Money|null $shipping_charge_tax shipping_charge_tax
     *
     * @return self
     */
    public function setShippingChargeTax($shipping_charge_tax)
    {
        $this->container['shipping_charge_tax'] = $shipping_charge_tax;

        return $this;
    }
}


