<?php
include("../../../setup.php");
// Key and Secret from AWS IAM
define('IAM_USER_KEY', 'YOUR_DATA_HERE');
define('IAM_USER_SECRET', 'YOUR_DATA_HERE');

// ID, Secret, and Refresh Token from Developer Central App
define('APP_LWA_ID', 'amzn1.application-oa2-client.994329e3c5114b2db6e0be721bb6f6d1');
define('APP_LWA_SECRET', 'amzn1.oa2-cs.v1.79ee57f2e6778754a5f51faeb9f5fe09ac0358525202435515b9dfeae7c8016d');
define('APP_REFRESH_TOKEN', 'Atzr|IwEBIKy6SZbMwGBwFpffTu80hChIZEuFaUQON8zHUxfgYfRxkh-mOshRrsc7k4nOTsUSFrMNO9jqtf5Xu5dWoxA72lGv-0evdwEB63gWonuH7hSOvg4fm17w_jzfBZxOAlWzkvVXSo4NVVPR6vfXC6XoLq_9c38nacyMqQyVfwI7exf43pwe-d3C-vXbeTwe9KXngMiq09yeeQv4rjSOTRYPZZh02M75WkKRuDQiR1fyFkBIdpr7JXA5lU8SCZGeYZRZ3MgYHOwOtfAZgTWv3QLP7NHQDnCwObkkNztL5KBTd6vqiDhYR4Ag2TIRAIrN1r-N0p7gWmly_uegx2jOpYeb-K3w');

// Path to secure token cache file
define('TOKEN_CACHE', 'amazon-access-token.json');

// Every request should have a user agent included, here is an example one
define('USER_AGENT', 'My Pricing Tool/1.0 (Language=PHP)');

// The host, endpoint, region, and marketplace ID (change HOST, REGION, and MP_ID if not in US)
define('HOST', 'sellingpartnerapi-eu.amazon.com');
define('ENDPOINT', 'https://' . HOST);
define('REGION', 'eu-west-2');
define('MP_ID', 'A1F83G8C2ARO7P');

// Include the Amazon SP-API functions
include 'functions.php';

// //Run a test request (enter an ASIN)
// echo '<pre>';
// print_r(getOrders(25));
// echo '</pre>';
// die;

if(isset($_POST['order_id']) && !is_array($_POST['order_id']) && $_POST['order_id'] != '')
{
    $order = getOrderInfo($_POST['order_id']);
    $orders = [$order];
    
    if(!empty($orders)){
        foreach($orders as $_order){
            processOrder($order);
        }
    }
    echo json_encode(['200', "success|Order fetched successfully"]);
}else{
    $days = (isset($_GET['days']) && !is_array($_GET['days']) && $_GET['days'] !='') ? intval($_GET['days']):2;
    $ordersPayload = getOrders($days);
    $orders= $ordersPayload['payload']['Orders'] ?? [];
    if(!empty($orders)){
        foreach($orders as $_order){
            $order = getOrderInfo($_order['AmazonOrderId']);
            processOrder($order);
            sleep(1);
        }
    }
}
