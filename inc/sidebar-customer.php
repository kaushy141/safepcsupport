<div class="sidebar">
  <div class="version_update"></div>
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item"> <a class="nav-link" href="<?php echo $app->siteUrl('dashboard'); ?>"><i class="icon-speedometer"></i> Dashboard <span class="badge badge-info">NEW</span></a> </li>
      <li class="nav-item nav-dropdown"> <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-outdent"></i> Request</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item"> <a class="nav-link redirect" data-title="Add Request" href="<?php echo $app->siteUrl('customerrequest'); ?>"><i class="icon-star"></i> Add Your Request(s)</a> </li>
          <li class="nav-item"> <a class="nav-link redirect" data-title="View All Request" href="<?php echo $app->siteUrl('viewcomplaintrequest'); ?>"><i class="icon-star"></i> View Your Request(s)</a> </li>
        </ul>
      </li>
      <li class="nav-item nav-dropdown"> <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-outdent"></i> Collection</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item"> <a class="nav-link redirect" data-title="View Complain Request" href="<?php echo $app->siteUrl('viewcustomercollection'); ?>"><i class="icon-star"></i> View Collection List</a> </li>
        </ul>
      </li>
      <li class="nav-item nav-dropdown"><a class="nav-link nav-dropdown-toggle"><i class="icon-wallet" style="color:#009688"></i> Sales</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item"> <a class="nav-link redirect" data-title="View Sales Invoice" href="<?php echo $app->siteUrl('viewsalesinvoice'); ?>"><i class="icon-list" ></i> View Sales Invoice</a> </li>
        </ul>
      </li>
	  <li class="nav-item nav-dropdown"> <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-outdent"></i> Website Order</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item"> <a class="nav-link redirect" data-title="View Complain Request" href="<?php echo $app->siteUrl('viewwebsiteorder'); ?>"><i class="icon-star"></i>All Order list</a> </li>
        </ul>
      </li>
    </ul>
  </nav>
</div>
