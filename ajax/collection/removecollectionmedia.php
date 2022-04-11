<?php

    $wc_id             = $record_id = 0;
    $record_status     = 1;
    $record_deleted_by = $_SESSION['user_id'];
    $data              = sanitizePostData($_POST);
    extract($data);
    $collection        = new Collection($wc_id);
    $wcData            = $collection->load();
    $wcRecordMedia     = new WcRecordMedia($record_id);
    $wcRecordMediaData = $wcRecordMedia->getDetails();
    if ($wcRecordMediaData) {
        $delFilePathLink = pathinfo($wcRecordMediaData['record_file_path'], PATHINFO_DIRNAME) . "/del/";
        $delFilePath     = $delFilePathLink . basename($wcRecordMediaData['record_file_path']);
        
        if (move_file($app->sitePath($wcRecordMediaData['record_file_path']), $app->sitePath($delFilePath))) {
            $wcRecordMedia->update(array("record_is_deleted" => 1,
                "record_deleted_by" => $record_deleted_by,
                "record_file_path" => $delFilePath
            ));
            unlink($app->sitePath($wcRecordMediaData['record_file_path']));
            Activity::add("removed Media Snapshot|^|{$wcData['wc_code']}", "W", $wc_id);
            echo json_encode(array("200",  "success|Collection Record removed successfully."
            ));
        } else
            echo json_encode(array("300", "danger|Unable to delete Record. Please try again"
            ));
    } else
        echo json_encode(array("300", "error|Collection Record not Exists or No Collection media file found."
        ));
?>