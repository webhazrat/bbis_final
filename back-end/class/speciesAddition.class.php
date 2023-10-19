<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');
	include_once ($filepath.'/../class/sendEmail.class.php');
	
	class SPECIESADDITION{
		private $db;
		private $fm;
		private $email;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
			$this->email = new SENDEMAIL();
		}

		// service media
		public function getPhoto($ids){
			$ids = explode(',', $ids);
			$photos = [];
			foreach($ids as $id){
				$query = "SELECT * FROM tbl_media WHERE id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while ($value = $result->fetch_assoc()) {
						array_push($photos, $value);
					}
				}
			}
			return $photos;
		}
		
		// service media
		public function removePhoto($ids){
			$ids = explode(',',$ids);
			$response = [];
			foreach($ids as $id){
				$query = "SELECT name FROM tbl_media WHERE id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while($value = $result->fetch_assoc()){
						$path = realpath(dirname(__FILE__))."/../../uploads/".$value['name'];
						$query2 = "DELETE FROM tbl_media WHERE id='$id'";
						$result2 = $this->db->delete($query2);
						if($result2){
							unlink($path);
							array_push($response, 'OK');
						}
					}
				}
			}
			return $response;
		}

		// contribute.php
		public function create($data){
			$res = [];
			if(empty($data['type'])){
				$res['status'] = '400';
				$res['msg'] = 'Type required';
				echo json_encode($res);
			}elseif(empty($data['taxonGroup'])){
				$res['status'] = '400';
				$res['msg'] = 'Taxon group required';
				echo json_encode($res);
			}elseif(empty($data['scientificName'])){
				$res['status'] = '400';
				$res['msg'] = 'Scientific name required';
				echo json_encode($res);
			}elseif(empty($data['commonName'])){
				$res['status'] = '400';
				$res['msg'] = 'Common name required';
				echo json_encode($res);
			}elseif(empty($data['locality'])){
				$res['status'] = '400';
				$res['msg'] = 'Locality required';
				echo json_encode($res);
			}elseif(empty($data['district'])){
				$res['status'] = '400';
				$res['msg'] = 'District required';
				echo json_encode($res);
			}elseif(empty($data['gpsCoordination'])){
				$res['status'] = '400';
				$res['msg'] = 'GPS coordination required';
				echo json_encode($res);
			}elseif(empty($data['dateTime'])){
				$res['status'] = '400';
				$res['msg'] = 'Collection date and time required';
				echo json_encode($res);
			}elseif(empty($data['photos']['name'][0])){
				$res['status'] = '400';
				$res['msg'] = 'Photos required';
				echo json_encode($res);
			}elseif(empty($data['terms'])){
				$res['status'] = '400';
				$res['msg'] = 'Check the terms and conditions';
				echo json_encode($res);
			}else{
				$type 				= $this->fm->validation($data['type']);
				$spGroup 			= $this->fm->validation($data['taxonGroup']);
				$spScName 			= $this->fm->validation($data['scientificName']);
				$spEngName 			= $this->fm->validation($data['commonName']);
				$locality			= $this->fm->validation($data['locality']); 
				$district 			= $this->fm->validation($data['district']);	
				$coordination		= $this->fm->validation($data['gpsCoordination']);	
				$spNotes			= $this->fm->validation($data['notes']);
				$spCollectionDate	= $this->fm->validation($data['dateTime']);
				$photos				= $data['photos'];
				$author				= $this->fm->validation($data['author']);

				$spLocalName 		= $this->fm->validation(array_key_exists('localName', $data) ? $data['localName'] : '');
				$spShortDes 		= $this->fm->validation(array_key_exists('description', $data) ? $data['description'] : '');
				$spHabitat 			= $this->fm->validation(array_key_exists('habitat', $data) ? $data['habitat'] : '');
				$spBiology 			= $this->fm->validation(array_key_exists('biology', $data) ? $data['biology'] : '');
				$spRef 				= $this->fm->validation(array_key_exists('reference', $data) ? $data['reference'] : '');
				
				$query = "SELECT id FROM tbl_group WHERE id='$spGroup' AND endLevel='true'";
				$result = $this->db->select($query);
				if($result){
					if($data['type'] == 'exist'){
						$query2 = "SELECT id FROM tbl_species WHERE groupId='$spGroup' AND id='$spScName'";
						$result2 = $this->db->select($query2);
						if($result2){
							
							$response = $this->photosUpload($photos, $author);
							if($response['status'] == '400'){
								echo json_encode($response);
							}else{
								$idArr = $response['data'];
								$count = count($response['data']);
								if($count > 0){
									$spPhotos = implode(",", $idArr);
					
									$query3 = "INSERT INTO tbl_species_addition(author, groupId, spId, locality, district, coordination, collectionDate, notes, localName, description, habitat, biology, reference,  photos, status) VALUES('$author', '$spGroup', '$spScName', '$locality', '$district', '$coordination', '$spCollectionDate', '$spNotes', '$spLocalName', '$spShortDes', '$spHabitat', '$spBiology', '$spRef', '$spPhotos', '4')";
									$result3 = $this->db->insert($query3);
									if($result3){
										$res['status'] = '200';
										$res['msg'] = 'Species additional information added successfully';
										echo json_encode($res);
									}
								}
							}
						}else{
							$res['status'] = '400';
							$res['msg'] = 'Taxon group and species doesn\'t match';
							echo json_encode($res);
						}
					}elseif($data['type'] == 'new'){
						
						$response = $this->photosUpload($photos, $author);

						if($response['status'] == '400'){
							echo json_encode($response);
						}else{
							$idArr = $response['data'];
							$count = count($response['data']);

							if($count > 0){
								$spPhotos = implode(",", $idArr);
								$spCode = $this->fm->codeGenerate(8);
								$query4 = "INSERT INTO tbl_species(author, spCode, groupId, spScName, spEngName, status) VALUES('$author', '$spCode', '$spGroup', '$spScName', '$spEngName', '4')";
								$result4 = $this->db->insert($query4);
								if($result4){
									$spId = $this->db->link->insert_id;
									$query5 = "INSERT INTO tbl_species_addition(author, groupId, spId, locality, district, coordination, collectionDate, notes, localName, description, habitat, biology, reference, photos, status) VALUES('$author', '$spGroup', '$spId', '$locality', '$district', '$coordination', '$spCollectionDate', '$spNotes', '$spLocalName', '$spShortDes', '$spHabitat', '$spBiology', '$spRef', '$spPhotos', '4')";
									$result5 = $this->db->insert($query5);
									if($result5){
										$res['status'] = '200';
										$res['msg'] = 'Species added successfully';
										echo json_encode($res);
									}
								}
							}

						}
					}
				}else{
					$res['status'] = '400';
					$res['msg'] = 'Taxon group should be endlevel';
					echo json_encode($res);
				}
			}
		}

		public function photosUpload($photos, $author){
			$res = [];
			$maxsize = 2097152;
			$error = '';
			$photoArr = [];
			$count = count($photos);

			for($i = 0; $i < $count; $i++){
				if(!empty($photos['name'][$i])){
					$file = $photos['name'][$i];
					$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$part = explode('@', pathinfo($file, PATHINFO_FILENAME));
					$fileName = $part[0];
					$fileNameExt = $part[0].'.'.$ext;
					$author2 = count($part) > 1 ? $part[count($part)-1] : '';

					$validExt = array("png","jpeg","jpg");

					if(($photos['size'][$i] >= $maxsize) || ($photos['size'][$i] == 0)) {
						$error = $photos['name'][$i].', File too large. Must be less than 2 megabytes.';
					}elseif(in_array($ext, $validExt) == false){
						$error = $photos['name'][$i].', Only JPEG, JPG and PNG types are accepted.';
					}else{
						if(file_exists('../../../uploads/'.$fileNameExt)){
							$fileNameExt = $fileName.'-1.'.$ext;
						}
						$datum = array();
						$datum['tmp'] = $photos['tmp_name'][$i];
						$datum['fileName'] = $fileNameExt;
						$datum['author2'] = $author2;
						$photoArr[] = $datum;
					}
				}
			}

			if(!empty($error)){
				$res['status'] = '400';
				$res['msg'] = $error;
				return $res;
			}else{
				$idArr = [];
				for($i = 0; $i < count($photoArr); $i++){
					if(move_uploaded_file($photoArr[$i]['tmp'], '../../../uploads/'.$photoArr[$i]['fileName'])){
						$query = "INSERT INTO tbl_media(name, author, author2) VALUES('".$photoArr[$i]['fileName']."', '$author', '".$photoArr[$i]['author2']."')";
						$result = $this->db->insert($query);
						if($result){
							array_push($idArr, $this->db->link->insert_id);
						}
					}
				}
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $idArr;
				return $res;
			}
		}

		// species-addition.php, my-contributions.php
		function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request'; 
				echo json_encode($res);
			}else{
				$id 	= $this->fm->validation($id);
				$query = "SELECT SA.*, 
				S.spEngName AS commonName
				FROM tbl_species_addition SA 
				LEFT JOIN tbl_species S ON SA.spId = S.id 
				WHERE SA.id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while($value = $result->fetch_assoc()){
						$datum = $value;
						$datum['photos'] = $this->getPhoto($value['photos']);
					}
					$data[] = $datum;
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
        
		// home.php, species-profile.php, contributors.php
		public function filter($info){
			$res = [];
			$type 		= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
			$status 	= $this->fm->validation($info['status']);
			$key 		= $this->fm->validation(array_key_exists('key', $info) ? $info['key'] : '');
			$val 		= $this->fm->validation(array_key_exists('value', $info) ? $info['value'] : '');

			$per_page 	= array_key_exists("per_page", $info) ? $info['per_page'] : '';
			$page 		= array_key_exists("page", $info) ? $info['page'] : '';
						
			$query = "SELECT SA.*, 
			SG.name AS groupName, 
			U.name AS authorName, 
			S.name AS statusName
			FROM tbl_species_addition SA 
			LEFT JOIN tbl_users U ON U.id = SA.author 
			LEFT JOIN tbl_group SG ON SG.id = SA.groupId 
			LEFT JOIN tbl_status S ON S.id = SA.status ";
			if(!empty($key) && !empty($val)){
				$query .= " WHERE SA.$key='$val' AND ";
			}else{
				$query .= " WHERE ";
			}
			$query .= " SA.status='$status' ";

			if($type == 'contributors'){
				$query .= " GROUP BY SA.author ";
			}

			$pagination = '';
			if(!empty($page)){
				$total = $this->db->rows($query);
				$pages = ceil($total/$per_page);
				$start = ($page - 1) * $per_page;
				if($pages > 1){
					if($page == 1){
						$pagination .= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"> <i data-feather="chevron-left"></i> </a></li><li class="page-item active"><a class="page-link" href="?p=1">1</a></li>';
					}else{
						$pagination .= '<li class="page-item"><a class="page-link" href="?p='.($page-1).'" tabindex="-1"><i data-feather="chevron-left"></i></a></li><li class="page-item"><a class="page-link" href="?p=1">1</a></li>';
					}
					if(($page-3)>1) {
						$pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a>';
					}
					for($link = ($page-2); $link <= ($page+2); $link++){
						if($link<2) continue;
						if($link>$pages) break;
						$class = $link == $page ? 'active' : '';
						$pagination .= '<li class="page-item '.$class.'"><a class="page-link" href="?p='.$link.'">'.$link.'</a></li>';
					}
					if(($pages-($page+2))>1) {
						$pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a>';
					}
					if($page < $pages){
						$pagination .= '<li class="page-item"><a class="page-link" href="?p='.($page+1).'" tabindex="-1"> <i data-feather="chevron-right"></i> </a></li>';
					}else{
						$pagination .= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"> <i data-feather="chevron-right"></i> </a></li>';
					}
				}
				$query .=" LIMIT ".$start. ",".$per_page;
			}

			$result = $this->db->select($query);
			if ($result) {
				$data = [];
				while ($value = $result->fetch_assoc()) {
					$datum = array();
					$datum = $value;
					$datum['photos'] = $this->getPhoto($value['photos']);
					$data[] = $datum;
				}
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $data;
				$res['pagination'] = $pagination;
				echo json_encode($res);
			}else{
				$res['status'] = '204';
				$res['msg'] = 'No Content'; 
				$res['data'] = [];
				echo json_encode($res);
			}
		}

		// species-addition.php, my-contributions.php
		public function filterAuth($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '400';
				$res['msg'] = 'Key and value required';
				echo json_encode($res);
			}else{
				
				$key 		= $this->fm->validation($info['key']);
				$val 		= $this->fm->validation($info['value']);
				$role		= $this->fm->validation($info['role']);
				$key2 		= $this->fm->validation(array_key_exists("key2", $info) ? $info['key2'] : '');
				$val2 		= $this->fm->validation(array_key_exists("value2", $info) ? $info['value2'] : '');
				$type		= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
				$status		= $this->fm->validation(array_key_exists('status', $info) ? $info['status'] : '');
				
				$for		= $this->fm->validation(array_key_exists('for', $info) ? $info['for'] : '');
	
				$columns = array('SA.id', 'U.name', 'SA.locality', 'SA.district', 'SA.collectionDate', 'SA.comment', 'S.name', 'SA.createdAt');
	
				$query = "SELECT SA.*, 
				SP.spCode, 
				SP.spScName,
				SP.spEngName,
				SP.spFamily,
				D.name AS districtName,
				G.name AS spGroup,
				U.name AS authorName, 
				U.userName, 
				S.name AS statusName, 
				GROUP_CONCAT(M.name) AS photosAll
				FROM tbl_species_addition SA 
				LEFT JOIN tbl_media M ON FIND_IN_SET(M.id, SA.photos) > 0
				LEFT JOIN tbl_species SP ON SP.id = SA.spId 
				LEFT JOIN tbl_group G ON G.id = SA.groupId 
				LEFT JOIN tbl_districts D ON D.id = SA.district 
				LEFT JOIN tbl_users U ON U.id = SA.author 
				LEFT JOIN tbl_status S ON S.id = SA.status ";
	
				if(!empty($info['search']['value'])){
					$query .= " WHERE ";
					$searchArr = explode(' ', $info['search']['value']);
					$count = count($searchArr);
					for ($i=0; $i < $count; $i++) { 
						$query .= " (U.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SA.locality LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR D.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SA.collectionDate LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SA.comment LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR S.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SA.createdAt LIKE '%".$searchArr[$i]."%' ";
						$query .= " ) ";
						if($i < $count - 1){
							$query .= ' AND ';
						}
					}
					if($key !== 'all'){
						$query .= " AND SA.$key='$val' ";
					}
					if($role === 'groupmanager' && !empty($key2) && !empty($val2)){
						$query .= " AND SA.$key2 IN ($val2) ";
					}
					if($key === 'author' && $status){
						$query .= " AND SA.status = '$status' ";
					}
				}
				
				if($key !== 'all'){
					$query .= " WHERE SA.$key='$val' ";
				}
				if($role === 'groupmanager' && !empty($key2) && !empty($val2)){
					$query .= " AND SA.$key2 IN ($val2) ";
				}
				if($key === 'author' && $status){
					$query .= " AND SA.status = '$status' ";
				}
				
				$query .= " GROUP BY SA.id ";
	
				if(array_key_exists('order', $info) && count($info['order']) > 0){
					$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
				}else{
					$query .= " ORDER BY SA.id DESC";
				}
	
				$query1 = '';
				if(array_key_exists('length', $info) && $info['length'] != -1){
					$query1 .= " LIMIT ".$info['start'].", ".$info['length'];
				}
				$result = $this->db->select($query.$query1);
				if ($result) {
					$rows = 0;
					$i = 0;
					$data = [];
					while ($value = $result->fetch_assoc()) {
						$i++;
						$rows++;
						$datum = array();
						$datum = $value;
						$datum['sn'] = $i;
						$datum['authorMod'] = '<a target="_blank" href="'.BASE_URL.'/user/'.$value['userName'].'">'.$value['authorName'].'</a>';
						$datum['coordination'] = '<a href="#" id="viewCoordination">'.$value['coordination'].'</a>';
						$datum['statusMod'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';
						if($type == 'dataTable'){
							$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
							<div class="dropdown-menu dropdown-menu-right" style="min-width:120px">';
							if($for != 'my-contributions'){
								$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="speciesAdditionStatus"><i data-feather="settings"> </i>Status</a>';
							}
							if($for == 'my-contributions'){
								if($value['status'] == '8'){
									$datum['action'] .='<a target="_blank" href="species/'.$value['spCode'].'" data_id="'.$value['id'].'" id="speciesView"><i data-feather="eye"> </i>View</a>';
								}else{
									$text = $value['status'] === '5' ? 'Resubmit' : 'Edit';
									$datum['action'] .='<a href="#" data_id="'.$value['id'].'" id="speciesAdditionEdit"><i data-feather="edit"> </i>'.$text.'</a>';
								}
							}else{
								$datum['action'] .='<a href="#" data_id="'.$value['id'].'" id="speciesAdditionEdit"><i data-feather="edit"> </i>Edit</a>';
							}
							if($for !== 'my-contributions'){
								$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="speciesAdditionDelete"><i data-feather="trash"></i>Delete</a>';
							}
							$datum['action'] .= '</div></div>';
						}
						$data[] = $datum;
					}
					
					if($type == 'dataTable'){
						$query2 = "SELECT id FROM tbl_species_addition";
						if($key !== 'all'){
							$query2 .= " WHERE $key = '$val' ";
						}
						if($role === 'groupmanager' && !empty($key2) && !empty($val2)){
							$query2 .= " AND $key2 IN ($val2) ";
						}
						if($key === 'author' && $status){
							$query2 .= " AND status = '$status' ";
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
					if($type == 'dataTable'){
						$res['draw'] = intval($info['draw']);
						$res['recordsTotal'] = 0;
						$res['recordsFiltered'] = 0;
					}
					$res['auth'] = $info;
					$res['status'] = '204';
					$res['data'] = [];
					echo json_encode($res);
				}
			}
		}

		// species-addition.php
		public function updateStatus($id, $data){
			$res = [];	
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400';
				$res['msg'] = 'Status required';
				echo json_encode($res);
			}elseif($data['status'] == '5' && empty($data['comment'])){
				$res['status'] = '400';
				$res['msg'] = 'Comment required';
				echo json_encode($res);
			}else{
				$id 		= $this->fm->validation($id);
				$status 	= $this->fm->validation($data['status']);
				$comment 	= $this->fm->validation($status == '5' ? $data['comment'] : '');
				$query = "SELECT U.name, U.email FROM tbl_species_addition SA LEFT JOIN tbl_users U ON SA.author = U.id WHERE SA.id = '$id' LIMIT 1";
				$result = $this->db->select($query)->fetch_assoc();
				$query2 = "UPDATE tbl_species_addition SET status='$status', comment='$comment' WHERE id='$id'";
				$result2 = $this->db->update($query2);
				if($result2){
					$msg = '';
					$this->email->send($result['email'], urlencode($msg));
					$res['status'] = '200';
					$res['msg'] = 'Species updated';
					echo json_encode($res);
				}
			}
		}

		// my-contributions.php
		public function updateAuth($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['taxonGroup'])){
				$res['status'] = '400';
				$res['msg'] = 'Taxon group required';
				echo json_encode($res);
			}elseif(empty($data['scientificName'])){
				$res['status'] = '400';
				$res['msg'] = 'Scientific name required';
				echo json_encode($res);
			}elseif(empty($data['dateTime'])){
				$res['status'] = '400';
				$res['msg'] = 'Collection date and time required';
				echo json_encode($res);
			}elseif(empty($data['locality'])){
				$res['status'] = '400';
				$res['msg'] = 'Locality required';
				echo json_encode($res);
			}elseif(empty($data['district'])){
				$res['status'] = '400';
				$res['msg'] = 'District required';
				echo json_encode($res);
			}elseif(empty($data['gpsCoordination'])){
				$res['status'] = '400';
				$res['msg'] = 'GPS coordination required';
				echo json_encode($res);
			}elseif(empty($data['prevPhotos']) && empty($data['photos']['name'][0])){
				$res['status'] = '400';
				$res['msg'] = 'Photos required';
				echo json_encode($res);
			}else{
				$spGroup 			= $this->fm->validation($data['taxonGroup']);
				$spScName 			= $this->fm->validation($data['scientificName']);
				$spEngName 			= $this->fm->validation($data['commonName']);
				$locality			= $this->fm->validation($data['locality']); 
				$district 			= $this->fm->validation($data['district']);	
				$coordination		= $this->fm->validation($data['gpsCoordination']);	
				$spCollectionDate	= $this->fm->validation($data['dateTime']);
				$notes				= $this->fm->validation($data['notes']);
				
				$prevPhotos			= $this->fm->validation($data['prevPhotos']);
				$removePhotos		= $this->fm->validation($data['removePhotos']);
				$photos				= $data['photos'];

				$author				= $this->fm->validation($data['author']);
				
				$spLocalName 		= $this->fm->validation(array_key_exists('localName', $data) ? $data['localName'] : '');
				$spShortDes 		= $this->fm->validation(array_key_exists('description', $data) ? $data['description'] : '');
				$spHabitat 			= $this->fm->validation(array_key_exists('habitat', $data) ? $data['habitat'] : '');
				$spBiology 			= $this->fm->validation(array_key_exists('biology', $data) ? $data['biology'] : '');
				$spRef 				= $this->fm->validation(array_key_exists('reference', $data) ? $data['reference'] : '');
				
				$query = "SELECT id FROM tbl_group WHERE id='$spGroup' AND endLevel='true'";
				$result = $this->db->select($query);
				if($result){

					if(!empty($removePhotos)){
						$this->removePhoto($removePhotos);
					}
					$response = [];
					if(!empty($data['photos']['name'][0])){
						$response = $this->photosUpload($photos, $author);
					}else{
						$response['status'] = '404';
					}
					
					if($response['status'] == '400'){
						echo json_encode($response);
						return false;
					}elseif($response['status'] == '404'){
						$spPhotos = $prevPhotos;
					}else{
						$idArr = $response['data'];
						$spPhotos = !empty($prevPhotos) ? $prevPhotos.",".implode(",", $idArr) : implode(",", $idArr);
					}

					$query = "UPDATE tbl_species_addition SET groupId = '$spGroup', spId = '$spScName', locality = '$locality', district = '$district', coordination = '$coordination', collectionDate = '$spCollectionDate', photos = '$spPhotos', notes='$notes', localName='$spLocalName', description='$spShortDes', habitat='$spHabitat', biology='$spBiology', reference='$spRef', status='4' WHERE id='$id'";
					$result = $this->db->update($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = 'Species updated successfully';
						echo json_encode($res);
					}
					
				}else{
					$res['status'] = '400';
					$res['msg'] = 'Taxon group should be endlevel';
					echo json_encode($res);
				}
			}
		}

	}

?>