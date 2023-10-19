<?php
/**
* Format Class
*/
  class Format{

    public function date($format, $datetime){
      $date = explode(' ', $datetime);
      return date($format, strtotime($date[0]));
    }

    public function today(){
			date_default_timezone_set('Asia/Dhaka');
			return date('Y-m-d');
    }

    public function codeGenerate($digit){
      return substr(str_shuffle("0123456789"), 0, $digit);
    }
    
    public function token(){
      $token = openssl_random_pseudo_bytes(16);
      $token = bin2hex($token);
      return $token;
    }

    function validPhone($phone){
      $pattern = "/(^(01){1}[3456789]{1}(\d){8})$/";
      return preg_match($pattern, $phone);
    }

    public function validation($data){
      $data = trim($data);
      $data = stripcslashes($data);
      $data = htmlspecialchars($data, ENT_QUOTES);
      return $data;
    }
        
    public function textShorten($txt, $limit = 400){
      $text = strip_tags($txt);
      $text = $text. " ";
      $text = substr($text, 0, $limit);
      $text = substr($text, 0, strrpos($text, ' '));
      $text = strlen($text) > $limit-5 ? $text."..." : $text;
      return $text;
    }    

    function format_uri( $string, $separator = '-' ) {
      $special_cases = array( '&' => 'and', "'" => '', ' ' => '-');
      $string = mb_strtolower( trim( $string ), 'UTF-8' );
      $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
      return $string;
    }

    function base64Decode($arr){
      $formatedArr = array();
			foreach($arr as $item){
        $formatedArr[] = base64_decode($item);
      }
      return $formatedArr;
		}
    
  }
?>