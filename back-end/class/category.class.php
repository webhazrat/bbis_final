<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');

	class CATEGORY{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
        
		// categories.php, post-new.php, post-edit.php
		public function create($data){
            $res = [];
			if(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Category name required';
				echo json_encode($res);
			}else{
				$author = $this->fm->validation($data['author']);
				$name 	= $this->fm->validation($data['name']);
				$name 	= mysqli_real_escape_string($this->db->link, $name);
				$query = "SELECT name FROM tbl_category WHERE name='$name'";
				$result = $this->db->select($query);
				if ($result) {
					$res['status'] = '208';
					$res['msg'] = 'Category already exists';
					echo json_encode($res);
				}else{
					$query = "INSERT INTO tbl_category(name, author) VALUES('$name', '$author')";
					$result = $this->db->insert($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = 'Category created';
						echo json_encode($res);
					}
				}
			}			
		}

		// categories.php
        public function update($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Category name required';
				echo json_encode($res);
			}else{
				$id = $this->fm->validation($id);
				$name = $this->fm->validation($data['name']);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$name = mysqli_real_escape_string($this->db->link, $name);
				$query = "SELECT name FROM tbl_category WHERE name = '$name' AND NOT id = '$id'";
				$result = $this->db->select($query);
				if ($result) {
					$res['status'] = '208';
					$res['msg'] = 'Category already exists';
					echo json_encode($res);
				}else{
					$query = "UPDATE tbl_category SET name = '$name' WHERE id='$id'";
					$result = $this->db->update($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = 'Category updated';
						echo json_encode($res);
					}
				}
			}
        }
		
		// categories.php, post-new.php, post-edit.php
		public function filter($info){
			$res = [];
			$type = $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');

			$columns = array('C.id', 'C.name', 'U.name', 'C.id', 'C.createdAt');
			$query = "SELECT C.*, ";
			if($type == 'dataTable'){
				$query .= " count(P.category) AS countItem, ";
			}
			$query .= " U.name AS authorName 
			FROM tbl_category C  
			LEFT JOIN tbl_users U ON C.author = U.id 
			LEFT JOIN tbl_posts P ON C.id = P.category ";
			if(!empty($info['search']['value'])){
				$query .= " WHERE ";
				$query .= " (C.name LIKE '%".$info['search']['value']."%' ";
				$query .= " OR U.name LIKE '%".$info['search']['value']."%' ";
				$query .= " OR C.createdAt LIKE '%".$info['search']['value']."%' ";
				$query .= " ) ";
			}
			if($type == 'dataTable'){
				$query .= " GROUP BY C.id ";
			}
			if(array_key_exists('order', $info) && count($info['order']) > 0){
				$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
			}else{
				$query .= " ORDER BY C.id DESC";
			}
			$query1 = '';
			if(array_key_exists('length', $info) && $info['length'] != -1){
				$query1 .= " LIMIT ".$info['start'].", ".$info['length'];
			}
			$result = $this->db->select($query.$query1);
			if ($result){
				$i = 0;
				$data = [];
				while ($value = $result->fetch_assoc()) {
					$i++;
					$datum = array();
                    $datum = $value;
					$datum['sn'] = $i;
					$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);

					if($type == 'dataTable'){
						$datum['count'] = $value['countItem'];
						$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
						<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">
						<a href="#" data-name="'.$value['name'].'"  data-id="'.$value['id'].'" id="editCategory"><i data-feather="edit"></i> Edit </a><a href="#" data-id="'.$value['id'].'" id="delCategory"><i data-feather="trash"></i>Delete</a>
						</div></div>';
					}
					$data[] = $datum;
				}
                if($type == 'dataTable'){
					$query2 = "SELECT id FROM tbl_category";
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
				if($type == 'dataTable'){
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
		
		// categories.php
		public function delete($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$id = $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "DELETE FROM tbl_category WHERE id ='$id'";
				$result = $this->db->delete($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Category Deleted';
					echo json_encode($res);
				}
			}
		}

	}

?>