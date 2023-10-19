<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');

	class POST{
		private $db;
		private $fm;

		public function __construct(){
			$this->db = new Database();
			$this->fm = new Format();
		}

		// post-new.php
		public function create($data){
			$res = [];
			if(empty($data['postType'])){
				$res['status'] = '400';
				$res['msg'] = 'Post type required';
				echo json_encode($res);
			}elseif(empty($data['title'])){
				$res['status'] = '400';
				$res['msg'] = 'Post title required';
				echo json_encode($res);
			}elseif(empty($data['slug'])){
				$res['status'] = '400';
				$res['msg'] = 'Post slug required';
				echo json_encode($res);
			}else{
				$author 	= $this->fm->validation($data['author']);
				$postType 	= $this->fm->validation($data['postType']);
				$title 		= $this->fm->validation($data['title']);
				$slug 		= $this->fm->validation($data['slug']);
				$content 	= $this->fm->validation(array_key_exists('content', $data) ? $data['content'] : '');
				$excerpt 	= $this->fm->validation(array_key_exists('excerpt', $data) ? $data['excerpt'] : '');
				$image 		= $this->fm->validation(array_key_exists('image', $data) ? $data['image'] : '');
				$template 	= $this->fm->validation(array_key_exists('template', $data) ? $data['template'] : '');
				$category 	= $this->fm->validation(array_key_exists('category', $data) ? $data['category'] : '');
				$status 	= $this->fm->validation(array_key_exists('status', $data) ? $data['status'] : '');
				$ordering 	= $this->fm->validation(array_key_exists('ordering', $data) ? $data['ordering'] : '');
	
				$postType 	= mysqli_real_escape_string($this->db->link, $postType);
				$title 		= mysqli_real_escape_string($this->db->link, $title);
				$slug 		= mysqli_real_escape_string($this->db->link, $slug);
				$content 	= mysqli_real_escape_string($this->db->link, $content);
				$excerpt 	= mysqli_real_escape_string($this->db->link, $excerpt);
				$image 		= mysqli_real_escape_string($this->db->link, $image);
				$template 	= mysqli_real_escape_string($this->db->link, $template);
				$category 	= mysqli_real_escape_string($this->db->link, $category);
				$status 	= mysqli_real_escape_string($this->db->link, $status);
				$ordering 	= mysqli_real_escape_string($this->db->link, $ordering);

				$slug = $this->fm->format_uri($slug);

				$query = "SELECT id FROM tbl_posts WHERE slug='$slug'";
				$result = $this->db->select($query);
				if ($result) {
					$res['status'] = '208';
					$res['msg'] = $postType.' slug already exists';
					echo json_encode($res);
				}else{
					$query = "INSERT INTO tbl_posts(author, title, content, excerpt, image, slug, isTemplate, postType, category, status, ordering) 
					VALUES('$author', '$title', '$content', '$excerpt', '$image', '$slug', '$template', '$postType', '$category', '$status', '$ordering')";
					$result = $this->db->insert($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = $postType.' added';
						echo json_encode($res);
					}
				}
			}			
		}
		
		// post-edit.php
		public function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = ' Bad request';
				echo json_encode($res);
			}else{
				$id = $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "SELECT P.*, 
				M.name AS mediaName
				FROM tbl_posts P 
				LEFT JOIN tbl_media M ON P.image = M.id 
				WHERE P.id='$id'";
				$result = $this->db->select($query);
				if($result){
					while($value = $result->fetch_assoc()){
						$datum = $value;
						$datum['content'] =  htmlspecialchars_decode($value['content'], ENT_QUOTES);
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

		// post-edit.php (admin auth)
		public function update($id, $data){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = ' Bad request';
				echo json_encode($res);
			}elseif(empty($data['title'])){
				$res['status'] = '400';
				$res['msg'] = 'Post title required';
				echo json_encode($res);
			}elseif(empty($data['slug'])){
				$res['status'] = '400';
				$res['msg'] = 'Post slug required';
				echo json_encode($res);
			}else{
				$id 		= $this->fm->validation($id);
				$postType 	= $this->fm->validation($data['postType']);
				$title 		= $this->fm->validation($data['title']);
				$slug 		= $this->fm->validation($data['slug']);
				$content 	= $this->fm->validation(array_key_exists('content', $data) ? $data['content'] : '');
				$excerpt 	= $this->fm->validation(array_key_exists('excerpt', $data) ? $data['excerpt'] : '');
				$image 		= $this->fm->validation(array_key_exists('image', $data) ? $data['image'] : '');
				$template 	= $this->fm->validation(array_key_exists('template', $data) ? $data['template'] : '');
				$category 	= $this->fm->validation(array_key_exists('category', $data) ? $data['category'] : '');
				$status 	= $this->fm->validation(array_key_exists('status', $data) ? $data['status'] : '');
				$ordering 	= $this->fm->validation(array_key_exists('ordering', $data) ? $data['ordering'] : '');
	
				$postType 	= mysqli_real_escape_string($this->db->link, $postType);
				$title 		= mysqli_real_escape_string($this->db->link, $title);
				$slug 		= mysqli_real_escape_string($this->db->link, $slug);
				$content 	= mysqli_real_escape_string($this->db->link, $content);
				$excerpt 	= mysqli_real_escape_string($this->db->link, $excerpt);
				$image 		= mysqli_real_escape_string($this->db->link, $image);
				$template 	= mysqli_real_escape_string($this->db->link, $template);
				$category 	= mysqli_real_escape_string($this->db->link, $category);
				$status 	= mysqli_real_escape_string($this->db->link, $status);
				$ordering 	= mysqli_real_escape_string($this->db->link, $ordering);
								
				$slug = $this->fm->format_uri($slug);
				
				$query = "SELECT id FROM tbl_posts WHERE slug='$slug' AND NOT id = '$id'";
				$result = $this->db->select($query);
				if ($result == true) {
					$res['status'] = '208';
					$res['msg'] = $postType.' slug already exists';
					echo json_encode($res);
				}else{
					$query = "UPDATE tbl_posts SET title ='$title', content='$content', excerpt='$excerpt', image ='$image', slug='$slug', isTemplate='$template', category='$category', status='$status', ordering='$ordering' WHERE id='$id'";
					$result = $this->db->update($query);
					if($result){
						$res['status'] = '200';
						$res['msg'] = $postType.' updated';
						$res['data'] = $data;
						echo json_encode($res);
					}
				}
			}
		}
		
		// posts.php, menus.php
		public function filter($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$type	= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
				$status	= $this->fm->validation(array_key_exists('status', $info) ? $info['status'] : '');
				$key 	= $this->fm->validation($info['key']);
				$val 	= $this->fm->validation($info['value']);
				$key 	= mysqli_real_escape_string($this->db->link, $key);
				$val 	= mysqli_real_escape_string($this->db->link, $val);
				
				$query = "SELECT P.*, 
				U.name AS authorName, 
				M.name As mediaName, 
				C.name AS categoryName,
				S.name AS statusName
				FROM tbl_posts P
				LEFT JOIN tbl_users U ON P.author = U.id 
				LEFT JOIN tbl_media M ON P.image = M.id 
				LEFT JOIN tbl_category C ON P.category = C.name 
				LEFT JOIN tbl_status S ON P.status = S.id 
				WHERE P.$key = '$val' ";
				
				if(!empty($status)){
					$query .=" AND P.status = '$status' ";
				}
				
				if(array_key_exists('operator', $info)){
					$operator = $this->fm->validation($info['operator']);
					$key2 = $this->fm->validation($info['key2']);
					$val2 = $this->fm->validation($info['value2']);
					$operator = mysqli_real_escape_string($this->db->link, $operator);
					$key2 = mysqli_real_escape_string($this->db->link, $key2);
					$val2 = mysqli_real_escape_string($this->db->link, $val2);
					$query .=" $operator P.$key2 = '$val2' ";
				}

				if(array_key_exists('ordering', $info)){
					$ordering = $this->fm->validation($info['ordering']);
					$ordering = mysqli_real_escape_string($this->db->link, $ordering);
					$query .=" ORDER BY P.ordering $ordering";
				}else{
					$query .=" ORDER BY P.id DESC";
				}

				if(array_key_exists('limit', $info)){
					$limit = $this->fm->validation($info['limit']);
					$limit = mysqli_real_escape_string($this->db->link, $limit);
					if($limit > 0){
						$query .=" LIMIT $limit";
					}
				}
				
				$pagination = '';
				if(array_key_exists('page', $info)){
					$per_page = $this->fm->validation(array_key_exists('per_page', $info) ? $info['per_page'] : 12);
					$per_page = mysqli_real_escape_string($this->db->link, $per_page);
					$page = $this->fm->validation($info['page'] ? $info['page'] : 1);
					$page = mysqli_real_escape_string($this->db->link, $page);
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
					$i = 0;
					$data = [];
					while ($value = $result->fetch_assoc()) {
						$i++;
						$datum = array();
						$datum = $value;
						$datum['sn'] = $i;
						$img = $value['mediaName'] ? '<span class="img"><img src="../uploads/'.$value['mediaName'].'"></span> ': '';
						$datum['titlePhoto'] = $img.$value['title'];
						$datum['category'] = $value['category'] ? '<span class="status statusVerified mr-1">'.$value['category'].'</span>' : '-' ;
						$datum['content'] =  htmlspecialchars_decode($value['content'], ENT_QUOTES);
						$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);
						$datum['statusAction'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';

						if($type == 'dataTable'){
							$datum['action'] = '<div class="dropleft"><a href="#" data-toggle="dropdown" class="action_btn"> <i data-feather="more-vertical"></i></a>
							<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">
							<a href="post-edit.php?post-type='.$value["postType"].'&id='.$value['id'].'"><i data-feather="edit"> </i>Edit</a>
							<a href="#" data-id="'.$value['id'].'" id="delPost"><i data-feather="trash"></i> Delete</a>
							</div></div>'; 
						}
						$data[] = $datum; 
					}
					$res['status'] = '200';
					$res['msg'] = 'OK';
					$res['pagination'] = $pagination;
					$res['data'] = $data;
					echo json_encode($res);
				}else{
					$res['status'] = '204'; 
					$res['msg'] = 'No Content'; 
					$res['data'] = [];
					echo json_encode($res);
				}	
			}
		}

		// posts.php (admin auth)
		public function delete($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request'; 
				echo json_encode($res);
			}else{
				$id = $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "DELETE FROM tbl_posts WHERE id = '$id'";
				$result = $this->db->delete($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Deleted';
					echo json_encode($res);
				}
			}
		}

		
		public function getTitle($slug){
			$res = [];
			if(empty($slug)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request'; 
				echo json_encode($res);
			}else{
				$slug = $this->fm->validation($slug);
				$slug = mysqli_real_escape_string($this->db->link, $slug);
				$query = "SELECT title FROM tbl_posts WHERE slug = '$slug'";
				$result = $this->db->select($query);
				if($result){
					while($value = $result->fetch_assoc()){
						$data[] = $value;
					}
					$res['status'] = '200';
					$res['msg'] = 'ok';
					$res['data'] = $data;
					echo json_encode($res);
				}
			}
		}

	}

?>