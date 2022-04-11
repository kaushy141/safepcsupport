<?php
	echo json_encode(array("200", "success|All tags marked readed", UserTag::markUserPaymentReminderAllRead()));
?>