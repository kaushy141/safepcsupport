<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php $login_page_id = md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."E"));?>
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
<title>SafePcSupport Employee Login</title>
<!-- Icons -->
<link href="<?=$app->cssPath('font-awesome.min')?>" rel="stylesheet">
<link href="<?=$app->cssPath('simple-line-icons')?>" rel="stylesheet">
<link href="<?=$app->cssPath('style-login')?>" rel="stylesheet">
<script type="text/javascript">
var USER_CAN_SEE_ORDER_NOTIFICATION = false;
</script>
<style type="text/css">body{margin:0;width:100%;height:100%} body,td,input,textarea,select{font-family:arial,sans-serif} input,textarea,select{font-size:100%} #loading{position:absolute;width:100%;height:100%;z-index:1000;background-color:#fff} .msg{ color: #757575; font: 20px/20px Arial, sans-serif; letter-spacing: .2px; text-align: center } #nlpt{ animation: a-s .5s 2.5s 1 forwards; background-color: #f1f1f1; height: 4px; margin: 20px auto 20px; opacity: 0; overflow: hidden; position: relative; width: 300px } #nlpt::before{ animation: a-lb 20s 3s linear forwards; background-color: #33CC00; content: ''; display: block; height: 100%; position: absolute; transform: translateX(-300px); width: 100% } @keyframes a-lb{ 0%{transform:translateX(-300px)}5%{transform:translateX(-240px)}15%{transform:translateX(-30px)}25%{transform:translateX(-30px)}30%{transform:translateX(-20px)}45%{transform:translateX(-20px)}50%{transform:translateX(-15px)}65%{transform:translateX(-15px)}70%{transform:translateX(-10px)}95%{transform:translateX(-10px)}100%{transform:translateX(-5px)} } @keyframes a-s{ 100%{opacity:1} } @keyframes a-h{ 100%{opacity:0} } @keyframes a-nt{ 100%{transform:none} } @keyframes a-e{ 43%{animation-timing-function:cubic-bezier(.8,0,.2,1);transform:scale(.75)} 60%{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-16px)} 77%{animation-timing-function:cubic-bezier(.16,0,.2,1);transform:none} 89%{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-5px)} 100%{transform:none} } @keyframes a-ef{ 24%{animation-timing-function:cubic-bezier(.8,0,.6,1);transform:scaleY(.42)} 52%{animation-timing-function:cubic-bezier(.63,0,.2,1);transform:scaleY(.98)} 83%{animation-timing-function:cubic-bezier(.8,0,.84,1);transform:scaleY(.96)} 100%{transform:none} } @keyframes a-efs{ 24%{animation-timing-function:cubic-bezier(.8,0,.6,1);opacity:.3} 52%{animation-timing-function:cubic-bezier(.63,0,.2,1);opacity:.03} 83%{animation-timing-function:cubic-bezier(.8,0,.84,1);opacity:.05} 100%{opacity:0} } @keyframes a-es{ 24%{animation-timing-function:cubic-bezier(.8,0,.6,1);transform:rotate(-25deg)} 52%{animation-timing-function:cubic-bezier(.63,0,.2,1);transform:rotate(-42.5deg)} 83%{animation-timing-function:cubic-bezier(.8,0,.84,1);transform:rotate(-42deg)} 100%{transform:rotate(-43deg)} } .invfr{position:absolute;left:0;top:0;z-index:-1;width:0;height:0;border:0} .msgb{position:absolute;right:0;font-size:12px;font-weight:normal;color:#000;padding:20px}</style>
<style type="text/css">
@-webkit-keyframes LoginPageAnimation {
0%{background-position:0% 80%}
50%{background-position:100% 20%}
100%{background-position:0% 80%}
}
@-moz-keyframes LoginPageAnimation {
0%{background-position:0% 80%}
50%{background-position:100% 20%}
100%{background-position:0% 80%}
}
@keyframes LoginPageAnimation {
0%{background-position:0% 80%}
50%{background-position:100% 20%}
100%{background-position:0% 80%}
}

.message_body{ font-size: 15px;}
/*
.login-body{background-color: #000; background-image:url('https://i.gifer.com/EF65.gif'); background-position: center;
background-size:cover;
background-repeat: no-repeat;}
*/
.card{background-color: rgba(255, 255, 255, .25);  
  backdrop-filter: blur(5px);
  box-shadow: 0 0 1rem 0 rgba(0, 0, 0, 0.2);
}
  .card:before {
	 width:100%;
	 height:100%;
	  position:absolute;
	  background: inherit;
    box-shadow: inset 0 0 0 3000px rgba(255,255,255,0.3);
 filter: blur(10px);
}
.login-body{
    /*background: linear-gradient(135deg,  #641e16 ,  #512e5f ,  #154360 ,  #0e6251  ,  #145a32 ,  #784212 ,  #424949 ,  #17202a);*/
	  background: linear-gradient(135deg,  #ffcdd2, #f8bbd0, #e1bee7, #d1c4e9,  #bbdefb,  #b3e5fc,  #b2ebf2,  #b2dfdb,  #c8e6c9,  #dcedc8,  #f0f4c3,  #fff9c4,  #ffecb3,  #ffe0b2,  #ffccbc,  #d7ccc8,  #f5f5f5,  #cfd8dc  );
        background-size: auto;
    background-size: 500% 10000%;
    animation: LoginPageAnimation 20s ease infinite;
}
.card-bg{ background-color: #bcaaa4 ;}
</style>
</head>
<body class="login-body">
<?php include("inc/login-popup.php");?>
<div class="container">
  <div class="row">
    <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 m-x-auto pull-xs-none vamiddle">
      <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""):?>
      <?php $msg	=	explode("|",$_SESSION['msg']); unset($_SESSION['msg']);?>
      <?php endif; ?>
      <div class="msg message_body" style="text-align:center; display:<?php echo isset($msg)?"block":"none";?>">
        <div class="text-xs-center" style="padding:8px 5px; margin:0px auto;"><?php echo isset($msg[1])?$msg[1]:"";?></div>
      </div>
      <div class="card-group">
        <div class="card p-a-2 card-bg">
          <div class="">
            <h1>Login</h1>
            <form id="loginform" name="loginform" onsubmit="return login();" aria-autocomplete="none">
              <p class="">Sign In to your account</p>
              <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-user"></i></span>
                <input name="username" autocomplete="off" id="username" class="form-control" placeholder="Username" type="text">
              </div>
              <div class="input-group m-b-2"> <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input name="password" autocomplete="off" id="password" class="form-control" placeholder="Password" type="password">
              </div>
              <div class="row">
              <?php if(ENABLE_LOGIN_CAPTCHA):?>
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center"> <a href="javascript:refreshEMPLoginCaptcha();" class="text-dark">Refresh</a> <br/>
                      <img id="employee_login_captcha_code_img" src="<?php echo $app->basePath("captcha.php?mode=EMPLOYEE-LOGIN")?>" style="margin:6px 0px;" /> </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <label for="employee_login_captcha_code">Captcha</label>
                        <input class="form-control" id="employee_login_captcha_code" name="employee_login_captcha_code" maxlength="4" placeholder="Code" type="text" value="">
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif;?>
                <div class="col-xs-6 col-sm-6">
                  <button type="submit" class="btn btn-primary p-x-2"><i class="icon-login"></i> Login</button>
                </div>
                <div class="col-xs-6 col-sm-6 text-xs-right"> <span href="#" data-toggle="modal" data-target="#loginPopup" class="p-x-0">Forgot password?</span> </div>
              </div>
            </form>
          </div>
        </div>
        <!--<div class="card card-inverse card-primary p-y-0 p-a-1">
          <div class="text-xs-center">
            <div>
              <h2>SafePcSupport</h2>
              <p>Welcome to safePCSupport Admin Section.</p>
              <p class="text-center"><img src="<?=$app->basePath("qrlogin.php");?>" height="180px"></p>
              <p>Scan this QR Code to login using SPD App</p>
              <p>
                <button type="button" onclick="window.location='main.php'" class="btn btn-primary active m-t-1">Back to Site</button>
              </p>
            </div>
          </div>
        </div>-->
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap and necessary plugins --> 
<script src="<?=$app->jsPath('jquery.min')?>"></script> 
<script src="<?=$app->jsPath('tether.min')?>"></script> 
<script src="<?=$app->jsPath('bootstrap.min')?>"></script> 
<script src="<?=$app->jsPath('md5')?>"></script>
<script type="text/javascript">
		var ENABLE_LOGIN_CAPTCHA = <?php echo ENABLE_LOGIN_CAPTCHA; ?>;
		var ENABLE_QR_LOGIN_CHECK = true;
		function refreshEMPLoginCaptcha()
		{
			$("#employee_login_captcha_code_img").attr('src','<?php echo $app->basePath("captcha.php?mode=EMPLOYEE-LOGIN&id=");?>'+Math.random());
		}
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
		var loginAttempt = 0;
		function login()
		{
			var captcha = "";
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
			if(ENABLE_LOGIN_CAPTCHA == 1)
			{
				captcha	=	$("#employee_login_captcha_code").val().trim();
				if(captcha == "")
				{
					$("#employee_login_captcha_code").focus();
					message("danger|Please fill captcha code.");
					return false;
				}
			}
			$(".card-group").hide();
			var data={
						action	:	'userlogin',
						username:	username,
						password:	password,	
						captcha:	captcha						
					}
			$.ajax({type:'POST', data:data, url:'aouth.php', 
				
				beforeSend: function(){
					message("default|<i class='fa icon-clock fa-spinner fa-2x'></i><br/>Connecting...");
				},		
				success:function(output){
					var arr	=	JSON.parse(output);
					if(arr[0]==200){
						ENABLE_QR_LOGIN_CHECK = false;
						//message('default|<span class="text-success"><i class="icon-check fa-2x"></i> <br/>'+arr[1] + '</span>');
						//arr[1] = "<i class='fa fa-check-circle'>" +arr[1];
						$('body').replaceWith('<div id="loading"><div style="bottom:0;left:0;overflow:hidden;position:absolute;right:0;top:0"><div style="animation:a-h .5s 1.25s 1 linear forwards,a-nt .6s 1.25s 1 cubic-bezier(0,0,.2,1);background:#eee;border-radius:50%;height:800px;left:50%;margin:-448px -400px 0;position:absolute;top:50%;transform:scale(0);width:800px"></div></div><div style="height:100%;text-align:center"><div style="height:50%;margin:0 0 -140px"></div><div style="height:128px;margin:0 auto;position:relative;width:176px"><div style="animation:a-s .5s .5s 1 linear forwards,a-e 1.75s .5s 1 cubic-bezier(0,0,.67,1) forwards;opacity:0;transform:scale(.68)"><div style="/*! background:#ddd; */border-radius:12px;/*! box-shadow:0 15px 15px -15px rgba(0,0,0,.3); */height:128px;left:0;overflow:hidden;position:absolute;top:0;transform:scale(1);width:176px"><div style="animation:a-nt .667s 1.5s 1 cubic-bezier(.4,0,.2,1) forwards;background:transparent;border-radius:50%;height:270px;left:88px;margin:-135px;position:absolute;top:25px;transform:scale(.5);width:270px"></div><div style="height:128px;left:20px;overflow:hidden;position:absolute;top:0;transform:scale(1);width:136px"><div style="/*! background:#e1e1e1; */height:128px;left:0;position:absolute;top:0;width:68px"><div style="animation:a-h .25s 1.25s 1 forwards;/*! background:#eee; */height:128px;left:0;opacity:1;position:absolute;top:0;width:68px"></div></div></div><div style="/*! background:#bbb; */height:176px;left:0;position:absolute;top:-100px;transform:scaleY(.73)rotate(135deg);width:176px"><div style="background:#eee;border-radius:12px 12px 0 0;bottom:117px;height:12px;left:55px;position:absolute;transform:rotate(-135deg)scaleY(1.37);width:136px"></div><div style="background:#eee;height:96px;position:absolute;right:0;top:0;width:96px"></div><div style="box-shadow:inset 0 0 10px #888;height:155px;position:absolute;right:0;top:0;width:155px"></div></div></div><div style="animation:a-ef 1.184s 1.283s 1 cubic-bezier(.4,0,.2,1) forwards;border-radius:12px;height:100px;left:0;overflow:hidden;position:absolute;top:0;transform:scaleY(1);transform-origin:top;width:176px"><div style="height:176px;left:0;position:absolute;top:-100px;transform:scaleY(.73)rotate(135deg);width:176px"><div style="animation:a-s .167s 1.283s 1 linear forwards;box-shadow:-1px -1px 8px rgba(0,0,0,.5);height:176px;left:0;opacity:0;position:absolute;top:0;width:176px"></div><div style="/*! background:#ddd; */height:176px;left:0;overflow:hidden;position:absolute;top:0;width:176px"><div style="animation:a-nt .667s 1.25s 1 cubic-bezier(.4,0,.2,1) forwards;background: #9966FF;border-radius:50%;bottom:41px;height:225px;left:41px;position:absolute;transform:scale(0);width:225px;box-shadow: 0 15px 15px -15px rgba(0,0,0,.3);"></div><div style="background:#fff;height:128px;left:24px;position:absolute;top:24px;transform:rotate(90deg);width:128px"></div><div style="animation:a-efs 1.184s 1.283s 1 cubic-bezier(.4,0,.2,1) forwards;background:#fff;height:176px;opacity:0;transform:rotate(90deg);width:176px"></div></div><div style="background:#fff;height:128px;left:95px;position:absolute;top:53px;transform:rotate(90deg);width:128px;/*! z-index: 10000000; */"></div><div style="background:#9966FF;height:24px;left:148px;position:absolute;top:37px;transform:rotate(90deg);width:32px;/*! z-index: 10000000; */border-radius: 12px;"></div></div></div></div></div><div id="nlpt"></div><div style="animation:a-s .25s 1.25s 1 forwards;opacity:0" class="msg">SafePcSupport Loading...</div></div></div>');
						setTimeout(function(){
						window.location = '<?=$app->basePath();?><?php if(URL_RETURN_ALLOWED && isset($_REQUEST['return']) && $_REQUEST['return']!="")echo "$_REQUEST[return]";?>';}, 5000);
					}
					else if(arr[0]==300){
						message(arr[1]);
						$(".card-group").show();
						$("#employee_login_captcha_code_img").attr("src","<?php echo $app->basePath('captcha.php?mode=EMPLOYEE-LOGIN&time=');?>"+loginAttempt++);
					}
					else
					message(arr[1]);
				}
			})	
			return false;
		}
		
		function message(msg)
		{
			$(".msg").show();
			var msg	=	msg.split("|");
			$(".msg div").removeClass("card-success card-warning card-danger ");
			$(".msg div").addClass("card-"+msg[0]);
			$(".msg div").html(msg[1]);
			
		}
		
	$(document).ready(function(e) {
		/*
		setInterval(function(){	
			if(ENABLE_QR_LOGIN_CHECK)	
			{
				$.ajax({type:'POST', data:{action:'checkmyaccount'}, url:'aouth.php', 						
					success:function(output){
						if(output==1){
							window.location = '<?=$app->basePath();?><?php if(URL_RETURN_ALLOWED && isset($_REQUEST['return']) && $_REQUEST['return']!="")echo "$_REQUEST[return]";?>';
						}
					}
				});
			}
		},10000);
		*/
	});

	var userPreservCoocoies = ['user_last_order_seen', 'old_location_lat', 'old_location_lng',	'live_session'];
	var userPreservCoocoiesValues = [];
	window.onload = function()
	{
		for(var i=0; i<userPreservCoocoies.length;i++)
		{
			console.log(window.localStorage.getItem(userPreservCoocoies[i]));
			userPreservCoocoiesValues.push(typeof (window.localStorage.getItem(userPreservCoocoies[i])) !== "undefined" ? window.localStorage.getItem(userPreservCoocoies[i]) : null);			
			
		}
		console.log(userPreservCoocoiesValues);
	 	window.localStorage.clear();
	 	for(var i=0; i<userPreservCoocoies.length;i++)
		{			
			if(userPreservCoocoiesValues[i] != null)
			window.localStorage.setItem(userPreservCoocoies[i], userPreservCoocoiesValues[i]);
		}
	}
</script>
</body>
</html>