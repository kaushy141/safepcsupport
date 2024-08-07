<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Api\SmallAndLightApi;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Exception\ApiException;
use AmazonPHP\SellingPartner\Exception\InvalidArgumentException;

/**
 * Selling Partner API for FBA Small And Light.
 *
 * The Selling Partner API for FBA Small and Light lets you help sellers manage their listings in the Small and Light program. The program reduces the cost of fulfilling orders for small and lightweight FBA inventory. You can enroll or remove items from the program and check item eligibility and enrollment status. You can also preview the estimated program fees charged to a seller for items sold while enrolled in the program.
 *
 * The version of the OpenAPI document: v1
 *
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh
 */
interface FBASmallAndLightSDKInterface
{
    public const API_NAME = 'FBASmallAndLight';

    public const OPERATION_DELETESMALLANDLIGHTENROLLMENTBYSELLERSKU = 'deleteSmallAndLightEnrollmentBySellerSKU';

    public const OPERATION_DELETESMALLANDLIGHTENROLLMENTBYSELLERSKU_PATH = '/fba/smallAndLight/v1/enrollments/{sellerSKU}';

    public const OPERATION_GETSMALLANDLIGHTELIGIBILITYBYSELLERSKU = 'getSmallAndLightEligibilityBySellerSKU';

    public const OPERATION_GETSMALLANDLIGHTELIGIBILITYBYSELLERSKU_PATH = '/fba/smallAndLight/v1/eligibilities/{sellerSKU}';

    public const OPERATION_GETSMALLANDLIGHTENROLLMENTBYSELLERSKU = 'getSmallAndLightEnrollmentBySellerSKU';

    public const OPERATION_GETSMALLANDLIGHTENROLLMENTBYSELLERSKU_PATH = '/fba/smallAndLight/v1/enrollments/{sellerSKU}';

    public const OPERATION_GETSMALLANDLIGHTFEEPREVIEW = 'getSmallAndLightFeePreview';

    public const OPERATION_GETSMALLANDLIGHTFEEPREVIEW_PATH = '/fba/smallAndLight/v1/feePreviews';

    public const OPERATION_PUTSMALLANDLIGHTENROLLMENTBYSELLERSKU = 'putSmallAndLightEnrollmentBySellerSKU';

    public const OPERATION_PUTSMALLANDLIGHTENROLLMENTBYSELLERSKU_PATH = '/fba/smallAndLight/v1/enrollments/{sellerSKU}';

    /**
     * Operation deleteSmallAndLightEnrollmentBySellerSKU.
     *
     * @param string $seller_sku The seller SKU that identifies the item. (required)
     * @param string[] $marketplace_ids The marketplace in which to remove the item from the Small and Light program. Note: Accepts a single marketplace only. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     */
    public function deleteSmallAndLightEnrollmentBySellerSKU(AccessToken $accessToken, string $region, string $seller_sku, array $marketplace_ids);

    /**
     * Operation getSmallAndLightEligibilityBySellerSKU.
     *
     * @param string $seller_sku The seller SKU that identifies the item. (required)
     * @param string[] $marketplace_ids The marketplace for which the eligibility status is retrieved. NOTE: Accepts a single marketplace only. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEligibility
     */
    public function getSmallAndLightEligibilityBySellerSKU(AccessToken $accessToken, string $region, string $seller_sku, array $marketplace_ids) : \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEligibility;

    /**
     * Operation getSmallAndLightEnrollmentBySellerSKU.
     *
     * @param string $seller_sku The seller SKU that identifies the item. (required)
     * @param string[] $marketplace_ids The marketplace for which the enrollment status is retrieved. Note: Accepts a single marketplace only. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEnrollment
     */
    public function getSmallAndLightEnrollmentBySellerSKU(AccessToken $accessToken, string $region, string $seller_sku, array $marketplace_ids) : \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEnrollment;

    /**
     * Operation getSmallAndLightFeePreview.
     *
     * @param \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightFeePreviewRequest $body body (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightFeePreviews
     */
    public function getSmallAndLightFeePreview(AccessToken $accessToken, string $region, \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightFeePreviewRequest $body) : \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightFeePreviews;

    /**
     * Operation putSmallAndLightEnrollmentBySellerSKU.
     *
     * @param string $seller_sku The seller SKU that identifies the item. (required)
     * @param string[] $marketplace_ids The marketplace in which to enroll the item. Note: Accepts a single marketplace only. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEnrollment
     */
    public function putSmallAndLightEnrollmentBySellerSKU(AccessToken $accessToken, string $region, string $seller_sku, array $marketplace_ids) : \AmazonPHP\SellingPartner\Model\FBASmallAndLight\SmallAndLightEnrollment;
}
