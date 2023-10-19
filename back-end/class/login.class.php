<?php 
	
	$filepath = realpath(dirname(__FILE__));

	include ($filepath.'/../lib/Session.php');
	Session::checkLogin();
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');
	include_once ($filepath.'/../class/sendEmail.class.php');

class LOGIN{
	private $db;
	private $fm;
	private $email;

	public function __construct(){
		$this->db = new Database();
		$this->fm = new Format();
		$this->email = new SENDEMAIL();
	}

	// login.php
	public function login($data){
		$res = [];
		if(empty($data['email']) || empty($data['password'])){
			$res['status'] = '400';
			$res['msg'] = 'Email and Password required';
			echo json_encode($res);
		}else{
			$email 		= $this->fm->validation($data['email']);
			$password 	= $this->fm->validation($data['password']);
			$email 		= mysqli_real_escape_string($this->db->link, $email);
			$password 	= mysqli_real_escape_string($this->db->link, $password);
			$query = "SELECT * FROM tbl_users WHERE BINARY email LIKE '$email' AND password = md5('$password')";
			$result = $this->db->select($query);
			if ($result) {
				$value = $result->fetch_assoc();
				$id = $value['id'];
				$email = $value['email'];
				if($value['status'] == '1'){
					Session::set("user_login", true);
					Session::set("user_id", $value['id']);
					Session::set("user_role", $value['role']);
					Session::set("manage_group", $value['manageGroup']);
					Session::set("user_name", $value['name']);
					$res['status'] = '200';
					$res['msg'] = 'Login successful';
					echo json_encode($res);
				}elseif($value['status'] == '2'){
					$token = $this->fm->token();
					
					$msg = '<h2>Account Verification</h2> <p>To verify your account click the verification action link. Your account verification link is: <a href="'.BASE_URL.'/login?token='.$token.'&email='.$email.'&action=verify">Click here to verify</a></p>';
					$subject = 'Account Verification';
					$response = $this->email->send($email, $subject, $msg);

					if($response['status'] == '200'){
						$query = "UPDATE tbl_users SET token = '$token' WHERE id = '$id'";
						$result = $this->db->update($query);
	
						$res['status'] = '401';
						$res['msg'] = 'Verify link has been sent to - '.$email;
						$res['sendEmail'] = $msg;
						$res['emailRes'] = $response;
						echo json_encode($res);
					}else{
						$res['status'] = '400';
						$res['error'] = $response;
						$res['msg'] = 'Something wrong. Please try again';
						echo json_encode($res);
					}
				}elseif($value['status'] == '3'){
					$res['status'] = '403';
					$res['msg'] = 'Account suspended. Contact to authority';
					echo json_encode($res);
				}
			}else{
				$res['status'] = '404';
				$res['msg'] = 'Phone and Password don\'t match';
				echo json_encode($res);
			}
		}
	}



}

 ?>


