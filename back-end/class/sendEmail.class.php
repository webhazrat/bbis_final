<?php 
	

	class SENDEMAIL{

		public function send($email, $subject, $msg){
			$res = [];

			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-type:text/html;charset=UTF-8";
			
			$headers[] = 'Reply-To: support@biodiversitybd.org';
			$headers[] = 'From: support@biodiversitybd.org';

			if(mail($email, $subject, $msg, implode("\r\n", $headers))){
				$res['status'] = '200';
				$res['msg'] = 'Email sent successfully';
			}else{
				$res['status'] = '400';
				$res['msg'] = error_get_last()['message'];
			}

			return $res;
		}
        
	}

?>