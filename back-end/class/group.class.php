<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	
	class GROUP{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}
		
		// species-group.php (admin auth)
		public function create($data){
			$res = [];
			if($data['endLevel'] == 'true' && empty($data['photo'])){
				$res['status'] = '400';
				$res['msg'] = 'Group photo required';
				echo json_encode($res);
			}elseif(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Group name required';
				echo json_encode($res);
			}elseif(empty($data['slug'])){
				$res['status'] = '400';
				$res['msg'] = 'Group slug required';
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400';
				$res['msg'] = 'Status required';
				echo json_encode($res);
			}else{
				$author	 	= $this->fm->validation($data['author']);
				$photo	 	= $this->fm->validation($data['photo']);
				$name	 	= $this->fm->validation($data['name']);
				$slug 		= $this->fm->validation($data['slug']);
				$parent 	= $this->fm->validation(empty($data['parent']) ? '-1' : $data['parent']);
				$endLevel 	= $this->fm->validation($data['endLevel'] == 'true' ? 'true' : 'false');
				$description= $this->fm->validation($data['description']);
				$ordering	= $this->fm->validation($data['ordering']);
				$status		= $this->fm->validation($data['status']);
	
				$author 	= mysqli_real_escape_string($this->db->link, $author);
				$photo 		= mysqli_real_escape_string($this->db->link, $photo);
				$name 		= mysqli_real_escape_string($this->db->link, $name);
				$slug 		= mysqli_real_escape_string($this->db->link, $slug);
				$parent		= mysqli_real_escape_string($this->db->link, $parent);
				$endLevel	= mysqli_real_escape_string($this->db->link, $endLevel);
				$description= mysqli_real_escape_string($this->db->link, $description);
				$ordering 	= mysqli_real_escape_string($this->db->link, $ordering);
				$status 	= mysqli_real_escape_string($this->db->link, $status);
				
				$slug = $this->fm->format_uri($slug);

				$query = "SELECT id FROM tbl_group WHERE name='$name' OR slug='$slug'";
				$result = $this->db->select($query);
				if($result){
					$res['status'] = '208';
					$res['msg'] = 'Group already exists';
					echo json_encode($res);
				}else{
					if($parent !== '-1'){
						$query1 = "SELECT hierarchyPath FROM tbl_group WHERE id='$parent' LIMIT 1";
						$result1 = $this->db->select($query1)->fetch_assoc();
					}else{
						$result1['hierarchyPath'] = '';
					}
					
					$query = "INSERT INTO tbl_group(photo, name, slug, parent, endLevel, description, author, status, ordering) VALUES('$photo', '$name', '$slug', '$parent', '$endLevel', '$description', '$author', '$status', '$ordering')";
					$result = $this->db->insert($query);
					if($result){
						$id = $this->db->link->insert_id;
						$hierarchyPath = $result1['hierarchyPath'] ? $result1['hierarchyPath'].'-'.$id : $id;
						$query = "UPDATE tbl_group SET hierarchyPath='$hierarchyPath' WHERE id='$id'";
						$result = $this->db->insert($query);
						if($result){
							$res['status'] = '200';
							$res['msg'] = 'Group added successfully';
							$res['data'] = $data;
							echo json_encode($res);
						}
					}
				}
			}
		}


		// service function
		public function hierarchy($path){
			$ids = explode('-', $path);
			$names = [];
			foreach($ids as $id){
				$query = "SELECT name FROM tbl_group WHERE id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while ($value = $result->fetch_assoc()) {
						array_push($names, $value['name']);
					}
				}
			}
			return $names;
		}

		
		// home.php, groups.php, contribute.php, group-new.php, group-edit.php
		public function filter($info){
			$res = [];
			$key	= $this->fm->validation(array_key_exists('key', $info) ? $info['key'] : '');
			$val	= $this->fm->validation(array_key_exists('value', $info) ? $info['value'] : '');
			$limit	= $this->fm->validation(array_key_exists('limit', $info) ? $info['limit'] : '');
			
			$query = "SELECT SG.*, 
			SGP.name AS parentName, 
			COUNT(DISTINCT SP.id) AS spTotalCount, 
			COUNT(DISTINCT SP2.id) AS spApprovedCount,
			U.name AS authorName, 
			M.name AS mediaName, 
			S.name AS statusName
			FROM tbl_group SG
			LEFT JOIN tbl_users U ON U.id = SG.author 
			LEFT JOIN tbl_species SP ON SP.groupId = SG.id 
			LEFT JOIN tbl_species SP2 ON SP2.groupId = SG.id AND SP2.status = '8'
			LEFT JOIN tbl_group SGP ON SG.parent = SGP.id 
			LEFT JOIN tbl_media M ON M.id = SG.photo 
			LEFT JOIN tbl_status S ON S.id = SG.status WHERE SG.status = '6' ";
			if(!empty($key) && !empty($val)){
				$query .= " AND SG.$key = '$val' ";
			}
			$query .=" GROUP BY SG.id";
			
			if(array_key_exists('ordering', $info)){
				$ordering = $this->fm->validation($info['ordering']);
				$ordering = mysqli_real_escape_string($this->db->link, $ordering);
				$query .=" ORDER BY SG.ordering $ordering";
			}else{
				$query .= " ORDER BY SG.id DESC";
			}
			
			if($limit){
				$query .= " LIMIT ".$limit;
			}

			$result = $this->db->select($query);
			if ($result) {
				$data = [];
				while ($value = $result->fetch_assoc()) {
					$datum = array();
					$datum = $value;
					$datum['hierarchyName'] = $this->hierarchy($value['hierarchyPath']);
					$datum['description'] = htmlspecialchars_decode($value['description']);
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

		// species-group.php, users.php, species-add.php
		public function filterAuth($info){
			$res = [];
			$type	= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
			$status	= $this->fm->validation(array_key_exists('status', $info) ? $info['status'] : '');
			$key	= $this->fm->validation(array_key_exists('key', $info) ? $info['key'] : '');
			$val	= $this->fm->validation(array_key_exists('value', $info) ? $info['value'] : '');
			$role	= $this->fm->validation(array_key_exists('role', $info) ? $info['role'] : '');
			
			$columns = array('SG.id', 'SG.name', 'SGP.name', 'SG.endLevel', 'spTotalCount', 'spApprovedCount', 'U.name', 'SG.ordering', 'S.name', 'SG.createdAt');

			$query = "SELECT SG.*, 
			SGP.name AS parentName, 
			COUNT(DISTINCT SP.id) AS spTotalCount, 
			COUNT(DISTINCT SP2.id) AS spApprovedCount,
			U.name AS authorName, 
			M.name AS mediaName, 
			S.name AS statusName
			FROM tbl_group SG
			LEFT JOIN tbl_users U ON U.id = SG.author 
			LEFT JOIN tbl_species SP ON SP.groupId = SG.id 
			LEFT JOIN tbl_species SP2 ON SP2.groupId = SG.id AND SP2.status = '8'
			LEFT JOIN tbl_group SGP ON SG.parent = SGP.id 
			LEFT JOIN tbl_media M ON M.id = SG.photo 
			LEFT JOIN tbl_status S ON S.id = SG.status ";

			if(!empty($info['search']['value']) && $type == 'dataTable'){
				$query .= " WHERE ";
				$searchArr = explode(' ', $info['search']['value']);
				$count = count($searchArr);
				for ($i=0; $i < $count; $i++) { 
					$query .= " (SG.name LIKE '%".$searchArr[$i]."%' ";
					$query .= " OR SGP.name LIKE '%".$searchArr[$i]."%' ";
					$query .= " OR SG.endLevel LIKE '".$searchArr[$i]."%' ";
					$query .= " OR U.name LIKE '%".$searchArr[$i]."%' ";
					$query .= " OR SG.ordering LIKE '%".$searchArr[$i]."%' ";
					$query .= " OR SG.createdAt LIKE '%".$searchArr[$i]."%' ";
					$query .= " ) ";
					if($i < $count - 1){
						$query .= ' AND ';
					}
				}
				if($role === 'groupmanager'){
					$query .= " AND SG.id IN ($val) AND SG.endLevel = 'true' ";
				}
				if($role = 'admin' && !empty($key) && !empty($val)){
					$query .= " AND SG.$key = '$val' ";
				}
			}
			if($role === 'groupmanager'){
				$query .= " WHERE SG.id IN ($val) AND SG.endLevel = 'true' ";
			}
			if($role === 'admin' && !empty($key) && !empty($val)){
				$query .= " WHERE SG.$key = '$val' ";
			}

			if($role === 'admin' && !empty($key) && !empty($val) && !empty($status)){
				$query .= " AND SG.status = '$status' ";
			}elseif($role === 'admin' && !empty($status)){
				$query .= " WHERE SG.status = '$status' ";
			}
			
			$query .=" GROUP BY SG.id";
			
			if($info && array_key_exists('order', $info) && count($info['order']) > 0){
				$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
			}else{
				$query .= " ORDER BY SG.id DESC";
			}

			$query1 = '';
			if($info && array_key_exists('length', $info) && $info['length'] != -1){
				$query1 .= " LIMIT ".$info['start'].", ".$info['length'];
			}

			$result = $this->db->select($query.$query1);
			if ($result) {
				$i = 0;
				$rows = 0;
				$data = [];
				while ($value = $result->fetch_assoc()) {
					$i++;
					$rows++;
					$datum = array();
					$datum = $value;
					$img = $value['mediaName'] ? '<span class="img"><img src="../uploads/'.$value['mediaName'].'"></span>' : '';
					$datum['sn'] = $i;
					$datum['groupName'] = $img.$value['name'];
					$datum['hierarchyName'] = implode(' > ', $this->hierarchy($value['hierarchyPath']));
					$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);
					if($type == 'dataTable'){
						$datum['statusMod'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';
						$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
						<div class="dropdown-menu dropdown-menu-right" style="min-width:100px"><a href="group-edit.php?id='.$value['id'].'"><i data-feather="edit"> </i>Edit</a><a href="#" dataId="'.$value['id'].'" id="groupDelete"><i data-feather="trash"></i>Delete</a></div></div>';
					}
					$data[] = $datum;
				}

				if($type == 'dataTable'){
					$query2 = "SELECT id FROM tbl_group ";
					if($role === 'groupmanager'){
						$query2 .= " WHERE id IN ($val) AND endLevel = 'true' ";
					}elseif($role === 'admin' && !empty($key) && !empty($val)){
						$query2 .= " WHERE $key = '$val' ";
					}
					if($role === 'admin' && !empty($key) && !empty($val) && !empty($status)){
						$query2 .= " AND status = '$status' ";
					}elseif($role === 'admin' && !empty($status)){
						$query2 .= " WHERE status = '$status' ";
					}
					$all_rows = $this->db->rows($query2);
					$filtered_rows = $this->db->rows($query);
					
					$res['draw'] = intval($info['draw']);
					$res['recordsTotal'] = $all_rows;
					$res['recordsFiltered'] = $filtered_rows;
				}
				$res['auth'] = $info;
				$res['rows'] = $rows;
				$res['status'] = '200'; 
				$res['data'] = $data;
				echo json_encode($res);
			}else{
				if($type === 'dataTable'){
					$res['draw'] = intval($info['draw']);
					$res['recordsTotal'] = 0;
					$res['recordsFiltered'] = 0;
				}
				$res['auth'] = $info;
				$res['status'] = '204'; 
				$res['msg'] = 'No Content';
				$res['data'] = [];
				echo json_encode($res);
			}
		}
		
		// species-group.php (admin auth)
		public function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$id	= $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "SELECT tbl_group.*, tbl_media.name AS photoName
				FROM tbl_group LEFT JOIN tbl_media ON tbl_group.photo = tbl_media.id WHERE tbl_group.id='$id' LIMIT 1";
				$result = $this->db->select($query);
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

		// species-group.php (admin auth)
		public function update($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif($data['endLevel'] == 'true' && empty($data['photo'])){
				$res['status'] = '400';
				$res['msg'] = 'Group photo required';
				echo json_encode($res);
			}elseif(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Group name required';
				echo json_encode($res);
			}elseif(empty($data['slug'])){
				$res['status'] = '400';
				$res['msg'] = 'Group slug required';
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400';
				$res['msg'] = 'Status required';
				echo json_encode($res);
			}else{
				$photo	 	= $this->fm->validation($data['photo']);
				$name	 	= $this->fm->validation($data['name']);
				$slug	 	= $this->fm->validation($data['slug']);
				$parent 	= $this->fm->validation(empty($data['parent']) ? '-1' : $data['parent']);
				$endLevel 	= $this->fm->validation($data['endLevel'] == 'true' ? 'true' : 'false');
				$ordering	= $this->fm->validation($data['ordering']);
				$description= $this->fm->validation($data['description']);
				$status		= $this->fm->validation($data['status']);

				$photo 		= mysqli_real_escape_string($this->db->link, $photo);
				$name 		= mysqli_real_escape_string($this->db->link, $name);
				$slug 		= mysqli_real_escape_string($this->db->link, $slug);
				$parent		= mysqli_real_escape_string($this->db->link, $parent);
				$ordering 	= mysqli_real_escape_string($this->db->link, $ordering);
				$endLevel 	= mysqli_real_escape_string($this->db->link, $endLevel);
				$description= mysqli_real_escape_string($this->db->link, $description);
				$status		= mysqli_real_escape_string($this->db->link, $status);

				$slug = $this->fm->format_uri($slug);

				$query = "SELECT id FROM tbl_group WHERE slug='$slug' AND NOT id='$id'";
				$result = $this->db->select($query);
				if($result){
					$res['status'] = '208';
					$res['msg'] = 'Group already exists';
					echo json_encode($res);
				}else{
					if($parent !== '-1'){
						$query1 = "SELECT hierarchyPath FROM tbl_group WHERE id='$parent' LIMIT 1";
						$result1 = $this->db->select($query1)->fetch_assoc();
					}else{
						$result1['hierarchyPath'] = '';
					}

					$hierarchyPath = $result1['hierarchyPath'] ? $result1['hierarchyPath'].'-'.$id : $id;

					$query = "UPDATE tbl_group SET photo='$photo', name='$name', slug='$slug', parent='$parent',  endLevel='$endLevel', hierarchyPath = '$hierarchyPath', description='$description', status='$status', ordering='$ordering' WHERE id='$id'";
					$result = $this->db->update($query);
					if($query){
						$res['status'] = '200';
						$res['msg'] = 'Group updated';
						echo json_encode($res);
					}
				}
			}
		}
		

		// species-group.php (admin auth)
		public function delete($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "SELECT id FROM tbl_species WHERE groupId='$id'";
				$result = $this->db->select($query);
				$query2 = "SELECT id FROM tbl_group WHERE parent='$id'";
				$result2 = $this->db->select($query2);
				if ($result || $result2) {
					$res['status'] = '406'; 
					$res['msg'] = 'This group is in use';
					echo json_encode($res);
				}else{
					$query = "DELETE FROM tbl_group WHERE id='$id'";
					$result = $this->db->delete($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = 'Group deleted';
						echo json_encode($res);
					}
				}
			}
		}


	}

?>