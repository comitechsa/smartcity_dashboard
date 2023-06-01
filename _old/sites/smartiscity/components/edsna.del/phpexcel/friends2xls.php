<?php
session_start();
$host = "localhost";
$user = "wangrjz_hotbox";
$database = "wangrjz_hotbox";
$pass = "qwe#123!@#";
mysql_set_charset('utf8');

# Connect
mysql_connect($host, $user, $pass) or die('Could not connect: ' . mysql_error());

# Choose a database
mysql_select_db($database) or die('Could not select database');

if (intval($_SESSION['userID']) > 0) {
	$user_id = $_SESSION['userID'];
} else {
	exit;
}
$query = "SELECT * FROM friends WHERE device_id IN (SELECT mac FROM devices WHERE user_id='" . $user_id . "')";
$result = mysql_query($query) or die("could not execute");

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
// $objPHPExcel = new PHPExcel();

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("YourPoint OOD")->setLastModifiedBy("Dimitrios Iordanidis")
	->setTitle("Friends xls export")->setSubject("Friends xls export")
	->setDescription("Friends xls export made by ViewPanel Suite (2017)")
	->setKeywords("Spotyy friends export")
	->setCategory("Reports");


$firstLine = $line = 5;
$sheet = 0;
$record = 1;
$lastcolumn = 'M';
// Set active sheet
$objPHPExcel->setActiveSheetIndex($sheet);


// Rename sheet
$sheetTitle = 'CertificationValidation';
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('Validation');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

// Set page orientation and size
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


//Title
$objPHPExcel->getActiveSheet()->mergeCells('A1:' . $lastcolumn . '1');
$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Friends');

//Subtitle
$objPHPExcel->getActiveSheet()->mergeCells('A2:' . $lastcolumn . '2');
$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);


// Set column titles and columnn widths (0 : Auto)
$columns = array(
	'A' => array('Id', 15), 'B' => array('Device', 15), 'C' => array('Friend Id', 15), 'D' => array('email', 20), 'E' => array('First Name', 20), 'F' => array('Last Name', 15), 'G' => array('Gender', 15), 'H' => array('Link', 7), 'I' => array('Timezone', 15), 'J' => array('Verified', 15), 'K' => array('Verified email', 10), 'L' => array('DateInsert', 10), 'M' => array('Agent', 18)
);

// Write titles
foreach ($columns as $key => $value) {
	$objPHPExcel->getActiveSheet()->setCellValue($key . '4', $value[0]);
	if ($value[1] > 0)
		$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
	else
		$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
}


// Set style for header row using alternative method
$objPHPExcel->getActiveSheet()->getStyle('A4:' . $lastcolumn . '4')->applyFromArray(
	array(
		'font'    => array('bold'      => true),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
		'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
			'rotation'   => 90,
			'startcolor' => array('argb' => 'FFeeeeee'),
			'endcolor' => array('argb' => 'FFeeeeee')
		)
	)
);

$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
	array(
		'font' => array(
			'name' => 'Arial',
			'size' => 18,
			'bold' => true,
			'color' => array(
				'rgb' => '000000'
			),
		),
		'fill' => array(
			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			'rotation'   => 0,
			'color' => array(
				'rgb' => 'eeeeee'
			)
		)
	)
);


$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);

// Add a drawing to the worksheet
//$objDrawing = new PHPExcel_Worksheet_Drawing();
//$objDrawing->setName('Logo');
//$objDrawing->setDescription('Logo');
//$objDrawing->setPath('./img/dio_logo.png');
//$objDrawing->setHeight(80);
//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$rowID = 0;
$line = 5;
while ($row = mysql_fetch_assoc($result)) {
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $line, $row['id']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $line, $row['device_id']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $line, $row['friend_id']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $line, $row['email']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $line, $row['first_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $line, $row['last_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $line, $row['gender']);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $line, $row['link']);
	$objPHPExcel->getActiveSheet()->setCellValue('I' . $line, $row['timezone']);
	$objPHPExcel->getActiveSheet()->setCellValue('J' . $line, $row['verified']);
	$objPHPExcel->getActiveSheet()->setCellValue('K' . $line, $row['verifiedEmail']);
	$objPHPExcel->getActiveSheet()->setCellValue('L' . $line, $row['date_insert']);
	$objPHPExcel->getActiveSheet()->setCellValue('M' . $line, $row['HTTP_USER_AGENT']);
	$line++;
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
