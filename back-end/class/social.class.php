<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	
	class SOCIAL{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}

		// profile.php
		public function create($data){
			$res = [];
			if(empty($data['userId'])){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['title'])){
				$res['status'] = '400';
				$res['msg'] = 'Title required';
				echo json_encode($res);
			}elseif(empty($data['url'])){
				$res['status'] = '400';
				$res['msg'] = 'URL required';
				echo json_encode($res);
			}elseif(filter_var($data['url'], FILTER_VALIDATE_URL) === false){
				$res['status'] = '400';
				$res['msg'] = 'Invalid URL';
				echo json_encode($res);
			}else{
				$id 	= $this->fm->validation($data['userId']);
				$title	= $this->fm->validation($data['title']);
				$url    = $this->fm->validation($data['url']);
				
				$title 	= mysqli_real_escape_string($this->db->link, $title);
				$url 	= mysqli_real_escape_string($this->db->link, $url);

                $query2 = "SELECT id FROM tbl_user_social WHERE title='$title' AND memberId='$id'";
                $result2 = $this->db->select($query2);
                if($result2){
                    $res['status'] = '208';
					$res['msg'] = $title.' already exists';
					echo json_encode($res); 
                }else{
                    $query = "INSERT INTO tbl_user_social(title, url, memberId) VALUES('$title', '$url', '$id')";
                    $result = $this->db->insert($query);
                    if($result == true){
                        $res['status'] = '200';
                        $res['msg'] = 'Social added successfully';
                        echo json_encode($res);
                    }
                }
			}
		}
        
		// profile.php, users.php
		public function filterAuth($data){
			$res = [];
			if(empty($data['key']) || empty($data['value'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Key value required';
				echo json_encode($res);
			}else{
				$key	= $this->fm->validation($data['key']);
				$val	= $this->fm->validation($data['value']);
				$key 	= mysqli_real_escape_string($this->db->link, $key);
				$val 	= mysqli_real_escape_string($this->db->link, $val);

				$query = "SELECT * FROM tbl_user_social WHERE $key='$val'";
				$result = $this->db->select($query);
				if($result){
					$data = [];
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
					$res['data'] = [];
					echo json_encode($res);
				}
			}			
		}
        
		// profile.php
		public function delete($id, $userId){
			$res = [];
			if(empty($id) || empty($userId)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "DELETE FROM tbl_user_social WHERE id='$id' AND memberId='$userId'";
				$result = $this->db->delete($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Social item removed';
					echo json_encode($res);
				}else{
					$res['status'] = '200';
					$res['msg'] = 'Bad request';
					echo json_encode($res);
				}
			}
		}
		



	}

?>