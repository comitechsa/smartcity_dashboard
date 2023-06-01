<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

define('core_login','Είσοδος');
define('core_userName','Όνομα χρήστη');
define('core_userPassword','Κωδικός χρήστη');
define('core_loginMessage','Για να συνδεθείτε στο σύστημα διαχείρισης βάλτε τα σωστά στοιχεία.');
define('core_noScript','!Warning! Javascript must be enabled for proper operation of the Administrator');

define('core_recordSelect','Παρακαλούμε επιλέξτε εγγραφή.');
define('core_preview','Προβολή');
define('core_insert','Εισαγωγή');
define('core_newRecord','Νέα Εγγραφή');
define('core_edit','Επεξεργασία');
define('core_delete','Διαγραφή');
define('core_deleteConfirm','Διαδικασία διαγραφής. Πατήστε `OK` για να προχωρήσετε');
define('core_save','Αποθήκευση');
define('core_saveConfirm','Διαδικασία αποθήκευσης. Πατήστε `OK` για να προχωρήσετε');
define('core_cancel','Ακύρωση');
define('core_back','Επιστροφή');
define('core_search','Αναζήτηση');
define('core_insertUpdate','Εισαγωγή - Ενημέρωση');
define('core_recordSaved','Η εγγραφή αποθηκεύτηκε με επιτυχία.');
define('core_user','Χρήστης');
define('core_insertDate','Ημερομηνία Καταχώρισης');
define('core_select','Επιλογή');
define('core_hits','Μετρητής');

function core_mouths(){return array("Ιανουάριος","Φεβρουάριος","Μάρτιος","Απρίλιος","Μάϊος","Ιούνιος","Ιούλιος","Αύγουστος","Σεπτέμβριος","Οκτώβριος","Νοέμβριος","Δεκέμβριος");};
function core_mouthsIU(){return array("Ιανουαρίου","Φεβρουαρίου","Μαρτίου","Απριλίου","Μαϊου","Ιουνίου","Ιουλίου","Αυγούστου","Σεπτεμβρίου","Οκτωβρίου","Νοεμβρίου","Δεκεμβρίου");};
function  core_days() {return array("Κυριακή","Δευτέρα","Τρίτη","Τετάρτη","Πέμπτη","Παρασκευή","Σαββάτο");};

define('core_securityCode','Κωδικός Ασφαλείας');
define('core_securityCodeError','Ο κωδικός ασφαλείας δεν είναι σωστός');
?>