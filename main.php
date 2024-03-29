<?php include("setup.php"); ?>
<?php
function sanitize_array($array = array())
{
	$data = array();
	if(is_array($array) && !empty($array))
	{
		foreach($array as $key=>$val)
		{
			if(is_array($val))
				$data[$key]=sanitizePostData($val);
			else
				$data[$key]	=trim(htmlspecialchars($val));
		}
		return $data;
		
	}
	else
		return htmlspecialchars($array);
}
if(count($_POST)>0)
{
	$postData = sanitize_array($_POST);
	if(isset($_SESSION['main_csrf']) && isset($postData['main_csrf']) && !is_array($postData['main_csrf']) && $postData['main_csrf'] == $_SESSION['main_csrf'])
	{	
	if(filter_var($postData['email'], FILTER_VALIDATE_EMAIL) == true && $postData['name']!="" && $postData['subject']!="")
	{
		$to = "farhan@safepcdisposal.co.uk";
		$to1 = $postData['email'];
		$to2 = "farhan@safepcdisposal.co.uk";
		$to3 = "kaushyedu@gmail.com";
		$subject =  "Query from SafePcSupport ".$postData['subject'];
		
		$message = "
		<html>
		<body style=\"font-family:Verdana, Geneva, sans-serif;\">
		<p><img src='".$app->siteUrl('img/logo.png')."' width='120px' /></p>
		<p>Thankyou <b>".$postData['name']."</b> for your query, we will get back you soon!<br/>
		
		All Details are captured by us as following....</p>
		
		<p style=\"text-align:left;font-size:14px\">
		Name &nbsp; : &nbsp; ".$postData['name']."<br/>
		Email &nbsp; : &nbsp; ".$postData['email']."<br/>
		Subject &nbsp; : &nbsp; ".$postData['subject']."<br/>
		Message &nbsp; : &nbsp; ".$postData['message']."<br/></p>
		</body>
		</html>
		";
		
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		// More headers
		$headers .= 'From: <support@safepcsupport.co.uk>' . "\r\n";
		$headers .= 'Cc: kaushyedu@gmail.com' . "\r\n";
		
		if(mail($to,$subject,$message,$headers))
			$_SESSION['msg'] = "success-Your Request submitted successfully";
		else
			$_SESSION['msg'] = "warning-Your Request can't submitted Please Try again.";
		
		mail($to1,$subject,$message,$headers);
		mail($to2,$subject,$message,$headers);
	}
	else
		$_SESSION['msg'] = "warning-Invalid Email format or Name found. We could not Receive your query.. Please try again";
}
else
{
	$_SESSION['msg'] = "warning-Form expired.. Please try again";
}
	
	header("location:main.php");
	exit();
}
?>
<!doctype html>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="<?=$app->siteDescription?>">
<meta name="keyword" content="<?=$app->siteKeyword?>">
<meta name="site-url" content="<?=$app->siteUrl?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?=$app->siteIcon?>">
<title>
<?=$app->siteTitle?>
</title>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,600italic,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<!-- Font -->

<link rel="stylesheet" href="end/css/normalize.css">
<link rel="stylesheet" href="end/css/main.css">
<link rel="stylesheet" href="end/css/font-awesome.min.css">
<link rel="stylesheet" href="end/css/animate.css">
<link rel="stylesheet" href="end/css/bootstrap.min.css">
<link rel="stylesheet" href="end/css/style.css">
<link rel="stylesheet" href="end/css/responsive.css">
<link rel="stylesheet" href="end/css/custom.css">
<script src="end/js/vendor/modernizr-2.8.3.min.js"></script>
<style type="text/css">
body {
	margin-top: 0px;
}
.navbar-brand{ padding:0px;}
.navbar-brand > img {
    margin-top:-3px;
}
.navbar-inverse {
	background-color: transparent;
}
ul.navbar-nav li a {
	color: #fff !important;
	font-size: 19px !important; 
	text-transform:none;
}

.btn.btn-learn {
	background: #21a3c3;
	border-radius: 3px;
	text-transform: none;
	padding: 18px 40px;
	font-size: 26px;
}
.site-padding {
	padding: 10px 0;
}
.price.panel-green > .panel-heading {
	background-color: #47cb82;
}
.price.panel-blue > .panel-heading {
	color: #FFF;
	background-color: #3880ff;
}
.panel-heading {
	padding: 20px 15px;
}
.price.panel-green .border-lr {
	border-left: 3px solid #47cb82;
	border-right: 3px solid #47cb82;
}
.price.panel-blue .border-lr {
	border-left: 3px solid #3880ff;
	border-right: 3px solid #3880ff;
}
.btn-green {
	background-color: #47cb82;
	text-transform: uppercase;
	color: #FFF;
}
.btn-blue {
	background-color: #3880ff;
	text-transform: uppercase;
	color: #FFF;
}
.price.panel-blue .list-group-item {
	font-weight: 600;
}
.list-item-name{ padding:8px 14px; color:#FFF; min-width:250px;font-size: 16px;
font-weight: 400;}
.panel-green .list-item-name{
	background:#47cb82;
}
.panel-blue .list-item-name{
	background:#3880ff;
}
.suportclass {
	min-height: 333;
	padding: 100px 20px;
	text-align: center;
	color: #FFF;
}
.suportclass h5 {
	font-size: 30px;
	font-weight: 600;
	color: #FFF;
}
.suportclass p {
	font-size: 70px;
	color: #FFF;
	font-weight: 400;
	margin-top: 66px;
}
.feature-icon {
	margin-bottom: 10px;
}
.feature-icon img {
	height: 100px;
}
.single-feature {
	box-shadow: 1px 2px 5px #666;
	padding: 20px;
}
#features {
	background: #fff;
	margin-top: 40px;
}
.is-sticky .main-menu ul.navbar-nav li a {
    color: #eee !important;
}
.border-lr {padding-top:8px;}
.field label{color: #aaa;
font-weight: 400;
font-size: 20px;}
form#contactform input[type="text"], form#contactform textarea{
	border:none; background:none; border-bottom:1px solid #999; color:#ddd; font-size:16px;}
	.footerlink ul li{ background-image:url(end/img/footer-icon-min.png); background-position:left; background-repeat:no-repeat; padding-left:16px;}
.footerlink ul li a{ color:#FFF; font-size:16px; font-weight:400; }
.footerpower p{ color:#999; font-size:14px;}
.title h3 span {
    color: #999;
	font-weight:400;
}

@media only screen and (max-width: 767px) { 
.btn.btn-learn {
	padding: 4px 18px;
	font-size: 20px;
}
.slider-btn {
    margin-top: 100px;
}
.navbar-brand > img {
    margin-top:-13px;
}
}
</style>
</head>
<body>
<header id="home">
  <div class="main-menu">
    <div class="navbar-wrapper">
      <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle Navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="#" class="navbar-brand"><img src="end/img/logo-light.png" height="60px" alt="Logo" /></a> </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#home">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#features">Services</a></li>
              <li><a href="#feature-work">Portfolio</a></li>
              <li><a href="#login">Login</a></li>
              <li><a href="#contact-us">Contact</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Main Menu End --> 
  
  <!-- Sider Start -->
  <div class="slider">
    <div id="fawesome-carousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators indicatior2">
        <li data-target="#fawesome-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#fawesome-carousel" data-slide-to="1"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active"> <img alt="slider" src="end/img/slider1.jpg" alt="Sider Big Image">
          <div class="carousel-caption">
            <h1 class="wow fadeInLeft hidden-xs">SafePcSupport<br/>
              Customer support Panel</h1>
            <div class="slider-btn wow fadeIn"> <a href="https://www.safepcdisposal.co.uk/" class="btn btn-learn">Learn More</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Sider End --> 
  
</header>
<!-- Header End --> 

<!-- About Section -->
<section id="about" class="site-padding">
  <div class="container">
    <div class="row">
    		<?php if(isset($_SESSION['msg']) && $_SESSION['msg']!=""):?>
          <?php $msg = explode("-",$_SESSION['msg']);?>
          <div class="alert alert-<?=$msg[0]?> fade in alert-dismissable" style="margin-top:18px; text-align:center;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> <strong>
            <?=ucwords($msg[0])?>
            !</strong>
            <?=$msg[1]?>
          </div>
          <?php endif;?>
      <div class="col-sm-12">
        <div class="about-text wow fadeInRight text-center">
          <h3 class="text-uppercase">About Our Company</h3>
          <p>As company we started our journey from IT recycling industry. Originally based in Abingdon oxfordshire we stared selling refurbished it equipment from our warehouse based shop and quickly grown to become one of the UK’s major technology specialist.
            
            We are family run business we made the move from a traditional local IT store to an online retail presence, which has allowed us to deliver our services to a wider audience.
            
            We don’t need to pay dividends to our shareholders so we only focus on quality service with cheapest possible prices.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- About Section -->
<section id="about" class="site-padding">
  <div class="container">
  	<div class="row">
      <div class="col-sm-12">
        <div class="title">
          <h3>ISO <span>Certified</span></h3>
        </div>
      </div>
    </div>
    </div>
    
      <div class="col-sm-12" style="background-image:url('end/img/iso-logos-bg-min.jpg'); background-repeat:no-repeat; background-size: cover;">
        <div class="about-text wow fadeInRight text-center" style="padding:10px 20px; margin-top:0px;" >   
        	<div class="row">       
          <div class="col-xs-12" style="margin-top:10px;margin-bottom:20px;">
          
            <div class="row">
              <div class="col-xs-6 col-sm-3"><img class="img-responsive" src="end/img/iso-27001-min.png"></div>
              <div class="col-xs-6 col-sm-3"><img class="img-responsive" src="end/img/iso-14001-min.png"></div>
              <div class="col-xs-6 col-sm-3"><img class="img-responsive" src="end/img/iso-9001-min.png"></div>
              <div class="col-xs-6 col-sm-3"><img class="img-responsive" src="end/img/iso-15713-min.png"></div>
            </div>
            
          </div>
          </div>
        </div>
      
    </div>
</section>
<!-- About Section -->
<section class="pricing">
  <div class="container">
    <div class="row">
      <div class="col-sm-12" style="margin-top:40px;">
        <div class="title">
          <h3 id="login">SafePcSupport <span>Login</span></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> 
        
        <!-- PRICE ITEM -->
        <div class="panel price panel-green">
          <div class="panel-heading arrow_box text-center">
            <h3>Customer login</h3>
          </div>
          <ul class="list-group list-group-flush text-center border-lr">
            <li class="text-center"><label class="list-item-name"> Check Ticket Status</label></li>
            <li class="text-center"><label class="list-item-name"> Live Chat</label></li>
            <li class="text-center"><label class="list-item-name"> Technician Support</label></li>
          </ul>
          <div class="text-center"> <a class="btn btn-lg btn-block btn-green" style="border-radius:0px; font-size:24px" href="customer-login.php<?php if(URL_RETURN_ALLOWED && isset($_REQUEST['return']) && $_REQUEST['return']!="" && ltrim($_REQUEST['return'],"/")!="logout")echo "?return=".ltrim($_REQUEST['return'],"/");?>">Login Now</a> </div>
        </div>
        <!-- /PRICE ITEM --> 
        
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> 
        
        <!-- PRICE ITEM -->
        <div class="panel price panel-blue">
          <div class="panel-heading arrow_box text-center">
            <h3>Employee Login</h3>
          </div>
          <ul class="list-group list-group-flush text-center border-lr">
            <li class="text-center"><label class="list-item-name"> Ticket Processing</label></li>
            <li class="text-center"><label class="list-item-name"> Manage Reports</label></li>
            <li class="text-center"><label class="list-item-name"> Customer Support</label></li>
          </ul>
          <div class="text-center"> <a class="btn-lg btn-block btn-blue" style="border-radius:0px; font-size:24px" href="login.php<?php if(URL_RETURN_ALLOWED && isset($_REQUEST['return']) && $_REQUEST['return']!="" && ltrim($_REQUEST['return'],"/")!="logout")echo "?return=".ltrim($_REQUEST['return'],"/");?>">Login Now</a> </div>
        </div>
        <!-- /PRICE ITEM --> 
        
      </div>
    </div>
  </div>
</section>
<!-- Award Winning Section -->

<section id="awards" class="site-padding" style="background-image:url('end/img/ticket-support-worlwide-min.jpg'); background-size: cover; background-repeat:no-repeat;">
  <div class="container">
    <div class="panel-heading arrow_box text-center">
      <h3 style="color:#FFF; font-weight:100;"><strong>1800+</strong> <br/>
        Ticket support wordwide</h3>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="progress-bar-custom wow fadeInLeft suportclass" style="background-image:url('end/img/support-min.png'); background-size:  100% 100%;">
          <h5>Support</h5>
          <p>60%</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="progress-bar-custom wow fadeInLeft suportclass" style="background-image:url('end/img/query-min.png'); background-size: 100% 100%;">
          <h5>Customers</h5>
          <p>80%</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="progress-bar-custom wow fadeInLeft suportclass" style="background-image:url('end/img/customers-min.png'); background-size:  100% 100%;">
          <h5>Query</h5>
          <p>90%</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="progress-bar-custom wow fadeInLeft suportclass" style="background-image:url('end/img/technicians-min.png'); background-size:  100% 100%;">
          <h5>Technician</h5>
          <p>70%</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Award Winning Section --> 

<!-- Feature Section -->

<section id="features" class="site-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="title">
          <h3>Our <span>Services</span></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row"> 
      
      <!-- Single Feature-->
      
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInLeft">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-01-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Secure hardware recycling</h4>
                <p>Our top priority is to ensure safe and secure destruction service for all type of IT equipment as well as offering cash-back.</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/secure-hardware-recycling/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>      
      <!-- Single Feature-->
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInLeft">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-02-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Secure Data Erasing</h4>
                <p>Safe Pc Disposal provides secure data destruction service. either in House or On-Site at your premises if you wish to witness process.</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/secure-data-erasing/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>      
      <!-- Single Feature-->
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInRight">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-03-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Secure IT Recycling</h4>
                <p>SafePC Disposal secure computer Recycling. Data destruction IT Removals and Re-location service nationwide</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/secure-it-recycling/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Single Feature-->      
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInLeft">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-04-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Photocopier Disposal</h4>
                <p>At Safe PC Disposal we specialize in coppier disposal. coppier recycling and coppier re-location across UK</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/free-computer-disposal/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Single Feature-->
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInLeft">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-05-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Equipment Refurbishment</h4>
                <p>If you have any end of life equipment and you want to get max value for your ex lease asset then contact us.</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/equipment-refurbishment/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>      
      <!-- Single Feature-->
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-feature wow fadeInLeft">
          <div class="row">
            <div class="col-md-12">
              <div class="feature-icon"> <img alt="icon" class="img-responsive" src="end/img/services-icon-06-min.png"> </div>
            </div>
            <div class="col-md-12">
              <div class="feature-text">
                <h4>Equipment Resale</h4>
                <p>Safe Pc Disposal is the company which helps an individual or any organisation currently looking to dispose IT equipment.</p>
                <a target="new" href="https://www.safepcdisposal.co.uk/equipment-resale/">Read More>></a> </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Single Feature--> 
      
    </div>
  </div>
</section>

<!-- Feature Section --> 

<!-- Featured Work -->

<section id="feature-work" class="protfolio-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="title">
          <h3>Featured <span>Work</span></h3>
        </div>
      </div>
    </div>
    </div>
    <div class="featured-list" style="padding:250px 0px; background-image:url(end/img/featured-work-img-min.png); background-size:cover;">
      <div id="grid" class="clearfix text-center">
        <ul class="list-inline">
          <li class="item"><a target="new" href="https://www.safepcdisposal.co.uk"><img alt="safepcdisposal.co.uk" class="img-responsive" src="end/img/safepc-disposal-min.jpg"></a></li>
          <li class="item"><a target="new" href="https://www.consolekillerpc.co.uk"><img alt="consolekillerpc.co.uk" class="img-responsive" src="end/img/consolekillerpc-min.jpg"></a></li>
          <li class="item"><a target="new" href="https://www.tecknosoft.com"><img alt="tecknosoft.com" class="img-responsive" src="end/img/tecknosoft-min.jpg"></a></li>
          <li class="item"><a target="new" href="https://www.mac4sale.co.uk"><img alt="mac4sale.co.uk" class="img-responsive" src="end/img/mac4sale-min.jpg"></a></li>
          <li class="item"><a target="new" href="https://www.safepcdirect.co.uk"><img alt="safepcdirect.co.uk" class="img-responsive" src="end/img/safepcdirect-min.jpg"></a></li>
        </ul>
    </div>
  </div>
</section>

<!-- Featured Work --> 

<!-- Contact -->
<section id="contact-us">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="title">
          <h3>Contact <span>Us</span></h3>
          <p class="text-center">Provide Exceptional<br/> Customer Support Everyday</p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xs-12" style="background-image:url(end/img/contact-us-background.jpg);background-size:cover; background-repeat:no-repeat; margin-top:40px;">
    <div class="row">
        <div class="col-md-6">
          <div class="contact">
            <div style="margin:-90px auto; padding:30px; max-width:500px;">
              <div class="row">
                <div class="col-xs-12" style="background:#000; padding:50px 30px;">
                  <h4 style="color:#FFF;font-size: 21px; font-weight: 400;">Please Contact With Us For Any Kind of Information</strong></h4>
                  <hr style="height:6px; border-radius:3px; border:none; background:#326fda; width:160px;"/>
                  <form id="contactform" action="main.php" method="post" class="validateform" name="send-contact">
                    <div class="row">
                      <div class="col-xs-12 field">
                      <label>Your name</label>
                        <input type="text" required name="name" data-rule="maxlen:50" data-msg="Please enter at least 4 chars" />
                        <div class="validation"> </div>
                      </div>
                      <div class="col-xs-12 field">
                      	<label>Your email</label>
                        <input type="text" required name="email" data-rule="email" data-msg="Please enter a valid email" />
                        <div class="validation"> </div>
                      </div>
                      <div class="col-xs-12 field">
                      <label>Subject</label>
                        <input type="text" required name="subject" data-rule="maxlen:50" data-msg="Please enter at least 4 chars" />
                        <div class="validation"> </div>
                      </div>
                      <div class="col-xs-12 margintop10 field">
                      <label>Message</label>
                        <textarea rows="3" required name="message" class="input-block-level" data-rule="required" data-msg="Please write something"></textarea>
                        <div class="validation"> </div>
                        <p>
                        	<input type="hidden" name="main_csrf" value="<?php $_SESSION['main_csrf'] = sha1(md5(time())); echo $_SESSION['main_csrf'];?>">
                          <button class="btn btn-theme margintop10 btn-lg pull-left" type="submit">Submit message</button>
                        </p>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
            <div style="margin:100px auto;">
                <iframe width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=safepcdisposal&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
        </div>
    </div>
  </div>
 
  
</section>
<!-- Contact --> 

<section id="footer" class="protfolio-padding" style="background:#2e323d;">
  <div class="container">
    <div class="row">
      <div class="col-sm-12" style="border-bottom:1px solid #444;">
        <div class="title text-center footerlink">
          <ul class="list-inline" style="padding:50px 0px 0px;">
          <li class="item"><a href="#about">About us</a></li>
          <li class="item"><a href="#login">Login</a></li>
          <li class="item"><a target="new" href="privacy.php">Privacy Policy</a></li>
          <li class="item"><a target="new" href="terms-condition.php">Terms & Condition</a></li>          
        </ul>
        </div>
      </div>
    </div>
    </div>
    <div class="featured-list" style="padding:20px 0px;">
      <div class="clearfix text-center footerpower">
        <p>
        Copyright &copy; SafePcSupport System | All Rights Reserved
        </p>
        <p>Powered by <a target="new" href="https://www.tecknosoft.com"><img height="20px" alt="Tecknosoft.com" src="end/img/tecknosoft-powered-logo.png"></a></p>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script> 
<script>window.jQuery || document.write('<script src="end/js/vendor/jquery-1.12.0.min.js"><\/script>')</script> 
<script src="end/js/plugins.js"></script> 
<script src="end/js/bootstrap.min.js"></script> 
<script src="end/js/jquery.mousewheel-3.0.6.pack.js"></script> 
<script src="end/js/paralax.js"></script> 
<script src="end/js/jquery.smooth-scroll.js"></script> 
<script src="end/js/jquery.sticky.js"></script> 
<script src="end/js/wow.min.js"></script> 
<script src="end/js/main.js"></script> 
<script type="text/javascript">
			$(document).ready(function(){
				$('a[href^="#"]').on('click',function (e) {
					e.preventDefault();

					var target = this.hash;
					var $target = $(target);

					$('html, body').stop().animate({
						 'scrollTop': $target.offset().top
					}, 900, 'swing');
					});
			});
			window.localStorage.setItem("user_sign_in", false);
		</script> 
<script src="end/js/custom.js"></script> 
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. --> 
<script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
</body>
</html>
