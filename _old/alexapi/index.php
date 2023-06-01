<?php

/*

Α) Πόσα και ποια POIs υπάρχουν ανά περιοχή?

Β) Πόσα και ποια POIs υπάρχουν ανά έργο;

Γ) Πόσες και ποιες διαδρομές υπάρχουν ανά περιοχή?

Δ) Πόσες και ποιες διαδρομές υπάρχουν ανά έργο?

Ε) Πόσα και ποια POIs υπάρχουν ανά κατηγορία?


base_url: https://www.projectbis.com/repo  api_key: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy
οι τιμές για το root_area_id είναι:

Ροδόπη: 1
Haskovo: 2
Kardzhali: 3
Smolyan: 4


/api/mainareas επιστρέφει τις κεντρικές περιοχές.
parameters (none)
response: array από objects με τα παρακάτω properties: id, name

/api/maincategories επιστρέφει τις κύριες κατηγορίες
parameters: root_area_id (το id της κεντρικής περιοχής)
georeferenced (optional,values {1|0}, default:0. Αν 1, τότε επιστρέφει μόνο κατηγορίες που έχουν έστω κι ένα σημείο με ορισμένες συντεταγμένες θέσης.)
response: array από objects με τα παρακάτω properties
id, name, is_timeline (values {1|0}, δείχνει αν τα σχετιζόμενα σημεία ενδιαφέροντος έχουν ημερομηνίες από-έως)

Πχ παράδειγμα κλήσης για να πάρουμε όλα τα pois για Ροδόπη

curl --location --request GET 'https://www.projectbis.com/repo/api/pois?root_area_id=1&lang=el' \
--header 'X-Api-Key: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy'

INSERT INTO `routes`(`route_id`, `id`, `project_id`, `category_id`, `name`, `main_image`, `region_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])

*/

	include('class.db.php');
	//include('class.mysql.php');
	$host = 'localhost';
	$database = 'pentaho';
	$dbuser = 'pentaho';
	$dbpass = 'qwe#123!@#';
	/*
	$host = 'localhost';
	$database = 'scities';
	$dbuser = 'scities';
	$dbpass = 'qwe#123!@#'; 
	*/
	//$db = new sql_db($host, $dbuser, $dbpass, $database, false);
	$db = new sql_db($host, $dbuser, $dbpass, $database);
	
	//$db = new sql_db($host, $dbuser, $dbpass, $database, false);

	//Variables
	$categories = array();
	$regions = array();
	$catArray = array();
	$pois = array();
	
	//Get all regions in array
	$regions = getRegions();

	//update routes
	//delete first
	$deleteRoutesQuery="DELETE FROM routes";
	$resultDelete = $db->sql_query($deleteRoutesQuery);
	//while ($dr = $db->sql_fetchrow($result)){	
	
	for($i=0;$i<sizeof($regions);$i++){
		$regionRoutes=getRoutes($regions[$i]['id']);
		if(sizeof($regionRoutes->success)>0){
			for($j=0;$j<sizeof($regionRoutes->success);$j++){
				$insertQuery="INSERT INTO routes(id, project_id, category_id, name, main_image, region_id) VALUES 
				('".$regionRoutes->success[$j]->id."','".$regionRoutes->success[$j]->project_id."','".$regionRoutes->success[$j]->category_id."','".$regionRoutes->success[$j]->name."','".$regionRoutes->success[$j]->main_image."','".$regions[$i]['id']."')";
				$resultInsert = $db->sql_query($insertQuery);
			}
		}
		//echo $regionRoutes->success[0]->name;
		//print_r($regionRoutes);
		//echo sizeof($regionRoutes->success);
		//for($i=0;$i<sizeof($data->success);$i++){
	}
	
	exit;
	
	/*
	//Get all region categories to an array
	$allCategories=array();
	$categoriesCount=0;
	for($i=0;$i<sizeof($regions);$i++){
		$categories=array();	
		$categories = getCategories($regions[$i]['id']);
		for($j=1;$j<sizeof($categories);$j++){
			//$allCategories[$categoriesCount]['id']=$categories[$j]['id'];
			//$allCategories[$categoriesCount]['name']=$categories[$j]['name'];
			$allCategories[$categories[$j]['id']]=$categories[$j]['name'];
			$categoriesCount++;
		}
	}
	
	//Build an array from categories ids
	foreach ($allCategories as $key=>$value) {
	  $catArray[]=$key;
	}
	
	

	$pois = getPois($catArray);
	*/
	
	
	//print_r($pois);
	
	//$poi=getPoi("1_53");
	//print_r($poi);
	//$route=getRoutes(1);
	//var_dump($route);
	
	//$arr=array(96,102);
	//$pois = getPois($arr);
	//print_r($allCategories);

function getCategories($regionId=1){
	//global $db,$auth,$config;
	$langId = $auth->LanguageID;
	if($langId==1) $lang="el";
	if($langId==2) $lang="en";
	if($langId==3) $lang="de";
	$lang="el";

		$url="https://www.projectbis.com/repo/api/maincategories?root_area_id=".$regionId."&lang=".$lang;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
		));
		
		$result = curl_exec ($curl) or die(curl_error($curl)); 
		$data = json_decode($result);
	//print_r($data);
	for($i=0;$i<sizeof($data->success);$i++){
		//echo $data->success[$i]->id.' - '.$data->success[$i]->name.'<br>';
		$dataRes[$i]['id']=$data->success[$i]->id;
		$dataRes[$i]['name']=$data->success[$i]->name;
	}
	return $dataRes;
	
	/*
		stdClass Object ( [success] => Array ( [0] => stdClass Object ( [id] => 34 [name] => ΠΟΛΙΤΙΣΜΟΣ [is_timeline] => 0 [cssClass] => cultureMenuItem ) [1] => stdClass Object ( [id] => 38 [name] => ΘΡΗΣΚΕΙΑ [is_timeline] => 0 [cssClass] => religionMenuItem ) [2] => stdClass Object ( [id] => 32 [name] => ΕΚΔΗΛΩΣΕΙΣ [is_timeline] => 1 [cssClass] => eventsMenuItem ) [3] => stdClass Object ( [id] => 60 [name] => ΣΥΝΕΔΡΙΑΚΟΣ [is_timeline] => 0 [cssClass] => professionalMenuItem ) [4] => stdClass Object ( [id] => 200 [name] => ΠΛΗΡΟΦΟΡΙΕΣ [is_timeline] => 0 [cssClass] => informationMenuItem ) [5] => stdClass Object ( [id] => 228 [name] => ΔΙΑΣΚΕΔΑΣΗ [is_timeline] => 0 ) [6] => stdClass Object ( [id] => 229 [name] => ΣΗΜΕΙΑ ΠΛΗΡΟΦΟΡΙΩΝ [is_timeline] => 0 ) [7] => stdClass Object ( [id] => 230 [name] => ΥΓΕΙΑ [is_timeline] => 0 ) [8] => stdClass Object ( [id] => 225 [name] => ΕΣΤΙΑΣΗ [is_timeline] => 0 ) [9] => stdClass Object ( [id] => 224 [name] => ΔΙΑΜΟΝΗ [is_timeline] => 0 ) [10] => stdClass Object ( [id] => 227 [name] => ΤΟΥΡΙΣΤΙΚΕΣ ΥΠΗΡΕΣΙΕΣ [is_timeline] => 0 ) [11] => stdClass Object ( [id] => 226 [name] => ΕΜΠΟΡΙΟ [is_timeline] => 0 ) ) )	
	*/
}

function getRegions(){
	//global $db,$auth,$config;
	$langId = $auth->LanguageID;
	if($langId==1) $lang="el";
	if($langId==2) $lang="en";
	if($langId==3) $lang="de";
	$lang="el";
		$url="https://www.projectbis.com/repo/api/mainareas?lang=".$lang;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
		));
		
		$result = curl_exec ($curl) or die(curl_error($curl));
		$data = json_decode($result);
	//print_r($data);
	for($i=0;$i<sizeof($data->success);$i++){
		//echo $data->success[$i]->id.' - '.$data->success[$i]->name.'<br>';
		$dataRes[$i]['id']=$data->success[$i]->id;
		$dataRes[$i]['name']=$data->success[$i]->name;
	}
	
	return $dataRes;
	
	/*
		stdClass Object ( [success] => Array ( [0] => stdClass Object ( [id] => 34 [name] => ΠΟΛΙΤΙΣΜΟΣ [is_timeline] => 0 [cssClass] => cultureMenuItem ) [1] => stdClass Object ( [id] => 38 [name] => ΘΡΗΣΚΕΙΑ [is_timeline] => 0 [cssClass] => religionMenuItem ) [2] => stdClass Object ( [id] => 32 [name] => ΕΚΔΗΛΩΣΕΙΣ [is_timeline] => 1 [cssClass] => eventsMenuItem ) [3] => stdClass Object ( [id] => 60 [name] => ΣΥΝΕΔΡΙΑΚΟΣ [is_timeline] => 0 [cssClass] => professionalMenuItem ) [4] => stdClass Object ( [id] => 200 [name] => ΠΛΗΡΟΦΟΡΙΕΣ [is_timeline] => 0 [cssClass] => informationMenuItem ) [5] => stdClass Object ( [id] => 228 [name] => ΔΙΑΣΚΕΔΑΣΗ [is_timeline] => 0 ) [6] => stdClass Object ( [id] => 229 [name] => ΣΗΜΕΙΑ ΠΛΗΡΟΦΟΡΙΩΝ [is_timeline] => 0 ) [7] => stdClass Object ( [id] => 230 [name] => ΥΓΕΙΑ [is_timeline] => 0 ) [8] => stdClass Object ( [id] => 225 [name] => ΕΣΤΙΑΣΗ [is_timeline] => 0 ) [9] => stdClass Object ( [id] => 224 [name] => ΔΙΑΜΟΝΗ [is_timeline] => 0 ) [10] => stdClass Object ( [id] => 227 [name] => ΤΟΥΡΙΣΤΙΚΕΣ ΥΠΗΡΕΣΙΕΣ [is_timeline] => 0 ) [11] => stdClass Object ( [id] => 226 [name] => ΕΜΠΟΡΙΟ [is_timeline] => 0 ) ) )	
	*/
}

//for($i=1;$i<sizeof($regions);$i++){
//	for($j=1;$j<sizeof($categories);$j++){
//		
//	}
//}
function getPois(array $catId)
{
	/*
		"id": "1_53",
		"hash": "1_a245850258a83c6ff0a11b4cc09ed8acfb406d0e",
		"project_id": 1,
		"name": "\u039d\u03ad\u03c3\u03c4\u03bf\u03c2",
		"google_place": "",
		"ar_json": null,
		"main_image": "https:\/\/www.projectbis.com\/repo\/instance_images\/1\/53\/main_image_1_53.jpg",
		"area_id": 42,
		"summary": "",
		"main_info": "	
	*/
	
	//global $db,$auth,$config;
	$langId = $auth->LanguageID;
	if($langId==1) $lang="el";
	if($langId==2) $lang="en";
	if($langId==3) $lang="de";
	
	$lang="el";
	$k=0;
	for($j=0;$j<sizeof($catId);$j++){
		//$url="https://tourisme.mitos.cityguideplatform.com/api/pois?root_area_id=1&category_id=".$catId[$j]."&lang=".$lang; 
		//$url="https://www.projectbis.com/repo/api/pois?root_area_id=1&category_id=".$catId[$j]."&lang=".$lang;
		$url="https://www.projectbis.com/repo/api/pois?root_area_id=1&lang=".$lang;
		//$url="https://www.projectbis.com/repo/api/pois";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
		));
		
		$result = curl_exec ($curl) or die(curl_error($curl));
//var_dump($result);
		
		$data = json_decode($result);
		for($i=0;$i<sizeof($data->success);$i++){
			$resArray[$k]['id']=$data->success[$i]->id;
			$resArray[$k]['name']=$data->success[$i]->name;
			$resArray[$k]['main_image']=$data->success[$i]->main_image;
			$resArray[$k]['main_info']=$data->success[$i]->main_info;
			$resArray[$k]['category_id']=$catId[$j];
			$details=getPoi($data->success[$i]->id);
			$resArray[$k]['lat']=$details->success->coordinates[0]->latitude;
			$resArray[$k]['lng']=$details->success->coordinates[0]->longitude;
			$k++;
		}
	}
	return $resArray;
}

function getPoi($poiId)
{
	//global $db,$auth,$config;
	//$langId = $auth->LanguageID;
	if($langId==1) $lang="el";
	if($langId==2) $lang="en";
	if($langId==3) $lang="de";
	$lang="el";

	$url="https://www.projectbis.com/repo/api/poi?id=".$poiId."&lang=".$lang; 
	//$url="https://tourisme.mitos.cityguideplatform.com/api/poi?id=".$poiId."&lang=".$lang; 
	//echo $url;
	//exit;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
	));
	
	$result = curl_exec ($curl) or die(curl_error($curl)); 

	$data = json_decode($result);
		/*
		for($i=0;$i<sizeof($data->success);$i++){
			$resArray[$k]['id']=$data->success[$i]->id;
			$resArray[$k]['name']=$data->success[$i]->name;
			$resArray[$k]['main_image']=$data->success[$i]->main_image;
			$resArray[$k]['main_info']=$data->success[$i]->main_info;
			$resArray[$k]['category_id']=$catId[$j];
			$k++;
		}		
		*/
	/*
		echo 'id:',$data->success->id.'<br>';
		echo $data->success->name.'<br>';
		echo $data->success->google_place.'<br>';
		echo $data->success->ar_json.'<br>';
		echo $data->success->main_image.'<br>';
		echo $data->success->main_info.'<br>';
		echo $data->success->portal_main_info.'<br>';
		echo $data->success->logo.'<br>';
		echo $data->success->logo_mobile.'<br>';
		echo $data->success->summary.'<br>';
		echo $data->success->phone.'<br>';
		echo $data->success->fax.'<br>';
		echo $data->success->site.'<br>';
		echo $data->success->email.'<br>';
		echo $data->success->facebook.'<br>';
		echo $data->success->twitter.'<br>';
		echo $data->success->instagram.'<br>';
		echo $data->success->pinterest.'<br>';
		echo $data->success->youtube.'<br>';
		echo $data->success->linkedin.'<br>';
		echo $data->success->googleplus.'<br>';
		echo $data->success->foursquare.'<br>';
		echo $data->success->contact_info.'<br>';
		echo $data->success->start_date.'<br>';
		echo $data->success->end_date.'<br>';
		echo $data->success->tags.'<br>'; //array
		echo $data->success->category_path.'<br>'; //array
		echo $data->success->coordinates[0]->name.'<br>';
		echo $data->success->coordinates[0]->address.'<br>';
		echo $data->success->coordinates[0]->map_icon.'<br>';
		echo $data->success->coordinates[0]->latitude.'<br>';
		echo $data->success->coordinates[0]->longitude.'<br>';
		echo $data->success->rating.'<br>';	
		echo $data->success->votesNum.'<br>';	
		echo $data->success->files.'<br>';//array		
	*/
	return $data;
}

function getVideos($poiId)
{
	global $db,$auth,$config;
	$langId = $auth->LanguageID;
	if($langId==1) $lang=el;
	if($langId==2) $lang=en;
	if($langId==3) $lang=de;
	//$url="https://kosevents.mitos.cityguideplatform.com/api/files?poi_id=".$poiId."&file_type_id=1";
	$url="https://www.projectbis.com/repo/api/files?poi_id=".$poiId."&file_type_id=1";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
	));
	
	$result = curl_exec ($curl) or die(curl_error($curl)); 

	$data = json_decode($result);
	//echo sizeof($data->success->{1});
	//echo 'id:'.$data->success->{1}[0]->url.'<br>';
	return $data;
}

function getImages($poiId)
{
	//echo $poiId;
	//exit;
	global $db,$auth,$config;
	$langId = $auth->LanguageID;
	if($langId==1) $lang=el;
	if($langId==2) $lang=en;
	if($langId==3) $lang=de;
	//$url="https://kosevents.mitos.cityguideplatform.com/api/files?poi_id=".$poiId."&file_type_id=1";
	$url="https://www.projectbis.com/repo/api/files?poi_id=".$poiId."&file_type_id=1";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
	));
	
	$result = curl_exec ($curl) or die(curl_error($curl)); 

	$data = json_decode($result);
	//echo sizeof($data->success->{1});
	//echo 'id:'.$data->success->{1}[0]->url.'<br>';
	return $data;
}

function getRoutes($areaId)
{
	/*
	  {
		"id": "3_48",
		"project_id": 1,
		"category_id": 53,
		"name": "Wildlife Refuge “Patermo – Adas” - Komotini Region",
		"main_image": "https://www.projectbis.com/repo/instance_images/3/48/main_image_3_48.jpg",
		"description": "asd"
	  }	
	*/
	//global $db,$auth,$config;
	//$langId = $auth->LanguageID;
	if($langId==1) $lang="el";
	if($langId==2) $lang="en";
	if($langId==3) $lang="de";
	//$url="https://kosevents.mitos.cityguideplatform.com/api/files?poi_id=".$poiId."&file_type_id=1";
	$url="https://www.projectbis.com/repo/api/routes?root_area_id=".$areaId."";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"X-API-KEY: p72FnSFw7o7fQmZjqYxnf6KHwoGDbheWlLy"
	));
	
	$result = curl_exec ($curl) or die(curl_error($curl)); 
	//var_dump($result);
	$data = json_decode($result);
	//echo sizeof($data->success->{1});
	//echo 'id:'.$data->success->{1}[0]->url.'<br>';
	return $data;
}
?>