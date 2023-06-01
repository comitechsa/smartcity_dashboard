<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") {
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Δεδομένα';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=data";
$command = array();
$command = explode("&", $_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
if (isset($_REQUEST["Command"])) {
	if ($_REQUEST["Command"] == "SAVE") {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
			$PrimaryKeys["data_id"] = intval($_GET["item"]);
			$QuotFields["data_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["is_valid"] = ($_POST["is_valid"] == "on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["our_installation"] = ($_POST["our_installation"] == "on" ? "True" : "False");
		$QuotFields["our_installation"] = true;

		$Collector["municipality_id"] = $_POST["municipality_id"];
		$QuotFields["municipality_id"] = true;

		$Collector["halfperiod_id"] = $_POST["halfperiod_id"];
		$QuotFields["halfperiod_id"] = true;
		
		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;

		$Collector["db"] = $_POST["db"];
		$QuotFields["db"] = true;
	
		$Collector["dc"] = $_POST["dc"];
		$QuotFields["dc"] = true;

		$Collector["dd"] = $_POST["dd"];
		$QuotFields["dd"] = true;

		$Collector["de"] = $_POST["de"];
		$QuotFields["de"] = true;

		$Collector["df"] = $_POST["df"];
		$QuotFields["df"] = true;

		$Collector["dg"] = $_POST["dg"];
		$QuotFields["dg"] = true;

		$Collector["dh"] = $_POST["dh"];
		$QuotFields["dh"] = true;
		
		$Collector["di"] = $_POST["di"];
		$QuotFields["di"] = true;		
		
		$Collector["f2e"] = $_POST["f2e"];
		$QuotFields["f2e"] = true;	
		
		$Collector["f2h"] = $_POST["f2h"];
		$QuotFields["f2h"] = true;			
		
		$Collector["f2i"] = $_POST["f2i"];
		$QuotFields["f2i"] = true;	
		
		$Collector["f2j"] = $_POST["f2j"];
		$QuotFields["f2j"] = true;	
			
		$Collector["f2k"] = $_POST["f2k"];
		$QuotFields["f2k"] = true;	
			
		$Collector["f2l"] = $_POST["f2l"];
		$QuotFields["f2l"] = true;	

		$Collector["f2o"] = $_POST["f2o"];
		$QuotFields["f2o"] = true;	
		
		$Collector["remarks"] = $_POST["remarks"];
		$QuotFields["remarks"] = true;	

		$db->ExecuteUpdater("data", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		if ($item != "") {
			//$filter = ($auth->UserType != "Administrator" ? ' AND customer_id=' . $auth->UserRow['customer_id'] : '');
			//$checkDelete = $db->sql_query("SELECT * FROM vehicles WHERE 1=1 " . $filter . " AND vehicle_id=" . $_GET['item']);
			//if ($db->sql_numrows($checkDelete) == 0) {
			//	$messages->addMessage("NOT FOUND.");
			//	Redirect($BaseUrl);
			//}
			$db->sql_query("DELETE FROM data WHERE data_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"]) ) {
		//$filter = ($auth->UserType != "Administrator" ? ' AND customer_id=' . $auth->UserRow['customer_id'] : '');
		$query = "SELECT * FROM data WHERE 1=1 " . $filter . " AND data_id=" . $_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (intval($dr_e['data_id']) == 0 && intval($_GET['item'])>0) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect($BaseUrl);
		}
	?>
	<!--
	<div id="modal-1" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Modal header</h3>
		</div>
		<div class="modal-body">
			<p>One fine body…</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary" data-dismiss="modal">Save changes</button>
		</div>
	</div>
	-->
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php">Αρχική</a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="<?= $BaseUrl ?>"><?= $nav ?></a>
			</li>
		</ul>
	</div>
	<!--<a href="#modal-1" role="button" class="btn" data-toggle="modal">Basic modal</a>-->
	<div class="row-fluid">
		<div class="span12">
			<div class="box-title">
				<h3><i class="icon-user"></i>Επεξεργασία</h3>
			</div>
			<div class="box-content nopadding">
				<div class="check-line">
					<label class="inline" for="is_valid"><?= active ?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?= ((isset($dr_e["is_valid"]) && $dr_e["is_valid"] == 'True') ? 'checked' : '') ?> />
					</div>
				</div>
				<div class="check-line">
					<label class="inline" for="our_installation">Εγκατάσταση ΕΔΣΝΑ</label>
					<div class="controls">
						<input id="our_installation" name="our_installation" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?= ((isset($dr_e["our_installation"]) && $dr_e["our_installation"] == 'True') ? 'checked' : '') ?> />
					</div>
				</div>				
				
				<div class="control-group">
					<div class="controls">
						<label for="period_id" class="control-label">Περίοδος</label>
						<select name="period_id" id="period_id" disabled>
						<? 
							$resultDefaultPeriod = $db->sql_query("SELECT * FROM periods WHERE 1=1 ORDER BY year DESC ");
							while ($drDefaultPeriod = $db->sql_fetchrow($resultDefaultPeriod)) {
								echo '<option value="'.$drDefaultPeriod['period_id'].'" '.($drDefaultPeriod['period_id']==$_SESSION['defaultperiod_id']?' selected':'').'>'.$drDefaultPeriod['year'].' - '.$drDefaultPeriod['description'].'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="halfperiod_id" class="control-label">Εξάμηνο</label>
						<select name="halfperiod_id" id="halfperiod_id">
							<option value="1">1ο Εξάμηνο</option>
							<option value="2">2ο Εξάμηνο</option>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<label for="textfield" class="control-label">Δήμος</label>
					<div class="controls">
						<select name="municipality_id" id="municipality_id" class='select2-me input-xlarge' required>
						<option value="">Επιλογή δήμου</option>'
						<?
							$resultMunicipalities = $db->sql_query("SELECT * FROM municipalities WHERE 1=1 ".$filter." ORDER BY name ");
							while ($drMunicipalities = $db->sql_fetchrow($resultMunicipalities)){
								echo '<option value="'.$drMunicipalities['municipality_id'].'"'.($drMunicipalities['municipality_id']==$dr_e['municipality_id']?' selected':'').'>'.$drMunicipalities['name'].'</option>';
							}
						?>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="description" class="control-label">Περιγραφή</label>
						<input type="text" name="description" id="description" class="input-xxlarge" <?= (isset($dr_e["description"]) ? 'value="' . $dr_e['description'] . '"' : "") ?>>
					</div>
				</div>
				
		
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-bordered">
				<div class="box-title">
					<h3><i class="icon-list"></i> Δεδομένα</h3>
				</div>
				<div class="box-content nopadding">
					<div class='form-horizontal form-column form-bordered'>
						<div class="span6">
							<div class="control-group">
								<label for="db" class="control-label" style="width:60%;">ΠΡΟΣ ΧΥΤΑ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="db" id="db" class="input-small" <?= (isset($dr_e["db"]) ? 'value="' . $dr_e['db'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="dc" class="control-label" style="width:60%;">ΠΡΟΣ ΣΜΑ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="dc" id="dc" class="input-small" <?= (isset($dr_e["dc"]) ? 'value="' . $dr_e['dc'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="dd" class="control-label" style="width:60%;">ΠΡΟΣ ΕΜΑ ΑΠΟΡΡΙΜΜΑΤΑ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="dd" id="dd" class="input-small" <?= (isset($dr_e["dd"]) ? 'value="' . $dr_e['dd'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="de" class="control-label" style="width:60%;">ΠΡΟΣ ΕΜΑ ΠΡΑΣΙΝΑ </label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="de" id="de" class="input-small" <?= (isset($dr_e["de"]) ? 'value="' . $dr_e['de'] . '"' : "") ?>>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label for="df" class="control-label" style="width:60%;">ΠΡΟΣ ΕΜΑ ΟΡΓΑΝΙΚΑ ΠΡΟΔΙΑΛΕΓΜΕΝΑ </label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="df" id="df" class="input-small" <?= (isset($dr_e["df"]) ? 'value="' . $dr_e['df'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="dg" class="control-label" style="width:60%;">ΠΡΟΣ ΕΜΑ ΑΝΑΚΥΚΛΩΣΗ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="dg" id="dg" class="input-small" <?= (isset($dr_e["dg"]) ? 'value="' . $dr_e['dg'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="dh" class="control-label" style="width:60%;">ΠΡΟΣ ΕΜΑ ΛΑΪΚΕΣ ΑΓΟΡΕΣ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="dh" id="dh" class="input-small" <?= (isset($dr_e["dh"]) ? 'value="' . $dr_e['dh'] . '"' : "") ?>>
								</div>
							</div>
							<div class="control-group">
								<label for="di" class="control-label" style="width:60%;">ΠΡΟΣ ΧΥΤΑ ΑΠΟ ΚΔΑΥ</label>
								<div class="controls" style="margin-left:280px;">
									<input type="number" name="di" id="di" class="input-small" <?= (isset($dr_e["di"]) ? 'value="' . $dr_e['di'] . '"' : "") ?>>
								</div>
							</div>
						</div>
						<hr>
						<div class="row-fluid">
							<div class="span12">
								<div class="box box-bordered">
									<div class="box-title">
										<h3><i class="icon-th-list"></i> Άλλα στοιχεία</h3>
									</div>
									<div class="box-content nopadding">
										<div class='form-horizontal form-bordered'>
											<div class="control-group">
												<label for="f2e" style="width:50%;" class="control-label">Απόβλητα συσκευασιών που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και  ανακύκλωση (ΚΔΑΥ)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2e" id="f2e" class="input-small" <?= (isset($dr_e["f2e"]) ? 'value="' . $dr_e['f2e'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2h" style="width:50%;" class="control-label">Υπόλειμμα ΜΕΒΑ προς διάθεση σε ΧΥΤΑ</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2h" id="f2h" class="input-small" <?= (isset($dr_e["f2h"]) ? 'value="' . $dr_e['f2h'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2i" style="width:50%;" class="control-label">Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (ΣΕΔ)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2i" id="f2i" class="input-small" <?= (isset($dr_e["f2i"]) ? 'value="' . $dr_e['f2i'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2j" style="width:50%;" class="control-label">Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (ΣΕΔ)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2j" id="f2j" class="input-small" <?= (isset($dr_e["f2j"]) ? 'value="' . $dr_e['f2j'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2k" style="width:50%;" class="control-label">Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (εκτός ΣΕΔ)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2k" id="f2k" class="input-small" <?= (isset($dr_e["f2k"]) ? 'value="' . $dr_e['f2k'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2l" style="width:50%;" class="control-label">Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (εκτός ΣΕΔ)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2l" id="f2l" class="input-small" <?= (isset($dr_e["f2l"]) ? 'value="' . $dr_e['f2l'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="f2o" style="width:50%;" class="control-label">Ποσότητες άλλων αποβλήτων που μεταφορτώνονται μέσω ΣΜΑ (Τ4)</label>
												<div class="controls" style="margin-left:50%;">
													<input type="number" name="f2o" id="f2o" class="input-small" <?= (isset($dr_e["f2o"]) ? 'value="' . $dr_e['f2o'] . '"' : "") ?>>
												</div>
											</div>
											<div class="control-group">
												<label for="remarks" name="remarks" style="width:40%;" class="control-label">Σημειώσεις</label>
												<div class="controls" style="margin-left:50%;">
													<textarea name="textarea" id="textarea" rows="5" class="input-block-level">  <?= (isset($dr_e["remarks"]) ?  $dr_e['remarks']  : "") ?></textarea>
												</div>
											</div>
											<div class="form-actions">
												<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
												<a href="index.php?com=data"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--
						<div class="span6"></div>
						<div class="span6">
							<div class="control-group">
								<label for="totalA" class="control-label">Σύνολο</label>
								<div class="controls" style="min-height:70px;">
									<input type="text" name="totalA" id="totalA" class="input-xlarge" value="">
								</div>
							</div>
						</div>
						-->
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?
	
	// Πορτοκαλί πίνακας C
	$sh1BCRow=$db->RowSelectorQuery("SELECT (SUM( db ) + SUM( dc )) AS val FROM data WHERE municipality_id=1");
	$sh1BC=$sh1BCRow['val'];
	// Γαλάζιος πίνακας C
	$sh1AC=$sh1BC;
	
	//Πορτοκαλί πίνακας D
	$sh1BDRow=$db->RowSelectorQuery("SELECT (SUM( dd )) AS val FROM data WHERE municipality_id=1");
	$sh1BD=$sh1BDRow['val'];		
	//Γαλάζιος Πίνακας D
	$sh1AD=$sh1BD;
	
	//Πορτοκαλί πίνακας E
	$sh1BERow=$db->RowSelectorQuery("SELECT (SUM( f2e )) AS val FROM data WHERE municipality_id=1");
	$sh1BE=$sh1BERow['val'];	
	//Γαλάζιος Πίνακας D
	$sh1AE=0;
	
	//Πορτοκαλί πίνακας F
	$sh1BFRow=$db->RowSelectorQuery("SELECT (SUM( di )) AS val FROM data WHERE municipality_id=1");
	$sh1BF=$sh1BFRow['val'];
	//Γαλάζιος Πίνακας F
	$sh1AF=$sh1BF;
	
	//Πορτοκαλί πίνακας G
	$sh1BGRow=$db->RowSelectorQuery("SELECT (SUM(de)+SUM(df)+SUM(dh)) AS val FROM data WHERE municipality_id=1");
	$sh1BG=$sh1BGRow['val'];
	//Γαλάζιος Πίνακας G
	$sh1AG=$sh1BG;

	//Πορτοκαλί πίνακας H
	$sh1BHRow=$db->RowSelectorQuery("SELECT (SUM( f2h )) AS val FROM data WHERE municipality_id=1");
	$sh1BH=$sh1BHRow['val'];
	//Γαλάζιος Πίνακας H
	$sh1AH=$sh1BH;

	//Πορτοκαλί πίνακας I
	$sh1BIRow=$db->RowSelectorQuery("SELECT (SUM( f2i )) AS val FROM data WHERE municipality_id=1");
	$sh1BI=$sh1BIRow['val'];
	//Γαλάζιος Πίνακας I
	$sh1AI=$sh1BI;

	//Πορτοκαλί πίνακας J
	$sh1BJRow=$db->RowSelectorQuery("SELECT (SUM( f2j )) AS val FROM data WHERE municipality_id=1");
	$sh1BJ=$sh1BJRow['val'];
	//Γαλάζιος Πίνακας J
	$sh1AJ=$sh1BJ;

	//Πορτοκαλί πίνακας K
	$sh1BKRow=$db->RowSelectorQuery("SELECT (SUM( f2k )) AS val FROM data WHERE municipality_id=1");
	$sh1BK=$sh1BKRow['val'];
	//Γαλάζιος Πίνακας K
	$sh1AK=$sh1BK;

	//Πορτοκαλί πίνακας L
	$sh1BLRow=$db->RowSelectorQuery("SELECT (SUM( f2l )) AS val FROM data WHERE municipality_id=1");
	$sh1BL=$sh1BLRow['val'];
	//Γαλάζιος Πίνακας L
	$sh1AL=$sh1BL;

	//Πορτοκαλί πίνακας M
	$sh1BM=($sh1BC+$sh1BD+$sh1BE+$sh1BF+$sh1BG+$sh1BH+$sh1BI+$sh1BJ+$sh1BK+$sh1BL);
	//Γαλάζιος Πίνακας M
	$sh1AM=($sh1AC+$sh1AD+$sh1AE+$sh1AF+$sh1AG+$sh1AH+$sh1AI+$sh1AJ+$sh1AK+$sh1AL);;
	
	//Γαλάζιος Πίνακας N
	$sh1ANRow=$db->RowSelectorQuery("SELECT SUM( dc ) AS val FROM data WHERE municipality_id=1");
	$sh1AN=$sh1ANRow['val'];	
	
	//Γαλάζιος Πίνακας O
	$sh1AORow=$db->RowSelectorQuery("SELECT SUM( f2o ) AS val FROM data WHERE municipality_id=1");
	$sh1AO=$sh1AORow['val'];	

	
	
	echo '<br><br>';
	echo '------------------------------------------------------------------------------------------';
	echo 'Γαλάζιος πίνακας φύλλο 1';
	echo '------------------------------------------------------------------------------------------';
	
	echo 'C:Σύμμεικτα ΑΣΑ προς διάθεση σε ΧΥΤ (Τ1α)  : '.$sh1AC.'<br>';
	echo 'D:Σύμμεικτα ΑΣΑ προς ΜΕΑ (Τ2) : '.$sh1AD.'<br>';
	echo 'E:Απόβλητα συσκευασιών που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και  ανακύκλωση (ΚΔΑΥ) (Τ3γ) : '.$sh1AE.'<br>';
	echo 'F:Υπόλειμμα ΚΔΑΥ προς διαθεση σε ΧΥΤΑ (Τ1β) : '.$sh1AF.'<br>';
	echo 'G:Χωριστά συλλεγέντα βιοαπόβλητα που ανακυκλώνονται (Τ3α) : '.$sh1AG.'<br>';
	echo 'H: Υπόλειμμα ΜΕΒΑ προς διάθεση σε ΧΥΤΑ (Τ1β) : '.$sh1AH.'<br>';
	echo 'I:Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (ΣΕΔ) (Τ3γ) : '.$sh1AI.'<br>';
	echo 'J: Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (ΣΕΔ) (Τ1β) : '.$sh1AJ.'<br>';
	echo 'K:Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (εκτός ΣΕΔ) (Τ3β) : '.$sh1AK.'<br>';
	echo 'L:Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (εκτός ΣΕΔ) (Τ1β): '.$sh1AL.'<br>';
	echo 'M:Συνολικά διαχειριζόμενα απόβλητα των ΟΤΑ από ΦΟΔΣΑ : <b>'.$sh1AM.'</b><br>';
	echo 'N:Ποσότητες σύμμεικτων ΑΣΑ που μεταφορτώνονται μέσω ΣΜΑ (Τ4) : '.$sh1AN.'<br>';
	echo 'O:Ποσότητες άλλων αποβλήτων που μεταφορτώνονται μέσω ΣΜΑ (Τ4): '.$sh1AO.'<br>';
	
	echo '<br><br>';
	echo '------------------------------------------------------------------------------------------';
	echo 'Πορτοκαλί πίνακας φύλλο 1';
	echo '------------------------------------------------------------------------------------------';
	
	echo 'C:Σύμμεικτα ΑΣΑ προς διάθεση : '.$sh1BC.'<br>';
	echo 'D:Σύμμεικτα ΑΣΑ προς ΜΕΑ : '.$sh1BD.'<br>';
	echo 'E:Απόβλητα συσκευασιών που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και  ανακύκλωση (ΚΔΑΥ) : '.$sh1BE.'<br>';
	echo 'F:Υπόλειμμα ΚΔΑΥ προς διαθεση σε ΧΥΤΑ : '.$sh1BF.'<br>';
	echo 'G:Χωριστά συλλεγέντα βιοαπόβλητα που ανακυκλώνονται (Τ3α) : '.$sh1BG.'<br>';
	echo 'H:Υπόλειμμα ΜΕΒΑ προς διάθεση σε ΧΥΤΑ : '.$sh1BH.'<br>';
	echo 'I:Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (ΣΕΔ) : '.$sh1BI.'<br>';
	echo 'J:Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (ΣΕΔ) : '.$sh1BJ.'<br>';
	echo 'K:Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (εκτός ΣΕΔ) : '.$sh1BK.'<br>';
	echo 'L:Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (εκτός ΣΕΔ) : '.$sh1BL.'<br>';
	echo 'M:Συνολικά διαχειριζόμενα απόβλητα ανά ΟΤΑ : <b>'.$sh1BM.'</b><br>';
	
	echo '<br><br>';
	echo '------------------------------------------------------------------------------------------';
	echo 'Φύλλο 2 - Επιδόσεις';
	echo '------------------------------------------------------------------------------------------<br>';

	//Υπολογισμός κλίμακας Α
	$sh2C = number_format($sh1BM==0?0:($sh1BE/$sh1BM)*100,2,".",",");
	$resultDiscountA = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.year=2019 AND t2.section_id=1 ORDER BY performance ASC');
	$discountA=0;
	while ($drDiscountA = $db->sql_fetchrow($resultDiscountA)) {
		if($sh2C>$drDiscountA['performance']) $discountA=$drDiscountA['discount'];
	}
	$sh2D = number_format($discountA,2,".",",");

	
	//Υπολογισμός κλίμακας Β
	$sh2E = number_format($sh1BM==0?0:($sh1BG/$sh1BM)*100,2,".",",");
	$resultDiscountB = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.year=2019 AND t2.section_id=2 ORDER BY performance ASC');
	$discountB=0;
	while ($drDiscountB = $db->sql_fetchrow($resultDiscountB)) {
		if($sh2E>$drDiscountB['performance']) $discountB=$drDiscountB['discount'];
	}
	$sh2F = number_format($discountB,2,".",",");
	

	//Υπολογισμός κλίμακας Γ
	$sh2G = number_format($sh1BM==0?0:(($sh1BE+$sh1BG+$sh1BI+$sh1BK)/$sh1BM)*100,2,".",",");
	$resultDiscountC = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.year=2019 AND t2.section_id=3 ORDER BY performance ASC');
	$discountC=0;
	while ($drDiscountC = $db->sql_fetchrow($resultDiscountB)) {
		if($sh2E>$drDiscountC['performance']) $discountC=$drDiscountC['discount'];
	}
	$sh2H = number_format($discountC,2,".",",");
	
	//Τελικό ποσοστό μείωσης
	$sh2I=number_format(max($discountA,$discountB,$discountC),2,".",",");
	echo 'α. Προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση αποβλήτων συσκευασιών σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού: '.$sh2C.'<br>';
	echo 'Ποσοστό μείωσης α.: '.$sh2D.'<br>';
	echo 'β. Χωριστή συλλογή και περαιτέρω ανακύκλωση βιοαποβλήτων σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού: '.$sh2E.'<br>';
	echo ' Ποσοστό μείωσης β.: '.$sh2F .'<br>';
	echo 'γ. Προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση των αστικών αποβλήτων σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού: '.$sh2G.'<br>';
	echo 'Ποσοστό μείωσης γ.: '.$sh2H .'<br>';
	echo 'Τελικό ποσοστό μείωσης γ.: '.$sh2H .'<br>';
	
	//echo '<table><tr><th>Παράμετρος</th><th>Τιμή<th></tr>';
	//echo '<tr><td>α. Προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση αποβλήτων συσκευασιών σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού</td><td></td></tr>';
	//echo '<tr><td>xxx</td><td></td></tr>';
	//echo '</table>'
	echo '<br><br>';
	echo '------------------------------------------------------------------------------------------';
	echo 'Φύλλο 3 - ETA';
	echo '------------------------------------------------------------------------------------------<br>';
	
	$multiplier1=1;
	$multiplier2=1;
	$multiplier3=1;
	$multiplier4=0;
	$multiplier5=0.3;
	$multiplier6=0.3;
	$multiplier7=0.3;
	$multiplier8=0.3;
	$sh3B=$sh2H;
	$sh3C=$sh1AC;
	$sh3D=$sh3C*$multiplier1;
	$sh3E=$sh1AF+$sh1AH+$sh1AJ+$sh1AL;
	$sh3F=$sh3E*$multiplier2;
	$sh3G=$sh1AD;
	$sh3H=$sh1AD*$multiplier3;
	$sh3I=$sh1AG;
	$sh3J=$sh3I*$multiplier4;
	$sh3K=$sh1AK;
	$sh3L=$sh3K*$multiplier5;
	$sh3M=$sh1AE+$sh1AI;
	$sh3N=$sh3K*$multiplier6;
	$sh3O=$sh1AN;
	$sh3P=$sh3O*$multiplier7;
	$sh3Q=$sh1AO;
	$sh3R=$sh3Q*$multiplier8;
	$sh3S=(1-$sh3B)*($sh3D+$sh3F+$sh3H+$sh3J+$sh3L+$sh3N+$sh3P+$sh3R);
	$sh3T=$sh3S*59.15; //Πρέπει να το υπολογίσω με τα αθροίσματα
	
	echo 'Μ ΟΤΑ: '.$sh3B.'<br>';
	echo '1α: Εργασίες διάθεσης σύμμεικτων αστικών αποβλήτων'.'<br>';
	echo 'Π1α: '.$sh3C.'<br>';
	echo 'Π1α x Σ1α: '.$sh3D.'<br>';
	echo '1β:Εργασίες διάθεσης υπολείμματος από μονάδες επεξεργασίας χωριστά συλλεγέντων αποβλήτων (ΜΕΒΑ, ΚΔΑΥ)'.'<br>';
	echo 'Π1β: '.$sh3E.'<br>';
	echo 'Π1β x Σ1β: '.$sh3F.'<br>';
	echo '2: Εργασίες ανάκτησης σύμμεικτων αστικών αποβλήτων σε μονάδες μηχανικής, βιολογικής επεξεργασίας αποβλήτων (MEA)-  (συμπεριλαμβάνεται και η διάθεση των υπολειμμάτων)	'.'<br>';
	echo 'Π2: '.$sh3G.'<br>';
	echo 'Π2 x Σ2: '.$sh3H.'<br>';
	echo '3α: Εργασίες ανάκτησης χωριστά συλλεγέντων βιοαποβλήτων (συμπεριλαμβάνεται και η χωριστή επεξεργασία των βιοαποβλήτων εντός MEA)'.'<br>';
	echo 'Π3α: '.$sh3I.'<br>';
	echo 'Π3α x Σ3α: '.$sh3J.'<br>';
	echo '3β: Εργασίες ανάκτησης υλικών εκτός ΣΕΔ'.'<br>';
	echo 'Π3β: '.$sh3K.'<br>';
	echo 'Π3β x Σ3β: '.$sh3L.'<br>';
	echo '3γ: Εργασίες ανάκτησης υλικών ΣΕΔ'.'<br>';
	echo 'Π3γ: '.$sh3M.'<br>';
	echo 'Π3γ x Σ3γ: '.$sh3M.'<br>';
	echo '4: Μεταφόρτωση σύμμεικτων αστικών αποβλήτων'.'<br>';
	echo 'Π4: '.$sh3O.'<br>';
	echo 'Π4 x Σ4: '.$sh3P.'<br>';
	echo '4: Μεταφόρτωση χωριστά συλλεγέντων ρευμάτων (ανακυκλώσιμα, βιοαπόβλητα ή άλλα)'.'<br>';
	echo 'Π4:'.$sh3Q.'<br>';
	echo 'Π4 x Σ4: '.$sh3R.'<br>';
	echo '(100%-Μ ΟΤΑ) x Σ(Π x Σ): '.$sh3S.'<br>';
	echo 'Ποσό: '.number_format($sh3T,2,",",".");
	
	echo '<br><br>';
	echo '------------------------------------------------------------------------------------------';
	echo 'Φύλλο 4 - Εισφορές';
	echo '------------------------------------------------------------------------------------------<br>';
	$sh4B=$sh2I;
	$sh4C=(1-$sh4B)*59.15*$multiplier1;
	$sh4D=$sh4C*$sh1AC;
	$sh4E=(1-$sh4B)*59.15*$multiplier2;
	$sh4F=$sh4E*($sh1AF+$sh1AH+$sh1AJ+$sh1AL);
	$sh4G=(1-$sh4B)*59.15*$multiplier3;
	$sh4H=$sh4G*$sh1AD;
	$sh4I=(1-$sh4B)*59.15*$multiplier4;
	$sh4J=$sh4I*$sh1AG;
	$sh4K=(1-$sh4B)*59.15*$multiplier5;
	$sh4L=$sh4K*$sh1AK;
	$sh4M=(1-$sh4B)*59.15*$multiplier6;
	$sh4N=$sh4M*($sh1AE+$sh1AI);
	$sh4O=(1-$sh4B)*59.15*$multiplier7;
	$sh4P=$sh4O*$sh1AN;
	$sh4Q=(1-$sh4B)*59.15*$multiplier8;
	$sh4R=$sh4Q*$sh1AO;
	$sh4S=$sh4D+$sh4F+$sh4H+$sh4J+$sh4L+$sh4N+$sh4P+$sh4R;
	$sh4T=($sh1AM==0?0:$sh4S/$sh1AM);
	
	
	echo 'Μ ΟΤΑ: '.$sh4B.'<br>';
	echo '1α: Εργασίες διάθεσης σύμμεικτων αστικών αποβλήτων'.'<br>';
	echo 'Τιμή μονάδος 1α (μετά τη μείωση): '.$sh4C;
	echo 'Εισφορές 1α: '.$sh4D.'<br>';
	echo '1β:Εργασίες διάθεσης υπολείμματος από μονάδες επεξεργασίας χωριστά συλλεγέντων αποβλήτων (ΜΕΒΑ, ΚΔΑΥ)'.'<br>';
	echo 'Τιμή μονάδος 1β (μετά τη μείωση): '.$sh4E.'<br>';
	echo 'Εισφορές 1β: '.$sh4F.'<br>';
	echo '2: Εργασίες ανάκτησης σύμμεικτων αστικών αποβλήτων σε μονάδες μηχανικής, βιολογικής επεξεργασίας αποβλήτων (MEA)-  (συμπεριλαμβάνεται και η διάθεση των υπολειμμάτων)'.'<br>';
	echo 'Τιμή μονάδος 2 (μετά τη μείωση): '.$sh4G.'<br>';
	echo 'Εισφορές 2: '.$sh4H.'<br>';
	echo '3α: Εργασίες ανάκτησης χωριστά συλλεγέντων βιοαποβλήτων (συμπεριλαμβάνεται και η χωριστή επεξεργασία των βιοαποβλήτων εντός MEA)'.'<br>';
	echo 'Τιμή μονάδος 3α (μετά τη μείωση): '.$sh4I.'<br>';
	echo 'Εισφορές  3α: '.$sh4J.'<br>';
	echo '3β: Εργασίες ανάκτησης υλικών εκτός ΣΕΔ'.'<br>';
	echo 'Τιμή μονάδος 3β (μετά τη μείωση): '.$sh4K.'<br>';
	echo 'Εισφορές 3β: '.$sh4L.'<br>';
	echo '3γ: Εργασίες ανάκτησης υλικών ΣΕΔ'.'<br>';
	echo 'Τιμή μονάδος 3γ (μετά τη μείωση)'.$sh4M.'<br>';
	echo 'Εισφορές 3γ: '.$sh4N.'<br>';
	echo '4: Μεταφόρτωση σύμμεικτων αστικών αποβλήτων'.'<br>';
	echo 'Τιμή μονάδος 4 (μετά τη μείωση): '.$sh4O.'<br>';
	echo 'Εισφορές 4: '.$sh4P.'<br>';
	echo '4: Μεταφόρτωση χωριστά συλλεγέντων ρευμάτων (ανακυκλώσιμα, βιοαπόβλητα ή άλλα)'.'<br>';
	echo 'Τιμή μονάδος 4 (μετά τη μείωση): '.$sh4Q.'<br>';
	echo 'Εισφορές 4: '.$sh4R.'<br>';
	echo 'Συνολικές Εισφορές OTA: '.$sh4S.'<br>';
	echo 'Συνολικές Εισφορές OTA ανά τόνο αποβλήτων: '.$sh4T.'<br>';
	
	
	?>

	


	
	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var valueCustomer = $('#municipality_id').val();
			var valueDescription = $('#description').val();
			if (valueCustomer.length >0 && valueDescription.length>=2) {
				cm('SAVE', 1, 0, ''); //document.getElementById("submitBtn").disabled = false;
			} //else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
			//}
		}
	</script>
<?
} else if(!isset($_GET["xls"]) ){
	?>
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php">Αρχική</a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="<?= $BaseUrl ?>"><?= $nav ?></a>
			</li>
		</ul>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3><i class="icon-table"></i><?= $nav ?></h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Ενεργό</th>
								<th>Δήμος</th>
								<th>Περιγραφή</th>
								<th>Ημ/νία εισαγωγής</th>
								<th>Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								//$filter = ($auth->UserType != "Administrator" ? ' AND customer_id=' . $auth->UserRow['customer_id'] : '');
								$query = "SELECT t1.*,t2.name AS municipalityName FROM data t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1  " . $filter . " ORDER BY date_insert DESC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["data_id"] ?></td>
									<td><?= $dr["is_valid"] ?></td>
									<td><?= $dr["municipalityName"] ?></td>
									<td><?= $dr["description"] ?></td>
									<td><?= $dr["date_insert"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=data&Command=edit&item=<?= $dr["data_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm ?>','index.php?com=data&Command=DELETE&item=<?= $dr['data_id'] ?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
									</td>
								</tr>
							<?
								}
								$db->sql_freeresult($result);
								?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<? } else if(isset($_GET["xls"]) ){
			//Υπολογισμός περιβαλλοντικής εισφοράς
			
			//Get period Row
			$periodRow=$db->RowSelectorQuery("SELECT * FROM periods WHERE period_id=".$_SESSION['defaultperiod_id']);
			//Να πάρω τους πολλαπλασιαστές από τη βάση
			$multiplier1=$periodRow['multiplier1'];
			$multiplier2=$periodRow['multiplier2'];
			$multiplier3=$periodRow['multiplier3'];
			$multiplier4=$periodRow['multiplier4'];
			$multiplier5=$periodRow['multiplier5'];
			$multiplier6=$periodRow['multiplier6'];
			$multiplier7=$periodRow['multiplier7'];
			$multiplier8=$periodRow['multiplier8'];
			$installationCalcDate=$periodRow['installation_status_date'];
			$envContribution=$periodRow['env_contribution'];
			$periodCost=$periodRow['cost'];
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$totalCapacityRow=$db->RowSelectorQuery("SELECT sum(capacity) AS totalCapacity FROM installations WHERE is_valid='True' AND discount='True'");
			$totalCapacity=$totalCapacityRow['totalCapacity'];
			$calculationQuery="SELECT t1.installation_id,max(t1.applicationdate),t3.installationstatus_id,t3.discount,t2.capacity FROM installationcondition t1 INNER JOIN installations t2 ON t1.installation_id=t2.installation_id INNER JOIN installationstatus t3 ON t1.installationstatus_id=t3.installationstatus_id WHERE t2.discount='True' AND t1.applicationdate<='".$installationCalcDate."' GROUP BY t1.installation_id";
			
			$resultCalculation = $db->sql_query($calculationQuery);
			$mOL=0;
			while ($drCalculation = $db->sql_fetchrow($resultCalculation)){
				$mOL=$mOL+($drCalculation['capacity']*$drCalculation['discount']/100);
			}
			
			$mOL=($mOL/$totalCapacity);
			
			$contributionPerTon=($envContribution-($envContribution*$mOL));
			
			/*
				echo(round(1.5,0,PHP_ROUND_HALF_UP) . "<br>");
				echo(round(-1.5,0,PHP_ROUND_HALF_DOWN) . "<br>");
			*/
			//exit;
			$counter=0;
			$result = $db->sql_query("SELECT * FROM municipalities WHERE is_valid='True' ORDER BY name ASC");

			while ($dr = $db->sql_fetchrow($result)) {
				// Πορτοκαλί πίνακας C
				$sh1BCRow=$db->RowSelectorQuery("SELECT (SUM( db ) + SUM( dc )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BC=($sh1BCRow['val']);
				// Γαλάζιος πίνακας C
				$sh1ACRow=$db->RowSelectorQuery("SELECT (SUM( db ) + SUM( dc )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AC=($sh1ACRow['val']);
				
				//Πορτοκαλί πίνακας D
				$sh1BDRow=$db->RowSelectorQuery("SELECT (SUM( dd )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BD=($sh1BDRow['val']);
				//Γαλάζιος Πίνακας D
				$sh1ADRow=$db->RowSelectorQuery("SELECT (SUM( dd )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AD=($sh1ADRow['val']);
				
				//Πορτοκαλί πίνακας E
				$sh1BERow=$db->RowSelectorQuery("SELECT (SUM( f2e )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BE=($sh1BERow['val']);
				//Γαλάζιος Πίνακας E
				$sh1AERow=$db->RowSelectorQuery("SELECT (SUM( f2e )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AE=($sh1AERow['val']);
				$sh1AE=round(0,2); //SOS
				
				
				//Πορτοκαλί πίνακας F
				$sh1BFRow=$db->RowSelectorQuery("SELECT (SUM( di )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BF=($sh1BFRow['val']);
				//Γαλάζιος Πίνακας F
				$sh1AFRow=$db->RowSelectorQuery("SELECT (SUM( di )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AF=($sh1AFRow['val']);

				//Πορτοκαλί πίνακας G
				$sh1BGRow=$db->RowSelectorQuery("SELECT (SUM(de)+SUM(df)+SUM(dh)) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BG=($sh1BGRow['val']);
				//Γαλάζιος Πίνακας G
				$sh1AGRow=$db->RowSelectorQuery("SELECT (SUM(de)+SUM(df)+SUM(dh)) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AG=($sh1AGRow['val']);

				//Πορτοκαλί πίνακας H
				$sh1BHRow=$db->RowSelectorQuery("SELECT (SUM( f2h )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BH=($sh1BHRow['val']);
				//Γαλάζιος Πίνακας H
				$sh1AHRow=$db->RowSelectorQuery("SELECT (SUM( f2h )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AH=($sh1AHRow['val']);

				//Πορτοκαλί πίνακας I
				$sh1BIRow=$db->RowSelectorQuery("SELECT (SUM( f2i )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BI=($sh1BIRow['val']);
				//Γαλάζιος Πίνακας I
				$sh1AIRow=$db->RowSelectorQuery("SELECT (SUM( f2i )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AI=($sh1AIRow['val']);

				//Πορτοκαλί πίνακας J
				$sh1BJRow=$db->RowSelectorQuery("SELECT (SUM( f2j )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BJ=($sh1BJRow['val']);
				//Γαλάζιος Πίνακας J
				$sh1AJRow==$db->RowSelectorQuery("SELECT (SUM( f2j )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AJ=($sh1AJRow['val']);

				//Πορτοκαλί πίνακας K
				$sh1BKRow=$db->RowSelectorQuery("SELECT (SUM( f2k )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BK=($sh1BKRow['val']);
				//Γαλάζιος Πίνακας K
				$sh1AKRow=$db->RowSelectorQuery("SELECT (SUM( f2k )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AK=($sh1AKRow['val']);

				//Πορτοκαλί πίνακας L
				$sh1BLRow=$db->RowSelectorQuery("SELECT (SUM( f2l )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1BL=($sh1BLRow['val']);
				//Γαλάζιος Πίνακας L
				$sh1ALRow==$db->RowSelectorQuery("SELECT (SUM( f2l )) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']." AND our_installation='True'");
				$sh1AL=($sh1ALRow['val']);

				//Πορτοκαλί πίνακας M
				$sh1BM=(($sh1BC+$sh1BD+$sh1BE+$sh1BF+$sh1BG+$sh1BH+$sh1BI+$sh1BJ+$sh1BK+$sh1BL));
				//Γαλάζιος Πίνακας M
				$sh1AM=(($sh1AC+$sh1AD+$sh1AE+$sh1AF+$sh1AG+$sh1AH+$sh1AI+$sh1AJ+$sh1AK+$sh1AL));
				
				//Γαλάζιος Πίνακας N
				$sh1ANRow=$db->RowSelectorQuery("SELECT SUM( dc ) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1AN=($sh1ANRow['val']);
				
				//Γαλάζιος Πίνακας O
				$sh1AORow=$db->RowSelectorQuery("SELECT SUM( f2o ) AS val FROM data WHERE municipality_id=".$dr['municipality_id']." AND period_id=".$_SESSION['defaultperiod_id']);
				$sh1AO=($sh1AORow['val']);

				//Υπολογισμός κλίμακας Α
				$sh2C = (($sh1BM==0?0:($sh1BE/$sh1BM)*100));
				$resultDiscountA = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.period_id='.$_SESSION['defaultperiod_id'].' AND t2.section_id=1 ORDER BY performance ASC');
				$discountA=0;
				while ($drDiscountA = $db->sql_fetchrow($resultDiscountA)) {
					if($sh2C>$drDiscountA['performance']) $discountA=$drDiscountA['discount'];
				}
				$sh2D = ($discountA);

				//Υπολογισμός κλίμακας Β
				$sh2E = (($sh1BM==0?0:($sh1BG/$sh1BM)*100));
				$resultDiscountB = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.period_id='.$_SESSION['defaultperiod_id'].' AND t2.section_id=2 ORDER BY performance ASC');
				$discountB=0;
				while ($drDiscountB = $db->sql_fetchrow($resultDiscountB)) {
					if($sh2E>$drDiscountB['performance']) $discountB=$drDiscountB['discount'];
				}
				$sh2F = ($discountB);
				
				//Υπολογισμός κλίμακας Γ
				$sh2G = (($sh1BM==0?0:(($sh1BE+$sh1BG+$sh1BI+$sh1BK)/$sh1BM)*100));
				$resultDiscountC = $db->sql_query('SELECT * FROM discounts t1 INNER JOIN discount_sections t2 ON t1.discount_id=t2.discount_id INNER JOIN discount_section_rates t3 ON t2.section_id=t3.section_id WHERE t1.period_id='.$_SESSION['defaultperiod_id'].' AND t2.section_id=3 ORDER BY performance ASC');
				$discountC=0;
				while ($drDiscountC = $db->sql_fetchrow($resultDiscountC)) {
					if($sh2G>$drDiscountC['performance']) $discountC=$drDiscountC['discount'];
				}
				$sh2H = ($discountC);
				
				//Τελικό ποσοστό μείωσης
				$sh2I=(max($discountA,$discountB,$discountC));
				
				$dataArray1[$counter]['id']=$dr['municipality_id'];
				$dataArray1[$counter]['name']=$dr['name'];
				$dataArray1[$counter]['C']=$sh1AC;
				$dataArray1[$counter]['D']=$sh1AD;
				$dataArray1[$counter]['E']=$sh1AE;
				$dataArray1[$counter]['F']=$sh1AF;
			
				$dataArray1[$counter]['G']=$sh1AG;
				$dataArray1[$counter]['H']=$sh1AH;
				$dataArray1[$counter]['I']=$sh1AI;
				$dataArray1[$counter]['J']=$sh1AJ;
				$dataArray1[$counter]['K']=$sh1AK;
				$dataArray1[$counter]['L']=$sh1AL;
				$dataArray1[$counter]['M']=$sh1AM;
				$dataArray1[$counter]['N']=$sh1AN;
				$dataArray1[$counter]['O']=$sh1AO;
				
				$dataArray2[$counter]['id']=$dr['municipality_id'];
				$dataArray2[$counter]['name']=$dr['name'];
				$dataArray2[$counter]['C']=$sh1BC;
				$dataArray2[$counter]['D']=$sh1BD;
				$dataArray2[$counter]['E']=$sh1BE;
				$dataArray2[$counter]['F']=$sh1BF;
				$dataArray2[$counter]['G']=$sh1BG;
				$dataArray2[$counter]['H']=$sh1BH;
				$dataArray2[$counter]['I']=$sh1BI;
				$dataArray2[$counter]['J']=$sh1BJ;
				$dataArray2[$counter]['K']=$sh1BK;
				$dataArray2[$counter]['L']=$sh1BL;
				$dataArray2[$counter]['M']=$sh1BM;
				$dataArray2[$counter]['N']=$sh1BN;
				$dataArray2[$counter]['O']=$sh1BO;
				$dataArray3[$counter]['id']=$dr['municipality_id'];
				$dataArray3[$counter]['name']=$dr['name'];
				$dataArray3[$counter]['C']=$sh2C;
				$dataArray3[$counter]['D']=$sh2D;
				$dataArray3[$counter]['E']=$sh2E;
				$dataArray3[$counter]['F']=$sh2F;
				$dataArray3[$counter]['G']=$sh2G;
				$dataArray3[$counter]['H']=$sh2H;
				$dataArray3[$counter]['I']=$sh2I;
				
				$sh3B=$sh2I;
				$sh3C=$sh1AC;
				$sh3D=($sh3C*$multiplier1);
				$sh3E=($sh1AF+$sh1AH+$sh1AJ+$sh1AL);
				
				$sh3F=($sh3E*$multiplier2);
				$sh3G=($sh1AD);
				$sh3H=($sh1AD*$multiplier3);
				$sh3I=($sh1AG);
				$sh3J=($sh3I*$multiplier4);
				$sh3K=($sh1AK);
				$sh3L=($sh3K*$multiplier5);
				$sh3M=($sh1AE+$sh1AI);
				$sh3N=($sh3M*$multiplier6);
				$sh3O=($sh1AN);
				$sh3P=($sh3O*$multiplier7);
				$sh3Q=($sh1AO);
				$sh3R=($sh3Q*$multiplier8);
				$sh3S=((1-$sh3B/100)*($sh3D+$sh3F+$sh3H+$sh3J+$sh3L+$sh3N+$sh3P+$sh3R));
			
				
				$total=$total+$sh3S;
				//$sh3T=($sh3S*59.1505498409839); //Πρέπει να το υπολογίσω με τα αθροίσματα

				
				$dataArray4[$counter]['name']=$dr['name'];
				$dataArray4[$counter]['B']=$sh3B;
				$dataArray4[$counter]['C']=$sh3C;
				$dataArray4[$counter]['D']=$sh3D;
				$dataArray4[$counter]['E']=$sh3E;
				$dataArray4[$counter]['F']=$sh3F;
				$dataArray4[$counter]['G']=$sh3G;
				$dataArray4[$counter]['H']=$sh3H;
				$dataArray4[$counter]['I']=$sh3I;
				$dataArray4[$counter]['J']=$sh3J;
				$dataArray4[$counter]['K']=$sh3K;
				$dataArray4[$counter]['L']=$sh3L;
				$dataArray4[$counter]['M']=$sh3M;
				$dataArray4[$counter]['N']=$sh3N;
				$dataArray4[$counter]['O']=$sh3O;
				$dataArray4[$counter]['P']=$sh3P;
				$dataArray4[$counter]['Q']=$sh3Q;
				$dataArray4[$counter]['R']=$sh3R;
				$dataArray4[$counter]['S']=$sh3S;
				$dataArray4[$counter]['T']=$sh3T;
				
				$counter++;
			}	
			//Πρώτη στήλη πορτοκαλί πίνακα
			$totalVal=$db->RowSelectorQuery("SELECT (SUM( db ) + SUM( dc )) AS totalVal FROM data WHERE period_id=".$_SESSION['defaultperiod_id']);
			$totalValRow=($totalVal['totalVal']);
			
			//Συνολική περιβαλλοντική εισφορά
			$totalContribution=$totalValRow*$contributionPerTon;
			
			//Συνολικό κόστος (κόστος + περιβαλλοντική εισφορά)
			$totalCost=$periodCost+$totalContribution;
			
			//Κόστος ανα τόνο
			$costPerTon=$totalCost/$total;
			
			
			$result = $db->sql_query("SELECT * FROM municipalities WHERE is_valid='True' ORDER BY name ASC");
			$counter2=0;
			while ($dr = $db->sql_fetchrow($result)) {
				$dataArray4[$counter2]['T']=$dataArray4[$counter]['S']*$costPerTon;

				
				//echo '------------------------------------------------------------------------------------------';
				//echo 'Φύλλο 4 - Εισφορές';
				//echo '------------------------------------------------------------------------------------------<br>';
				//$sh4B=$sh2I;
				//echo($dataArray3[1]['I']);
				//echo $counter2+1;
				//echo $dataArray3[$counter2][Ι];
				//exit;
				$sh4B=$dataArray3[$counter2]['I'];
				
				$sh4C=((100-$sh4B)/100)*$costPerTon*$multiplier1;
				$sh4D=$sh4C*$dataArray1[$counter2]['C'];
				$sh4E=((100-$sh4B)/100)*$costPerTon*$multiplier2;
				$sh4F=$sh4E*($dataArray1[$counter2]['F']+$dataArray1[$counter2]['H']+$dataArray1[$counter2]['J']+$dataArray1[$counter2]['L']);
				$sh4G=((100-$sh4B)/100)*$costPerTon*$multiplier3;
				$sh4H=$sh4G*($dataArray1[$counter2]['D']);//$sh1AD;
				$sh4I=((100-$sh4B)/100)*$costPerTon*$multiplier4;
				$sh4J=$sh4I*($dataArray1[$counter2]['G']); //$sh1AG;
				$sh4K=((100-$sh4B)/100)*$costPerTon*$multiplier5;
				$sh4L=$sh4K*($dataArray1[$counter2]['K']); //$sh1AK;
				$sh4M=((100-$sh4B)/100)*$costPerTon*$multiplier6;
				$sh4N=$sh4M*($dataArray1[$counter2]['E']+$dataArray1[$counter2]['I']);//($sh1AE+$sh1AI);
				$sh4O=((100-$sh4B)/100)*$costPerTon*$multiplier7;
				$sh4P=$sh4O*($dataArray1[$counter2]['N']); //$sh1AN;
				$sh4Q=((100-$sh4B)/100)*$costPerTon*$multiplier8;
				$sh4R=$sh4Q*($dataArray1[$counter2]['O']);//$sh1AO;
				$sh4S=$sh4D+$sh4F+$sh4H+$sh4J+$sh4L+$sh4N+$sh4P+$sh4R;
				//$sh4T=($sh1AM==0?0:$sh4S/$sh1AM);
				$sh4T=($dataArray1[$counter2]['M']==0?0:$sh4S/$dataArray1[$counter2]['M']);
				
				$dataArray5[$counter2]['name']=$dr['name'];
				$dataArray5[$counter2]['B']=$sh4B;
				$dataArray5[$counter2]['C']=$sh4C;
				$dataArray5[$counter2]['D']=$sh4D;
				$dataArray5[$counter2]['E']=$sh4E;
				$dataArray5[$counter2]['F']=$sh4F;
				$dataArray5[$counter2]['G']=$sh4G;
				$dataArray5[$counter2]['H']=$sh4H;
				$dataArray5[$counter2]['I']=$sh4I;
				$dataArray5[$counter2]['J']=$sh4J;
				$dataArray5[$counter2]['K']=$sh4K;
				$dataArray5[$counter2]['L']=$sh4L;
				$dataArray5[$counter2]['M']=$sh4M;
				$dataArray5[$counter2]['N']=$sh4N;
				$dataArray5[$counter2]['O']=$sh4O;
				$dataArray5[$counter2]['P']=$sh4P;
				$dataArray5[$counter2]['Q']=$sh4Q;
				$dataArray5[$counter2]['R']=$sh4R;
				$dataArray5[$counter2]['S']=$sh4S;
				$dataArray5[$counter2]['T']=$sh4T;
				$counter2++;
			}
			


				

			
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////           XLS REPORT //////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			date_default_timezone_set('Europe/London');

			if (PHP_SAPI == 'cli')
				die('This example should only be run from a Web Browser');

			/** Include PHPExcel */
			require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();

			// Set properties
			$objPHPExcel->getProperties()->setCreator("YourPoint OOD") ->setLastModifiedBy("Dimitrios Iordanidis")
											 ->setTitle("Invoicing xls export")	 ->setSubject("Invoicing xls export")
											 ->setDescription("Invoicing xls export made by ViewPanel Suite (2018)") 
											 ->setKeywords("Invoicing XLS export")
											 ->setCategory("Reports");


			$firstLine = $line = 5; $sheet=0; $record=1;
			$lastcolumn = 'O';
			// Set active sheet
			$objPHPExcel->setActiveSheetIndex($sheet);
			

			// Rename sheet
			//$sheetTitle = "Invoicing";
			//$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

			// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader(''); //Validation
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

			// Set page orientation and size
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

			 //Title
			$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "ΚΑΝΟΝΙΣΜΟΣ ΤΙΜΟΛΟΓΗΣΗΣ");
			
			//Subtitle
			$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
			//$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
			//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
			$objPHPExcel->getActiveSheet()->getStyle('C:C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('D:D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('F:F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('G:G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('H:H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('I:I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('J:J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('K:K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('L:L')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('M:M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('N:N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('O:O')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			
			$objPHPExcel->getActiveSheet()->mergeCells ('A3:'.$lastcolumn.'3');
			$objPHPExcel->getActiveSheet()->setCellValue('A3',"ΠΙΝΑΚΑΣ 1.1: ΠΑΡΕΧΟΜΕΝΕΣ ΥΠΗΡΕΣΙΕΣ ΦΟΔΣΑ για ΟΤΑ Α` Βαθμού - Διαχειριζόμενες ποσότητες ανά υπηρεσία\n (Συμπληρώνονται μόνο οι ποσότητες για τις οποίες παρέχεται υπηρεσία και η υπηρεσία τιμολογείται σύμφωνα με τον Πίνακα 1 της ΚΥΑ)");
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
			
			$objPHPExcel->getActiveSheet()->setAutoFilter('A4:'.$lastcolumn.'4');
			// Set column titles and columnn widths (0 : Auto)
			$columns = array(
				 'A'=>array('Α/Α', 10)
				,'B'=>array('ΔΗΜΟΣ', 30)
				,'C'=>array("Σύμμεικτα ΑΣΑ προς διάθεση σε ΧΥΤ (Τ1α)\n 1", 15)
				,'D'=>array('Σύμμεικτα ΑΣΑ προς ΜΕΑ (Τ2)', 15)
				,'E'=>array('Απόβλητα συσκευασιών που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και  ανακύκλωση (ΚΔΑΥ) (Τ3γ)', 15)
				,'F'=>array('Υπόλειμμα ΚΔΑΥ προς διαθεση σε ΧΥΤΑ (Τ1β)', 15)
				,'G'=>array('Χωριστά συλλεγέντα βιοαπόβλητα που ανακυκλώνονται (Τ3α)', 15)
				,'H'=>array('Υπόλειμμα ΜΕΒΑ προς διάθεση σε ΧΥΤΑ (Τ1β)', 15)
				,'I'=>array('Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (ΣΕΔ) (Τ3γ)', 15)
				,'J'=>array('Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (ΣΕΔ) (Τ1β)', 15)
				,'J'=>array('Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (εκτός ΣΕΔ) (Τ3β)', 15)
				,'L'=>array('Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (εκτός ΣΕΔ) (Τ1β)', 15)
				,'M'=>array('Συνολικά διαχειριζόμενα απόβλητα των ΟΤΑ από ΦΟΔΣΑ', 15)
				,'N'=>array('Ποσότητες σύμμεικτων ΑΣΑ που μεταφορτώνονται μέσω ΣΜΑ (Τ4)', 15)
				,'O'=>array('Ποσότητες άλλων αποβλήτων που μεταφορτώνονται μέσω ΣΜΑ (Τ4)', 15)
			);

			// Write titles
			foreach($columns as $key=>$value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.'4', $value[0]);
				if ($value[1]>0)
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
				else
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->getAlignment()->setWrapText(true);
			// Set style for header row using alternative method
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						//'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						//'rotation'   => 90,	
						//'startcolor' => array('argb' => '005B9BD5'),
						//'endcolor' => array('argb' => '005B9BD5')
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '5B9BD5')
					)
				)
			);
				  
			$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
				 array(
					'font' => array(
					'name' => 'Arial',
					'size' => 16,
					'bold' => true,
					'color' => array(
					'rgb' => '000000'
					),	
				 ),
					'fill'=>array(
					 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					 'rotation'   => 0,
					 'color' => array(
					  'rgb' => 'eeeeee'
					 ))
				 )
			);	
	
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(9);
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
		  
			// Add a drawing to the worksheet
			//$objDrawing = new PHPExcel_Worksheet_Drawing();
			//$objDrawing->setName('Logo');
			//$objDrawing->setDescription('Logo');
			//$objDrawing->setPath('./img/dio_logo.png');
			//$objDrawing->setHeight(80);
			//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			
			$rowID = 0;
			$line=5;
			
			foreach ($dataArray1 as $value1) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $value1['id']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $value1['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $value1['C']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $value1['D']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $value1['E']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $value1['F']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $value1['G']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, $value1['H']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, $value1['I']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $value1['J']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$line, $value1['K']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$line, $value1['L']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$line, $value1['M']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$line, $value1['N']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$line, $value1['O']);
				$line++;
			}
			$objPHPExcel->getActiveSheet()->getStyle('A5:'.$lastcolumn.$line)->getFont()->setSize(8);
				
				
			$columns = array(
				 'A'=>array('Α/Α', 10)
				,'B'=>array('ΔΗΜΟΣ', 30)
				,'C'=>array('Σύμμεικτα ΑΣΑ προς διάθεση', 15)
				,'D'=>array('Σύμμεικτα ΑΣΑ προς ΜΕΑ', 15)
				,'E'=>array('Απόβλητα συσκευασιών που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και  ανακύκλωση (ΚΔΑΥ)', 15)
				,'F'=>array('Υπόλειμμα ΚΔΑΥ προς διαθεση σε ΧΥΤΑ', 15)
				,'G'=>array('Χωριστά συλλεγέντα βιοαπόβλητα που ανακυκλώνονται (Τ3α)', 15)
				,'H'=>array('Υπόλειμμα ΜΕΒΑ προς διάθεση σε ΧΥΤΑ', 15)
				,'I'=>array('Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (ΣΕΔ)', 15)
				,'J'=>array('Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (ΣΕΔ)', 15)
				,'J'=>array('Λοιπά υλικά που υπόκεινται σε προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση (εκτός ΣΕΔ)', 15)
				,'L'=>array('Υπόλειμμα από την   προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση λοιπών υλικών (εκτός ΣΕΔ)', 15)
				,'M'=>array('Συνολικά διαχειριζόμενα απόβλητα ανά ΟΤΑ', 15)
			);

			$line=$line+4;
			$lastcolumn='M';
			
			
			// Write titles
			foreach($columns as $key=>$value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.$line, $value[0]);
				if ($value[1]>0)
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
				else
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$line.':'.$lastcolumn.$line)->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ED7D31')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->getRowDimension($line)->setRowHeight(50);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$line.':'.$lastcolumn.$line)->getAlignment()->setWrapText(true);
			
			$line++;
			foreach ($dataArray2 as $value2) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $value2['id']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $value2['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, $value2['C']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $value2['D']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $value2['E']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $value2['F']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $value2['G']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, $value2['H']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, $value2['I']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $value2['J']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$line, $value2['K']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$line, $value2['L']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$line, $value2['M']);
				$line++;
			}
			$objPHPExcel->getActiveSheet()->getStyle('A5:'.$lastcolumn.$line)->getFont()->setSize(8);
			
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle("1. INPUT");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////        2nd sheet ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sheet=1;
			$line=3;
			$lastcolumn='I';
			
			$tempSheet = $objPHPExcel->createSheet($sheet);
			// Rename sheet
			$sheetTitle = "2.ΕΠΙΔΟΣΕΙΣ ΟΤΑ";
			$objPHPExcel->setActiveSheetIndex(1);
			$objPHPExcel->getActiveSheet(1)->setTitle($sheetTitle);
			 //Title
			$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "ΕΠΙΔΟΣΕΙΣ Ο.Τ.Α.");
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
				 array(
					'font' => array(
					'name' => 'Arial',
					'size' => 16,
					'bold' => true,
					'color' => array(
					'rgb' => '000000'
					),	
				 ),
					'fill'=>array(
					 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					 'rotation'   => 0,
					 'color' => array(
					  'rgb' => 'eeeeee'
					 ))
				 )
			);	
			//Subtitle
			//$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Επιδόσεις ΟΤΑ' );
			//$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
			//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
			$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			$objPHPExcel->getActiveSheet()->setCellValue('A2',"ΠΙΝΑΚΑΣ 2.1: Πίνακας υπολογισμού μείωσης συντελεστών βάσει Πίνακα 2 της ΚΥΑ \n(Δε συμπληρώνεται - υπολογίζεται αυτόματα από τα στοιχεία του Πίνακα 1.1 του excel)");
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => '000000'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					//'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
				)
			);
			$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line.':'.$lastcolumn.$line);
			
			$columns = array(
				 'A'=>array('Α/Α', 10)
				,'B'=>array('ΔΗΜΟΣ', 30)
				,'C'=>array('α. Προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση αποβλήτων συσκευασιών σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού', 20)
				,'D'=>array('Ποσοστό μείωσης α.', 20)
				,'E'=>array('β. Χωριστή συλλογή και περαιτέρω ανακύκλωση βιοαποβλήτων σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού', 20)
				,'F'=>array('Ποσοστό μείωσης β.', 20)
				,'G'=>array('γ. Προετοιμασία για επαναχρησιμοποίηση και ανακύκλωση των αστικών αποβλήτων σε σχέση με τα συνολικά αστικά απόβλητα (ΑΣΑ) που διαχειρίζεται ο Ο.Τ.Α. Α’ βαθμού', 20)
				,'H'=>array(' Ποσοστό μείωσης γ.', 20)
				,'I'=>array('Τελικό ποσοστό μείωσης', 20)
			);
			// Write titles
			foreach($columns as $key=>$value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.$line, $value[0]);
				if ($value[1]>0)
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
				else
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastcolumn.'3')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ED7D31')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->getRowDimension($line)->setRowHeight(60);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$line.':'.$lastcolumn.$line)->getAlignment()->setWrapText(true);
			$line++;
			foreach ($dataArray3 as $value3) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $value3['id']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, $value3['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, ($value3['C']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, ($value3['D']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, ($value3['E']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, ($value3['F']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, ($value3['G']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, ($value3['H']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, ($value3['I']/100));
				$line++;
			}
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.$line)->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle('C4:C'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('D4:D'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('E4:E'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('F4:F'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('G4:G'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('H4:H'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('I4:I'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////        3nd sheet ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sheet=2;
			$line=4;
			$lastcolumn='T';
			
			$tempSheet = $objPHPExcel->createSheet($sheet);
			// Rename sheet
			$sheetTitle = "3.ETA";
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet(2)->setTitle($sheetTitle);
			 //Title
			$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "ETA");
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
				 array(
					'font' => array(
					'name' => 'Arial',
					'size' => 16,
					'bold' => true,
					'color' => array(
					'rgb' => '000000'
					),	
				 ),
					'fill'=>array(
					 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					 'rotation'   => 0,
					 'color' => array(
					  'rgb' => 'eeeeee'
					 ))
				 )
			);	
			//Subtitle
			//$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Επιδόσεις ΟΤΑ' );
			//$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
			//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
			$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			$objPHPExcel->getActiveSheet()->setCellValue('A2',"ΠΙΝΑΚΑΣ 3.1: ΥΠΟΛΟΓΙΣΜΟΣ ΕΝΙΑΙΑΣ ΤΙΜΗΣ ΑΝΑΦΟΡΑΣ (ΕΤΑ) ΒΑΣΕΙ ΜΑΘΗΜΑΤΙΚΟΥ ΤΥΠΟΥ ΚΥΑ\nΣυμπληρώνονται μόνο οι συντελεστές με κόκκινο και το ετήσιο κόστος διαχείρισης");
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => '000000'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					//'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
				)
			);
			
			$objPHPExcel->getActiveSheet()->mergeCells ('C3:D3');
			$objPHPExcel->getActiveSheet()->mergeCells ('E3:F3');
			$objPHPExcel->getActiveSheet()->mergeCells ('G3:H3');
			$objPHPExcel->getActiveSheet()->mergeCells ('I3:J3');
			$objPHPExcel->getActiveSheet()->mergeCells ('K3:L3');
			$objPHPExcel->getActiveSheet()->mergeCells ('M3:N3');
			$objPHPExcel->getActiveSheet()->mergeCells ('O3:P3');
			$objPHPExcel->getActiveSheet()->mergeCells ('Q3:R3');
			$objPHPExcel->getActiveSheet()->setCellValue('C3',"1α: Εργασίες διάθεσης σύμμεικτων αστικών αποβλήτων");
			$objPHPExcel->getActiveSheet()->setCellValue('E3',"1β:Εργασίες διάθεσης υπολείμματος από μονάδες επεξεργασίας χωριστά συλλεγέντων αποβλήτων (ΜΕΒΑ, ΚΔΑΥ)");
			$objPHPExcel->getActiveSheet()->setCellValue('G3',"2: Εργασίες ανάκτησης σύμμεικτων αστικών αποβλήτων σε μονάδες μηχανικής, βιολογικής επεξεργασίας αποβλήτων (MEA)-  (συμπεριλαμβάνεται και η διάθεση των υπολειμμάτων)");
			$objPHPExcel->getActiveSheet()->setCellValue('I3',"1α: Εργασίες διάθεσης σύμμεικτων αστικών αποβλήτων");
			$objPHPExcel->getActiveSheet()->setCellValue('K3',"3β: Εργασίες ανάκτησης υλικών εκτός ΣΕΔ");
			$objPHPExcel->getActiveSheet()->setCellValue('M3',"3γ: Εργασίες ανάκτησης υλικών ΣΕΔ");
			$objPHPExcel->getActiveSheet()->setCellValue('O3',"4: Μεταφόρτωση σύμμεικτων αστικών αποβλήτων");
			$objPHPExcel->getActiveSheet()->setCellValue('Q3',"4: Μεταφόρτωση χωριστά συλλεγέντων ρευμάτων (ανακυκλώσιμα, βιοαπόβλητα ή άλλα)");
			$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('O3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('Q3')->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(50);

			$objPHPExcel->getActiveSheet()->getStyle('C3:R3')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '5B9BD5')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line.':'.$lastcolumn.$line);
			
			
			$columns = array(
				 'A'=>array('ΔΗΜΟΣ', 30)
				,'B'=>array('Μ ΟΤΑ', 20)
				,'C'=>array('Π1α', 20)
				,'D'=>array('Π1α x Σ1α', 20)
				,'E'=>array('Π1β', 20)
				,'F'=>array('Π1β x Σ1β', 20)
				,'G'=>array('Π2', 20)
				,'H'=>array('Π2 x Σ2', 20)
				,'I'=>array('Π3α', 20)
				,'J'=>array('Π3α x Σ3α', 20)
				,'K'=>array('Π3β', 20)
				,'L'=>array('Π3β x Σ3β', 20)
				,'M'=>array('Π3γ', 20)
				,'N'=>array('Π3γ x Σ3γ', 20)
				,'O'=>array('Π4', 20)
				,'P'=>array('Π4 x Σ4', 20)
				,'Q'=>array('Π4', 20)
				,'R'=>array('Π4 x Σ4', 20)
				,'S'=>array('(100%-Μ ΟΤΑ) x Σ(Π x Σ)', 20)
				,'T'=>array('Ποσο', 20)
			);
		
			// Write titles
			foreach($columns as $key=>$value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.$line, $value[0]);
				if ($value[1]>0)
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
				else
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ED7D31')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->getRowDimension($line)->setRowHeight(70);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$line.':'.$lastcolumn.$line)->getAlignment()->setWrapText(true);
			$line++;
			foreach ($dataArray4 as $value4) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $value4['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, ($value4['B']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, ($value4['C']));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, ($value4['D']));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, ($value4['E']));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, ($value4['F']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, ($value4['G']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, ($value4['H']));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, ($value4['I']));
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, ($value4['J']));
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$line, ($value4['K']));
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$line, ($value4['L']));
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$line, ($value4['M']));
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$line, ($value4['N']));
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$line, ($value4['O']));
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$line, ($value4['P']));
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$line, ($value4['Q']));
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$line, ($value4['R']));
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$line, ($value4['S']));
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$line, ($value4['T']));
				$line++;
			}
			
			$newLine=$line+5;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Κόστος περιόδου');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $periodCost);
			$newLine++;
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Συνολική ποσότητα προς ταφή');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $totalValRow);
			$newLine++;
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Περιβαλλοντική εισφορά ανα τόνο');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $contributionPerTon);
			$newLine++;
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Περιβαλλοντική εισφορά');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $totalContribution);
			$newLine++;
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Συνολικό κόστος');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $totalCost);
			$newLine++;
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$newLine, 'Κόστος ανα τόνο');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$newLine, $costPerTon);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($newLine-5).':A'.$newLine)->applyFromArray(
				array(
					'font'    => array(	'bold' => true, 'color' => array('rgb' => '000000'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill'=>array( 'type' => PHPExcel_Style_Fill::FILL_SOLID,'rotation'   => 0, 'color' => array( 'rgb' => 'eeeeee' ))
				)
			);
			$objPHPExcel->getActiveSheet()->getStyle('B'.($newLine-5).':B'.$newLine)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			
			
	
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.$line)->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle('B4:B'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('C4:C'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('D4:D'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('E4:E'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('F4:F'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('G4:G'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('H4:H'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('I4:I'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('J4:J'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('K4:K'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('L4:L'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('M4:M'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('N4:N'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('O4:O'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('P4:P'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('Q4:Q'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('R4:R'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('S4:S'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('T4:T'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////        4nd sheet ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sheet=3;
			$line=4;
			$lastcolumn='T';
			
			$tempSheet = $objPHPExcel->createSheet($sheet);
			// Rename sheet
			$sheetTitle = "4.ΕΙΣΦΟΡΕΣ";
			$objPHPExcel->setActiveSheetIndex($sheet);
			$objPHPExcel->getActiveSheet($sheet)->setTitle($sheetTitle);
			 //Title
			$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "ΕΙΣΦΟΡΕΣ");
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
				 array(
					'font' => array(
					'name' => 'Arial',
					'size' => 16,
					'bold' => true,
					'color' => array(
					'rgb' => '000000'
					),	
				 ),
					'fill'=>array(
					 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					 'rotation'   => 0,
					 'color' => array(
					  'rgb' => 'eeeeee'
					 ))
				 )
			);	
			//Subtitle
			//$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
			//$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Επιδόσεις ΟΤΑ' );
			//$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
			//$objPHPExcel->getActiveSheet()->getStyle('E:E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
			$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
			$objPHPExcel->getActiveSheet()->setCellValue('A2',"ΠΙΝΑΚΑΣ 4.1: ΥΠΟΛΟΓΙΣΜΟΣ ΕΙΣΦΟΡΩΝ ΒΑΣΕΙ ΜΑΘΗΜΑΤΙΚΟΥ ΤΥΠΟΥ ΚΥΑ\n(δε συμπληρώνεται - οι τιμές μπορούν να μεταβληθούν με αλλαγή των συντελεστών διαβάθμισης στο φύλλο 3 ΕΤΑ)");
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => '000000'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					//'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
				)
			);
			
			$objPHPExcel->getActiveSheet()->mergeCells ('C3:D3');
			$objPHPExcel->getActiveSheet()->mergeCells ('E3:F3');
			$objPHPExcel->getActiveSheet()->mergeCells ('G3:H3');
			$objPHPExcel->getActiveSheet()->mergeCells ('I3:J3');
			$objPHPExcel->getActiveSheet()->mergeCells ('K3:L3');
			$objPHPExcel->getActiveSheet()->mergeCells ('M3:N3');
			$objPHPExcel->getActiveSheet()->mergeCells ('O3:P3');
			$objPHPExcel->getActiveSheet()->mergeCells ('Q3:R3');
			$objPHPExcel->getActiveSheet()->setCellValue('C3',"1α: Εργασίες διάθεσης σύμμεικτων αστικών αποβλήτων");
			$objPHPExcel->getActiveSheet()->setCellValue('E3',"1β:Εργασίες διάθεσης υπολείμματος από μονάδες επεξεργασίας χωριστά συλλεγέντων αποβλήτων (ΜΕΒΑ, ΚΔΑΥ)");
			$objPHPExcel->getActiveSheet()->setCellValue('G3',"2: Εργασίες ανάκτησης σύμμεικτων αστικών αποβλήτων σε μονάδες μηχανικής, βιολογικής επεξεργασίας αποβλήτων (MEA)-  (συμπεριλαμβάνεται και η διάθεση των υπολειμμάτων)");
			$objPHPExcel->getActiveSheet()->setCellValue('I3',"3α: Εργασίες ανάκτησης χωριστά συλλεγέντων βιοαποβλήτων (συμπεριλαμβάνεται και η χωριστή επεξεργασία των βιοαποβλήτων εντός MEA)");
			$objPHPExcel->getActiveSheet()->setCellValue('K3',"3β: Εργασίες ανάκτησης υλικών εκτός ΣΕΔ");
			$objPHPExcel->getActiveSheet()->setCellValue('M3',"3γ:Εργασίες ανάκτησης υλικών ΣΕΔ");
			$objPHPExcel->getActiveSheet()->setCellValue('O3',"4: Μεταφόρτωση σύμμεικτων αστικών αποβλήτων");
			$objPHPExcel->getActiveSheet()->setCellValue('Q3',"4: Μεταφόρτωση χωριστά συλλεγέντων ρευμάτων (ανακυκλώσιμα, βιοαπόβλητα ή άλλα)");
			$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('O3')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('Q3')->getAlignment()->setWrapText(true);
			
			$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(50);

			$objPHPExcel->getActiveSheet()->getStyle('C3:R3')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '5B9BD5')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$line.':'.$lastcolumn.$line);
			
			
			$columns = array(
				 'A'=>array('ΔΗΜΟΣ', 30)
				,'B'=>array('Μ ΟΤΑ', 20)
				,'C'=>array('Τιμή μονάδος 1α (μετά τη μείωση)', 20)
				,'D'=>array('Εισφορές 1α', 20)
				,'E'=>array('Τιμή μονάδος 1β (μετά τη μείωση)', 20)
				,'F'=>array('Εισφορές 1β', 20)
				,'G'=>array('Τιμή μονάδος 2 (μετά τη μείωση)', 20)
				,'H'=>array('Εισφορές 2', 20)
				,'I'=>array('Τιμή μονάδος 3α (μετά τη μείωση)', 20)
				,'J'=>array('Εισφορές  3α', 20)
				,'K'=>array('Τιμή μονάδος 3β (μετά τη μείωση)', 20)
				,'L'=>array('Εισφορές 3β', 20)
				,'M'=>array('Τιμή μονάδος 3γ (μετά τη μείωση)', 20)
				,'N'=>array('Εισφορές 3γ', 20)
				,'O'=>array('Τιμή μονάδος 4 (μετά τη μείωση)', 20)
				,'P'=>array('Εισφορές 4', 20)
				,'Q'=>array('Τιμή μονάδος 4 (μετά τη μείωση)', 20)
				,'R'=>array('Εισφορές 4', 20)
				,'S'=>array('Συνολικές Εισφορές OTA', 20)
				,'T'=>array('Συνολικές Εισφορές OTA ανά τόνο αποβλήτων', 20)
			);
		
			// Write titles
			foreach($columns as $key=>$value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.$line, $value[0]);
				if ($value[1]>0)
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
				else
					$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'4')->applyFromArray(
				array(
					'font'    => array(	'normal'      => true, 'color' => array('rgb' => 'ffffff'),'size'  => 9,	'name'  => 'Calibri'),
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
					'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ED7D31')
					)
				)
			);
			$objPHPExcel->getActiveSheet()->getRowDimension($line)->setRowHeight(70);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$line.':'.$lastcolumn.$line)->getAlignment()->setWrapText(true);
			$line++;
			foreach ($dataArray5 as $value5) {
				
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $value5['name']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, ($value5['B']/100));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, ($value5['C']));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, ($value5['D']));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, ($value5['E']));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, ($value5['F']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, ($value5['G']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, ($value5['H']));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, ($value5['I']));
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, ($value5['J']));
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$line, ($value5['K']));
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$line, ($value5['L']));
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$line, ($value5['M']));
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$line, ($value5['N']));
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$line, ($value5['O']));
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$line, ($value5['P']));
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$line, ($value5['Q']));
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$line, ($value5['R']));
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$line, ($value5['S']));
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$line, ($value5['T']));
				$line++;
			}
			$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.$line)->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle('B4:B'.$line)->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
			$objPHPExcel->getActiveSheet()->getStyle('C4:C'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('D4:D'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('E4:E'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('F4:F'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('G4:G'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('H4:H'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('I4:I'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('J4:J'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('K4:K'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('L4:L'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('M4:M'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('N4:M'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('O4:O'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('P4:P'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('Q4:Q'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('R4:R'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('S4:S'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('T4:T'.$line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			///////////////////// End excel file
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="invoicing-'.date("d-m-Y").'.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		
			//}
	}
	?>
	<script>
	/*
	function doTotal(){
		var db1 = Number($('#db1').val());
		var dc1 = Number($('#dc1').val());
		var dd1 = Number($('#dd1').val());
		var de1 = Number($('#de1').val());
		var df1 = Number($('#df1').val());
		var dg1 = Number($('#dg1').val());
		var dh1 = Number($('#dh1').val());
		var di1 = Number($('#di1').val());
		var totalA = (db1+dc1+dd1+de1+df1+dg1+dh1+di1);
		$('#totalA').val(totalA);
	}
	*/
	</script>