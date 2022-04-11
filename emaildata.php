<?php 
include('config.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Email Data</title>
<script type="text/javascript">
function submitform()
{
	var filter = document.getElementById('filter').value;
	if(filter!="")
	document.form.submit();
	else
	return false;
}
</script>
</head>
<body style="margin:0px; padding:0px; background:#EAEAEA; font-family:Arial, Helvetica, sans-serif;">
<div style="background:#FFF; margin:10px auto; min-height:400px; width:80%; border-radius:2px;">
<div style="background:#252525; padding:10px 20px; text-align:center;">
<h3 style="color:#FFF;">Email data</h3>
</div>
<div style="background:#595959; padding:10px 20px; text-align:center;">
<form name="form" id="form" method="post">
<select id="filter" onchange="return submitform();"  name="filter" style="width:100%; padding:8px 0px;">
	<option value="">  -- Select Filer by Type -- </option>
    <?php
    $sql = "SELECT Distinct `customer_filter`, count(`customer_id`) as countsum FROM `xcel_customer_data` WHERE 1 GROUP BY `customer_filter` ORDER by `customer_filter` ";
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result))
	{
		while($row = mysqli_fetch_assoc($result)){
		if(trim($row['customer_filter'])!="")
			echo "<option value=\"$row[customer_filter]\">  $row[customer_filter]  { $row[countsum] }</option>";
		}
	}
	?>
</select>
</form>
</div>
<div style="padding:10px; word-wrap:break-word;">
<?php
if(isset($_POST['filter']) && $_POST['filter']!="")
{
	$sql = "SELECT `customer_email` FROM `xcel_customer_data` WHERE TRIM(LOWER(`customer_filter`)) = TRIM(LOWER('$_POST[filter]')) ";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result))
	{
		while($data = mysqli_fetch_assoc($result))
		echo $data['customer_email'].", ";
	}
}
?>
</div>
</div>
</body>
</html>