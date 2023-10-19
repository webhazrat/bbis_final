<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');

	class PEOPLETYPE{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
						
		// people-type.php
		public function create($data){
			$res = [];
			if(empty($data['name'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Name required';						
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Status required';						
				echo json_encode($res);
			}else{
				$name 	= $this->fm->validation($data['name']);
				$status = $this->fm->validation($data['status']);
				$order = $this->fm->validation($data['order']);
				$author = $this->fm->validation($data['author']);

				$name 	= mysqli_real_escape_string($this->db->link, $name);
				$status = mysqli_real_escape_string($this->db->link, $status);
				$order = mysqli_real_escape_string($this->db->link, $order);
				
				$query = "SELECT id FROM tbl_people_type WHERE name = '$name'";
				$result = $this->db->select($query);
				if($result){
					$res['status'] = '208'; 
					$res['msg'] = 'People type already exist.';
					$res['data'] = $data;
					echo json_encode($res);
				}else{
					$query = "INSERT INTO tbl_people_type(name, author, status, ordering) VALUES('$name', '$author', '$status', '$order')";
					$result = $this->db->insert($query);
					if($result){
						$res['status'] = '200'; 	
						$res['msg'] = 'People type created';
						echo json_encode($res);
					}
				}
			}	
		}
        
		// people-type.php
		public function filterAuth($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$type		= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
				$key		= $this->fm->validation($info['key']);
				$val		= $this->fm->validation($info['value']);
				$key 		= mysqli_real_escape_string($this->db->link, $key);
				$val 		= mysqli_real_escape_string($this->db->link, $val);
				
				$columns = array('PT.id', 'PT.name', 'PT.id', 'PT.ordering', 'S.name', 'PT.createdAt');
				
				$query = "SELECT PT.*,
				S.name AS statusName, 
                U.name AS authorName 
				FROM tbl_people_type PT 
                LEFT JOIN tbl_users U ON PT.author = U.id 
				LEFT JOIN tbl_status S ON PT.status = S.id ";
				
				if(!empty($info['search']['value'])){
					$query .= " WHERE ";
					$searchArr = explode(' ', $info['search']['value']);
                    $count = count($searchArr);
					for ($i=0; $i < $count; $i++) { 
						$query .= " (PT.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR PT.createdAt LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR S.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " ) ";
						if($i < $count - 1){
							$query .= ' AND ';
						}
					}
					if($key !== 'all'){
						$query .= " AND PT.$key = '$val' ";
					}
				}

				if(empty($info['search']['value']) && $key !== 'all'){
					$query .= " WHERE PT.$key = '$val' ";
				}

				$query .= " GROUP BY PT.id ";

				if(array_key_exists('order', $info) && count($info['order']) > 0){
					$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
				}else{
					$query .= " ORDER BY PT.id DESC";
				}

				$query1 = '';
				if(array_key_exists('length', $info) && $info['length'] != -1){
					$query1 .= " LIMIT ".$info['start'].", ".$info['length'];
				}

				$result = $this->db->select($query.$query1);
				if($result) {
					$i = 0;
					$data = [];
					while ($value = $result->fetch_assoc()) {						
						$i++;
						$datum = array();
						$datum = $value;
						$datum['sn'] = $i;						
						$datum['count'] = '**';						
						$datum['statusName'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';
						$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);
						
						if($type === 'dataTable'){
							$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
							<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">';
							$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="typeUpdate"><i data-feather="edit"> </i>Edit</a><a href="#" data_id="'.$value['id'].'" id="typeDelete"><i data-feather="trash"> </i>Delete</a>';
							$datum['action'] .= '</div></div>';
						}
						$data[] = $datum; 
					}

					if($type == 'dataTable'){
						$query2 = "SELECT id FROM tbl_people_type ";
						if($key !== 'all'){
							$query2 .= " WHERE $key = '$val'";
						}
						
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
					$res['data'] = [];
					echo json_encode($res);
				}
			}
			
		}

		//people.php
		public function filter($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$key		= $this->fm->validation($info['key']);
				$val		= $this->fm->validation($info['value']);
				$key 		= mysqli_real_escape_string($this->db->link, $key);
				$val 		= mysqli_real_escape_string($this->db->link, $val);
								
				$query = "SELECT PT.* FROM tbl_people_type PT WHERE PT.$key='$val' AND PT.status='6' ";
				
				if(array_key_exists('ordering', $info)){
					$ordering = $this->fm->validation($info['ordering']);
					$ordering = mysqli_real_escape_string($this->db->link, $ordering);
					$query .=" ORDER BY PT.ordering $ordering";
				}else{
					$query .=" ORDER BY PT.id DESC";
				}
				$result = $this->db->select($query);
				if($result) {
					$i = 0;
					$data = [];
					while ($value = $result->fetch_assoc()) {						
						$i++;
						$datum = array();
						$datum = $value;
						$data[] = $datum; 
					}					
					$res['status'] = '200';
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
        
		// people-type.php
		public function findOne($id){
			$res = [];
            if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
            }else{
				$query = "SELECT * FROM tbl_people_type WHERE id='$id' LIMIT 1";
				$result= $this->db->select($query);
				if($result){
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

        // people-type.php
		public function update($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Name required';
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400';
				$res['msg'] = 'Status required';
				echo json_encode($res);
			}else{
				$name 		= $this->fm->validation($data['name']);
				$status 	= $this->fm->validation($data['status']);
				$order 		= $this->fm->validation($data['order']);

				$name 		= mysqli_real_escape_string($this->db->link, $name);
				$status 	= mysqli_real_escape_string($this->db->link, $status);
				$order 		= mysqli_real_escape_string($this->db->link, $order);

                $query = "SELECT id FROM tbl_people_type WHERE name = '$name' AND NOT id='$id'";
				$result = $this->db->select($query);
                if($result){
					$res['status'] = '208'; 
					$res['msg'] = 'People type already exist.';
					$res['data'] = $data;
					echo json_encode($res);
				}else{
                    $query = "UPDATE tbl_people_type SET name='$name', status='$status', ordering='$order' WHERE id='$id'";
                    $result = $this->db->update($query);
                    if($result){
                        $res['status'] = '200';
                        $res['msg'] = 'People type updated';
                        echo json_encode($res);
                    }
                }
			}
		}

        // people-type.php
        function delete($id){
            $res = [];
            if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
            }else{
                $query = "DELETE FROM tbl_people_type WHERE id='$id'";
                $result = $this->db->delete($query);
                if($result){
                    $res['status'] = '200';
                    $res['msg'] = 'People type deleted';
                    echo json_encode($res);
                }
            }
        }
        

		

	}

?>