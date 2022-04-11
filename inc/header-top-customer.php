<header class="app-header navbar">
  <button class="navbar-toggler mobile-sidebar-toggler hidden-lg-up" type="button">☰</button>
  <a class="navbar-brand" href="<?=$app->siteUrl?>"></a>
  <ul class="nav navbar-nav hidden-md-down">
    <li class="nav-item"> <a class="nav-link navbar-toggler sidebar-toggler" href="#">☰</a> </li>
    <li class="nav-item px-1"> <a class="nav-link" href="javascript:Redirect('dashboard');">Dashboard</a> </li>
    <li class="nav-item px-1"> <a class="nav-link" href="javascript:Redirect('viewcustomercomplaint');">View Your Complaint </a> </li> 
    <li class="nav-item px-1"> <a class="nav-link" href="javascript:Redirect('viewcustomercollection');">View Collection </a> </li>    
    </li>
  </ul>
  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item hidden-md-down"> <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger" id="complaint_global_log_count_header"></span></a> </li>  
    <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <img src="<?=$app->imagePath($_SESSION['customer_image'])?>" class="img-avatar" alt="<?=$_SESSION['customer_fname']?>"> <span class="hidden-md-down"><?=$_SESSION['customer_fname']?></span> </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="javascript:Redirect('profile');"><i class="fa fa-user"></i> Profile</a>  <a class="dropdown-item" href="javascript:Redirect('logout');"><i class="fa fa-lock"></i> Logout</a> </div>
    </li>
    <!--<li class="nav-item hidden-md-down"> <a class="nav-link navbar-toggler aside-menu-toggler" href="javascript:void(0)">☰</a> </li>-->
  </ul>
</header>