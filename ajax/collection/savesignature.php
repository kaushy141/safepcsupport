<?php

    $wc_id = 0;
    $data  = sanitizePostData($_POST);
    extract($data);
    $signature   = str_replace('data:image/png;base64,', '', $signature);
    $signature   = str_replace(' ', '+', $signature);
    $signature   = base64_decode($signature);
    $filePath    = 'upload/temp/' . time() . '.png';
    $fileBaseUrl = $app->sitePath($filePath);
    $fileUrl     = $app->basePath($filePath);
    if (file_put_contents($fileBaseUrl, $signature))
        echo json_encode(array("200",  "success|Signature Saved successfully",
            $filePath,
            $fileUrl
        ));
    else
        echo json_encode(array("300", "danger|Ooops..Signature could not saved on server"
        ));

?>