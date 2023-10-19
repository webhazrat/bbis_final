<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');

	class ROUTER{
		private $db;
        private $fm;
        public static $routes;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
        }

        public static function get($url, $view){
            self::$routes[$url] = $view;
        }

        public static function run($url){
            if(array_key_exists($url, self::$routes)){
                return self::$routes[$url];
            }else{
                return '404';
            }
        }

        public function not_found(){
            header("location: ".BASE_URL."/404.php");
        }

        public function checkUrl($path){
			$path = $this->fm->validation($path);
            $path = mysqli_real_escape_string($this->db->link, $path);
            $query = "SELECT * FROM tbl_posts WHERE slug='$path' AND status='6'";
            $result = $this->db->select($query);
            return $result;
        }

        public function checkGroupUrl($path){
            $path = $this->fm->validation($path);
            $path = mysqli_real_escape_string($this->db->link, $path);
            $slug = explode('/', $path)[1];
            $query = "SELECT * FROM tbl_group WHERE slug='$slug' AND status='6'";
            $result = $this->db->select($query);
            return $result;
        }

        public function checkSpeciesUrl($path){
            $path = $this->fm->validation($path);
            $path = mysqli_real_escape_string($this->db->link, $path);
            $spCode = explode('/', $path)[1];
            $query = "SELECT * FROM tbl_species WHERE spCode='$spCode' AND status='8'";
            $result = $this->db->select($query);
            return $result;
        }

        public function checkUserUrl($path){
            $path = $this->fm->validation($path);
            $path = mysqli_real_escape_string($this->db->link, $path);
            $userName = explode('/', $path)[1];
            $query = "SELECT * FROM tbl_users WHERE userName='$userName' AND status='1'";
            $result = $this->db->select($query);
            return $result;
        }

    }
?>