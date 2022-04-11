<!-- Modal -->
<div id="appModal" class="modal open" role="dialog">
  <form name="modalform" id="modalform" onsubmit="return false;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">#</h4>
        </div>
        <div class="modal-notice popmsg" style="display:none;">
          <div class="card-inverse text-xs-center noticebox" style="padding:8px 5px;"> </div>
        </div>
        <div class="modal-body pb-0"></div>
        <div class="modal-footer mt-0"></div>
        <div class="modal-history log_row_box"></div>
      </div>
    </div>
    <input type="hidden" id="keyid" name="keyid" value="" />
  </form>
</div>

<div id="appModalQuick" class="modal open" role="dialog" style="z-index: 1035;">  
    <div class="modal-dialog modal-quick-view">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">#</h4>
        </div>
        <div class="modal-body pb-0 px-0"></div>
        <div class="modal-footer mt-0"></div>        
      </div>
    </div>  
</div>


<div id="appModalHtml" class="modal open" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Customer Messages</h4>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
          <a type="button" href="javascript:Redirect('messages');" class="btn btn-outline-success">Show Message</a>
        </div>
      </div>
    </div>
</div>

<div id="appModalMessage" class="modal open" role="dialog" style="background:rgba(0,0,0,0.6)">
    <div class="modal-dialog" style="margin:15% auto;">
      <div class="modal-content" style="padding:20px 20px;">        
        <div class="modal-body pb-0" style="font-size:17px; text-align:center;"></div> 
        <div class="modal-footer mt-0 >
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>       
      </div>
    </div>
</div>

<div id="appModalConfirm" class="modal open" role="dialog" style="background:rgba(0,0,0,0.4);">
    <div class="modal-dialog" style="margin:15% auto;">
      <div class="modal-content" style="padding:12px 10px;">        
        <div class="modal-body" style="font-size:17px;">Are you sure to add this record...?</div> 
        <div class="modal-footer" >
          <button type="button" id="appModalConfirmYes" class="btn btn-outline-success" data-dismiss="modal">Confirm</button>
          &nbsp; &nbsp;
          <button type="button" id="appModalConfirmNo" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
        </div>       
      </div>
    </div>
</div>

<!--Galley Modal-->
<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="image-gallery-title"></h4>
				<button type="button" class="close" data-dismiss="modal" style="margin-top: -23px;"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-x:hidden">
				<img id="image-gallery-image" class="img-responsive col-md-12" src="">
			</div>
			<div class="modal-footer">
				<button type="button" target="new" data-href="#" class="data-download btn btn-outline-success float-left"><i class="fa fa-download"></i> Download
				</button> &nbsp; 
				<button type="button" class="btn btn-outline-secondary float-left" id="show-previous-image"><i class="fa fa-arrow-left"></i>
				</button>

				<button type="button" id="show-next-image" class="btn btn-outline-secondary float-right"><i class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal opaque_bg" id="general-modal" tabindex="-1" role="dialog" aria-labelledby="generalModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">			
			<div class="modal-body modal_birth_body" style="background-image:url('<?php echo $app->siteUrl('img/birthday_bg.png')?>')">
				
			</div>
			<div class="modal-footer flex-space-around mt-0">
				<button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
			</button>
			</div>
		</div>
	</div>
</div>
<style type="text/css">.snowflake { color: #fff; font-size: 1.2em;  font-family: Arial;} .bsr{ text-shadow: 1px 2px 3px #f00;} .bsg{ text-shadow: 1px 2px 3px #0f0;} .bsb{ text-shadow: 1px 2px 3px #00f;} .bsy{ text-shadow: 1px 2px 3px #f8fc04;} .bss{ text-shadow: 1px 2px 3px #04fcde;}  .text-nrm{font-size: 1em;}
@-webkit-keyframes snowflakes-fall{0%{top:-10%}100%{top:100%}}@-webkit-keyframes snowflakes-shake{0%{-webkit-transform:translateX(0px);transform:translateX(0px)}50%{-webkit-transform:translateX(80px);transform:translateX(80px)}100%{-webkit-transform:translateX(0px);transform:translateX(0px)}}@keyframes snowflakes-fall{0%{top:-10%}100%{top:100%}}@keyframes snowflakes-shake{0%{transform:translateX(0px)}50%{transform:translateX(80px)}100%{transform:translateX(0px)}}.snowflake{position:fixed;top:-10%;z-index:9999;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;cursor:default;-webkit-animation-name:snowflakes-fall,snowflakes-shake;-webkit-animation-duration:10s,3s;-webkit-animation-timing-function:linear,ease-in-out;-webkit-animation-iteration-count:infinite,infinite;-webkit-animation-play-state:running,running;animation-name:snowflakes-fall,snowflakes-shake;animation-duration:10s,3s;animation-timing-function:linear,ease-in-out;animation-iteration-count:infinite,infinite;animation-play-state:running,running}.snowflake:nth-of-type(0){left:1%;-webkit-animation-delay:0s,0s;animation-delay:0s,0s}.snowflake:nth-of-type(1){left:10%;-webkit-animation-delay:1s,1s;animation-delay:1s,1s}.snowflake:nth-of-type(2){left:20%;-webkit-animation-delay:6s,.5s;animation-delay:6s,.5s}.snowflake:nth-of-type(3){left:30%;-webkit-animation-delay:4s,2s;animation-delay:4s,2s}.snowflake:nth-of-type(4){left:40%;-webkit-animation-delay:2s,2s;animation-delay:2s,2s}.snowflake:nth-of-type(5){left:50%;-webkit-animation-delay:8s,3s;animation-delay:8s,3s}.snowflake:nth-of-type(6){left:60%;-webkit-animation-delay:6s,2s;animation-delay:6s,2s}.snowflake:nth-of-type(7){left:70%;-webkit-animation-delay:2.5s,1s;animation-delay:2.5s,1s}.snowflake:nth-of-type(8){left:80%;-webkit-animation-delay:1s,0s;animation-delay:1s,0s}.snowflake:nth-of-type(9){left:90%;-webkit-animation-delay:3s,1.5s;animation-delay:3s,1.5s}.snowflake:nth-of-type(10){left:95%;-webkit-animation-delay:8s,2.5s;animation-delay:8,2.5s} .snowflake:nth-of-type(11){left:98%;-webkit-animation-delay:6s,2s;animation-delay:6s,2s} .snowflake:nth-of-type(12){left:8%;-webkit-animation-delay:5s,2s;animation-delay:5s,2s} .snowflake:nth-of-type(13){left:77%;-webkit-animation-delay:3s,8s;animation-delay:1s,3.9s} .snowflake:nth-of-type(14){left:48%;-webkit-animation-delay:1s,3s;animation-delay:3s,1.3s}
</style>

<?php if(time() >= strtotime(date('Y')."-12-23") && strtotime(date('Y-m-d')) <= strtotime(date('Y')."-12-27")){ ?>
<style> header.navbar .navbar-brand {background-image: url("../img/logo-christmas.png");}</style>
<script>
bottomNotificationStack.push("🎅🏻 Merry Christmas, <?php echo $_SESSION['user_fname']?> !");
bottomNotificationStack.push("<img src='<?php echo $app->siteUrl('img/santa2.gif')?>' height='50px; margin-top:-10px margin-bottom:-10px;'>");</script>
<?php if((!isset($_COOKIE['hideFestiveModal']) || $_COOKIE['hideFestiveModal'] != true)){?>
<div class="snowflakes" aria-hidden="true" data-title="🎅🏻 Hide Christmas animation for me"> 
<div class="snowflake bsr"> 🎊 </div> <div class="snowflake bsg"> ❆ </div> <div class="snowflake bsb"> 🎁 </div> <div class="snowflake bsr text-nrm">🎄</div> <div class="snowflake bsy"> ❄ </div> <div class="snowflake bsy"> 🎅🏻 </div>  <div class="snowflake bsr">🌟</div><div class="snowflake nrm"> ❆ </div>   <div class="snowflake bsb"> 🎉 </div><div class="snowflake bsg">🎅</div> <div class="snowflake bsy"> 🎄 </div> <div class="snowflake bsr"> 🎁 </div>
<?php }?>
<?php }?>


<?php if((!isset($_COOKIE['hideFestiveModal']) || $_COOKIE['hideFestiveModal'] != true)){?>
<?php if( time() >= strtotime(date('Y')."-04-02") && strtotime(date('Y-m-d')) <= strtotime(date('Y')."-04-03")){ ?>
<div class="snowflakes" aria-hidden="true" data-title="🌙 Hide Ramadan Kareem animation for me"> 
<div class="snowflake bsr"> 🌙 </div> <div class="snowflake bsg"> ❆ </div> <div class="snowflake bsb"> 💫 </div> <div class="snowflake bsr text-nrm"><center>🏵️<br/>Ramadan Kareem<br/><?php echo $_SESSION['user_fname']?></center></div> <div class="snowflake bsy"> 🌟 </div> <div class="snowflake bss"> ❆ </div> <div class="snowflake bsr"> 🏵️ </div> <div class="snowflake"> 🌙 </div> <div class="snowflake"> 💫 </div> <div class="snowflake"> 🌛 </div>   <div class="snowflake bsb"> 🏵️ </div><div class="snowflake bsg"> 🌟 </div> <div class="snowflake bsy"> 💫 </div> <div class="snowflake bsy"> ❆ </div> <div class="snowflake bsr"> 🌟 </div> <div class="snowflake bsg"> 🌟 </div> <div class="snowflake bsb"> 🌛 </div>
</div>
<?php }?>
<?php }?>


