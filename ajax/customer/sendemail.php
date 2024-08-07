<?php

Activity::add("Email sent to selected " . count($customers) . " customers");
// echo json_encode(array(
//     "200",  "success|Email sent to selected " . count($customers) . " customers"
// ));
// exit();

$customers = array();
$content = '';
extract(App::exploreApiCredentilas('SendLayer'));
$data           = sanitizePostData($_POST);
extract($data);

$subject = $subject ?? "Protect Your Data: Book Our Secure IT Equipment Recycling Service";

$sender_email = "info@safepcdisposal.co.uk";
$sender_name = "Team Collection";

$cc_email = "info@safepcdisposal.co.uk";
$cc_name = "Team Collection";

$bcc_email = "info@safepcdisposal.co.uk";
$bcc_name = "Team Collection";

$reply_email = "info@safepcdisposal.co.uk";
$reply_name = "Team Collection";
try {
    if (count($customers)) {
        foreach ($customers as $customer_id) {
            $customer       = new Customer($customer_id);
            $customerData  = $customer->getDetails();
            $apiData = array(
                "From" => [
                    "name" => $sender_name,
                    "email" => $sender_email
                ],
                "To" => [
                    [
                        "name" => $customerData['customer_fname'] . " " . $customerData['customer_lname'],
                        "email" => $customerData['customer_email']
                    ]
                ],
                "CC" => [
                    [
                        "name" => $cc_name,
                        "email" => $cc_email
                    ]
                ],
                "BCC" => [
                    [
                        "name" => $bcc_name,
                        "email" => $bcc_email
                    ]
                ],
                "ReplyTo" => [
                    [
                        "name" => $reply_name,
                        "email" => $reply_email
                    ]
                ],
                "Subject" => $subject,
                "ContentType" => "HTML",
                "HTMLContent" => evalString($content, "customer_name", $customerData['customer_fname'] . " " . $customerData['customer_lname'], "b"),
                "Tags" => [
                    "Secure Logistics, Repair & Refurbishment, Free WEEE Disposal, Secure IT Recycling, Computer Recycling, IT Asset Auditing"
                ],
                "Headers" => [
                    "X-Mailer" => "SafePcDisposal",
                    "X-Test" => "SafePcDisposal Collection"
                ],
                // "Attachments" => [
                //     [
                //         "Content" => "BASE 64 ENCODED STRING",
                //         "Type" => "image/png",
                //         "Filename" => "test.png",
                //         "Disposition" => "attachment",
                //         "ContentId" => 0
                //     ]
                // ]
            );
            $fields = json_encode($apiData);
            $url = "https://console.sendlayer.com/api/v1/email";
            $headers = array(
                'Authorization: Bearer ' . $ApiKey,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $result = curl_exec($ch);
            $response = json_decode($result, true);

            $emailLog       = new EmailLog();
            $emailLog->insert(array("service_name" => "Sendlayer", "message_id" => $response['MessageID'] ?? "NULL", "from_email" => $sender_email, "to_email" => $customerData['customer_email'], "subject" => $subject, "user_id" => $_SESSION['user_id'], "status" =>  $response['MessageID'] ? 1 : 0));
        }
    }


    Activity::add("Email sent to selected " . count($customers) . " customers");
    echo json_encode(array(
        "200",  "success|Email sent to selected " . count($customers) . " customers"
    ));
} catch (Exception $e) {
    echo json_encode(array(
        "400",  "success|Unable to sent email to selected " . count($customers) . " customers"
    ));
}
