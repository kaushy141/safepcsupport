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
Privacy Policy - <?=$app->siteTitle?>
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

<section id="about" class="site-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="about-text wow fadeInRight text-justify">
          <h3>Data Protection Policy</h3>
<p>          
The Data Protection Policy ("DPP") governs the treatment (e.g., receipt, storage, usage, transfer, and disposition) of the data vended and retrieved through the Marketplace APIs (including the Marketplace Web Service APIs). This Policy supplements the Amazon Marketplace Developer Agreement and the Acceptable Use Policy. Failure to comply may result in suspension or termination of Marketplace API access.
<br/><br/>
Definitions
<br/>
"Application" means a software application or website that interfaces with the Marketplace APIs.
<br/>
"Amazon Information" means any information that is exposed by Amazon through the Marketplace APIs, Seller Central, or Amazon's public-facing websites. This data can be public or non-public, including Personally Identifiable Information about Amazon customers.
<br/>
"Customer" means any person or entity who has purchased items or services from Amazon's public-facing websites.
<br/>
"Developer" means any person or entity (including you, if applicable) that uses the Marketplace APIs for the purpose of integrating or enhancing a third-party Seller's systems with the features and functionality permitted by Amazon to be accessed through the Marketplace APIs.
<br/>
"Personally Identifiable Information" ("PII") means information that can be used on its own or with other information to identify, contact, or locate an individual (e.g., Customer or Seller), or to identify an individual in context. This includes, but is not limited to, a Customer or Seller's name, address, e-mail address, phone number, gift message content, survey responses, payment details, purchases, cookies, digital fingerprint (e.g., browser, user device), IP Address, geo-location, or Internet-connected device product identifier.
<br/>
"Security Incident" means any actual or suspected unauthorized access, collection, acquisition, use, transmission, disclosure, corruption, or loss of Amazon Information, or breach of any environment (i) containing Amazon Information, or (ii) managed by a Developer with controls substantially similar to those protecting Amazon Information.
<br/>
"Seller" means any person or entity (including you, if applicable) selling on Amazon's public-facing websites.
<br/>
General Security Requirements
<br/><br/>
Consistent with industry-leading security standards and other requirements specified by Amazon based on the classification and sensitivity of Amazon Information, Developers will maintain physical, administrative, and technical safeguards, and other security measures (i) to maintain the security and confidentiality of Amazon Information accessed, collected, used, stored, or transmitted by a Developer, and (ii) to protect that information from known or reasonably anticipated threats or hazards to its security and integrity, accidental loss, alteration, disclosure, and all other unlawful forms of processing. Without limitation, the Developer will comply with the following requirements:
<br/>
Network Protection. Developers must implement network protection controls (e.g., AWS VPC subnet/Security Groups, network firewalls) to deny access to unauthorized IP addresses and public access must be restricted only to approved users.
Access Management. Developers must assign a unique ID to each person with computer access to Amazon Information. Developers must not create or use generic, shared, or default login credentials or user accounts. Developers must implement baselining mechanisms to ensure that at all times only the required user accounts access Amazon Information. Developers must review the list of people and services with access to Amazon Information on a regular basis (at least quarterly), and remove accounts that no longer require access. Developers must restrict developer employees from storing Amazon data on personal devices. Developers will maintain and enforce "account lockout" by detecting anomalous usage patterns and log-in attempts, and disabling accounts with access to Amazon Information as needed.
<br/>
Encryption in Transit. Developers must encrypt all Amazon Information in transit (e.g., when the data traverses a network, or is otherwise sent between hosts. This can be accomplished using HTTP over TLS (HTTPS). Developers must enforce this security control on all applicable external endpoints used by customers as well as internal communication channels (e.g., data propagation channels among storage layer nodes, connections to external dependencies) and operational tooling. Developers must disable communication channels which do not provide encryption in transit even if unused (e.g., removing the related dead code, configuring dependencies only with encrypted channels, and restricting access credentials to use of encrypted channels). Developers must use data message-level encryption (e.g., using AWS Encryption SDK) where channel encryption (e.g., using TLS) terminates in untrusted multi-tenant hardware (e.g., untrusted proxies).
<br/>
Incident Response Plan. Developers must create and maintain a plan and/or runbook to detect and handle Security Incidents. Such plans must identify the incident response roles and responsibilities, define incident types that may impact Amazon, define incident response procedures for defined incident types, and define an escalation path and procedures to escalate Security Incidents to Amazon. Developers must review and verify the plan every six (6) months and after any major infrastructure or system change. Developers must investigate each Security Incident, and document the incident description, remediation actions, and associated corrective process/system controls implemented to prevent future recurrence (if applicable). Developers must maintain the chain of custody for all evidences or records collected, and such documentation must be made available to Amazon on request (if applicable).
<br/>
Developers must inform Amazon (via email to 3p-security@amazon.com) within 24 hours of detecting any Security Incidents. Developers cannot notify any regulatory authority, nor any customer, on behalf of Amazon unless Amazon specifically requests in writing that the Developer do so. Amazon reserves the right to review and approve the form and content of any notification before it is provided to any party, unless such notification is required by law, in which case Amazon reserves the right to review the form and content of any notification before it is provided to any party. Developers must inform Amazon within 24 hours when their data is being sought in response to legal process or by applicable law.
<br/>
Request for Deletion or Return. Developers must promptly (but within no more than 72 hours after Amazon's request), permanently, and securely delete (in accordance with industry-standard sanitization processes, e.g., NIST 800-88) or return Amazon Information upon and in accordance with Amazon's notice requiring deletion and/or return. Developers must also permanently and securely delete all live (online or network accessible) instances of Amazon Information within 90 days after Amazon's notice. If requested by Amazon, the Developer will certify in writing that all Amazon Information has been securely destroyed.
<br/>
Additional Security Requirements Specific to Personally Identifiable Information
<br/><br/>
The following additional Security Requirements must be met for all Personally Identifiable Information ("PII") (see PII definition in Section 1). PII is granted to MWS developers for select tax and merchant fulfilled shipping purposes, on a must-have basis. If a Marketplace API contains PII, or PII is combined with non-PII, then the entire data store must comply with the following requirements:
<br/>
Data Retention and Recovery. Developers will retain PII only for the purpose of, and as long as is necessary to fulfill orders (no longer than 30 days after order shipment), or to calculate/remit taxes. If a Developer is required by law to retain archival copies of PII for tax or similar regulatory purposes, this archived Amazon Information must be stored as a "cold" or offline (e.g., not available for immediate or interactive use) backup stored in a physically secure facility, and all archived data on backup media must be encrypted. In the event that PII is lost, you must be able to recover all PII lost (i.e., the data is erased or unavailable for processing due to system crash or ransomware).
<br/>
Data Governance. Developers must create, document, and abide by a privacy and data handling policy for their Applications or services which govern the appropriate conduct and technical controls to be applied in managing and protecting information assets. Developers must keep inventory of software and physical assets (e.g. computers, mobile devices) with access to PII, and update regularly. A record of data processing activities such as specific data fields and how they are collected, processed, stored, used, shared, and disposed for all PII Information should be maintained to establish accountability and compliance with regulations. Developers must establish and abide by their privacy policy for customer consent and data rights to access, rectify, erase, or stop sharing/processing their information where applicable or required by data privacy regulation.
Encryption and Storage. Developers must encrypt all PII at rest (e.g., when the data is persisted) using industry best practice standards (e.g. using either AES-128, AES-256, or RSA with 2048-bit key size (or higher). The cryptographic materials (e.g., encryption/decryption keys) and cryptographic capabilities (e.g., daemons implementing virtual Trusted Platform Modules and providing encryption/decryption APIs) used for encryption of PII at rest must be only accessible to the Developer's processes and services. Developers must not store PII in removable media (e.g., USB) or unsecured public cloud applications (e.g., public links made available through Google Drive). Developers must securely dispose of any printed documents containing PII.
Least Privilege Principle. Developers must implement fine-grained access control mechanisms to allow granting rights to any party using the Application (e.g., access to a specific set of data at its custody) and the Application's operators (e.g., access to specific configuration and maintenance APIs such as kill switches) following the principle of least privilege. Application sections or features that vend PII must be protected under a unique access role, and access should be granted on a "need-to-know" basis.
<br/>
Logging and Monitoring. Developers must gather logs to detect security-related events (e.g., access and authorization, intrusion attempts, configuration changes) to their Applications and systems. Developers must implement this logging mechanism on all channels (e.g., service APIs, storage-layer APIs, administrative dashboards) providing access to Amazon Information. All logs must have access controls to prevent any unauthorized access and tampering throughout their lifecycle. Logs themselves should not contain PII and must be retained for at least 90 days for reference in the case of a Security Incident. Developers must build mechanisms to monitor the logs and all system activities to trigger investigative alarms on suspicious actions (e.g., multiple unauthorized calls, unexpected request rate and data retrieval volume, and access to canary data records). Developers should perform investigation when monitoring alarms are triggered, and this should be documented in the Developer's Incident Response Plan.
<br/>
Audit
<br/><br/>
Developers must maintain all appropriate books and records reasonably required to verify compliance with the Acceptable Use Policy, Data Protection Policy, and Amazon Marketplace Developer Agreement during the period of this agreement and for 12 months thereafter. Upon Amazon's written request, Developers must certify in writing to Amazon that they are in compliance with these policies.
<br/>
Upon request, Amazon may, or may have an independent certified public accounting firm selected by Amazon, audit and inspect the books, records, facilities, operations, and security of all systems that are involved with a Developer's application in the retrieval, storage, or processing of Amazon Information. Developers must cooperate with Amazon or Amazon's auditor in connection with the audit, which may occur at the Developer's facilities and/or subcontractor facilities. If the audit reveals deficiencies, breaches, and/or failures to comply with our terms, conditions, or policies, the Developer must, at its sole cost and expense, and take all actions necessary to remediate those deficiencies within an agreed-upon timeframe.
</p>
          <p>As company we started our journey from IT recycling industry. Originally based in Abingdon oxfordshire we stared selling refurbished it equipment from our warehouse based shop and quickly grown to become one of the UK’s major technology specialist.
 <br/>           
            We are family run business we made the move from a traditional local IT store to an online retail presence, which has allowed us to deliver our services to a wider audience.
 <br/>           
            We don’t need to pay dividends to our shareholders so we only focus on quality service with cheapest possible prices.</p>
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
