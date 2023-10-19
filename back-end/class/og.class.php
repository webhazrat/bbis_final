<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');

	class OG{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function getOG($slug){
			$slug = $this->fm->validation($slug);
			$slug = mysqli_real_escape_string($this->db->link, $slug);
			$query = "SELECT P.title, 
			P.content, 
			M.name AS mediaName 
			FROM tbl_posts P
			LEFT JOIN tbl_media M ON P.image = M.id 
			WHERE P.slug = '$slug' LIMIT 1";
			$result = $this->db->select($query);
			if($result){
				$value = $result->fetch_assoc();
				$value['content'] = $this->fm->textShorten(htmlspecialchars_decode($value['content'], ENT_QUOTES), 300);
				return $value;
			}
		}

	}

?>