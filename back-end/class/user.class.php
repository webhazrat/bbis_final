<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');
	include_once ($filepath.'/../class/sendEmail.class.php');

	class USER{
		private $db;
		private $fm;
		private $email;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
			$this->email = new SENDEMAIL();
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

		public function social($id){
			$socials = [];
			$query = "SELECT * FROM tbl_user_social WHERE memberId='$id'";
			$result = $this->db->select($query);
			if($result){
				while ($value = $result->fetch_assoc()) {
					array_push($socials, $value);
				}
			}
			return $socials;
		}
						
		// join.php
		public function create($data){
			$res = [];
			if(empty($data['name'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Name required';						
				echo json_encode($res);
			}elseif(empty($data['phone'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Phone number required';						
				echo json_encode($res);
			}elseif($this->fm->validPhone($data['phone']) == false){
				$res['status'] = '400'; 
				$res['msg'] = 'Invalid phone number';						
				echo json_encode($res);
			}elseif(empty($data['email'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Email address required';						
				echo json_encode($res);
			}elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				$res['status'] = '400'; 
				$res['msg'] = 'Invalid email format';						
				echo json_encode($res);
			}elseif(empty($data['password'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Password required';						
				echo json_encode($res);
			}elseif(strlen($data['password']) < 8){
				$res['status'] = '400'; 
				$res['msg'] = 'Password must be more than seven characters';						
				echo json_encode($res);
			}elseif(empty($data['cPassword'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Confirm password required';						
				echo json_encode($res);
			}elseif($data['password'] !== $data['cPassword']){
				$res['status'] = '400'; 
				$res['msg'] = 'Password and confirm password don\'t match';						
				echo json_encode($res);
			}elseif(empty($data['profession'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Profession required';						
				echo json_encode($res);
			}elseif(empty($data['institution'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Institution required';						
				echo json_encode($res);
			}elseif($data['terms'] == 'false'){
				$res['status'] = '400'; 
				$res['msg'] = 'Please check the terms and conditions';						
				echo json_encode($res);
			}else{
				$name 				= $this->fm->validation($data['name']);
				$phone 				= $this->fm->validation($data['phone']);
				$email 				= $this->fm->validation($data['email']);
				$password 			= $this->fm->validation($data['password']);
				$profession 		= $this->fm->validation($data['profession']);
				$institution 		= $this->fm->validation($data['institution']);
	
				$name 				= mysqli_real_escape_string($this->db->link, $name);
				$phone 				= mysqli_real_escape_string($this->db->link, $phone);
				$email 				= mysqli_real_escape_string($this->db->link, $email);
				$password			= mysqli_real_escape_string($this->db->link, $password);
				$profession			= mysqli_real_escape_string($this->db->link, $profession);
				$institution		= mysqli_real_escape_string($this->db->link, $institution);

				$userName = preg_replace('/[^a-z0-9]+/i', '-', strtolower(trim($name)));
				
				$query = "SELECT id FROM tbl_users WHERE email = '$email'";
				$result = $this->db->select($query);
				if($result){
					$res['status'] = '208'; 
					$res['msg'] = 'Account already exist. Please login';
					$res['data'] = $data;
					echo json_encode($res);
				}else{
					$query = "INSERT INTO tbl_users(name, userName, phone, email, password, profession, institution, role, status) VALUES('$name', '$userName', '$phone', '$email', md5('$password'), '$profession', '$institution', '4', '2')";
					$result = $this->db->insert($query);
					if($result){
						$res['status'] = '200'; 	
						$res['msg'] = 'Account created. Please login and verify your account';
						echo json_encode($res);
					}
				}
			}	
		}

		// login.php
		public function verify($data){
			$res = [];
			$action  		= $this->fm->validation($data['action']);
			$verifyToken	= $this->fm->validation($data['verifyToken']);
			$verifyEmail	= $this->fm->validation($data['verifyEmail']);
			$verifyToken 	= mysqli_real_escape_string($this->db->link, $verifyToken);
			$verifyEmail 	= mysqli_real_escape_string($this->db->link, $verifyEmail);

			$query = "SELECT id FROM tbl_users WHERE token = '$verifyToken' AND email = '$verifyEmail'";
			$result = $this->db->select($query);
			if($result){
				$query = "UPDATE tbl_users SET status = '1' WHERE token = '$verifyToken'";
				$result = $this->db->update($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Verified';
					echo json_encode($res);
				}
			}else{
				$res['status'] = '400';
				$res['msg'] = 'Invalid token and email. Try again';
				echo json_encode($res);
			}	
		}

		// forgot.php
		public function forgot($data){
			$res = [];
			if(empty($data['email'])){
				$res['status'] = '400';
				$res['msg'] = 'Email required';
				echo json_encode($res);
			}elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
				$res['status'] = '400'; 
				$res['msg'] = 'Invalid email format';						
				echo json_encode($res);
			}else{
				$email	= $this->fm->validation($data['email']);
				$email	= mysqli_real_escape_string($this->db->link, $email);
				$query = "SELECT id, status FROM tbl_users WHERE email = '$email' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					$value = $result->fetch_assoc();
					if($value['status'] == '1'){
						$id = $value['id'];
						
						$token = $this->fm->token();
						$msg = 'Your account password reset link is: '.BASE_URL.'/reset-password?token='.$token.'&email='.$email.'&action=reset';
						$response = $this->email->send($email, urlencode($msg));
	
						$query = "UPDATE tbl_users SET token = '$token' WHERE id = '$id'";
						$result = $this->db->update($query);
	
						$res['status'] = '200';
						$res['msg'] = 'Password reset link has been sent to - '.$email;
						$res['sendEmail'] = $msg;
						echo json_encode($res);
					}else{
						$res['status'] = '400';
						$res['msg'] = 'Your account isn\'t verified';
						echo json_encode($res);	
					}					
				}else{
					$res['status'] = '404';
					$res['msg'] = 'Email doesn\'t exist';
					echo json_encode($res);
				}
			}
		}

		// forgot.php
		public function changePassword($data){
			$res = [];
			if((empty($data['access']) && empty($data['verifyToken'])) || (empty($data['access']) && empty($data['verifyEmail']))){
				$res['status'] = '400';
				$res['msg'] = 'Invalid token and email. Try again';
				echo json_encode($res);
			}elseif(!empty($data['access']) && $data['access'] == 'session' && empty($data['id'])){
				$res['status'] = '400';
				$res['msg'] = 'Access denied';
				echo json_encode($res);
			}elseif(!empty($data['access']) && $data['access'] == 'session' && empty($data['oldPassword'])){
				$res['status'] = '400';
				$res['msg'] = 'Old password required';
				echo json_encode($res);
			}elseif(empty($data['password'])){
				$res['status'] = '400';
				$res['msg'] = 'New password required';
				echo json_encode($res);
			}elseif(strlen($data['password']) < 8){
				$res['status'] = '400';
				$res['msg'] = 'Password must be more than seven characters';
				echo json_encode($res);
			}elseif(empty($data['cPassword'])){
				$res['status'] = '400';
				$res['msg'] = 'Confirm password required';
				echo json_encode($res);
			}elseif($data['password'] !== $data['cPassword'] ){
				$res['status'] = '400';
				$res['msg'] = 'New and confirm password doesn\'t match';
				echo json_encode($res);
			}else{
				$id 			= $this->fm->validation($data['id']);
				$oldPassword 	= $this->fm->validation($data['oldPassword']);
				$password 		= $this->fm->validation($data['password']);
				$verifyToken 	= $this->fm->validation($data['verifyToken']);
				$verifyEmail 	= $this->fm->validation($data['verifyEmail']);
				$id 			= mysqli_real_escape_string($this->db->link, $id);
				$oldPassword 	= mysqli_real_escape_string($this->db->link, $oldPassword);
				$password 		= mysqli_real_escape_string($this->db->link, $password);
				$verifyToken 	= mysqli_real_escape_string($this->db->link, $verifyToken);
				$verifyEmail 	= mysqli_real_escape_string($this->db->link, $verifyEmail);

				if(!empty($data['access']) && $data['access'] == 'session'){
					$query = "SELECT id FROM tbl_users WHERE password = md5('$oldPassword') AND id = '$id'";
					$result = $this->db->select($query);
					if($result){
						$query = "UPDATE tbl_users SET password= md5('$password') WHERE id = '$id'";
						$result = $this->db->update($query);
						if($result){
							$res['status'] = '200';
							$res['msg'] = 'Password updated';
							echo json_encode($res);
						}
					}else{
						$res['status'] = '400';
						$res['msg'] = 'Old password don\'t match';
						echo json_encode($res);
					}
				}else{
					$query = "SELECT id FROM tbl_users WHERE token = '$verifyToken' AND email = '$verifyEmail'";
					$result = $this->db->select($query);
					if($result){
						$query = "UPDATE tbl_users SET password= md5('$password'), token = '' WHERE email = '$verifyEmail'";
						$result = $this->db->update($query);
						if($result){
							$res['status'] = '200';
							$res['msg'] = 'Password updated';
							echo json_encode($res);
						}
					}else{
						$res['status'] = '400';
						$res['msg'] = 'Invalid token and email. Try again';
						echo json_encode($res);
					}
				}				
			}
		}
		
		// profile.php, users.php
		public function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "SELECT U.*,
				D.name AS districtName, 
				UP.name AS areaName, 
				UN.name AS zoneName, 
				GROUP_CONCAT(DISTINCT R.role SEPARATOR ', ') AS userRoles
				FROM tbl_users U
				LEFT JOIN tbl_districts D ON U.district = D.id 
				LEFT JOIN tbl_upazilas UP ON U.area = UP.id 
				LEFT JOIN tbl_unions UN ON U.zone = UN.id 
				LEFT JOIN tbl_role R ON FIND_IN_SET(R.id, U.role) > 0
				WHERE U.id = '$id' 
				GROUP BY U.id LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while($value = $result->fetch_assoc()){
						$datum = $value;
						$datum['mId'] = 'PF'.$value['id'];
						$datum['userRoles'] = $value['userRoles'];
						$datum['about'] = htmlspecialchars_decode($value['about']);
						$datum['createdAt'] = $this->fm->date('d M, Y', $value['createdAt']);
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

		// profile.php
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
			}elseif(empty($data['gender'])){
				$res['status'] = '400';
				$res['msg'] = 'Gender required';
				echo json_encode($res);
			}elseif(empty($data['dob'])){
				$res['status'] = '400';
				$res['msg'] = 'Date of birth required';
				echo json_encode($res);
			}elseif(empty($data['phone'])){
				$res['status'] = '400';
				$res['msg'] = 'Phone required';
				echo json_encode($res);
			}elseif(empty($data['district'])){
				$res['status'] = '400';
				$res['msg'] = 'District required';
				echo json_encode($res);
			}elseif(empty($data['area'])){
				$res['status'] = '400';
				$res['msg'] = 'Area required';
				echo json_encode($res);
			}elseif(empty($data['zone'])){
				$res['status'] = '400';
				$res['msg'] = 'Zone required';
				echo json_encode($res);
			}elseif(empty($data['profession'])){
				$res['status'] = '400';
				$res['msg'] = 'Profession required';
				echo json_encode($res);
			}elseif(empty($data['institution'])){
				$res['status'] = '400';
				$res['msg'] = 'Institution required';
				echo json_encode($res);
			}else{
				$name 			= $this->fm->validation($data['name']);
				$gender 		= $this->fm->validation($data['gender']);
				$dob 			= $this->fm->validation($data['dob']);
				$phone 			= $this->fm->validation($data['phone']);
				$district		= $this->fm->validation($data['district']);
				$area			= $this->fm->validation($data['area']);
				$zone			= $this->fm->validation($data['zone']);
				$profession		= $this->fm->validation($data['profession']);
				$institution	= $this->fm->validation($data['institution']);
				$about			= $this->fm->validation($data['about']);

				$name 			= mysqli_real_escape_string($this->db->link, $name);
				$gender 		= mysqli_real_escape_string($this->db->link, $gender);
				$dob 			= mysqli_real_escape_string($this->db->link, $dob);
				$phone 			= mysqli_real_escape_string($this->db->link, $phone);
				$district		= mysqli_real_escape_string($this->db->link, $district);
				$area			= mysqli_real_escape_string($this->db->link, $area);
				$zone			= mysqli_real_escape_string($this->db->link, $zone);
				$profession 	= mysqli_real_escape_string($this->db->link, $profession);
				$institution	= mysqli_real_escape_string($this->db->link, $institution);
				$about 			= mysqli_real_escape_string($this->db->link, $about);

				$query = "UPDATE tbl_users SET name='$name', gender='$gender', dob='$dob', district='$district', area='$area', zone='$zone', profession='$profession', institution='$institution', about='$about' WHERE id='$id'";
				$result = $this->db->update($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Profile updated';
					echo json_encode($res);
				}
			}
		}

		// users.php
		public function updateAuth($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['role'])){
				$res['status'] = '400';
				$res['msg'] = 'Role required';
				echo json_encode($res);
			}elseif(in_array('3', explode(',', $data['role'])) && empty($data['manageGroup'])){
				$res['status'] = '400';
				$res['msg'] = 'Manage group required';
				echo json_encode($res);
			}elseif(empty($data['status'])){
				$res['status'] = '400';
				$res['msg'] = 'Status required';
				echo json_encode($res);
			}else{
				$role 			= $this->fm->validation($data['role']);
				$manageGroup 	= $this->fm->validation($data['manageGroup']);
				$peopleType 	= $this->fm->validation($data['peopleType']);
				$status 		= $this->fm->validation($data['status']);
				$role 			= mysqli_real_escape_string($this->db->link, $role);
				$manageGroup	= mysqli_real_escape_string($this->db->link, $manageGroup);
				$peopleType		= mysqli_real_escape_string($this->db->link, $peopleType);
				$status 		= mysqli_real_escape_string($this->db->link, $status);
				$query = "UPDATE tbl_users SET role='$role', manageGroup='$manageGroup', peopleType='$peopleType', status='$status' WHERE id='$id'";
				$result = $this->db->update($query);
				if($result == true){
					$res['status'] = '200';
					$res['msg'] = 'Profile status updated';
					echo json_encode($res);
				}
			}
		}
		
		// users.php
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
				
				$columns = array('U.id', 'U.id', 'U.name', 'U.gender', 'U.dob', 'U.phone', 'U.email', 'U.role', 'U.manageGroup', 'PT.name', 'S.name', 'U.createdAt');
				
				$query = "SELECT U.*, ";
				$query .= " GROUP_CONCAT(DISTINCT R.role SEPARATOR ', ') AS roles, ";
				$query .= " GROUP_CONCAT(DISTINCT SG.hierarchyPath SEPARATOR ', ') AS groups, ";
				$query .= " PT.name AS type,
				DS.name AS district,
				UP.name AS area,
				UN.name AS zone,
				S.name AS statusName
				FROM tbl_users U ";
				$query .= " LEFT JOIN tbl_role R ON FIND_IN_SET(R.id, U.role) > 0  ";
				$query .= " LEFT JOIN tbl_group SG ON FIND_IN_SET(SG.id, U.manageGroup) > 0  ";
				$query .= " LEFT JOIN tbl_people_type PT ON U.peopleType = PT.id 
				LEFT JOIN tbl_districts DS ON U.district = DS.id 
				LEFT JOIN tbl_upazilas UP ON U.area = UP.id 
				LEFT JOIN tbl_unions UN ON U.zone = UN.id 
				LEFT JOIN tbl_status S ON U.status = S.id ";
				
				if(!empty($info['search']['value'])){
					$query .= " WHERE ";
					$searchArr = explode(' ', $info['search']['value']);
					$count = count($searchArr);
					for ($i=0; $i < $count; $i++) { 
						$query .= " (U.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR U.phone LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR U.email LIKE '".$searchArr[$i]."%' ";
						$query .= " OR DS.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR UP.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR UN.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR R.role LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR U.manageGroup LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR S.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " ) ";
						if($i < $count - 1){
							$query .= ' AND ';
						}
					}
					if($key !== 'all'){
						$query .= " AND U.$key = '$val' ";
					}
				}

				if(empty($info['search']['value']) && $key !== 'all'){
					$query .= " WHERE U.$key = '$val' ";
				}

				$query .= " GROUP BY U.id ";

				if(array_key_exists('order', $info) && count($info['order']) > 0){
					$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
				}else{
					$query .= " ORDER BY U.id DESC";
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
						
						$photoPath = $value['photo'] == '' ? '<span class="img"><img src="assets/images/profile.png"></span>' : '<span class="img"><img src="'.BASE_URL.'/uploads/'.$value['photo'].'"></span>';
						
						$i++;
						$datum = array();
						$datum = $value;

						$groups = [];
						$groupPath = explode(',', $value['groups']);
						foreach($groupPath as $path){
							array_push($groups, implode(' > ', $this->hierarchy($path)));
						}

						$datum['sn'] = $i;
						$datum['mId'] = 'PF'.$value['id'];
						$datum['namePhoto'] = '<a href="#" data_id="'.$value['id'].'" id="userView">'.$photoPath.' '.$value['name'].'</a>';
						$datum['address'] = $value['zone'] . ', ' . $value['area'] . ', ' . $value['district'];
						$datum['phoneAction'] = '<a href="tel:+88'.$value['phone'].'">'.$value['phone'].'</a>';
						$datum['emailAction'] = '<a href="mailto:'.$value['email'].'">'.$value['email'].'</a>';
						$datum['groups'] = implode(', ', $groups);
						$datum['statusName'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';
						$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);
						
						if($type === 'dataTable'){
							$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"> <i data-feather="more-vertical"></i></a>
							<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">';
							$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="userAuthStatus"><i data-feather="refresh-cw"> </i>Status</a><a href="#" data_id="'.$value['id'].'" id="userView"><i data-feather="eye"> </i>View</a><a href="#" data_id="'.$value['id'].'" id="userDelete"><i data-feather="trash"> </i>Delete</a>';
							$datum['action'] .= '</div></div>';
						}
						$data[] = $datum; 
					}

					if($type == 'dataTable'){
						$query2 = "SELECT id FROM tbl_users ";
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

		// profile.php
		public function profilePhoto($data){
			$res = [];
			if(empty($data['image']) || empty($data['author'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$author	= $this->fm->validation($data['author']);
				$image	= $this->fm->validation($data['image']);
				$image 	= mysqli_real_escape_string($this->db->link, $image);
				
				$filepath = realpath(dirname(__FILE__));
				$query = "SELECT photo FROM tbl_users WHERE id='$author'";
				$result = $this->db->select($query);
				if($result == true){
					$photo = $result->fetch_assoc();
					if($photo['photo'] != ''){
						unlink($filepath.'/../../uploads/'.$photo['photo']);
					}
				}
				$image_array_1 = explode(";", $image);
				$image_array_2 = explode(",", $image_array_1[1]);
				$data = base64_decode($image_array_2[1]);
				$u_id=uniqid();
				$imagename = $u_id .'.png';
				file_put_contents($filepath.'/../../uploads/'.$imagename,  $data);
	
				$query = "UPDATE tbl_users SET photo='$imagename' WHERE id='$author'";
				$result= $this->db->update($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'OK';
					$res['data'] = $imagename;
					echo json_encode($res);
				}
			}
		}

		// people.php, contributors.php
		public function filter($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '400';
				$res['msg'] = 'Key and Value required';
				echo json_encode($res);
			}else{
				$key	= $this->fm->validation($info['key']);
				$val	= $this->fm->validation($info['value']);
				$key 	= mysqli_real_escape_string($this->db->link, $key);
				$val 	= mysqli_real_escape_string($this->db->link, $val);

				$per_page 	= array_key_exists("per_page", $info) ? $info['per_page'] : '';
				$page 		= array_key_exists("page", $info) ? $info['page'] : '';

				$query = "SELECT U.id, U.name, U.userName, U.photo, U.phone, U.email, U.dob, U.gender, U.profession, U.institution, U.about, 
				D.name AS districtName, 
				UP.name AS areaName, 
				UN.name AS zoneName
				FROM tbl_users U 
				LEFT JOIN tbl_districts D ON U.district = D.id 
				LEFT JOIN tbl_upazilas UP ON U.area = UP.id 
				LEFT JOIN tbl_unions UN ON U.zone = UN.id 
				WHERE U.$key = '$val' AND status='1'";

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
				if($result){
					$data = [];
					while ($value = $result->fetch_assoc()) {
						$datum = array();
						$datum = $value;
						$datum['about'] = htmlspecialchars_decode($value['about']);
						$datum['socials'] = $this->social($value['id']);
						$data[] = $datum;
					}
					$res['status'] = '200';
					$res['msg'] = 'OK';
					$res['data'] = $data;
					$res['pagination'] = $pagination;
					echo json_encode($res);
				}else{
					$res['status'] = '204';
					$res['msg'] = 'No content';
					echo json_encode($res);
				}
			}
		}

		

	}

?>