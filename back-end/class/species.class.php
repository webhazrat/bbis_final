<?php 
	
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/Database.php');
	include_once ($filepath.'/../helpers/Format.php');
	include_once ($filepath.'/../helpers/Helpers.php');
	include_once ($filepath.'/../class/sendEmail.class.php');
	
	class SPECIES{
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
				$query = "SELECT M.*, 
				U.name AS author1 
				FROM tbl_media M 
				LEFT JOIN tbl_users U ON M.author = U.id 
				WHERE M.id='$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while ($value = $result->fetch_assoc()) {
						array_push($photos, $value);
					}
				}
			}
			return $photos;
		}

		// service species-addition
		public function speciesAddition($id){
			$addition = [];
			$query = "SELECT SA.*,
			U.name AS authorName, 
			D.name AS districtName
			FROM tbl_species_addition SA 
			LEFT JOIN tbl_users U ON SA.author = U.id 
			LEFT JOIN tbl_districts D ON SA.district = D.id 
			WHERE SA.spId='$id' AND SA.status='8'";
			$result = $this->db->select($query);
			if($result){
				while ($value = $result->fetch_assoc()) {
					$datum = $value;
					$datum['photos'] = $this->getPhoto($value['photos']);
					array_push($addition, $datum);
				}
			}
			return $addition;
		}

		// service hierarchy
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

		// species-add.php
		public function createAuth($data){
			$res = [];			
			if(empty($data['spGroup'])){
				$res['status'] = '400';
				$res['msg'] = 'Species group required';
				echo json_encode($res);
			}else{
				$SequencesArr = array();			
				$OccurrencesArr = array();			
				$AcceptedNameSynonymArr = array();			
				$ReferencesArr = array();			
				$i = 0;
				foreach($data as $key => $item){
					$i++;
					if(array_key_exists('Sequence'.$i, $data) && !empty($data['Sequence'.$i])){
						$SequencesArr[] = base64_encode($data['Sequence'.$i]);
					}
					if(array_key_exists('Occurrence'.$i, $data) && !empty($data['Occurrence'.$i])){
						$OccurrencesArr[] = base64_encode($data['Occurrence'.$i]);
					}
					if(array_key_exists('AcceptedNameSynonym'.$i, $data) && !empty($data['AcceptedNameSynonym'.$i])){
						$AcceptedNameSynonymArr[] = base64_encode($data['AcceptedNameSynonym'.$i]);
					}
					if(array_key_exists('Reference'.$i, $data) && !empty($data['Reference'.$i])){
						$ReferencesArr[] = base64_encode($data['Reference'.$i]);
					}
				}

				$author 					= $this->fm->validation($data['author']);
				$spGroup 					= $this->fm->validation($data['spGroup']);
				$spCode 					= $this->fm->codeGenerate(8);

				$Kingdom 					= $this->fm->validation($data['Kingdom']);
				$Phylum 					= $this->fm->validation($data['Phylum']);
				$Class 						= $this->fm->validation($data['Class']);
				$Order 						= $this->fm->validation($data['Order']);
				$Family 					= $this->fm->validation($data['Family']);
				$SubFamily 					= $this->fm->validation($data['SubFamily']);
				$Genus 						= $this->fm->validation($data['Genus']);
				$GenusAuthority 			= $this->fm->validation($data['GenusAuthority']);
				$Species 					= $this->fm->validation($data['Species']);
				$SpeciesAuthority 			= $this->fm->validation($data['SpeciesAuthority']);
				$SubSpecies 				= $this->fm->validation($data['SubSpecies']);
				$SubSpeciesAuthority 		= $this->fm->validation($data['SubSpeciesAuthority']);
				$ScientificName 			= $this->fm->validation($data['ScientificName']);
				$ScientificNameAuthority 	= $this->fm->validation($data['ScientificNameAuthority']);
				$EnglishName 				= $this->fm->validation($data['EnglishName']);
				$LocalName 					= $this->fm->validation($data['LocalName']);
				$Habitat 					= $this->fm->validation($data['Habitat']);
				$BangladeshDistribution 	= $this->fm->validation($data['BangladeshDistribution']);
				$GlobalDistribution 		= $this->fm->validation($data['GlobalDistribution']);
				$Map 						= $this->fm->validation($data['Map']);
				$BangladeshIUCNStatus 		= $this->fm->validation($data['BangladeshIUCNStatus']);
				$BangladeshAssessingYear 	= $this->fm->validation($data['BangladeshAssessingYear']);
				$GlobalIUCNStatus 			= $this->fm->validation($data['GlobalIUCNStatus']);
				$GlobalAssesingYear 		= $this->fm->validation($data['GlobalAssesingYear']);
				$CITIS 						= $this->fm->validation($data['CITIS']);
				$ShortDescription 			= $this->fm->validation($data['ShortDescription']);
				$Biology 					= $this->fm->validation($data['Biology']);
				$CiteThisPage 				= $this->fm->validation($data['CiteThisPage']);

				$Sequences 					= implode(',', $SequencesArr);
				$Occurrences 				= implode(',', $OccurrencesArr);
				$AcceptedNameSynonym 		= implode(',', $AcceptedNameSynonymArr);
				$References 				= implode(',', $ReferencesArr);
				
				$query = "INSERT INTO tbl_species(author, groupId, spCode, spKingdom, spPhylum, spClass, spOrder, spFamily, spSubFamily, spGenus, spGenusAuth, spSpecies, spSpeciesAuth, spSubSpecies, spSubSpeciesAuth, spScName, spScNameAuth, spEngName, spLocalName, spHabitat, spSeq, spBdDist, spGbDist, spOc, spMap, spIucnBd, spIucnBdYear, spIucnGb, spIucnGbYear, spCitis, spAcName, spShortDes, spBiology, spCitePage, spRef, status) VALUES('$author', '$spGroup', '$spCode', '$Kingdom', '$Phylum', '$Class', '$Order', '$Family', '$SubFamily', '$Genus', '$GenusAuthority', '$Species', '$SpeciesAuthority', '$SubSpecies', '$SubSpeciesAuthority', '$ScientificName', '$ScientificNameAuthority', '$EnglishName', '$LocalName', '$Habitat', '$Sequences', '$BangladeshDistribution', '$GlobalDistribution', '$Occurrences', '$Map', '$BangladeshIUCNStatus', '$BangladeshAssessingYear', '$GlobalIUCNStatus', '$GlobalAssesingYear', '$CITIS', '$AcceptedNameSynonym', '$ShortDescription', '$Biology', '$CiteThisPage', '$References', '8')";
				$result = $this->db->insert($query);
				if($result){
					$res['status'] = '200';
					$res['count'] = $data['count'];
					$res['msg'] = 'Species added successfully';
					echo json_encode($res);
				}
			}
		}
		
		// species.php, my-contributions.php
		public function filterAuth($info){
			$res = [];
			if(empty($info['key']) || empty($info['value'])){
				$res['status'] = '200';
				$res['msg'] = 'Key and value required';
				echo json_encode($res);
			}else{
				$key 		= $this->fm->validation($info['key']);
				$val 		= $this->fm->validation($info['value']);
				$role		= $this->fm->validation($info['role'] );
				$type		= $this->fm->validation(array_key_exists('type', $info) ? $info['type'] : '');
				$status		= $this->fm->validation(array_key_exists('status', $info) ? $info['status'] : '');
				
				$columns = array('SP.id', 'U.name', 'SG.name', 'SP.spCode', 'SP.spScName', 'SP.spEngName', 'SP.spKingdom', 'SP.spPhylum', 'SP.spClass', 'SP.spFamily', 'reviewNums', 'S.name', 'SP.createdAt');
	
				$query = "SELECT SP.*, 
				SG.hierarchyPath,
				SG.name AS groupName,
				U.name AS authorName, 
				U.userName, 
				S.name AS statusName, 
				COUNT(DISTINCT SA.id) AS reviewNums
				FROM tbl_species SP 
				LEFT JOIN tbl_group SG ON SG.id = SP.groupId 
				LEFT JOIN tbl_users U ON U.id = SP.author 
				LEFT JOIN tbl_status S ON S.id = SP.status 
				LEFT JOIN tbl_species_addition SA ON SA.spId = SP.id AND SA.status != '8' ";
	
				if(!empty($info['search']['value'])){
					$query .= " WHERE ";
					$searchArr = explode(' ', $info['search']['value']);
					for ($i=0; $i < count($searchArr); $i++) { 
						$query .= " (U.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SG.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spCode LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spScName LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spEngName LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spKingdom LIKE '".$searchArr[$i]."%' ";
						$query .= " OR SP.spPhylum LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spClass LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.spFamily LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR S.name LIKE '%".$searchArr[$i]."%' ";
						$query .= " OR SP.createdAt LIKE '%".$searchArr[$i]."%' ";
						$query .= " ) ";
						if($i < count($searchArr) - 1){
							$query .= ' AND ';
						}
					}
	
					if($key !== 'all' && $key !== 'manageGroup'){
						$query .= " AND SP.$key = '$val' ";
					}elseif($key === 'manageGroup'){
						$query .= " AND SP.groupId in ($val) ";
					}
					if($key === 'author' && $status){
						$query .= " AND SP.status = '$status' ";
					}
				}
	
				if($key !== 'all' && $key !== 'manageGroup'){
					$query .= " WHERE SP.$key = '$val' ";
				}elseif($key === 'manageGroup'){
					$query .= " WHERE SP.groupId in ($val) ";
				}
				if($key === 'author' && $status){
					$query .= " AND SP.status = '$status' ";
				}
	
				$query .= " GROUP BY SP.id ";
	
				if(array_key_exists('order', $info) && count($info['order']) > 0){
					$query .= " ORDER BY ".$columns[$info['order']['0']['column']]." ".$info['order']['0']['dir'];
				}else{
					$query .= " ORDER BY SP.id DESC";
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
						$datum['spEngName'] = $this->fm->textShorten($value['spEngName'], 40);
						$datum['authorMod'] = '<a target="_blank" href="'.BASE_URL.'/user/'.$value['userName'].'">'.$value['authorName'].'</a>';
						$datum['statusName'] = '<span class="status status'.$value['statusName'].'">'.$value['statusName'].'</span>';
						$datum['hierarchyName'] = implode(' > ', $this->hierarchy($value['hierarchyPath']));
						$datum['createdMod'] = $this->fm->date('d M, Y', $value['createdAt']);
						if($type === 'dataTable'){
							$datum['action'] = '<div class="dropleft"><a href="#" class="action_btn" data-toggle="dropdown"><i data-feather="more-vertical"></i></a>
								<div class="dropdown-menu dropdown-menu-right" style="min-width:100px">';
									if($key !== 'author'){ 
									$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="speciesStatus"><i data-feather="settings"></i>Status</a>
									<a href="species-addition.php?id='.$value['id'].'" id="speciesAddition"><i data-feather="list"> </i>Addition</a>';
									$datum['action'] .= '<a href="species-edit.php?id='.$value['id'].'" data_id="'.$value['id'].'" id="speciesEdit"><i data-feather="edit"> </i>Edit</a>';
									}
									if($key === 'author'){
										$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="speciesEdit"><i data-feather="edit"> </i>Edit</a>';
									}
									if($key !== 'author'){
										$datum['action'] .= '<a href="#" data_id="'.$value['id'].'" id="speciesDelete"><i data-feather="trash"></i>Delete</a>';
									}
							$datum['action'] .= '</div>
							</div>';
						}
						$data[] = $datum;
					}
					
					if($type == 'dataTable'){
						$query2 = "SELECT id FROM tbl_species ";
						if($key !== 'all' && $key !== 'manageGroup'){
							$query2 .= " WHERE $key = '$val' ";
						}elseif($key === 'manageGroup'){
							$query2 .= " WHERE groupId in ($val) ";
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

		// home.php, species-list.php, species-profile.php
		public function filter($info){
			$res = [];
			$status 	= array_key_exists("status", $info) ? $info['status'] : '';
			$key 		= array_key_exists("key", $info) ? $info['key'] : '';
			$val 		= array_key_exists("value", $info) ? $info['value'] : '';
			$slug 		= array_key_exists("slug", $info) ? $info['slug'] : '';
			$per_page 	= array_key_exists("per_page", $info) ? $info['per_page'] : '12';
			$page 		= array_key_exists("page", $info) ? $info['page'] : '';
			$limit 		= array_key_exists("limit", $info) ? $info['limit'] : '';
			$order 		= array_key_exists("order", $info) ? $info['order'] : '';
			
			$query = "SELECT SP.*, 
			U.name AS authorName,
			SPG.hierarchyPath
			FROM tbl_species SP 
			LEFT JOIN tbl_users U ON U.id = SP.author 
			LEFT JOIN tbl_group SPG ON SPG.id = SP.groupId ";

			if(!empty($key) && !empty($val)){
				$query .= " WHERE SP.$key = '$val' ";
			}elseif(!empty($status)){
				$query .= " WHERE SP.status = '$status' ";
			}elseif(!empty($slug)){
				$query .= " WHERE SPG.slug = '$slug' ";
			}
			
			if(!empty($status) && !empty($slug)){
				$query .= " AND SPG.slug = '$slug' ";
			}elseif(!empty($status) && !empty($slug) && !empty($key) && !empty($val)){
				$query .= " AND SP.$key = '$val' ";
			}
			
			$query .= " GROUP BY SP.id ";
			
			if(!empty($order)){
				$query .= " ORDER BY SP.id $order";
			}else{
				$query .= " ORDER BY SP.id DESC";
			}
			if(!empty($limit)){
				$query .= " LIMIT $limit";
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
					$datum['hierarchyName'] = $this->hierarchy($value['hierarchyPath']);
					$datum['addition'] = $this->speciesAddition($value['id']);
					$datum['spCitePage'] = htmlspecialchars_decode($value['spCitePage'], ENT_QUOTES);
					$datum['spBiology'] = htmlspecialchars_decode($value['spBiology'], ENT_QUOTES);
					$datum['spSeq'] = $this->fm->base64Decode(explode(",", $value['spSeq']));
					$datum['spOc'] = $this->fm->base64Decode(explode(",", $value['spOc']));
					$datum['spAcName'] = $this->fm->base64Decode(explode(",", $value['spAcName']));
					$datum['spRef'] = $this->fm->base64Decode(explode(",", $value['spRef']));
					$datum['spPhotos'] = $this->getPhoto($value['spPhotos']);
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
		
		// species-edit.php
		public function findOne($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request'; 
				echo json_encode($res);
			}else{
				$id	= $this->fm->validation($id);
				$id = mysqli_real_escape_string($this->db->link, $id);
				$query = "SELECT * FROM tbl_species WHERE id = '$id' LIMIT 1";
				$result = $this->db->select($query);
				if($result){
					while ($value = $result->fetch_assoc()) {
						$datum = $value;
						$datum['spCitePage'] = htmlspecialchars_decode($value['spCitePage'], ENT_QUOTES);
						$datum['spBiology'] = htmlspecialchars_decode($value['spBiology'], ENT_QUOTES);
						$datum['spScNameAuth'] = htmlspecialchars_decode($value['spScNameAuth'], ENT_QUOTES);
						$datum['spSpeciesAuth'] = htmlspecialchars_decode($value['spSpeciesAuth'], ENT_QUOTES);
						$datum['spIucnGb'] = htmlspecialchars_decode($value['spIucnGb'], ENT_QUOTES);
						$datum['spHabitat'] = htmlspecialchars_decode($value['spHabitat'], ENT_QUOTES);
						$datum['spSeq'] = !empty($value['spSeq']) ? $this->fm->base64Decode(explode(",", $value['spSeq'])) : '';
						$datum['spOc'] = !empty($value['spOc']) ? $this->fm->base64Decode(explode(",", $value['spOc'])) : '';
						$datum['spAcName'] = !empty($value['spAcName']) ? $this->fm->base64Decode(explode(",", $value['spAcName'])) : '';
						$datum['spRef'] = !empty($value['spRef']) ? $this->fm->base64Decode(explode(",", $value['spRef'])) : '';
						$datum['spPhotos'] = $this->getPhoto($value['spPhotos']);
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

		// species-edit.php
		public function update($id, $data){
			$res = [];	
			if(empty($id)){
				$res['status'] = '400';
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}elseif(empty($data['groupId'])){
				$res['status'] = '400';
				$res['msg'] = 'Species group required';
				echo json_encode($res);
			}else{
				$SequencesArr = array();
				$OccurrencesArr = array();
				$AcceptedNameSynonymArr = array();
				$ReferencesArr = array();
				$i = 0;
				foreach($data as $key => $item){
					$i++;
					if(array_key_exists('Sequence'.$i, $data) && !empty($data['Sequence'.$i])){
						$SequencesArr[] = base64_encode($data['Sequence'.$i]);
					}
					if(array_key_exists('Occurrence'.$i, $data) && !empty($data['Occurrence'.$i])){
						$OccurrencesArr[] = base64_encode($data['Occurrence'.$i]);
					}
					if(array_key_exists('AcceptedNameSynonym'.$i, $data) && !empty($data['AcceptedNameSynonym'.$i])){
						$AcceptedNameSynonymArr[] = base64_encode($data['AcceptedNameSynonym'.$i]);
					}
					if(array_key_exists('Reference'.$i, $data) && !empty($data['Reference'.$i])){
						$ReferencesArr[] = base64_encode($data['Reference'.$i]);
					}
				}

				$groupId 			= $this->fm->validation($data['groupId']);
				$spKingdom 			= $this->fm->validation($data['spKingdom']);
				$spPhylum 			= $this->fm->validation($data['spPhylum']);
				$spClass 			= $this->fm->validation($data['spClass']);
				$spOrder 			= $this->fm->validation($data['spOrder']);
				$spFamily 			= $this->fm->validation($data['spFamily']);
				$spSubFamily 		= $this->fm->validation($data['spSubFamily']);
				$spGenus 			= $this->fm->validation($data['spGenus']);
				$spGenusAuth 		= $this->fm->validation($data['spGenusAuth']);
				$spSpecies 			= $this->fm->validation($data['spSpecies']);
				$spSpeciesAuth 		= $this->fm->validation($data['spSpeciesAuth']);
				$spSubSpecies 		= $this->fm->validation($data['spSubSpecies']);
				$spSubSpeciesAuth 	= $this->fm->validation($data['spSubSpeciesAuth']);
				$spScName 			= $this->fm->validation($data['spScName']);
				$spScNameAuth 		= $this->fm->validation($data['spScNameAuth']);
				$spEngName 			= $this->fm->validation($data['spEngName']);
				$spLocalName 		= $this->fm->validation($data['spLocalName']);
				$spHabitat 			= $this->fm->validation($data['spHabitat']);
				$spBdDist 			= $this->fm->validation($data['spBdDist']);
				$spGbDist 			= $this->fm->validation($data['spGbDist']);
				$spIucnBd 			= $this->fm->validation($data['spIucnBd']);
				$spIucnBdYear 		= $this->fm->validation($data['spIucnBdYear']);
				$spIucnGb 			= $this->fm->validation($data['spIucnGb']);
				$spIucnGbYear 		= $this->fm->validation($data['spIucnGbYear']);
				$spCitis 			= $this->fm->validation($data['spCitis']);
				$spShortDes 		= $this->fm->validation($data['spShortDes']);
				$spBiology 			= $this->fm->validation($data['spBiology']);
				$spCitePage 		= $this->fm->validation($data['spCitePage']);

				$spSeq 				= implode(',', $SequencesArr);
				$spOc 				= implode(',', $OccurrencesArr);
				$spAcName 			= implode(',', $AcceptedNameSynonymArr);
				$spRef 				= implode(',', $ReferencesArr);
				$spPhotos 			= $this->fm->validation($data['spPhotos']);

				$query = "UPDATE tbl_species SET spKingdom='$spKingdom', spPhylum='$spPhylum', spClass='$spClass', spOrder='$spOrder', spFamily='$spFamily', spSubFamily='$spSubFamily', spGenus='$spGenus', spGenusAuth='$spGenusAuth', spSpecies='$spSpecies', spSpeciesAuth='$spSpeciesAuth', spSubSpecies='$spSubSpecies', spSubSpeciesAuth='$spSubSpeciesAuth', spScName='$spScName', spScNameAuth='$spScNameAuth', spEngName='$spEngName', spLocalName='$spLocalName', spHabitat='$spHabitat', spSeq='$spSeq', spBdDist='$spBdDist', spGbDist='$spGbDist', spOc='$spOc', spIucnBd='$spIucnBd', spIucnBdYear='$spIucnBdYear', spIucnGb='$spIucnGb', spIucnGbYear='$spIucnGbYear', spCitis='$spCitis', spAcName='$spAcName', spShortDes='$spShortDes', spBiology='$spBiology', spCitePage='$spCitePage', spRef='$spRef', spPhotos='$spPhotos' WHERE id='$id'";
				$result = $this->db->update($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Species updated';
					echo json_encode($res);
				}
			}
		}

		// species.php
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
				$query = "UPDATE tbl_species SET status='$status', comment='$comment' WHERE id='$id'";
				$result = $this->db->update($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Species updated';
					echo json_encode($res);
				}
			}
		}

		// species.php
		public function delete($id){
			$res = [];
			if(empty($id)){
				$res['status'] = '400'; 
				$res['msg'] = 'Bad request';
				echo json_encode($res);
			}else{
				$query = "DELETE FROM tbl_species WHERE id='$id'";
				$result = $this->db->delete($query);
				if($result){
					$res['status'] = '200';
					$res['msg'] = 'Species Deleted';
					echo json_encode($res);
				}
			}			
		}
		
		// home.php
		public function search($info){
			$res = [];
			if(empty($info["key"])){
				$res['status'] = '400';
				$res['msg'] = 'Key required';
				echo json_encode($res);
			}else{
				$key = $this->fm->validation($info["key"]);
				$key = mysqli_real_escape_string($this->db->link, $key);
				$query = "SELECT SP.id, SP.spCode, SP.spFamily, SP.spScName, SP.spScNameAuth, SP.spEngName, SP.createdAt, U.name AS authorName 
				FROM tbl_species SP 
				LEFT JOIN tbl_users U ON SP.author = U.id 
				WHERE (SP.spFamily LIKE '%".$key."%' OR SP.spScName LIKE '%".$key."%' OR SP.spEngName LIKE '%".$key."%') AND SP.status = '8' ORDER BY id DESC";
				$result = $this->db->select($query);
				if ($result) {
					$data = [];
					while ($value = $result->fetch_assoc()) {
						$datum = array();
						$datum = $value;
						$datum['createdAt'] = $this->fm->date('d M, Y', $value['createdAt']);
						$data[] = $datum;
					}
					$res['status'] = '200';
					$res['msg'] = 'OK';
					$res['data'] = $data;
					echo json_encode($res);
				}else{
					$res['status'] = '204';
					$res['msg'] = 'No content';
					$res['data'] = [];
					echo json_encode($res);
				}
			}
		}
		



	}

?>