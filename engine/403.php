<?php $formHeading	=	"Permission denied";?>
<div class="row" style="background-image: url('https://live.safepcsupport.co.uk/img/system/anim/403_<?php echo rand(1,6)?>-min.gif');
background-repeat: no-repeat;
background-position: center;
background-size: contain;">
  <div class="col-md-12 m-x-auto pull-xs-none vamiddle" style="margin-top: 148.55px;">
    <div class="clearfix">
	  <h4 class="p-t-1 text-center text-warning"><i class="fa fa-warning fa-3x"></i></h4> 
      <h1 class="p-t-1 text-center display-3">Permission denied</h1>      
      <h4 class="p-t-1 text-center">You don't have permission to access this section</h4>
      <p class="p-t-1 text-center text-muted">Go to <a class="redirect" href="<?php echo $app->siteUrl('dashboard');?>">Dashboard</a></p>
     
    </div>    
  </div>
</div>
