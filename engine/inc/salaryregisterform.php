<div class="row" id="collection_form_container">
  <div class="col-sm-12">
    <div class="card" id="card-detail-print">
      <div class="card-header card-primary"><i class="fa fa-newspaper-o"></i> Salary Register</b>
      </div>
      <div class="card-block">
        <div class="row">
        <div class="col-xs-12">            
            <table id="tablesalaryregister" class="table table-bordered table-responsive display responsive nowrap" width="100%">
            <tr>
            <th>Employee</th>
            <?php 			
			for ($i = MAX_SALARY_REGISTER_VIEW; $i >= 0; $i--) {
				echo "<th class=\"text-center\">". date("M-y", strtotime( date( 'Y-m-01' )." -$i months")) . "</th>";
			}
			echo "<th>Due</th>";
			
			$sRs = new SalaryRegister();
			$employeePayRecords = $sRs->getEmployeePayRecords();
			//echo "<pre>";
			//print_r($employeePayRecords);
			//echo "</pre>";
			foreach($employeePayRecords as $record){
				$user = $record['user'];
				$pay = $record['pay'];
				echo "<tr>";
					echo "<td data-join='".$user['user_pay_joining_date']."'>".($user['user_status'] ? "<i title='Active' class='fa fa-check-circle text-success'></i>":"<i title='Inactive' class='fa fa-check-circle text-muted'></i>")." $user[user_name]</td>";
					$unPaidBalance = 0;
					for ($i = MAX_SALARY_REGISTER_VIEW; $i >= 0; $i--) {
						$month_id = date("ym", strtotime( date( 'Y-m-01' )." -$i months"));
						echo "<td class=\"text-center\">";
						if(isset($pay[$month_id])){
							
								if($pay[$month_id]['pay_slip_paid_status'] == PAY_STATUS_PAID)
								{
									echo "<div class=\"btn-group\">
								<button type=\"button\" class=\"btn btn-success dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Details
								</button>
								<div class=\"dropdown-menu dropdown-menu-right\"><a data-id=\"$user[user_id]\" data-month-id=\"$month_id\" class=\"dropdown-item salaryoptionbtn\"><i class=\"fa fa-eye\"></i> View </a>
								<a target=\"new\" href=\"".DOC::PAYSLIP($pay[$month_id]['pay_slip_id'])."\" class=\"dropdown-item \"><i class=\"fa fa-download\"></i> Download</a></div></div>";
								}
								else
								{									
									$unPaidBalance += $pay[$month_id]['pay_slip_grant_pay'];
									echo "<a data-id=\"$user[user_id]\" data-month-id=\"$month_id\" class=\"salaryoptionbtn btn btn-primary text-white\">Unpaid</a>";}
							}
							elseif((int)$month_id >= (int)date('ym', strtotime($user['user_pay_joining_date'])))
								echo "<a data-id=\"$user[user_id]\" data-month-id=\"$month_id\" class=\"salaryoptionbtn btn btn-default\">Generate</a>";
							else
								echo "&nbsp;";
						echo "</td>";
					}
					echo "<td class=\"text-center\">".CURRENCY."$unPaidBalance</td>";
				echo "</tr>";	
			}
			?>
            </tr>
            </table>
        </div>          
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$('#tablesalaryregister').DataTable( {
    responsive: true,
	details: false
} );
</script>