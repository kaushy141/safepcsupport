<div class="row">
  <div class="col-md-12 m-x-auto pull-xs-none vamiddle" style="margin-top: 148.55px;">
    <div class="clearfix">
     
      <h4 class="p-t-1 text-center text-warning"><i class="fa fa-warning fa-2x"></i></h4> 
      <p class="text-center"><?php echo $message; ?></p>
     
      <p class="text-center">
      <?php if(isset($backlink) && $backlink !=""){
		  echo $backlink;
		  }else{?>
      <a class="btn btn-warning" href="javascript:goBack()">BACK</a>
      <?php }?>
      </p>
      
    </div>    
  </div>
</div>
<script>
function goBack(){
	window.history.back();
}
</script>
