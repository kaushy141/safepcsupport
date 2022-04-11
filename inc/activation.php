<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php 
$color = array("success"=>"#69B121", "error"=>"#DF4815", "warning"=>"#EDA90C");
$title = "Activation";
$data = sanitizePostData($_REQUEST);
if(isset($data['r']))
{
	$action=@$data['r']; 
	if(function_exists($action))
		$MSG = call_user_func($action);
	else
		echo json_encode(array(404,"danger|Invalid Action found. Please Refresh and try again."));
}
else
	echo json_encode(array(404,"danger|No Action found. Please Refresh and try again."));
?>
<?php
function emp()
{
	global $data, $app;
	$emp = new Employee();
	if($user_id = $emp->getIdByEmail(md5($data['u'])))
	{
		$user = new Employee($user_id);
		$info = $user->getDetails();
		if(md5($info['user_password'])==$data["l"] && md5($info['user_id'])==$data["i"])
		{
			$user->update(array("user_is_email_verified" => "1"));
			return "success::Hi, $info[user_name], Your Email Activated Successfully ..!!!<br/><h4>Please login here to continue... <a href='".$app->basePath("login.php")."'>Login</a></h4>";
		}
		else
			return "error::Activation couldn't Completed ..!!!<br/><h4>Activation Link Expired or You have Completed Logged in Activity</h4>";
	}
	else
	{
		
	}
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif;">
	<div style="position:fixed; top:0px; bottom:0px; right:0px; left:0px; background:#E8E8E8;">
    	<div style="position:relative; background:#FFF; margin:50px auto; width:90%; min-height:100px;  max-width:800px; box-shadow:2px 3px 5px #999; text-align:center;">
        	<div style="padding:40px 20px;">
			<?php 
            if(isset($MSG)):
            $msg = explode("::",$MSG);?>            
                <div style="width:100%;">
                    <h1 style="color:<?=$color[$msg[0]]?>"><?=$msg[1]?></h1>
                </div>   
            <?php endif; ?>           
            </div>
        	<div style="width:100%;">
            <center><h2 id="countbar">You will be autoredirect in <span id="count"></span> seconds</h2></center>
            	<progress id="progress" max="100" value="0" style="width:100%; margin-bottom:-2px; margin-top:-2px; height:20px; border:none;"></progress>
        	</div>
        </div>
    </div>
</body>
<script type="text/javascript">
var redirectInterval = setInterval(redirect, 1000);
var RedirectCount = 10;
function redirect()
{
	var val = document.getElementById("progress").value;
	var newVal = val+RedirectCount;
	document.getElementById("progress").value = newVal;	
	document.getElementById("count").innerHTML = RedirectCount - (val/RedirectCount); 
	
	if(val>=100)
	{
		redirectInterval = null;
		clearInterval(redirectInterval);
		document.getElementById("countbar").innerHTML = "Redirecting...";
		window.location = "<?=$app->basePath()?>";
	}
}
</script>
</html>