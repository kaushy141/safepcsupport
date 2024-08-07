<?php
/**
 * ServiceUploadDocument
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  SellingPartnerApi
 */

/**
 * Selling Partner API for Services
 *
 * With the Services API, you can build applications that help service providers get and modify their service orders and manage their resources.
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

namespace SellingPartnerApi\Model\ServiceV1;
use ArrayAccess;
use SellingPartnerApi\Model\BaseModel;
use SellingPartnerApi\Model\ModelInterface;

/**
 * ServiceUploadDocument Class Doc Comment
 *
 * @category Class
 * @description Input for to be uploaded document.
 * @package  SellingPartnerApi
 * @group 
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class ServiceUploadDocument extends BaseModel implements ModelInterface, ArrayAccess, \JsonSerializable, \IteratorAggregate
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ServiceUploadDocument';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'content_type' => 'string',
        'content_length' => 'float',
        'content_md5' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'content_type' => null,
        'content_length' => 'int64',
        'content_md5' => null
    ];



    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'content_type' => 'contentType',
        'content_length' => 'contentLength',
        'content_md5' => 'contentMD5'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'content_type' => 'setContentType',
        'content_length' => 'setContentLength',
        'content_md5' => 'setContentMd5'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'content_type' => 'getContentType',
        'content_length' => 'getContentLength',
        'content_md5' => 'getContentMd5'
    ];



    const CONTENT_TYPE_TIFF = 'TIFF';
    const CONTENT_TYPE_JPG = 'JPG';
    const CONTENT_TYPE_PNG = 'PNG';
    const CONTENT_TYPE_JPEG = 'JPEG';
    const CONTENT_TYPE_GIF = 'GIF';
    const CONTENT_TYPE_PDF = 'PDF';
    
    

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getContentTypeAllowableValues()
    {
        $baseVals = [
            self::CONTENT_TYPE_TIFF,
            self::CONTENT_TYPE_JPG,
            self::CONTENT_TYPE_PNG,
            self::CONTENT_TYPE_JPEG,
            self::CONTENT_TYPE_GIF,
            self::CONTENT_TYPE_PDF,
        ];

        // This is necessary because Amazon does not consistently capitalize their
        // enum values, so we do case-insensitive enum value validation in ObjectSerializer
        return array_map(function ($val) { return strtoupper($val); }, $baseVals);
    }
    
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
        $this->container['content_type'] = $data['content_type'] ?? null;
        $this->container['content_length'] = $data['content_length'] ?? null;
        $this->container['content_md5'] = $data['content_md5'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];
        if ($this->container['content_type'] === null) {
            $invalidProperties[] = "'content_type' can't be null";
        }
        $allowedValues = $this->getContentTypeAllowableValues();
        if (
            !is_null($this->container['content_type']) &&
            !in_array(strtoupper($this->container['content_type']), $allowedValues, true)
        ) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'content_type', must be one of '%s'",
                $this->container['content_type'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['content_length'] === null) {
            $invalidProperties[] = "'content_length' can't be null";
        }
        if (($this->container['content_length'] > 5.24288E+6)) {
            $invalidProperties[] = "invalid value for 'content_length', must be smaller than or equal to 5.24288E+6.";
        }

        if (($this->container['content_length'] < 1)) {
            $invalidProperties[] = "invalid value for 'content_length', must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['content_md5']) && !preg_match("/^[A-Za-z0-9\\\\+\/]{22}={2}$/", $this->container['content_md5'])) {
            $invalidProperties[] = "invalid value for 'content_md5', must be conform to the pattern /^[A-Za-z0-9\\\\+\/]{22}={2}$/.";
        }

        return $invalidProperties;
    }


    /**
     * Gets content_type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->container['content_type'];
    }

    /**
     * Sets content_type
     *
     * @param string $content_type The content type of the to-be-uploaded file
     *
     * @return self
     */
    public function setContentType($content_type)
    {
        $allowedValues = $this->getContentTypeAllowableValues();
        if (!in_array(strtoupper($content_type), $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'content_type', must be one of '%s'",
                    $content_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['content_type'] = $content_type;

        return $this;
    }
    /**
     * Gets content_length
     *
     * @return float
     */
    public function getContentLength()
    {
        return $this->container['content_length'];
    }

    /**
     * Sets content_length
     *
     * @param float $content_length The content length of the to-be-uploaded file
     *
     * @return self
     */
    public function setContentLength($content_length)
    {

        if (($content_length > 5.24288E+6)) {
            throw new \InvalidArgumentException('invalid value for $content_length when calling ServiceUploadDocument., must be smaller than or equal to 5.24288E+6.');
        }
        if (($content_length < 1)) {
            throw new \InvalidArgumentException('invalid value for $content_length when calling ServiceUploadDocument., must be bigger than or equal to 1.');
        }

        $this->container['content_length'] = $content_length;

        return $this;
    }
    /**
     * Gets content_md5
     *
     * @return string|null
     */
    public function getContentMd5()
    {
        return $this->container['content_md5'];
    }

    /**
     * Sets content_md5
     *
     * @param string|null $content_md5 An MD5 hash of the content to be submitted to the upload destination. This value is used to determine if the data has been corrupted or tampered with during transit.
     *
     * @return self
     */
    public function setContentMd5($content_md5)
    {

        if (!is_null($content_md5) && (!preg_match("/^[A-Za-z0-9\\\\+\/]{22}={2}$/", $content_md5))) {
            throw new \InvalidArgumentException("invalid value for $content_md5 when calling ServiceUploadDocument., must conform to the pattern /^[A-Za-z0-9\\\\+\/]{22}={2}$/.");
        }

        $this->container['content_md5'] = $content_md5;

        return $this;
    }
}


