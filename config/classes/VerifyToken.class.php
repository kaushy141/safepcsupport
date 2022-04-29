<?php
final class VerifyToken extends DB{
		
	public $id;
	public $key_id	=	"token_id";
	public $table_name	=	"app_verification_token";
	public $status	=	"token_is_used";
	public static $token_section = 'Default';
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
						
	function getDetails()
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	static function getTokenId($hash)
	{
		$sql="SELECT * FROM `app_verification_token` WHERE MD5(`token_id`) = '".$hash."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){	
			$data= $dbc->db_fetch_assoc(true);
			return $data['token_id'];
		}
		else
			return false;
	}
	
	function geneareTokenId($token_code, $token_section = 'Default'){
		$token_code = rand(10000000, 99999999);
		// `token_section`, `token_code`, `token_created_date`, `token_expiration_date`, `token_is_used`, `token_use_date`, `token_created_by`, `token_used_ip`
		$tokenData = array(
			"token_section" => $token_section,
			"token_code" 	=> $token_code,
			"token_created_date" => 'NOW()',
			"token_is_used" => 0,
			"token_created_by" => getLoginId()
		);
		return $this->insert($tokenData);
	}
	
	function getDetailsByCode($token_code)
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `token_code` = '".$token_code."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function verify($token_code, $token_section = 'Default'){
		$sql="SELECT `".$this->key_id."` FROM `".$this->table_name."` WHERE  `token_code` = '".$token_code."' AND  `token_section` = '".$token_section."' AND `token_is_used` = '1'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function isValid(){
		$tokenData = $this->getDetails();
		return $tokenData['token_is_used'] == 0;
	}
	
	function markUsed(){
		$sql="SELECT * FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			$record = $dbc->db_fetch_assoc(true);
			$verifyToken = new VerifyToken($record['token_id']);
			$verifyToken->update(
				array(
					"token_is_used" => 1,
					"token_use_date" => 'NOW()',
					"token_used_ip" => IP_ADDRESS
				)
			);
		}
	}
}
?>