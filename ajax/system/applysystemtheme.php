<?php

	$theme = 'default';
	$data  = sanitizePostData($_POST);
	extract($data);	
	$user = new Employee($_SESSION['user_id']);
	$oldTheme = $_SESSION['app_theme'];
	$_SESSION['app_theme'] = $theme;
	$css = '<link rel="stylesheet" data-theme="'.$theme.'" href="'.$app->cssPath('theme_'.$theme).'" type="text/css" />';
	$user->update(array('user_theme'=> $theme));
	echo json_encode(array("200", "success|Theme Applied successfully", $css, $app->cssPath('theme_'.$oldTheme)));

?>