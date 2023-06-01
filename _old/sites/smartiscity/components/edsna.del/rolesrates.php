<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");

if($auth->UserType != "Administrator" ) Redirect('index.php');
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Ποσοστά';
$config["navigation"] = 'Ποσοστά';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=roles";
//$command=array();
//$command=explode("&",$_POST["Command"]);

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		//print_r($_POST);
		foreach ($_POST as $key => $value) {
			if(intval($key)>0){
				echo "{$key} => {$value} ";
				//echo "SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key." <br>";
				$dr_e = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key);
				if(isset($dr_e["id"])){
					//update
					$db->sql_query("UPDATE rolesrates set percent=".$value." WHERE id=".$dr_e['id']);
					//echo "UPDATE rolesrate set percent=".$value." WHERE id=".$dr_e['id'].'<br>';
				} else {
					//insert
					$db->sql_query("INSERT INTO rolesrates (parent,role,percent) values ('".$_GET['item']."','".$key."',".$value.") ");
				}
			}
		}
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") {
		if($item != "")
		{
			//Θα πρέπει να γίνει έλεγχος αν μπορει να γίνει διαγραφή
			
			//$checkDelete=$db->sql_query("SELECT * FROM products WHERE category_id=".$item);
			//if ($db->sql_numrows($checkDelete)>0) {
			//	$messages->addMessage(errorRecordsFound);
			//	Redirect($BaseUrl);				
			//}
			echo "DELETE FROM rolesrates WHERE parent=" . $item;
			$db->sql_query("DELETE FROM rolesrate WHERE parent=" . $item);			
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	if(intval($_GET['item']>0)){
		$query="SELECT * FROM roles WHERE 1=1 AND role_id=".$_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["role_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=roles");
		}
	}
?>
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php"><?=homePage?></a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="<?=$BaseUrl?>"><?=$nav?></a>
			</li>
		</ul>
	</div>
		
    <div class="form-horizontal">
		<div class="row-fluid" style="margin-bottom:15px;"> 
			<div class="span12">            
				<div class="box-title">
					<h3><i class="icon-user"></i><?=edit?></h3>
				</div>
			</div>
		</div>
		
		<?	
			//$dr_e = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key);
			//$query = "SELECT * FROM roles t1 LEFT JOIN rolesrates t2 ON t1.role_id=t2.role WHERE 1=1 AND t1.role_id=".$_GET['item'];
			$dr = $db->RowSelectorQuery("SELECT * FROM roles WHERE role_id=".$_GET['item']);
			$dr_p = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$_GET['item']);
			//$result = $db->sql_query($query);
			//$counter = 0;
			//$dr_e = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key);
			//while ($dr = $db->sql_fetchrow($result))
			//{
				
				?>
					<div class="row-fluid" style="margin-bottom:15px;"> 
						<label for="<?=$dr["role_id"]?>" class="control-label col-sm-2"><?=$dr["name"]?></label>
						<div class="col-sm-10">
							<input type="text" name="<?=$dr["role_id"]?>" id="<?=$dr["role_id"]?>" class="form-control" <?=(isset($dr_p["percent"]) ? 'value="'.$dr_p['percent'].'"': "")?>>
						</div>
					</div>
				<?
			//}
			$db->sql_freeresult($result);
		?>
		
		<?	
			//$query = "SELECT * FROM roles t1 LEFT JOIN rolesrates t2 ON t1.role_id=t2.role WHERE 1=1 AND t1.role_id<>".$_GET['item']." ORDER BY t1.priority";
			$query = "SELECT * FROM roles WHERE role_id<>".$_GET['item']." ORDER BY priority";
			$result = $db->sql_query($query);
			$counter = 0;
			while ($dr = $db->sql_fetchrow($result))
			{
				$dr_p = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$dr['role_id']);
				?>
					<div class="row-fluid" style="margin-bottom:15px;"> 
						<label for="<?=$dr["role_id"]?>" class="control-label col-sm-2"><?=$dr["name"]?></label>
						<div class="col-sm-10">
							<input type="text" name="<?=$dr["role_id"]?>" id="<?=$dr["role_id"]?>" class="form-control" <?=(isset($dr_p["percent"]) ? 'value="'.$dr_p['percent'].'"': "")?>>
						</div>
					</div>
				<?
			}
			$db->sql_freeresult($result);
		?>

	</div>
	<div style="margin-top:20px;">
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
		<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=rolesrates&Command=DELETE&item=<?=$_GET['item']?>');"><button id="submitBtn" type="button" class="btn btn-primary"><?=delete?></button></a>
		<a href="index.php?com=roles"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
	</div>


	
<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#name').val();
		//if ( value.length >= 5){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		//} 
		//else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
		//}
	}
</script>

	<?
} else 	{
	Redirect($BaseUrl);
}