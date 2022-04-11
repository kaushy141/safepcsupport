<aside class="aside-menu">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabtagtimeline" aria-action-tag="marktagallreaded" aria-controls="tabtagtimeline" aria-selected="false" role="tab"><i class="icon-tag"></i> Tag <sup class="badge badge-pill-xs badge-info text-white"></sup></a> </li>
	<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabpaymentreminder" aria-action-tag="markallpaymentreminderreaded" aria-controls="tabpaymentreminder" aria-selected="false" role="tab"><i class="icon-wallet"></i> Pay <sup class="badge badge-pill-xs badge-info text-white"></sup></a> </li>
	<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabgeneralaction" aria-action-tag="" aria-controls="tabgeneralaction" aria-selected="false" role="tab"><i class="icon-drop"></i> Action <sup class="badge badge-pill-xs badge-info text-white"></sup></a> </li>
  </ul>
  
  
  <div class="tab-content">
    <div class="tab tab-pane active" id="tabtagtimeline" role="tabpanel">
		<div class="tabdetails">
			<div class="card-body d-flex align-items-center  justify-content-between p-1">
				<div class="pl-0 justify-content-start">Tag timeline<br/></div>
				<div class="pl-1 justify-content-center">
				  <div class="text-right"><a class="redirect btn btn-info btn-xs" href="<?php echo $app->basePath('tagnotification')?>">view all</a><br/><a class="text-small markallreadanchor" data-action="marktagallreaded" href="#"><small>Mark all read</small></a></div>
				</div>
			  </div>
		</div>
		<div class="notification_content" aria-action-content="marktagallreaded"></div>
    </div>
	<div class="tab tab-pane" id="tabpaymentreminder" role="tabpanel">
		<div class="tabdetails">
			<div class="card-body d-flex align-items-center  justify-content-between p-1">
				<div class="pl-0 justify-content-start">Payment reminder</div>
				<div class="pl-1 justify-content-center">
				  <div class="text-right"><a class="redirect btn btn-info btn-xs" href="<?php echo $app->basePath('paymentreminderlist')?>">view all</a><br/><a class="text-small markallreadanchor" data-action="markallpaymentreminderreaded" href="#"><small>Mark all read</small></a></div>
				</div>
			  </div>
		</div>
		<div class="notification_content" aria-action-content="markallpaymentreminderreaded"></div>
    </div>
	<div class="tab tab-pane" id="tabgeneralaction" role="tabpanel">
		<div class="tabdetails">
			<div class="card-body d-flex align-items-center  justify-content-between p-1">
				<div class="pl-0 justify-content-start">General action</div>
				<div class="pl-1 justify-content-center">
				  <div class="text-right"><a class="redirect" href="<?php echo $app->basePath('paymentreminderlist')?>">view all</a></div>
				</div>
			  </div>
		</div>
		<div class="notification_content" aria-action-content=""></div>
    </div>	
  </div>
</aside>
