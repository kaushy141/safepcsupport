<?php $formHeading	=	"404 Page";?>
<div class="row">
  <div class="col-md-12 m-x-auto pull-xs-none vamiddle" style="margin-top: 148.55px;">
    <div class="clearfix">
    <?php if(isset($message) && $message !="" ):?>      
      <h4 class="p-t-1 text-center text-warning"><i class="fa fa-warning fa-2x"></i></h4> 
      <p class="text-muted text-center"><?php echo $message; ?></p>
      <?php else:?>
      <h1 class="pull-left display-3 m-r-2">404</h1>      
      <h4 class="p-t-1">Oops! You're lost.</h4>
      <p class="text-muted">The page you are looking for was not found.</p>
      <?php endif; ?>
    </div>    
  </div>
</div>
