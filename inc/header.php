<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $app->siteDescription ?>">
  <meta name="keyword" content="<?= $app->siteKeyword ?>">
  <meta name="site-url" content="<?= $app->siteUrl ?>">
  <link rel="shortcut icon" href="<?= $app->siteIcon ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
  <title>
    <?= $app->siteTitle ?>
  </title>
  <?php
  $navbarBgColors = [
    "4a235a",
    "154360",
    "641e16",
    "0b5345",
    "1b2631",
    "263238",
  ];
  shuffle($navbarBgColors);
  $bgColorCode = array_pop($navbarBgColors);
  ?>
  <!-- Icons -->
  <?php if (isset($_SESSION["app_theme"])) {
    array_push($autoloadCSS, "theme_{$_SESSION["app_theme"]}");
  } ?>
  <?php echo loadCSS(); ?>
  <script src="https://cdn.tiny.cloud/1/ybmyg6hp9ngvs0m74tchwvzykdawgro75n29ttwgisapc3us/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

  <script type="text/javascript">
    var sitePath = '<?php echo $app->basePath(); ?>';
    var siteTitle = '<?php echo $app->siteTitle; ?>';
    var logoutUrl = '<?php echo $app->siteUrl("logout"); ?>';
    var userId = '<?php echo getLoginId(); ?>';
    var userName = '<?php echo getUserType() == "E"
                      ? $_SESSION["user_fname"] . " " . $_SESSION["user_lname"]
                      : $_SESSION["customer_fname"] . " " . $_SESSION["customer_lname"]; ?>';
    var userImage = '<?= getResizeImage(
                        getUserType() == "E"
                          ? $_SESSION["user_image"]
                          : $_SESSION["customer_image"],
                        50
                      ) ?>';
    var userType = '<?php echo getUserType(); ?>';
    var app_filter_state = <?php echo isset($_SESSION["app_filter_state"])
                              ? $_SESSION["app_filter_state"]
                              : 0; ?>;
    var VERSION = '<?php echo VERSION; ?>';
    window.localStorage.setItem("user_sign_in", true);
    var complaint_log_offset_count = 0;
    var user_tag_id = 0;

    var isDataTableRunning = false;
    var enableChatAutoLoad = false;
    var complaintChatAutoLoadIntervalTimeOut = 10000;
    var complaintChatAutoLoadFlag = false;
    var complaintChatAutoLoadInterval = null;
    var mentionUserData = null;

    var pasteImageBlob = null;
    var bottomNotificationStack = [];

    var logOffTimePassValue = <?php echo isset($_COOKIE["LOGOFF"]) &&
                                isset($_COOKIE["LOGOFFTIME"])
                                ? time() - $_COOKIE["LOGOFFTIME"]
                                : 0; ?>;

    var enableComplaintChatAutoLoadCount = false;
    var countComplaintChatAutoLoadIntervalTimeOut = 10000;
    var countComplaintChatAutoLoadFlag = false;
    var countComplaintChatAutoLoadInterval = null;
    var countUserTagAutoLoad = <?php echo isAdmin() ? "true" : "false"; ?>;
    <?php $navbar = new Navbar(); ?>



    var LIVE_CHAT_ENABLE_STATUS = <?php echo LIVE_CHAT_ENABLE_STATUS
                                    ? "true"
                                    : "false"; ?>;
    var CHASE_CUTOMER_NOTIFICATION = <?php echo Employee::isUserServiceEnabled(
                                        getLoginId(),
                                        "user_show_chase_customer"
                                      ) && getUserType() == "E"
                                        ? "true"
                                        : "false"; ?>;
    var JIVO_CHAT_EVENT_ENABLED = true;
    var FETCH_PAID_INVOICE_ENABLED = true;

    var USER_CAN_SEE_ORDER_NOTIFICATION = <?php echo $navbar->isModulePemissionAllow(
                                            82
                                          )
                                            ? 1
                                            : 0; ?>;
    var ORDER_NOTIFICATION_SOUND = sitePath + 'assets/audio/order_notification.mp3';
    var USER_ONLINE_NOTIF_SOUND = sitePath + 'assets/audio/user_online.mp3';
    var USER_SESSION_EXPIRE_SOUND = sitePath + 'assets/audio/session-expire-alarm.mp3';
    var USER_NEW_CUSTOMER_SITE_SOUND = sitePath + 'assets/audio/new_customer_on_website.mp3';
    var SALES_INVOICE_PAID_SUCCESS = sitePath + 'assets/audio/sales_invoice_paid_success.mp3';

    var MAX_AJAX_REQUEST_RETRING_ATTEMPT = 4;
    var MAX_AJAX_REQUEST_RETRING_ATTEMPT_COUNT = 0;
    var LOADING_HTML = '<div class="loading_box text-center mt-2 mb-2"><br/><br/><center><span style="color:#C0C0C0;"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span><br/><br/>Loading... </span></center></div>';
    var EMPTY_IMAGE_BOX = '<div class="empty_image_box col-sm-12 col-md-12 text-center pt-3 pb-3" style="color:#EAEAEA;"><i class="fa fa-image fa-4x"></i><br/>No media files found.</div>';
    var EMPTY_TEXT_BOX = '<div class="empty_text_box col-sm-12 col-md-12 text-center pt-3 pb-3"><i class="fa fa-comment-o fa-4x"></i><br/>Be the first. No record found</div>'
    var globalEditor = null;


    var AJAX_REQUEST_MAX_TIME = <?php echo AJAX_REQUEST_MAX_TIME; ?>;

    var GOOGLE_MAP_API_KEY = '<?php echo GOOGLE_MAP_API_KEY; ?>';
    var GOOGLE_FIREBASE_API_KEY = '<?php echo GOOGLE_FIREBASE_API_KEY; ?>';
    var GOOGLE_FIREBASE_API_AUTH_DOMAIN = '<?php echo GOOGLE_FIREBASE_API_AUTH_DOMAIN; ?>';
    var GOOGLE_FIREBASE_API_DATABASE_URL = '<?php echo GOOGLE_FIREBASE_API_DATABASE_URL; ?>';
    var GOOGLE_FIREBASE_API_PROJECT_ID = '<?php echo GOOGLE_FIREBASE_API_PROJECT_ID; ?>';
    var GOOGLE_FIREBASE_API_STORAGE_BUCKET = '<?php echo GOOGLE_FIREBASE_API_STORAGE_BUCKET; ?>';
    var GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID = '<?php echo GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID; ?>';
    var GOOGLE_FIREBASE_API_APP_ID = '<?php echo GOOGLE_FIREBASE_API_APP_ID; ?>';
    var GOOGLE_FIREBASE_API_MEASUREMENT_ID = '<?php echo GOOGLE_FIREBASE_API_MEASUREMENT_ID; ?>';
  </script>
  <link rel="manifest" href="/manifest.json">


  <script src="https://kit.fontawesome.com/957a43b0fe.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-analytics.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-firestore.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-database.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-messaging.js"></script>

  <script id="mcjs">
    ! function(c, h, i, m, p) {
      m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
    }(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/0489299c62c36b01ead938fdf/3f285fd842d57baf698c28a7b.js");
  </script>
</head>

<div id="session_live_popup" style="z-index:10000; display:none; position:fixed; top:0px; bottom:0px; left:0px; right:0px; background:rgba(0,0,0,0.85);">
  <div style="position:relative; margin:10% auto; max-width:600px; width:90%; border-radius:2px; background:#FFF; min-height:200px;">
    <div style="padding:10px 20px; background:#FE2333; color:#FFF; font-size:18px;"><i class="fa fa-warning"></i> Warning :: Session about to expire...</div>
    <div style="padding:30px 20px; color:#444; font-size:16px; text-align:center;"> Hi <b><?= $_SESSION["user_fname"] ?></b>, Your Session will be expired in <br />
      <span id="session_live_count" style="font-size:24px;">99</span><br />
      seconds
    </div>
    <div style="padding:0px 20px 70px; font-size:16px; text-align:center;">
      <div style="max-width:300px; margin:0px auto;">
        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><a id="keppmelivesession" href="#" class="btn btn-success btn-block">Keep Live</a></div>
        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><a id="logedmeoutsession" href="#" class="btn btn-danger btn-block">Logout</a></div>
      </div>
    </div>
  </div>
</div>

<?php if (isAdmin() && !isset($_SESSION["is_good_morning"])) { ?>
  <div id="good_morning_popup" style="z-index:9999;  background:rgba(0,0,0,0.85); display:block; position:fixed; top:0px; bottom:0px; left:0px; right:0px;">
    <div style="position:relative; margin:8% auto; max-width:500px; border-radius: 3px; width:90%; background:#FFF; min-height:100px; 
background-position: bottom;
background-size: cover;
background-repeat: no-repeat;">

      <div style="padding:30px 5px 20px; color:#444; font-size:16px; text-align:center;">
        <div style="padding-bottom:20px; text-align:center;">
          <img class="img-avatar" src="<?= getResizeImage(
                                          $_SESSION["user_image"],
                                          50
                                        ) ?>">
        </div>
        <?php if (date("H") > 18) {
          $wish = "Evening";
        } elseif (date("H") > 12) {
          $wish = "Afternoon";
        } else {
          $wish = "Morning";
        } ?>
        Good <?php echo $wish; ?>, <b><?= $_SESSION["user_fname"] ?></b>
      </div>
      <div style="padding:5px 20px 60px; font-size:16px; text-align:center;">
        <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
          <a id="goodmorningclose" href="#" onclick="closeGoodMornig();" class="btn btn-info">Close</a>
        </div>
      </div>
    </div>

  </div>
  <script type="text/javascript">
    function closeGoodMornig() {
      $("#good_morning_popup").remove();
    }
  </script>
<?php $_SESSION["is_good_morning"] = true;
} ?>