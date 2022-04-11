<?php
if(isset($_SESSION['app_log_type']))
{
	$app->logLogout();
	session_destroy();	
}
?>
<script type="text/javascript">
window.location='main.php';
</script>