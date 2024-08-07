<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Api\VendorTransactionApi;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Exception\ApiException;
use AmazonPHP\SellingPartner\Exception\InvalidArgumentException;

/**
 * Selling Partner API for Retail Procurement Transaction Status.
 *
 * The Selling Partner API for Retail Procurement Transaction Status provides programmatic access to status information on specific asynchronous POST transactions for vendors.
 *
 * The version of the OpenAPI document: v1
 *
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh
 */
interface VendorTransactionStatusSDKInterface
{
    public const API_NAME = 'VendorTransactionStatus';

    public const OPERATION_GETTRANSACTION = 'getTransaction';

    public const OPERATION_GETTRANSACTION_PATH = '/vendor/transactions/v1/transactions/{transactionId}';

    /**
     * Operation getTransaction.
     *
     * @param string $transaction_id The GUID provided by Amazon in the &#39;transactionId&#39; field in response to the post request of a specific transaction. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\VendorTransactionStatus\GetTransactionResponse
     */
    public function getTransaction(AccessToken $accessToken, string $region, string $transaction_id) : \AmazonPHP\SellingPartner\Model\VendorTransactionStatus\GetTransactionResponse;
}
