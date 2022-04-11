<div class="row">
   <style type="text/css">
.userimageblock{margin-top:8px; margin-bottom:40px; text-align:center; border-radius:2px; box-shadow: 1.294px 8px 25px 0 rgba(0,22,46,.1); padding: 17px 0px 12px;}
.usernameblock{ padding:3px 2px; overflow:hidden; font-weight:400;}
.userloginblock{ padding:2px 2px 2px;}
.usertimeblock{ padding:3px 2px 8px;}
</style>
  <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12"> 
    <!--<div class="carouselExampleSlidesOnly carousel slide" data-ride="carousel">
      <div class="carousel-inner" style="display:flex;">-->
    <div class="container-fluid">
      <div class="row flex-row usercontainer">
        <?php 
	 $notice = new EmployeeNotice();
	 $noticeUser = $notice->empNoticeSummary();
	  if($noticeUser)
	  {
		foreach($noticeUser  as $_noticeUser)
		{
			?>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
          <div class="userimageblock">
            <div class="text-center"> <img class="img-circle" src="<?php echo $_noticeUser['user_image']?>" height="90px" /></div>
            <div class="text-center usernameblock"> <?php echo limitText($_noticeUser['user_name'],20)?> </div>
            <div class="text-center userloginblock text-muted"> <?php echo $_noticeUser['notice_count']?> times noticed</div>
			<div class="text-center userloginblock text-muted">Last notice <?php echo date('d M-y', strtotime($_noticeUser['last_notice']))?> </div>
            <div class="text-center usertimeblock text-muted">
			<?php $noticeCount = explode(',', $_noticeUser['notice_list']);
			if(count($noticeCount)){
				$counter = 0;
				foreach($noticeCount as $_notice){
					?>
					<a  class="redirect" href="editempnotice/<?php echo $_notice;?>"><span class="badge badge-danger"><?php echo ++$counter; ?></span></a>
					<?php
				}
			}
			?>
			</div>
          </div>
        </div>
        <?php			
		}
	  }
	  
	  ?>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i> <?php echo $formHeading; ?>
      <div class="card-actions">
      <?php if(isAdmin()):?>
            <a data-title="Add New Notice" class="redirect" title="Add New Notice" href="<?php echo $app->siteUrl("addempnotice");?>"><i class="icon-plus icons font-2xl d-block m-t-2"></i></a>
	 <?php endif;?>
        </div>
      </div>
       <div class="block-fluid table-sorting clearfix">
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>Img</th>
			  <th>Date</th>
			  <th>Notice</th>
              <th>User</th>
              <th>Reason</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			//$Complaint = new Complaint(0);
			//echo $Complaint->getRecords();
			?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

var data = {
				"action"	:	"viewempnoticelist",				
		   };
$(document).ready(function() {
    $('#tblSortable').DataTable( {
        "processing": true,
        "serverSide": true,
		"bStateSave": true,
        "ajax":  {
            "url": "<?=$app->basePath("server_processing.php")?>",
            "type": "POST",
			"data": data
        },
		'fnCreatedRow': function (nRow, aData, iDataIndex) {
        $(nRow).attr('id', 'row_' + aData[6]); // or whatever you choose to set as the id
    },
		//"rowCallback": function( row, data, index ) {$('td', row).css('background-color', data[6]);},
		"order": [[ 1, 'desc' ]],
		columnDefs: [{ targets: [0,5], orderable: false },
					 { className: "hidden-xs hidden-md hidden-sm visible-lg", "targets": [ 4 ] }]
    } );
} );
	

</script> 
