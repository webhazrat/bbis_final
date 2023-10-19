<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');

	class LOCATION{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
        
		// blood-requests.php
		public function findAllDistrict(){
            $query = "SELECT * FROM tbl_districts";
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
                $res['msg'] = 'No content'; 
				echo json_encode($res);
			}
		}

		// blood-requests.php
        public function filterArea($districtId){
			$districtId = $this->fm->validation($districtId);
			$districtId = mysqli_real_escape_string($this->db->link, $districtId);
			$query = "SELECT * FROM tbl_upazilas WHERE districtId = '$districtId'";
			$result = $this->db->select($query);
			$res = [];
			if($result == true){
				$getData = array();
				while($value = $result->fetch_assoc()){
					$data[] = $value;
				} 
				$res['status'] = '200';
                $res['msg'] = 'OK';
				$res['data'] = $data;
				echo json_encode($res);
			}else{
				$res['status'] = '204';
                $res['msg'] = 'No content'; 
				echo json_encode($res);
			}
        }
		
		// blood-requests.php
		public function filterZone($areaId){
			$areaId = $this->fm->validation($areaId);
			$areaId = mysqli_real_escape_string($this->db->link, $areaId);
			$query = "SELECT * FROM tbl_unions WHERE upazillaId = '$areaId'";
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
                $res['msg'] = 'No content'; 
				echo json_encode($res);
			}
		}

	}

?>