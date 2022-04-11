<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php $login_page_id = md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."E"));?>
<?php
$_REQUEST = sanitizePostData($_REQUEST);
$feedback_hashcode	= (isset($_REQUEST['feedback_hashcode']) && !is_array($_REQUEST['feedback_hashcode']))? strtoupper($_REQUEST['feedback_hashcode']) : "";
$feedback_likes		= (isset($_REQUEST['likes']) && !is_array($_REQUEST['likes']))? $_REQUEST['likes'] : "";
$feedback_aouthv1	= (isset($_REQUEST['aouthv1']) && !is_array($_REQUEST['aouthv1']))? strtoupper($_REQUEST['aouthv1']) : "";

if($feedback_aouthv1 !="" && $feedback_hashcode !="" & $feedback_likes != "")
{
	if(strtoupper(md5(trim($feedback_hashcode))) != trim($feedback_aouthv1))
	{
		$_SESSION['msg'] = "warning|Your Feedback url is not valid. Please try again.";
		$_SESSION['FD_VALIDATION'] = false;
		header("location:".SPD_GOOGLE_REVIEW_LINK);
		exit();
	}
	else
	{
		$feedback = new Feedback();
		if($feedback_id = $feedback->isValidForSubmission($feedback_hashcode))
		{
			$_SESSION['FD_VALIDATION'] = true;	
		}
		else
		{
			$_SESSION['msg'] = "warning|Feedback url allready used or expired.";
			$_SESSION['FD_VALIDATION'] = false;
			header("location:".SPD_GOOGLE_REVIEW_LINK);
			exit();
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=$app->siteDescription?>">
<meta name="keyword" content="<?=$app->siteKeyword?>">
<meta name="site-url" content="<?=$app->siteUrl?>">
<link rel="shortcut icon" href="<?=$app->siteIcon?>">
<title>SafePcSupport Customer Feedback form</title>
<!-- Icons -->
<link href="<?=$app->cssPath('font-awesome.min')?>" rel="stylesheet">
<link href="<?=$app->cssPath('simple-line-icons')?>" rel="stylesheet">
<link href="<?=$app->cssPath('style-login')?>" rel="stylesheet">
</head>
<body class="" style="min-height:50vh;">
<div class="container">
  <div class="row">
    <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
    <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""):?>
    <?php $msg	=	explode("|",$_SESSION['msg']); unset($_SESSION['msg']);?>
    <?php endif; ?>
        <div class="msg" style="text-align:center; display:<?php echo isset($msg)?"block":"none";?>">
        	<div class="card card-inverse card-<?php echo isset($msg)?$msg[0]:"";?> text-xs-center" style="padding:8px 5px; margin:0px auto;"><?php echo isset($msg)?$msg[1]:"";?></div>
        </div>
    <?php if($_SESSION['FD_VALIDATION']) :?>
      <div class="card-group" id="card-group-form">
        <div class="card p-a-2">
          <div class="card-block">
            <h1>Submit FeedBack</h1>
            <p class="text-muted">Your feedback is importance to us</p>
            <div class="input-group m-b-1">
              <input type="text" name="feedback_title" id="feedback_title" class="form-control" placeholder="feedback title">
            </div>
            <div class="input-group m-b-2">
              <textarea name="feedback_comments" placeholder="write feedback comments..." id="feedback_comments" rows="3" class="form-control"></textarea>
              <input type="hidden" name="feedback_likes" id="feedback_likes" value="<?php echo $feedback_likes; ?>">
              <input type="hidden" name="feedback_hashcode" id="feedback_hashcode" value="<?php echo $feedback_hashcode; ?>">
              <input type="hidden" name="feedback_aouthv1" id="feedback_aouthv1" value="<?php echo $feedback_aouthv1; ?>">
            </div>
            <div class="row">
              <div class="col-xs-6">
                <button type="button" onClick="submitFeedback();" class="btn btn-primary p-x-2">Submit Feedback</button>
              </div>
              <div class="col-xs-6 text-xs-right">
                <a href="/" class="btn-link p-x-0">Back to site</a>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- Bootstrap and necessary plugins --> 
<script src="<?=$app->jsPath('jquery.min')?>"></script> 
<script src="<?=$app->jsPath('tether.min')?>"></script> 
<script src="<?=$app->jsPath('bootstrap.min')?>"></script> 
<script type="text/javascript">
        function verticalAlignMiddle()
        {
            var bodyHeight = $(window).height();
            var formHeight = $('.vamiddle').height();
            var marginTop = (bodyHeight / 2) - (formHeight / 2);
            if (marginTop > 0)
            {
                $('.vamiddle').css('margin-top', marginTop);
            }
        }
        $(document).ready(function()
        {
            verticalAlignMiddle();
        });
        $(window).bind('resize', verticalAlignMiddle);
		
		function submitFeedback()
		{
			var feedback_title		=	$("#feedback_title").val().trim();
			var feedback_comments	=	$("#feedback_comments").val().trim();
			var feedback_likes		=	$("#feedback_likes").val().trim();
			var feedback_hashcode	=	$("#feedback_hashcode").val().trim();
			if(feedback_title==""){
				$("#feedback_title").focus();
				message("danger|Please fill Title.");
				return false;
			}
			if(feedback_comments==""){
				$("#feedback_comments").focus();
				message("danger|Please Comments.");
				return false;
			}
			var data={
						action	:	'feedback/updatefeedback',
						feedback_title		:	feedback_title,
						feedback_comments	:	feedback_comments,	
						feedback_likes		:	feedback_likes,
						feedback_hashcode	:	feedback_hashcode						
					}
			$.ajax({type:'POST', data:data, url:'aouth.php', 
				
				beforeSend: function(){
					message("warning|Connecting...");
				},		
				success:function(output){
					var arr	=	JSON.parse(output);	
					if(arr[0] == 200)
					{
						$("#card-group-form").remove();
						window.location.href = arr[2];
					}				
					message(arr[1]);
				}
			})	
		}
		
		function message(msg)
		{
			$(".msg").show();
			var msg	=	msg.split("|");
			$(".msg div").removeClass("card-success card-warning card-danger ");
			$(".msg div").addClass("card-"+msg[0]);
			$(".msg div").html(msg[1]);
			
		}
        </script>
</body>
</html>