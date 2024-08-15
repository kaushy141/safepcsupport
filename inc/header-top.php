<header class="app-header navbar">
  <button class="navbar-toggler mobile-sidebar-toggler hidden-lg-up" type="button">☰</button>
  <a class="navbar-brand" href="<?=$app->siteUrl?>"></a>
  <ul class="nav navbar-nav hidden-md-down">
    <li class="nav-item"> <a class="nav-link navbar-toggler sidebar-toggler" href="#"  data-step="1" data-title="Navbar" data-intro="Toggle to full screen">☰</a> </li>
    <?php 
	  $navbar = new Navbar();
	  $topBarList = $navbar->getTopBarList();
	  $i=0;
	  foreach($topBarList as $nav)
	  {					 
		echo "<li class=\"nav-item px-1\"><a class=\"nav-link redirect\" href=\"".$app->siteUrl($nav['module_key'])."\"><i class=\"".$nav['module_icon']."\"  ></i> ".$nav['module_topbar_name']."</a></li>";
	  }
	  ?>
    <li class="nav-item px-1"> <a class="nav-link" href="javascript:createSchedule();"><i class="icon-calendar"></i> ToDo</a> </li>
    <li class="nav-item px-1">
      <form onsubmit="return searchCode();" method="get" id="searchform" data-step="2" data-title="Search bar" data-intro="Search/Scan Sales, RMA, Collection Product etc. by their code">
        <input class="form-control text-center input_text_upper" id="searchformbox" maxlength="12" size="14" type="search" placeholder="scan code here..">
      </form>
    </li>
  </ul>
  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item hidden-md-down"> <a title="Clock out" class="logoffbtn" href="#"><i class="icon-logout"></i></a> </li>
    <!--<li class="nav-item hidden-md-down"> <a id="locationTracking" class="nav-link redirect" href="<?php echo $app->siteUrl("geotracking");?>"><i class="icon-location-pin text-success"></i></a> </li>-->
	<li class="nav-item hidden-md-down"> <a id="lotanchortab" class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" role="button" aria-haspopup="true"  href="#"><i class="icon-handbag"></i><span class="badge badge-pill badge-success lot_product_count"></span></a> 
		<div class="dropdown-menu dropdown-menu-right" style="position:absolute; top:35px; margin-top:0px;">
		  <a class="dropdown-item createnewlot" href="#"><i class="fa fa-plus text-success"></i> Create new lot</a>
		  <a class="dropdown-item viewcurrentlot redirect hide" href="<?php echo $app->siteUrl('viewlotitems');?>"><i class="fa fa-list"></i> View lot items</a> 
		  <a class="dropdown-item clearcurrentlot hide" href="#"><i class="fa fa-trash text-danger"></i>Clear this lot</a> 
	  </div>
	</li>
    <li class="nav-item hidden-md-down"> 
		<a id="appModalHtmlNotification" data-toggle="modal" data-target="#appModalHtml"  class="nav-link" href="#" data-position="left" data-step="3" data-title="Task Scheduling" data-intro="Add your task here. task will auto populate on your schedule time"><i class="icon-envelope"></i><span class="badge badge-pill badge-danger" id="complaint_global_log_count_header"></span></a> 
		</li>
	<li class="nav-item hidden-md-down"> <a id="appModalTagNotification" data-target="aside" class="nav-link navbar-toggler aside-menu-toggler ringing-bell" href="#" data-position="left" data-step="4" data-title="Tag Notification" data-intro="See your tagged information. This will auto populate when anyone tagged you"><i class="fa fa-bell faa-ring animated"></i><span class="badge badge-pill badge-danger" id="global_tag_count_header"></span></a> </li>
    <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <img src="<?=getResizeImage($_SESSION['user_image'],50)?>" class="img-avatar" alt="<?=$_SESSION['user_fname']?>"></a>
      <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item redirect" href="<?php echo $app->siteUrl('messages');?>"><i class="fa fa-envelope-o text-success"></i> Messages<span class="badge badge-success" id="complaint_global_log_count_dropdown"></span></a> <a class="dropdown-item redirect" href="<?php echo $app->siteUrl('profile');?>"><i class="fa fa-user text-primary"></i> Profile</a> <a class="logoffbtn dropdown-item" href="#"><i class="fa fa-sign-out text-warning"></i> Clock Out</a>
	  <a class="dropdown-item systheme" data-theme="dark" href="#"><i class="fa fa-moon"></i> <?php echo $_SESSION['app_theme'] == 'dark' ? '' : 'Enable'?> Night mode</a>
	  <a class="dropdown-item radiodatalistfilterstate" user-filter-state="<?php echo (isset($_SESSION['app_filter_state']) && $_SESSION['app_filter_state'] == 1) ? 1:0?>"><i class="fa <?php echo (isset($_SESSION['app_filter_state']) && $_SESSION['app_filter_state'] == 1) ? "text-success fa-toggle-on":"fa-toggle-off"?>"></i> <span class="filter_state_text"><?php echo (isset($_SESSION['app_filter_state']) && $_SESSION['app_filter_state'] == 1) ? "Disable":"Enable"?> Filter State</span></a>
      <a class="dropdown-item" href="#"><i class="fa fa-paint-brush"></i> 
      <i class="systheme fa fa-square" style="color:#20c997;" data-theme="teal"></i>  
      <i class="systheme fa fa-square" style="color:#f8cb00;" data-theme="yellow"></i>  
      <i class="systheme fa fa-square" style="color:#f86c6b;" data-theme="red"></i>  
      <i class="systheme fa fa-square" style="color:#3880ff;" data-theme="blue"></i>  
      <i class="systheme fa fa-square" style="color:#8e24aa;" data-theme="violet"></i>
      <i class="systheme fa fa-square" style="color:#5c6873;" data-theme="default"></i>
	  <i class="systheme fa fa-square" style="color:#FFFFFF;" data-theme="light"></i>
      </a>
      <a class="dropdown-item redirect" href="<?php echo $app->siteUrl('logout');?>"><i class="fa fa-power-off text-danger"></i> Logout</a>
      <!--<a class="dropdown-item" href="#"><i class="fa fa-locale"></i><div id="google_translate_element"></div></a>--> </div>
    </li>
  </ul>
</header>