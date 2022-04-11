<?php include("setup.php"); ?>
<!doctype html>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="Privacy Policy - <?=$app->siteDescription?>">
<meta name="keyword" content="<?=$app->siteKeyword?>">
<meta name="site-url" content="<?=$app->siteUrl?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?=$app->siteIcon?>">
<title>
Terms & Condition - <?=$app->siteTitle?>
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
  
</header>
<!-- Header End --> 

<!-- About Section -->
<section id="about" class="site-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="about-text wow fadeInRight text-justify">
          <h3 class="text-uppercase">Acceptable Use Policy</h3>
          <p>
The Acceptable Use Policy ("AUP") clarifies the appropriate use of Amazon Marketplace APIs. In addition to the Amazon Marketplace Developer Agreement, Marketplace Developers must comply with the following policies. Failure to comply may result in suspension or termination of Marketplace API access.
<br/>
Help Sellers manage their Amazon Business
<br/><br/>
Marketplace APIs are for Developers who wish to help Sellers build, manage, and grow successful businesses in Amazon's store.
<br/>
Use Marketplace APIs only to perform acceptable Amazon Seller activities, and only for Amazon Sellers who have authorized you to perform these activities on their behalf.
Do not facilitate or promote violation of the Amazon Services Business Solutions Agreement, directly or indirectly.
<br/>
If you discover that a Seller is using your service to violate the Amazon Services Business Solutions Agreement, notify Amazon and block the Seller's access to your Application.
Keep up to date on Amazon policies that pertain to specific APIs (such as the Merchant Fulfillment API, Orders API, and Reports API) or specific functionality that your Application provides (such as Buyer-Seller messaging).
Provide quality Applications and services
<br/>
Seller transparency
<br/><br/>
Do not falsely advertise your Application or service.
Be clear and honest with Sellers about what data you are accessing and for what purpose.
Do not attempt to deceive Sellers by deliberate modification of Marketplace API data.
Be explicit about any calculations and the use of models such as Artificial Intelligence in the service you provide, their accuracy, and data freshness.
<br/>
Compliance
<br/><br/>
Comply with all applicable laws including data privacy and data protection laws (e.g., GDPR, Cybersecurity Law of the People's Republic of China).
Do not offer Applications or services that infringe on the copyrights, patents, or trademarks of others.
<br/>
Do not use, offer, or promote external (non-Amazon) data services that vend Amazon data, including data retrieved from Amazon's public-facing websites.
<br/>
Quality and performance
<br/><br/>
Provide Application availability, performance, and support required to successfully perform the business task.
<br/>
Identify and mitigate any negative Seller impact before launching new features, especially for business-critical tasks.
Design your Application to respect per-Seller throttling quotas.
Implement data integrity and validation checks within your Application for any analytical processing (e.g., AI models for insights, automated decision-making) that has material impact on a Seller's business.
<br/>
Keep data secure
<br/><br/>
Account access
<br/>
Never share keys or passwords.
Never ask for or accept a Seller's Secret Keys for any purpose.
Only act on behalf of Sellers that have granted you permission through third-party authorization.
Do not apply for keys that you will not use. Amazon will baseline access keys every 90 days. Keys that do not make a successful call in 90 days will be deleted and the Developer will need to re-apply for keys.
<br/>
Do not request or share Seller Central credentials. If necessary, ask the Seller to grant Seller Central access through a secondary user permission, but do so only if Seller Central is required to provide features or services that benefit the Seller.
Data access and usage
<br/>
Do not request access to or retrieve information that is not necessary for your Application's functionality.
<br/>
Only grant access to data on a "need-to-know" basis within your organization and among your Application users.
<br/>
Do not attempt to circumvent throttling quotas through the creation of multiple Developer accounts within the same region.
<br/>
Data sharing
<br/><br/>
Do not disclose information, individually labeled or aggregated, obtained through Marketplace APIs on behalf of a Seller to other Application users or any outside parties, unless required by law.
Do not calculate or publish insights about the health of Amazon's business.
Comply with the Data Protection Policy ("DPP"), which provides specific requirements on the receipt, storage, usage, transfer, and disposition of the data accessed through Marketplace APIs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

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
 
 
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. --> 

</body>
</html>
