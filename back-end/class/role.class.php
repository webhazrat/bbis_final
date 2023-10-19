<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');

	class ROLE{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
        
		// verified
		public function findAll(){
			$query = "SELECT * FROM tbl_role";
			$result = $this->db->select($query);
            $res = [];
			if($result == true){
				while($value = $result->fetch_assoc()){
					$data[] = $value;
				}
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $data;
				echo json_encode($res);
			}else{
				$res['status'] = '204'; 
				$res['msg'] = 'No Content';
				echo json_encode($res);
			}
		}




	}

?>