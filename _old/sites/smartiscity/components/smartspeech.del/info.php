<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	if (!($permissions & $FLAG_500) &&  !$auth->UserType == "Administrator") {
		Redirect("index.php");
	}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Οδηγίες";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=info";
$command=array();
$command=explode("&",$_POST["Command"]);
?>

					<div class="row">
						<div class="col">
							<h4>Οδηγίες</h4>
							<div class="accordion" id="accordion">
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
												INTERNET
											</a>
										</h4>
									</div>
									<div id="collapse1" class="collapse show">
										<div class="card-body">
											<p>Για την εισαγωγή προσωπικών στοιχείων του χρήστη (γονέα) στη web εφαρμογή smartspeech.io αλλά και την ολοκλήρωση της επεξεργασίας στοιχείων για τα παιδιά, απαιτείται σύνδεση στο Internet. Όταν το παιδί παίζει το παιχνίδι σε συσκευή τύπου tablet, είναι προτιμότερο να υπάρχει σύνδεση στο internet, ωστόσο αυτό δεν είναι αναγκαίο. Η σύνδεση στην κεντρική βάση δεδομένων από τη συσκευή tablet θα γίνει μόλις υπάρχει διαθέσιμη σύνδεση στο internet. </p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
												TABLET
											</a>
										</h4>
									</div>
									<div id="collapse2" class="collapse">
										<div class="card-body">
											<p>Για να παίξει το παιδί το παιχνίδι απαιτείται να χρησιμοποιεί συγκεκριμένη συσκευή tablet, στην πλάγια θέση. Το tablet θα πρέπει να έχει camera στην πλάγια διάσταση του tablet.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
												ΒΙΟΜΕΤΡΙΚΑ
											</a>
										</h4>
									</div>
									<div id="collapse3" class="collapse">
										<div class="card-body">
											<p>Για την ολοκληρωμένη διάγνωση δυσκολιών επικοινωνίας, το παιδί που θα παίξει το παιχνίδι «ΑΨΟΥ» θα πρέπει να φορά συγκεκριμένο ρολόι, το THOR.
												Η ομάδα του smartspeech παρέχει δωρεάν το ρολόι στον παίκτη για όλη τη διάρκεια που το παιδί παίζει το παιχνίδι.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
												ΣΥΓΧΡΟΝΙΣΜΟΣ ΒΙΟΜΕΤΡΙΚΩΝ ΔΕΔΟΜΕΝΩΝ
											</a>
										</h4>
									</div>
									<div id="collapse4" class="collapse">
										<div class="card-body">
											<p>Για να μπορούν να ληφθούν δεδομένα από το βιομετρικό ρολόι, θα πρέπει ο χρήστης (γονέας) να εισέλθει με το account που έχει δημιουργήσει στη σελίδα smartspeech.io μέσα από το μενού του ρολογιού. Το ρολόι θα πρέπει να είναι συνδεδεμένο στο Internet.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse5">
												EYE TRACKING
											</a>
										</h4>
									</div>
									<div id="collapse5" class="collapse">
										<div class="card-body">
											<p>Στο πλαίσιο της διάγνωσης, αξιολογούνται ζητήματα σχετικά με τη διάσπαση της προσοχής (gaze analysis). Για το σκοπό αυτό, το παιδί θα πρέπει να παίξει το παιχνίδι κοιτώντας το ίδιο το tablet, όχι ο γονέας. Ο γονέας είναι χρήσιμο να καθοδηγεί το παιδί να παίξει το παιχνίδι χωρίς σε συνεχή ροή, δηλαδή να μην μετακινεί το βλέμμα και τη στάση του μακρυά από την οθόνη του tablet, προκειμένου να παραμείνει εντός λειτουργίας η διαδικασία eye tracking calibration</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse6">
												ΣΤΑΣΗ ΠΑΙΔΙΟΥ
											</a>
										</h4>
									</div>
									<div id="collapse6" class="collapse">
										<div class="card-body">
											<p>Ενδείκνυται το tablet να είναι σταθερό σε σχετική βάση και τα δάχτυλα του παιδιού ελεύθερα για να μπορεί να ανταποκριθεί με ευχέρεια στις δοκιμασίες. Το παιδί είναι προτιμότερο να βρίσκεται σε άνετη καθιστή θέση.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse7">
												PAUSE – STOP
											</a>
										</h4>
									</div>
									<div id="collapse7" class="collapse">
										<div class="card-body">
											<p>Όταν το παιδί θέλει να διακόψει τη ροή του παιχνιδιού και είναι ορατό το σύμβολο «ΙΙ» στο δεξί μέρος της οθόνης, ο γονέας μπορεί να πατήσει το σύμβολο αυτό με αποτέλεσμα να γίνει σύντομη διακοπή στο παιχνίδι. Όταν πατήσει εκ νέου το σύμβολο, το παιχνίδι συνεχίζει από εκεί που σταμάτησε. Εάν το παιδί θέλει να σταματήσει και να μην παίξει το παιχνίδι, τότε ο γονέας μπορεί να πατήσει STOP. Στην περίπτωση αυτή, δεν θα αποθηκευτούν στην κεντρική βάση δεδομένων οι τελευταίες αποκρίσεις του παιδιού.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse8">
												ΒΟΗΘΕΙΑ - ΟΔΗΓΙΕΣ
											</a>
										</h4>
									</div>
									<div id="collapse8" class="collapse">
										<div class="card-body">
											<p>Πατώντας επάνω στην κουκουβάγια «Σόφη» μέσα στο παιχνίδι, μπορείτε να ακούσετε ηχητικές οδηγίες σχετικά με το παιχνίδι. 
												Όταν το παιδί έχει ολοκληρώσει μία δοκιμασία πριν τον συνολικό προβλεπόμενο χρόνο, μπορεί να πατήσει στο βέλος δεξιά κάτω στην οθόνη, προκειμένου να συνεχίσει στην επόμενη δοκιμασία.</p>
										</div>
									</div>
								</div>
								<div class="card card-default">
									<div class="card-header">
										<h4 class="card-title m-0">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse9">
												GUEST
											</a>
										</h4>
									</div>
									<div id="collapse9" class="collapse">
										<div class="card-body">
											<p>Μπορείτε ως γονέας να δείτε πως παίζεται το παιχνίδι – μόνος- εάν επιλέξετε «Guest» στα στοιχεία το χρήστη. Εκεί θα έχετε τη δυνατότητα να παίξετε το παιχνίδι (καλό είναι το παιδί να βρίσκεται σε άλλο χώρο και ο ήχος να είναι χαμηλά) για να δείτε τη ροή του παιχνιδιού. Ομοίως εάν κάποιο άλλο παιδί (ή ενήλικας) επιθυμεί να παίξει το παιχνίδι, μπορεί επίσης να δοκιμάσει το παιχνίδι. </p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>