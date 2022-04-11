<?php $formHeading	=	"Add Hardware Type(s)";?>
<?php 
if(isset($parameter1))
{
	$crm_email_template_id = $parameter1;
	$CrmEmailTemplate = new CrmEmailTemplate($crm_email_template_id);
	$data = $CrmEmailTemplate->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"updateemailtemplate";
		$formHeading	=	"Update Email Template";
		$btnText	=	"Update Email Template";
		include("engine/inc/emailtemplateform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");

?>