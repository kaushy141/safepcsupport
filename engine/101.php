<?php $formHeading	=	"Session Expired";?>
<div class="row">
  <div class="col-md-12 m-x-auto pull-xs-none vamiddle" style="margin-top: 148.55px;">
    <div class="clearfix">
    <?php if(isset($message) && $message !="" ):?>      
      <h4 class="p-t-1 text-center text-warning"><i class="fa fa-warning fa-2x"></i></h4> 
      <p class="text-muted text-center"><?php echo $message; ?></p>
      <?php else:?>
      <h1 class="pull-left display-3 m-r-2">Session Expired</h1>      
      <h4 class="p-t-1">Oops! Your session expired.</h4>
      <p class="text-muted"><a class="" href="<?php echo $app->siteUrl('logout');?>">Logout</a> & Login again to continue...</p>
      <?php endif; ?>
    </div>    
  </div>
</div>
