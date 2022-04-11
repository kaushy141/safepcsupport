<div class="row">
  
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header"> 
      	<i class="fa fa-align-justify"></i> Schedule List 
        <div class="card-actions">
            <a data-title="Add New Schedule" title="Add New Schedule" onclick="createSchedule()" href="#"><i class="icon-user-follow icons font-2xl d-block m-t-2"></i></a>
        </div>
      </div>
      <div class="table-responsive">
       <div class="block-fluid table-sorting clearfix">
       
        <table id="tblSortable" class="table table-striped">
          <thead>
            <tr>
              <th>User</th>
              <th>Customer</th>
              <th>Title</th>
              <th>Schedule</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
			$Schedule = new Schedule(0);
			echo $Schedule->getRecords();
			?>
          </tbody>
          <tfoot>
          </tfoot>
        </table> 
       </div>       
      </div>
    </div>
  </div>
  <!--/col--> 
</div>
<script type="text/javascript">

$("#tbldatatable").dataTable({"iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100], "sPaginationType": "full_numbers", "aoColumns": [ { "bSortable": false }, null, null, null, null]});

$("#tbldatatable").dataTable({"iDisplayLength": 10, "sPaginationType": "full_numbers","bLengthChange": false,"bFilter": false,"bInfo": false,"bPaginate": true, "aoColumns": [ { "bSortable": false }, null, null, null, null]});

</script> 
