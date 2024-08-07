<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Api\FeesApi;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Exception\ApiException;
use AmazonPHP\SellingPartner\Exception\InvalidArgumentException;

/**
 * Selling Partner API for Product Fees.
 *
 * The Selling Partner API for Product Fees lets you programmatically retrieve estimated fees for a product. You can then account for those fees in your pricing.
 *
 * The version of the OpenAPI document: v0
 *
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh
 */
interface ProductFeesSDKInterface
{
    public const API_NAME = 'ProductFees';

    public const OPERATION_GETMYFEESESTIMATEFORASIN = 'getMyFeesEstimateForASIN';

    public const OPERATION_GETMYFEESESTIMATEFORASIN_PATH = '/products/fees/v0/items/{Asin}/feesEstimate';

    public const OPERATION_GETMYFEESESTIMATEFORSKU = 'getMyFeesEstimateForSKU';

    public const OPERATION_GETMYFEESESTIMATEFORSKU_PATH = '/products/fees/v0/listings/{SellerSKU}/feesEstimate';

    public const OPERATION_GETMYFEESESTIMATES = 'getMyFeesEstimates';

    public const OPERATION_GETMYFEESESTIMATES_PATH = '/products/fees/v0/feesEstimate';

    /**
     * Operation getMyFeesEstimateForASIN.
     *
     * @param string $asin The Amazon Standard Identification Number (ASIN) of the item. (required)
     * @param \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateRequest $body body (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateResponse
     */
    public function getMyFeesEstimateForASIN(AccessToken $accessToken, string $region, string $asin, \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateRequest $body) : \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateResponse;

    /**
     * Operation getMyFeesEstimateForSKU.
     *
     * @param string $seller_sku Used to identify an item in the given marketplace. SellerSKU is qualified by the seller&#39;s SellerId, which is included with every operation that you submit. (required)
     * @param \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateRequest $body body (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateResponse
     */
    public function getMyFeesEstimateForSKU(AccessToken $accessToken, string $region, string $seller_sku, \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateRequest $body) : \AmazonPHP\SellingPartner\Model\ProductFees\GetMyFeesEstimateResponse;

    /**
     * Operation getMyFeesEstimates.
     *
     * @param \AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateByIdRequest[] $body body (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateResult[]
     */
    public function getMyFeesEstimates(AccessToken $accessToken, string $region, array $body) : array;
}
