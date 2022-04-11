<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php $login_page_id = md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."C"));?>
<?php $signup_page_id = md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."S"));?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?=$app->siteDescription?>">
<meta name="keyword" content="<?=$app->siteKeyword?>">
<meta name="site-url" content="<?=$app->siteUrl?>">
<link rel="shortcut icon" href="<?=$app->siteIcon?>">
<title>SafePcSupport Customer Login</title>
<!-- Icons -->
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/simple-line-icons.css" rel="stylesheet">
<link href="css/style-login.css" rel="stylesheet">
</head>
<body class="" style="min-height:auto;">
<?php include("inc/login-popup.php");?>
<?php include("inc/signup-popup.php");?>
<div class="container">
  <div class="row">
    <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
    <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""):?>
    <?php $msg	=	explode("|",$_SESSION['msg']); unset($_SESSION['msg']);?>
    <?php endif; ?>
        <div class="msg" style="text-align:center; display:<?php echo isset($msg)?"block":"none";?>">
        	<div class="card card-inverse card-<?php echo isset($msg)?$msg[0]:"";?> text-xs-center" style="padding:8px 5px; margin:0px auto;"><?php echo isset($msg)?$msg[1]:"";?></div>
        </div>
    
      <div class="card-group ">
        <div class="card p-a-2">
          <div class="card-block">
            <h1>Customer <small>Login</small></h1>
            <p class="text-muted">Sign In to check your ticket status</p>
            <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-user"></i></span>
              <input type="text" name="username" id="username" class="form-control" placeholder="Email-Id">
            </div>
            <div class="input-group m-b-2"> <span class="input-group-addon"><i class="icon-lock"></i></span>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>
            <div class="row">
              <div class="col-xs-6">
                <button type="button" onClick="login();" class="btn btn-success p-x-2"><i class="fa fa-sign-in"></i> Login</button>
              </div>
              <div class="col-xs-6 text-xs-right">
                <a href="#"  data-toggle="modal" data-target="#loginPopup" type="button" class="btn btn-link p-x-0">Forgot password?</a> &nbsp;
                <a href="#"  data-toggle="modal" data-target="#signupPopup" type="button" class="btn btn-link p-x-0">Signup</a>
              </div>
              <div class="col-xs-12 text-center">
              <a href="#"  data-toggle="modal" data-target="#signupPopup" type="button" class="btn btn-link p-x-0">new on Support System Signup</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-inverse card-warning  p-y-3">
          <div class="card-block text-xs-center">
            <div>
              <h2>SafePcSupport</h2>
              <p>Welcome to safePCSupport Clients Section.</p>
              <button type="button" onClick="window.location='main.php'" class="btn btn-success  active m-t-1">Back to Site</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap and necessary plugins --> 
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/tether.min.js"></script> 
<script type="text/javascript" src="js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?=$app->jsPath("custom")?>"></script>
<script>
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
		
		function login()
		{
			var username	=	$("#username").val().trim();
			var password	=	$("#password").val().trim();
			if(username==""){
				$("#username").focus();
				message("danger|Please fill username.");
				return false;
			}
			if(password==""){
				$("#password").focus();
				message("danger|Please fill password.");
				return false;
			}
			var data={
						action	:	'customerlogin',
						username:	username,
						password:	password,							
					}
			$.ajax({type:'POST', data:data, url:'aouth.php', 
				
				beforeSend: function(){
					message("warning|Connecting...");
				},		
				success:function(output){
					var arr	=	JSON.parse(output);
					if(arr[0]==200)
						window.location = '<?=$app->basePath();?><?php if(URL_RETURN_ALLOWED && isset($_REQUEST['return']) && $_REQUEST['return']!="")echo "$_REQUEST[return]";?>';
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
			$(".msg div").text(msg[1]);
			
		}
        </script>
</body>
</html>