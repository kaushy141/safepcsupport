<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php
$_SESSION['FD_SUBSCRIPTION'] = true;
if(isset($_REQUEST) && count($_REQUEST))
$_REQUEST = sanitizePostData($_REQUEST);
$hash	= (isset($_REQUEST['hash']) && !is_array($_REQUEST['hash']))? strtoupper($_REQUEST['hash']) : "";
$email		= (isset($_REQUEST['email']) && !is_array($_REQUEST['email']))? $_REQUEST['email'] : "";
$time		= (isset($_REQUEST['time']) && !is_array($_REQUEST['time']))? $_REQUEST['time'] : "";

if($hash !="" && $email !="")
{
	if(trim(strtolower($hash)) != trim(md5(strtolower($email).$time)))
	{
		$_SESSION['msg'] = "warning|Your subscribe url is not valid.";
		$_SESSION['FD_SUBSCRIPTION'] = false;
		header("location:index.php");
		exit();
	}
	else
	{
		$_SESSION['FD_SUBSCRIPTION'] = true;		
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
<title>SafePcSupport Customer Unsubscrib form</title>
<!-- Icons -->
<link href="<?=$app->cssPath('font-awesome.min')?>" rel="stylesheet">
<link href="<?=$app->cssPath('simple-line-icons')?>" rel="stylesheet">
<link href="<?=$app->cssPath('style-login')?>" rel="stylesheet">
</head>
<body style="min-height:20vh;">
<div class="container">
  <div class="row">
    <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
    <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""):?>
    <?php $msg	=	explode("|",$_SESSION['msg']); unset($_SESSION['msg']);?>
    <?php endif; ?>
        <div class="msg" style="text-align:center; display:<?php echo isset($msg)?"block":"none";?>">
        	<div class="card card-inverse card-<?php echo isset($msg)?$msg[0]:"";?> text-xs-center" style="padding:8px 5px; margin:0px auto;"><?php echo isset($msg)?$msg[1]:"";?></div>
        </div>
    <?php if($_SESSION['FD_SUBSCRIPTION']) :?>
      <div class="card-group" id="card-group-form">
        <div class="card p-a-2">
          <div class="card-block">
            <h1>Email Unsubscription</h1>
            <div class="input-group m-b-1">
            	<p>
                <select class="form-control" style="width:100%;" id="subscribe_resion" name="subscribe_resion">
                    <option value=""> -- Please select reason -- </option>
                    <option value="Your emails are not relevant to me">Your emails are not relevant to me</option>
                    <option value="Your emails are too frequent">Your emails are too frequent</option>
                    <option value="I don't remember signing up for this">I don't remember signing up for this</option>
                    <option value="I no longer want to receive these emails">I no longer want to receive these emails</option>
                    <option value="The emails are spam and should be reported">The emails are spam and should be reported</option>
                    <option value="others">Others</option>
                </select>
                </p>
            </div>
            
            <p class="text-muted">Your Remark is importance to us</p>
            <div class="input-group m-b-2">
              <textarea name="subscribe_remark" placeholder="write tell us why you want to unsubscribe our email service..." id="subscribe_remark" rows="3" maxlength="500" class="form-control"></textarea>
              <input type="hidden" name="subscribe_email" id="subscribe_email" value="<?php echo $email; ?>">
            </div>
            <div class="row">
              <div class="col-xs-6">
                <button type="button" onClick="submitUnsubscibe();" class="btn btn-primary p-x-2">Submit</button>
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
		
		function submitUnsubscibe()
		{
			var subscribe_remark	=	$("#subscribe_remark").val().trim();
			var subscribe_resion	=	$("#subscribe_resion").val().trim();
			var subscribe_email		=	$("#subscribe_email").val().trim();
			if(subscribe_resion==""){
				$("#form-control").focus();
				message("danger|Please select valid resion.");
				return false;
			}
			if(subscribe_remark==""){
				$("#subscribe_remark").focus();
				message("danger|Please Comments.");
				return false;
			}
			var data={
						action	:	'unsubscribecustomer',
						subscribe_email		:	subscribe_email,
						subscribe_remark	:	subscribe_remark,	
						subscribe_resion	:	subscribe_resion					
					}
			$.ajax({type:'POST', data:data, url:'aouth.php', 
				
				beforeSend: function(){
					message("primary|Connecting...");
				},		
				success:function(output){
					var arr	=	JSON.parse(output);	
					if(arr[0] == 200)
					$("#card-group-form").remove();
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