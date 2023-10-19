<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');

	class MENU{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}

		// menus.php
		public function create($data){
			$res = [];
			if(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Menu name required';
				echo json_encode($res);
			}else{
				$name = $this->fm->validation($data['name']);
				$name = mysqli_real_escape_string($this->db->link, $name);
				$query = "SELECT name FROM tbl_menu WHERE name='$name'";
				$result = $this->db->select($query);
				if ($result) {
					$res['status'] = '208';
					$res['msg'] = 'Menu already exist';
					echo json_encode($res);
				}else{
					$query = "INSERT INTO tbl_menu(name) VALUES('$name')";
					$result = $this->db->insert($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = 'Menu created';
						echo json_encode($res);
					}
				}
			}
		}

		// menus.php
		public function createMenuItem($data){
			$res = [];
			if(empty($data['menuId']) || empty($data['slug'])){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$menuId = $this->fm->validation($data['menuId']);
				$label 	= $this->fm->validation($data['label']);
				$menuId = mysqli_real_escape_string($this->db->link, $menuId);
				$label 	= mysqli_real_escape_string($this->db->link, $label);
				$slug 	= $data['slug'];
				if(is_array($slug)){
					for ($i = 0; $i < count($slug); $i++) {
						$label = str_replace('-', ' ', ucfirst($slug[$i]));
						$query = "INSERT INTO tbl_nav_menus(menuId, label, slug) VALUES('$menuId', '$label', '$slug[$i]')";
						$result = $this->db->insert($query);
					}
				}else{
					$query = "INSERT INTO tbl_nav_menus(menuId, label, slug) VALUES('$menuId', '$label', '$slug')";
						$result = $this->db->insert($query);
				}
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Menu items added';
					echo json_encode($res);
				}
			}
		}
		
		// menus.php
		public function findAll(){
			$res = [];
			$query = "SELECT * FROM tbl_menu";
			$result = $this->db->select($query);
			if($result){
				while ($value = $result->fetch_assoc()) {
					$data[] = $value;
				}
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $data;
				echo json_encode($res);
			}
		}

		// menus.php
		public function filter($data){
			$res = [];
			if(empty($data['menuId'])){
				$res['status'] = '400';
				$res['msg'] = 'Menu ID required';
				echo json_encode($res);
			}else{
				$menuId = $this->fm->validation($data['menuId']);
				$menuId = mysqli_real_escape_string($this->db->link, $menuId);
				$query = "SELECT * FROM tbl_nav_menus WHERE menuId='$menuId' ORDER BY sort";
				$result = $this->db->select($query);
				if($result == true){
					$ref   = [];
					$items = [];
					while($data = $result->fetch_assoc()) {
	
						$thisRef = &$ref[$data['id']];
						$thisRef['parent'] = $data['parent'];
						$thisRef['label'] = $data['label'];
						$thisRef['slug'] = $data['slug'];
						$thisRef['id'] = $data['id'];
						if($data['parent'] == 0) {
							$items[$data['id']] = &$thisRef;
						} else {
							$ref[$data['parent']]['child'][$data['id']] = &$thisRef;
						}
					}
	
					function get_menu($items, $class = 'dd-list') {
	
						$html = "<ol class=\"".$class."\" id=\"menu-id\">";
	
						foreach($items as $key=>$value) {
							$html.= '<li class="dd-item dd3-item" data-id="'.$value['id'].'">
							<div class="dd-handle dd3-handle"></div><div class="dd3-content"><span>'.$value['label'].'</span> <span class="float-right"><span>/'.$value['slug'].'</span><a href="#" data_id="'.$value['id'].'" data_label="'.$value['label'].'" id="menuItemEdit"><i data-feather="edit"></i></a><a href="#" data_id="'.$value['id'].'" id="menuItemDel"><i data-feather="trash"></i></a></span></div>';
							if(array_key_exists('child', $value)) {
								$html .= get_menu($value['child'],'child');
							}
							$html .= "</li>";
						}
						$html .= "</ol>";
						return $html;
					}
					$res['status'] = '200';
					$res['msg'] = 'OK';
					$res['data'] = get_menu($items);
					echo json_encode($res);
				}else{
					$res['status'] = '204';
					$res['msg'] = 'No content';
					$res['data'] = '';
					echo json_encode($res);
				}
			}
			
		}

		// menus.php
		public function sortMenuItem($data){
			$menu_data = json_decode($data);
			function parseJsonArray($jsonArray, $parentID = 0) {
				$return = array();
				foreach ($jsonArray as $subArray) {
					$returnSubSubArray = array();
					if (isset($subArray->children)) {
						$returnSubSubArray = parseJsonArray($subArray->children, $subArray->id);
					}
					$return[] = array('id' => $subArray->id, 'parentID' => $parentID);
					$return = array_merge($return, $returnSubSubArray);
				}
				return $return;
			}
			$readbleArray = parseJsonArray($menu_data);

			$i=0;
			foreach($readbleArray as $row){
				$i++;
				$query = "UPDATE tbl_nav_menus SET parent = '".$row['parentID']."', sort= '".$i."' WHERE id = '".$row['id']."' ";
				$result = $this->db->update($query);
			}
			$res = [];
			if($result == true){
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $data;
				echo json_encode($res);
			}else{
				$res['status'] = '400'; 
				$res['msg'] = 'Bad Request';
				echo json_encode($res);
			}

		}

		// menus.php
		public function deleteMenuItem($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$id = $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "DELETE FROM tbl_nav_menus WHERE id='$id'";
				$result = $this->db->delete($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Menu item deleted';
					$res['data'] = $id;
					echo json_encode($res);
				}
			}
		}
		
		// verified
		public function updateMenuItem($id, $data){
			$res = [];
			if(empty($data['label'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Label required';
				echo json_encode($res);
			}else{
				$label = $this->fm->validation($data['label']);
				$label = mysqli_real_escape_string($this->db->link, $label);
				$query = "UPDATE tbl_nav_menus SET label='$label' WHERE id='$id'";
				$result = $this->db->update($query);
				if($result == true){
					$res['status'] = '200';
					$res['msg'] = 'Menu lebel updated';
					$res['data'] = $data;
					echo json_encode($res);
				}
			}
		}
		
		// header.php
		function multilevelMenus($name, $parent_id=0){
			$menu = '';
			$query = '';
			if($parent_id==0){
				$query = "SELECT tbl_nav_menus.*, 
				tbl_menu.id AS menuId 
				FROM tbl_nav_menus 
				LEFT JOIN tbl_menu ON tbl_nav_menus.menuId = tbl_menu.id 
				WHERE tbl_nav_menus.parent=0 
				AND tbl_menu.name='$name' 
				ORDER BY tbl_nav_menus.sort ASC";
			}else{
				$query = "SELECT tbl_nav_menus.*, 
				tbl_menu.* FROM tbl_nav_menus 
				LEFT JOIN tbl_menu ON tbl_nav_menus.menuId = tbl_menu.id 
				WHERE tbl_nav_menus.parent='$parent_id' 
				AND tbl_menu.name='$name' 
				ORDER BY tbl_nav_menus.sort ASC";
			}
			$result = mysqli_query($this->db->link, $query);
			while ($data = mysqli_fetch_assoc($result)) {
				if($data['slug'] !== '#'){
					$menu .='<li class="nav-item"><a href="'.BASE_URL.'/'.$data['slug'].'" class="nav-link">'.$data['label'].'</a>';
				}else{
					$menu .='<li class="nav-item dropdown"><a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
					'.$data['label'].' <i data-feather="chevron-down"></i></a>';
				}
				$menu .= '<ul class="dropdown-menu dropdown-menu-right">'.$this->multilevelMenus($name, $data['id']).'</ul>';
				$menu .= '</li>';
			}
			return $menu;
		}

		// header.php
		function navMenus($data){
			$res = [];
			if(empty($data['name'])){
				$res['status'] = '400';
				$res['msg'] = 'Menu name required';
				echo json_encode($res);	
			}else{
				$name = $this->fm->validation($data['name']);
				$name = mysqli_real_escape_string($this->db->link, $name);
				$menus = '';
				$menus .= $this->multilevelMenus($name);			
				$res['status'] = '200';
				$res['msg'] = 'OK';
				$res['data'] = $menus;
				echo json_encode($res);	
			}	
		} 

	}

?>