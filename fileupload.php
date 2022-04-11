<?php include("setup.php"); ?>
<?php include("config/session.php");?>
<?php include("config/fd/facedetector.php");?>
<?php $app = new App();?>
<?php 
//error_reporting(0);
$enable_face_detection = 0;
$field_handler	=	"";
if(isset($_POST["field_handler"]) && !is_array($_POST["field_handler"]) && $_POST["field_handler"]!="")
$field_handler	=	$_POST["field_handler"];

$supported_format = array("image/jpeg", "image/jpg", "image/png");
$Ori_min_width = $Ori_min_height = 250;
if($field_handler!="")
{
	$fileName = $_FILES[$field_handler]["name"];
	$fileTmpLoc = $_FILES[$field_handler]["tmp_name"];  
	$fileType = $_FILES[$field_handler]["type"]; 
	$fileSize = $_FILES[$field_handler]["size"];  
	$fileErrorMsg = $_FILES[$field_handler]["error"];
	
	//---------------------------------file check started------------------------------
						
	$image_name = pathinfo($_FILES[$field_handler]['name']);
	$extension = strtolower($image_name['extension']);
	$name=time()."_".rand(100000,999999).".".$extension;
	$target = "upload/temp/".$name;
	$crop_target = "upload/temp/crop_".$name;
	$crop_min_target = "upload/temp/crop_min_".$name;
	$crop_fd_target	 = "upload/temp/crop_fd_".$name;
	$_SESSION['UPLOAD'][$field_handler]['PIC']=$crop_target;
	if(move_uploaded_file($_FILES[$field_handler]['tmp_name'], $target)) 
	{
		$final_target_file = $target;
		$Ori_image = getimagesize($target,$info);
		//print_r($Ori_image);
		if(!in_array($Ori_image['mime'], $supported_format))
		{
			echo json_encode(array(300, "warning|Invalid File Format. It Should be of ".implode(" | ", $supported_format)));
			exit();
		}
		if(min($Ori_image[0], $Ori_image[1]) < $Ori_min_width)
		{
			echo json_encode(array(300, "warning|File Size is too small it should be minimum $Ori_min_width Pixel"));
			exit();
		}
		
		$Ori_width 	= $Ori_image[0];
		$Ori_height = $Ori_image[1];
		if($enable_face_detection)
		{
			$detector = new svay\FaceDetector('detection.dat');
			if($detector->faceDetect($target));
			{
				$fd = $detector->getFace();
				
				$w = $fd['w'];
				$x = $fd['x'];
				$y = $fd['y'];
				/*if($Ori_width-$x<$Ori_min_width)
				$x = $Ori_width - $Ori_min_width;
				
				if($Ori_height-$y<$Ori_min_height)
				$y = $Ori_width - $Ori_min_height;*/
				
				
				crop($target, $crop_fd_target, $w, $x, $y);
				$final_target_file = $crop_fd_target;
			}
		}
		//echo $final_target_file;die;
		crop($final_target_file, $crop_min_target, 34);
		crop($final_target_file, $crop_target, 250);
		$_SESSION['UPLOAD'][$field_handler]['PIC'] = $crop_target;
		echo json_encode(array(200, "success|File Uploaded Successfully", "<img src=\"".$app->basePath($crop_min_target)."\" />", $crop_target));
		//unset($final_target_file);						
	}
	else
	{
	  echo json_encode(array(300, "warning|File Could not Uploaded. Please try again"));
	}
}
else
echo json_encode(array(300, "danger|Ooops... File Upload handler not found. Please refresh and try again."));

function crop($image, $crop_image, $crop_size=200, $x=NULL, $y=NULL)
{
	$original_image = $image;
	$new_image = $crop_image;
	$image_quality = '99';
	
	// Get dimensions of the original image
	list( $current_width, $current_height) = getimagesize( $original_image );
	
	// Get coordinates x and y on the original image from where we
	// will start cropping the image, the data is taken from the hidden fields of form.
	$image_name = pathinfo($original_image);
	$image_extension = strtolower($image_name['extension']);
	
	$height = $width = min($current_width, $current_height);
	if($width < $crop_size){$height = $width = $crop_size;  }  
	
	$crop_width = $crop_height = $crop_size;
	if($x!=NULL && $y!=NULL)
	{
		$x1 = $x;
		$y1 = $y;
	}
	else
	{
		$x1 = round(($current_width - $width)/2);
		$y1 = round(($current_height - $height)/2);
	}
	
	$new = imagecreatetruecolor( $crop_width, $crop_height );
	if($image_extension == "png")
		$current_image = imagecreatefrompng( $original_image );
	if($image_extension == "jpg" || $image_extension == "jpeg")
		$current_image = imagecreatefromjpeg( $original_image );
		
	 imagealphablending($new, false);
	 imagesavealpha($new,true);
	 $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
	 imagefilledrectangle($new, 0, 0, $crop_width, $crop_height, $transparent);
 
	imagecopyresampled( $new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $width, $height );
	
	
	if($image_extension == "png")
		imagepng($new, $new_image, $image_quality/10 );
	if($image_extension == "jpg")
		imagejpeg( $new, $new_image, $image_quality );
		
}
?>