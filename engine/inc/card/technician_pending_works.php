<div class="col-lg-12 col-md-12 col-xl-12 col-sm-12 mt-2 mb-1">
    <div class="card">
      <div class="card-header" data-step="8" data-title="Tech. Pending work" data-intro="Summarised Technician's pending work as per alloted"> <i class="fa fa-align-justify"></i> Technician's Pending Work </div>
      <div class="card-body pb-1 px-1">
        <div class="mt-1">
          <div class="row">
            <?php
			  $employee = new Employee();
			  $pendingWork = $employee->getTechnicianPendingWork();
			  if(count($pendingWork)){
				  foreach($pendingWork as $_work){
			  ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-header"><img class="img-circle" style="margin:-5px -3px -3px" alt="<?php echo $_work['user_fname']?>" src="<?php echo $_work['user_image'];?>" height="32px"> &nbsp; <?php echo limitText($_work['user_fname'] .' '. $_work['user_lname'], 15)?></div>
                <div class="card-body p-0">
                  <ul class="list-group mb-0">
                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Repair request <span class="badge badge-primary badge-pill"><?php echo $_work['complaint'];?></span> </li>
                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">Batch Product <span class="badge badge-primary badge-pill"><?php echo $_work['batch_product'];?></span> </li>
                  </ul>
                </div>
              </div>
            </div>
            <?php }
			  }?>
          </div>
        </div>
      </div>
    </div>
  </div>