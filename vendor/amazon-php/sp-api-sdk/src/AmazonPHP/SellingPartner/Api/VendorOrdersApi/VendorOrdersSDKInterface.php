<?php declare(strict_types=1);

namespace AmazonPHP\SellingPartner\Api\VendorOrdersApi;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Exception\ApiException;
use AmazonPHP\SellingPartner\Exception\InvalidArgumentException;

/**
 * Selling Partner API for Retail Procurement Orders.
 *
 * The Selling Partner API for Retail Procurement Orders provides programmatic access to vendor orders data.
 *
 * The version of the OpenAPI document: v1
 *
 * This class was auto-generated by https://github.com/OpenAPITools/openapi-generator/.
 * Do not change it, it will be overwritten with next execution of /bin/generate.sh
 */
interface VendorOrdersSDKInterface
{
    public const API_NAME = 'VendorOrders';

    public const OPERATION_GETPURCHASEORDER = 'getPurchaseOrder';

    public const OPERATION_GETPURCHASEORDER_PATH = '/vendor/orders/v1/purchaseOrders/{purchaseOrderNumber}';

    public const OPERATION_GETPURCHASEORDERS = 'getPurchaseOrders';

    public const OPERATION_GETPURCHASEORDERS_PATH = '/vendor/orders/v1/purchaseOrders';

    public const OPERATION_GETPURCHASEORDERSSTATUS = 'getPurchaseOrdersStatus';

    public const OPERATION_GETPURCHASEORDERSSTATUS_PATH = '/vendor/orders/v1/purchaseOrdersStatus';

    public const OPERATION_SUBMITACKNOWLEDGEMENT = 'submitAcknowledgement';

    public const OPERATION_SUBMITACKNOWLEDGEMENT_PATH = '/vendor/orders/v1/acknowledgements';

    /**
     * Operation getPurchaseOrder.
     *
     * @param string $purchase_order_number The purchase order identifier for the order that you want. Formatting Notes: 8-character alpha-numeric code. (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrderResponse
     */
    public function getPurchaseOrder(AccessToken $accessToken, string $region, string $purchase_order_number) : \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrderResponse;

    /**
     * Operation getPurchaseOrders.
     *
     * @param null|int $limit The limit to the number of records returned. Default value is 100 records. (optional)
     * @param null|\DateTimeInterface $created_after Purchase orders that became available after this time will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|\DateTimeInterface $created_before Purchase orders that became available before this time will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|string $sort_order Sort in ascending or descending order by purchase order creation date. (optional)
     * @param null|string $next_token Used for pagination when there is more purchase orders than the specified result size limit. The token value is returned in the previous API call (optional)
     * @param null|bool $include_details When true, returns purchase orders with complete details. Otherwise, only purchase order numbers are returned. Default value is true. (optional)
     * @param null|\DateTimeInterface $changed_after Purchase orders that changed after this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|\DateTimeInterface $changed_before Purchase orders that changed before this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|string $po_item_state Current state of the purchase order item. If this value is Cancelled, this API will return purchase orders which have one or more items cancelled by Amazon with updated item quantity as zero. (optional)
     * @param null|bool $is_po_changed When true, returns purchase orders which were modified after the order was placed. Vendors are required to pull the changed purchase order and fulfill the updated purchase order and not the original one. Default value is false. (optional)
     * @param null|string $purchase_order_state Filters purchase orders based on the purchase order state. (optional)
     * @param null|string $ordering_vendor_code Filters purchase orders based on the specified ordering vendor code. This value should be same as &#39;sellingParty.partyId&#39; in the purchase order. If not included in the filter, all purchase orders for all of the vendor codes that exist in the vendor group used to authorize the API client application are returned. (optional)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrdersResponse
     */
    public function getPurchaseOrders(AccessToken $accessToken, string $region, ?int $limit = null, ?\DateTimeInterface $created_after = null, ?\DateTimeInterface $created_before = null, ?string $sort_order = null, ?string $next_token = null, ?bool $include_details = null, ?\DateTimeInterface $changed_after = null, ?\DateTimeInterface $changed_before = null, ?string $po_item_state = null, ?bool $is_po_changed = null, ?string $purchase_order_state = null, ?string $ordering_vendor_code = null) : \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrdersResponse;

    /**
     * Operation getPurchaseOrdersStatus.
     *
     * @param null|int $limit The limit to the number of records returned. Default value is 100 records. (optional)
     * @param null|string $sort_order Sort in ascending or descending order by purchase order creation date. (optional)
     * @param null|string $next_token Used for pagination when there are more purchase orders than the specified result size limit. (optional)
     * @param null|\DateTimeInterface $created_after Purchase orders that became available after this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|\DateTimeInterface $created_before Purchase orders that became available before this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|\DateTimeInterface $updated_after Purchase orders for which the last purchase order update happened after this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|\DateTimeInterface $updated_before Purchase orders for which the last purchase order update happened before this timestamp will be included in the result. Must be in ISO-8601 date/time format. (optional)
     * @param null|string $purchase_order_number Provides purchase order status for the specified purchase order number. (optional)
     * @param null|string $purchase_order_status Filters purchase orders based on the specified purchase order status. If not included in filter, this will return purchase orders for all statuses. (optional)
     * @param null|string $item_confirmation_status Filters purchase orders based on their item confirmation status. If the item confirmation status is not included in the filter, purchase orders for all confirmation statuses are included. (optional)
     * @param null|string $item_receive_status Filters purchase orders based on the purchase order&#39;s item receive status. If the item receive status is not included in the filter, purchase orders for all receive statuses are included. (optional)
     * @param null|string $ordering_vendor_code Filters purchase orders based on the specified ordering vendor code. This value should be same as &#39;sellingParty.partyId&#39; in the purchase order. If not included in filter, all purchase orders for all the vendor codes that exist in the vendor group used to authorize API client application are returned. (optional)
     * @param null|string $ship_to_party_id Filters purchase orders for a specific buyer&#39;s Fulfillment Center/warehouse by providing ship to location id here. This value should be same as &#39;shipToParty.partyId&#39; in the purchase order. If not included in filter, this will return purchase orders for all the buyer&#39;s warehouses used for vendor group purchase orders. (optional)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrdersStatusResponse
     */
    public function getPurchaseOrdersStatus(AccessToken $accessToken, string $region, ?int $limit = null, ?string $sort_order = null, ?string $next_token = null, ?\DateTimeInterface $created_after = null, ?\DateTimeInterface $created_before = null, ?\DateTimeInterface $updated_after = null, ?\DateTimeInterface $updated_before = null, ?string $purchase_order_number = null, ?string $purchase_order_status = null, ?string $item_confirmation_status = null, ?string $item_receive_status = null, ?string $ordering_vendor_code = null, ?string $ship_to_party_id = null) : \AmazonPHP\SellingPartner\Model\VendorOrders\GetPurchaseOrdersStatusResponse;

    /**
     * Operation submitAcknowledgement.
     *
     * @param \AmazonPHP\SellingPartner\Model\VendorOrders\SubmitAcknowledgementRequest $body body (required)
     *
     * @throws ApiException on non-2xx response
     * @throws InvalidArgumentException
     *
     * @return \AmazonPHP\SellingPartner\Model\VendorOrders\SubmitAcknowledgementResponse
     */
    public function submitAcknowledgement(AccessToken $accessToken, string $region, \AmazonPHP\SellingPartner\Model\VendorOrders\SubmitAcknowledgementRequest $body) : \AmazonPHP\SellingPartner\Model\VendorOrders\SubmitAcknowledgementResponse;
}