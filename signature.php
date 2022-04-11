<?php include("setup.php"); ?>
<?php $app = new App();?>
<?php $login_page_id = md5(strtolower($_SERVER['SERVER_NAME'].$_SERVER['REMOTE_ADDR']."E"));?>
<?php
$_GET = sanitizePostData($_GET);
$c	= (isset($_GET['c']) && !is_array($_GET['c']))? strtolower($_GET['c']) : "";
$i	= (isset($_GET['i']) && !is_array($_GET['i']))? strtolower($_GET['i']) : "";
$v	= (isset($_GET['v']) && !is_array($_GET['v']))? strtolower($_GET['v']) : "";
$t	= (isset($_GET['t']) && !is_array($_GET['t']))? strtolower($_GET['t']) : "";
$validation = false;
if($c !="" && $i !="" & $v != ""& $t != "")
{
	if(strlen($c) == 32)
	{
		$s = new Signature();
		if($record = $s->getDetailsSecure(md5($i), md5($c))){
			if($record['signature_is_used'] == 0){
				if(strtotime($record['signature_created_date']) + $record['signature_validity_second'] > time()){ 
					$validation = true;
				}
				else
					$msg = "Link expired.";
			}
			else
				$msg = "Link already used";
		}
		else
			$msg = "Invalid Link found";
	}
	else
		$msg = "Invalid urlound";
}
elseif(isset($_POST['action']) && !is_array($_POST['action']) && $_POST['action'] == "savesignature")
{
	$signature_id = $signature_code = 0;
	if(isset($_POST['signature_id']) && !is_array($_POST['signature_id']) && $_POST['signature_id'] != ""){
		$signature_id = $_POST['signature_id'];
	}
	if(isset($_POST['signature_code']) && !is_array($_POST['signature_code']) && $_POST['signature_code'] != ""){
		$signature_code = $_POST['signature_code'];
	}
	$s = new Signature();
	if($record = $s->getDetailsSecure(md5($signature_id), md5($signature_code))){
		if($record['signature_is_used'] == 0){
			if(strtotime($record['signature_created_date']) + $record['signature_validity_second'] > time()){
				$signature   = str_replace('data:image/png;base64,', '', $_POST['signature']);
				$signature   = str_replace(' ', '+', $signature);
				$signature   = base64_decode($signature);
				$filePath    = 'upload/temp/' . time() . '.png';
				$fileBaseUrl = $app->sitePath($filePath);
				$fileUrl     = $app->basePath($filePath);
				if (file_put_contents($fileBaseUrl, $signature)){
					echo json_encode(array("200",  "success|Signature Saved successfully",
						$filePath,
						$fileUrl
					));
				}
				else{
					echo json_encode(array("300", "danger|Ooops..Signature could not saved on server"
					));
				}
			}else{
				echo json_encode(array("300", "danger|Ooops..Signature link expired"
				));
			}
		}else{
			echo json_encode(array("300", "danger|Ooops..Signature link already used"
			));
		}
	}
	die;	
}
elseif(isset($_POST['action']) && !is_array($_POST['action']) && $_POST['action'] == "acceptsignaturerequest")
{
	$signature_id = $signature_code = 0;
	$signature_path = "";
	if(isset($_POST['signature_id']) && !is_array($_POST['signature_id']) && $_POST['signature_id'] != ""){
		$signature_id = $_POST['signature_id'];
	}
	if(isset($_POST['signature_code']) && !is_array($_POST['signature_code']) && $_POST['signature_code'] != ""){
		$signature_code = $_POST['signature_code'];
	}
	if(isset($_POST['signature_path']) && !is_array($_POST['signature_path']) && $_POST['signature_path'] != ""){
		$signature_path = $_POST['signature_path'];
	}
	
	if(!is_array($signature_code) && !is_array($signature_path) && !is_array($signature_id)){
		if($signature_code != "" && $signature_path != "" && $signature_id != ""){
			if(file_exists($app->sitePath($signature_path))){
				$s = new Signature();
				if($record = $s->getDetailsSecure(md5($signature_id), md5($signature_code))){
					if($record['signature_is_used'] == 0){
						if(strtotime($record['signature_created_date']) + $record['signature_validity_second'] > time()){						
							$sign_path = "upload/user/sign/".getDirectorySeparatorPath()."link-" . time(). ".png";
							if (move_file($app->sitePath($signature_path), $app->sitePath($sign_path))){
								
								$signClass = new $record['signature_class']($record['signature_record_id']);
								$signClass->update(array($record['signature_column']=> $sign_path));
								
								$signature = new Signature($record['signature_id']);
								$signature->update(array("signature_is_used" => 1, "signature_path"=>$sign_path));
								
								echo json_encode(array("200", "success|Signature uploaded successfully"));
							}else
								echo json_encode(array("300",  "danger|Signature can't saved. try again."));
						}else
							echo json_encode(array("300",  "danger|Signature link expired."));
					}else
						echo json_encode(array("300",  "danger|Signature link already used."));
				}else
					echo json_encode(array("300",  "danger|Invalid Signature link."));
			}else
				echo json_encode(array("300",  "danger|Uploaded signature not found."));
		}else
			echo json_encode(array("300",  "danger|Invalid input found."));
	}else
		echo json_encode(array("300",  "danger|Input format is not correct."));
	die;	
}
else
	$msg = "Parameter requesred";
if($validation):
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=$app->siteDescription?>">
<meta name="keyword" content="<?=$app->siteKeyword?>">
<meta name="site-url" content="<?=$app->siteUrl?>">
<link rel="shortcut icon" href="<?=$app->siteIcon?>">
<title>Signature form</title>
<!-- Icons -->
<link href="<?=$app->cssPath('font-awesome.min')?>" rel="stylesheet">
<link href="<?=$app->cssPath('simple-line-icons')?>" rel="stylesheet">
<link href="<?=$app->cssPath('style-login')?>" rel="stylesheet">
</head>
<body class="">
<div class="container">
  <div class="row">
    <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
    
      <div class="card-group" id="card-group-form">
        <div class="card p-a-2">
          <div class="card-block">
            <h1>Signature 
            <div class="input-group m-b-1">
             <div class="col-md-6 m-x-auto pull-xs-none vamiddle" id="signature_content">
            <div class="form-group">
              <div id="signature-pad" class="m-signature-pad">
                <div class="m-signature-pad--body" style="border: 1px dashed #ddd; background:#FFF;">
                  <canvas></canvas>
					<input type="hidden" name="signature_path" id="signature_path" value="">
                </div>                
              </div>
            </div>
			
          </div>
            </div>
            <div class="input-group m-b-2">
              <center id="message"></center>
            </div>
            <div class="row">
              <div class="col-xs-6">
                
				  <button type="button" class="btn btn-primary p-x-2" data-action="save-png" onclick="saveSignature(event);">Upload Signature</button> 
				  <button type="button" class="btn btn-outline clear" onclick="clearSignature(event);" data-action="clear">Clear</button>
              </div>
              <div class="col-xs-6 text-xs-right">
                <a href="/" class="btn-link p-x-0">Back to site</a>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap and necessary plugins --> 
<script src="<?=$app->jsPath('jquery.min')?>"></script> 
<script src="<?=$app->jsPath('tether.min')?>"></script> 
<script src="<?=$app->jsPath('bootstrap.min')?>"></script> 
<script src="<?=$app->jsPath('signature_pad')?>"></script> 
<script type="text/javascript">
	var sitePath = '<?php echo $app->basePath()?>';
var wrapper = document.getElementById("signature-pad");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas);


function clearSignature(event)
{
	signaturePad.clear();
	$("#employee_signature").val('');
	$("#signature_saved_image").html('');
}

function saveSignature(event)
{
	if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
		var data={
			action	:	'savesignature',
			signature_code : '<?php echo $c;?>'	,
			signature_id : '<?php echo $i;?>'	,
			signature:signaturePad.toDataURL()				
		};		
		$.ajax({type:'POST', data:data, url:sitePath +'signature.php', 		
				beforeSend: function(){
				$("#message").text("Uploading Signature...", 0);
			},		
			success:function(output){ 
				var arr	=	JSON.parse(output);	
				if(arr[0]==200)
				{					
					$("#message").text(arr[1]);
					$("#signature_path").val(arr[2]);
					
					var data={
						action	:	'acceptsignaturerequest',
						signature_code : '<?php echo $c;?>'	,
						signature_id : '<?php echo $i;?>'	,
						signature_path : $("#signature_path").val()
					};
					$.ajax({type:'POST', data:data, url:sitePath +'signature.php', 		
							beforeSend: function(){
							$("#message").text("Saving Signature...", 0);
						},		
						success:function(output){ 
							var arr	=	JSON.parse(output);	
							if(arr[0]==200)
							{					
								$("#signature_content").html('');
								$("#signature_content").remove();
							}
							$("#message").text(arr[1]);
						}
					});
					
				}
			}
		});
    }
}
        </script>
</body>
</html>
	
<?php else:?>
	<p><?php echo $msg?>
<?php endif;?>