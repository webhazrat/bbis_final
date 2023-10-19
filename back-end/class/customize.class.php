<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	

	class CUSTOMIZE{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}

        public function create($data){
			$res = [];
			foreach($data as $datum){
				if(empty($datum->key)){
					$res['status'] = '400';
					$res['msg'] = 'Key required'; 
					echo json_encode($res);
				}else{
					$key	 = $this->fm->validation($datum->key);
					$value	 = $this->fm->validation($datum->value);
					$query 	= "SELECT id FROM tbl_option WHERE optionKey = '$key'";
					$result = $this->db->select($query);
					if($result){
						$query 	= "UPDATE tbl_option SET optionValue = '$value' WHERE optionKey = '$key'";
						$result = $this->db->update($query);
					}else{
						$query 	= "INSERT INTO tbl_option(optionKey, optionValue) VALUES('$key', '$value')";
						$result = $this->db->insert($query);
					}					
				}
			}
			if($result){
				$res['status'] = '200';
				$res['msg'] = 'Pulished successfully';
				echo json_encode($res);
			}

		}
        
		public function filter($data){
			$res = [];
			$keys = "'".implode("','", $data->keys)."'";
			if(!empty($keys)){
				$query = "SELECT O.*, M.name AS mediaName 
				FROM tbl_option O 
				LEFT JOIN tbl_media M ON O.optionValue = M.id 
				WHERE O.optionKey IN ($keys)";
				$result = $this->db->select($query);
				if($result){
					$data = array();
					while ($value = $result->fetch_assoc()) {
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


	}

?>