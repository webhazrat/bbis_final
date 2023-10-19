<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Helpers.php');
	include_once ($filepath.'/../helpers/Format.php');

	class MEDIA{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
        
		// media.php
		public function create($filename, $author){
			$query = "INSERT INTO tbl_media(name, author) 
			VALUES('$filename', '$author')";
			$result=$this->db->insert($query);
		}
		
		// media.php, post-new.php
		public function filter($info){
            $res = [];
			$type	= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
			$columns = array('M.id', 'M.name', 'M.id', 'U.name', 'M.author2', 'M.createdAt');
			$query = "SELECT M.*, 
            U.name as authorName 
			FROM tbl_media M
            LEFT JOIN tbl_users U ON M.author = U.id ";
			if(array_key_exists('load_more', $info) && $info['load_more']){
				$last_id = $info['last_id'];
				$limit = $info['length'];
				$query .= " WHERE id < '$last_id' ORDER BY M.id DESC LIMIT $limit";
				$query1 = '';
			}else{
				if(!empty($info['search']['value'])){
					$query .= " WHERE ";
					$searchArr = explode(' ', $info['search']['value']);
					$count = count($searchArr);
					for ($i=0; $i < $count; $i++) { 
						$query .= " (M.name LIKE '%".$info['search']['value']."%' ";
						$query .= " OR U.name LIKE '%".$info['search']['value']."%' ";
						$query .= " OR M.author2 LIKE '%".$info['search']['value']."%' ";
						$query .= " OR M.createdAt LIKE '%".$info['search']['value']."%' ";
						$query .= " ) ";
						if($i < $count - 1){
							$query .= ' AND ';
						}
					}
				}
				if(array_key_exists('order', $info) && count($info['order']) > 0){
					$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
				}else{
					$query .= " ORDER BY M.id DESC";
				}
				$query1 = '';
				if(array_key_exists('length', $info) && $info['length'] != -1){
					$query1 .= " LIMIT ".$info['start'].", ".$info['length'];
				}
			}
			$result = $this->db->select($query.$query1);
			if ($result) {
				$i = 0;
				$data = [];
				while ($value = $result->fetch_assoc()) {
					$path = realpath(dirname(__FILE__));
					$file_real_path = $path."/../../uploads/".$value['name'];
					$ext = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
					$file_path = '../uploads/'.$value['name'];
					if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'svg' || $ext == 'ico' || $ext == 'png' || $ext == 'gif'){
						$photo = '<img src="'.$file_path.'" alt="">';
					}else{
						$photo = '<i data-feather="file-text"></i>';
					}
					$i++;
					$datum = array();
                    $datum = $value;
					$datum['sn'] = $i;
					$datum['photoMod'] = $photo;
					$datum['namePhoto'] = '<a href="#" id="viewMedia" data-id="'.$value['id'].'"><span class="img">'.$photo.'</span>'.$value['name'].'</a>';
					$datum['filesize'] = file_exists($file_real_path) ? round(filesize($file_real_path)/1024, 2)."KB" : '-';

					$datum['filePath'] = $file_real_path;
					
					if($type == 'dataTable'){
						$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
						<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">
						<a href="#" data-id="'.$value['id'].'" data="'.$file_real_path.'" id="viewMedia"><i data-feather="eye"></i> View</a><a href="#" data-id="'.$value['id'].'" data="'.$file_real_path.'" id="delMedia"><i data-feather="trash"></i>Delete</a></div></div>';
					}
					$data[] = $datum;
				}

				if($type == 'dataTable'){
					$query2 = "SELECT id FROM tbl_media ";
					$all_rows = $this->db->rows($query2);
					$filtered_rows = $this->db->rows($query);
					
					$res['draw'] = intval($info['draw']);
					$res['recordsTotal'] = $all_rows;
					$res['recordsFiltered'] = $filtered_rows;
				}
				$res['status'] = '200';
				$res['data'] = $data;
				echo json_encode($res);
			}else{
				if($type === 'dataTable'){
					$res['draw'] = intval($info['draw']);
					$res['recordsTotal'] = 0;
					$res['recordsFiltered'] = 0;
				}
				$res['status'] = '204'; 
				$res['msg'] = 'No Content';
				$res['data'] = '';
				echo json_encode($res);
			}
		}
		
		// media.php
		public function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "SELECT M.*, 
				U.name AS author1 
				FROM tbl_media M 
				LEFT JOIN tbl_users U ON U.id = M.author 
				WHERE M.id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					$filepath = realpath(dirname(__FILE__));
					while($value = $result->fetch_assoc()){
						$file_real_path = $filepath.'/../../uploads/'.$value['name'];
						$file_size = getimagesize($file_real_path);
						$date = date('d, F Y', strtotime($value['createdAt']));
						$time = date('h:i A', strtotime($value['createdAt']));
						$datum = $value;
						$datum['url'] = BASE_URL.'/uploads/'.$value['name'];
						$datum['dateTimeMod'] = $date.' : '.$time;
						$datum['size'] = round(filesize($file_real_path)/1024, 2)." KB";
						$datum['dimensions'] = $file_size["0"].'px × '.$file_size["1"].'px';
					}
					$data[] = $datum;
					$res['status'] = '200';
					$res['data'] = $data;
					echo json_encode($res);
				}else{
					$res['status'] = '204'; 
					$res['msg'] = 'No Content';
					echo json_encode($res);
				}
			}
		}
		
		// media.php
		public function delete($id, $path){
			$res = [];
			if(empty($id) || empty($path)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "DELETE FROM tbl_media WHERE id='$id'";
				$result = $this->db->delete($query);
				if($result){
					if(file_exists($path)){
						unlink($path);
					}
					$res['status'] = '200';
					$res['msg'] = 'OK';
					echo json_encode($res);
				}
			}
		}



	}

?>