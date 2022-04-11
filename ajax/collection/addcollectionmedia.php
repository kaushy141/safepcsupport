<?php

    $wc_id              = 0;
    $record_file_name   = $record_file_path = $record_remark = "";
    $record_status      = 1;
    $record_added_by    = $user_id = $_SESSION['user_id'];
    $emp                = new Employee($user_id);
    $record_media_array = array();
    $data               = sanitizePostData($_POST);
    extract($data);
    $collection = new Collection($wc_id);
    $wcData     = $collection->load();
    $wcRecord   = new WcRecord(0);
    if ($wcData && !empty($record_media_array)) {
        foreach ($record_media_array as $media) {
            if (file_exists($app->sitePath($media))) {
                $image_name       = pathinfo($app->sitePath($media));
                $extension        = strtolower($image_name['extension']);
                $record_file_path = "upload/collection/record/" . $wcData['wc_code'] . "_" . time() . ".$extension";
                if (rename($app->sitePath($media), $app->sitePath($record_file_path))) {
                    $wcRecord->add($wc_id, $record_file_name, $record_file_path, $record_added_by, $record_remark, $record_status);
                    unlink($app->sitePath($media));
                } else {
                    echo json_encode(array("300", "Error|Ooops... Some Uploaded media file \"$record_file_name\" does not exist or removed."
                    ));
                    exit;
                }
            }
        }
        Activity::add("added Media Snapshot|^|{$wcData['wc_code']}", "W", $wc_id);
        echo json_encode(array("200",  "Success|Collection Record media file added successfully."
        ));
    } else
        echo json_encode(array("300", "Error|Collection Record not Exists or No Collection media file found."
        ));

?>