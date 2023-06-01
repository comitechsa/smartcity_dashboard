<?php


/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
error_reporting(0);

date_default_timezone_set('Europe/London');
ini_set("memory_limit","2048");
ini_set("memory_limit","-1");
ini_set("max_execution_time",900);
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');



	$url="https://bd4qol.ddns.net/testing/mobile_test_data";
	$ch = curl_init( $url );
	
	//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	//echo "<pre>$result</pre>";
	//exit;
	$data = json_decode($result);
	//print_r($data);
	//exit;
	/*
	light, steps, screen, accelerometer, gyroscope.
	*/
	//print_r($data[0]);
	//exit;

	
	//for($i=0; $i<sizeof($data);$i++){
	//for($i=0; $i<20;$i++){
	//	$var = get_object_vars($data[$i]);
	//	echo $var["id"].' - '.$var["lat"].' - '.$var["lng"].' - '.$var["wifi"].' - '.$var["datetime"].' - '.$var["activity"].' - '.$var["lastcall"].' - '.$var["lastmessage"].' - '.$var["light"].' - '.$var["screen"].' - '.$var["steps"].' - '.$var["accelerometer"].' - '.$var["gyroscope"].'<br><br>';
		//echo sizeof($var["usage"]);
	//}
	
	//exit;
	/**/

	
	

	
		/** Include PHPExcel */
		require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Webstudio") ->setLastModifiedBy("Dimitrios Iordanidis")
										 ->setTitle("Friends xls export")	 ->setSubject("BD4QOL xls export")
										 ->setDescription("Export made by ViewPanel Suite (2020)") 
										 ->setKeywords("BD4QOL xls export")
										 ->setCategory("Reports");

		$firstLine = $line = 2; $sheet=0; $record=1;
		$lastcolumn = 'T';
		// Set active sheet
		$objPHPExcel->setActiveSheetIndex($sheet);
		
		// Rename sheet
		$sheetTitle = 'bd4qol';
		$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

		// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('Validation');
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

		// Set page orientation and size
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		 //Title
		//$objPHPExcel->getActiveSheet()->mergeCells ('A1:'.$lastcolumn.'1');
		//$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'BD4QOL');
		
		//Subtitle
		////$objPHPExcel->getActiveSheet()->mergeCells ('A2:'.$lastcolumn.'2');
		//$objPHPExcel->getActiveSheet()->setCellValue('A2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
		//$objPHPExcel->getActiveSheet()->getStyle('A2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
		//$objPHPExcel->getActiveSheet()->setAutoFilter('A5:'.$lastcolumn.'5');
		
		// Set column titles and columnn widths (0 : Auto)
		$columns = array(
			 'A'=>array('ID', 15)
			,'B'=>array('Lat', 15)
			,'C'=>array('Lng', 15)
			,'D'=>array('Wifi', 20)
			,'E'=>array('datetime', 20)
			,'F'=>array('date', 20)
			,'G'=>array('time', 20)
			,'H'=>array('Activity', 20)
			,'I'=>array('LastcallNumber', 20)
			,'J'=>array('LastcallDate', 20)
			,'K'=>array('LastcallTime', 20)
			,'L'=>array('LastmessageDate', 20)	
			,'M'=>array('LastmessageTime', 20)	
			,'N'=>array('LastmessageType', 20)	
			,'O'=>array('MD5 Call', 20)	
			,'P'=>array('light', 20)	
			,'Q'=>array('steps', 20)	
			,'R'=>array('screen', 20)	
			,'S'=>array('accelerometer', 20)	
			,'T'=>array('gyroscope', 20)	
		);


		
		
		// Write titles
		foreach($columns as $key=>$value) {
			$objPHPExcel->getActiveSheet()->setCellValue($key.'1', $value[0]);
			if ($value[1]>0)
				$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value[1]);
			else
				$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
		}

 
		// Set style for header row using alternative method
		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastcolumn.'1')->applyFromArray(
			array(
				'font'    => array(	'bold'      => true	),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	),
				'borders' => array(	'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
				'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,	
				'startcolor' => array('argb' => 'FFeeeeee'),
				'endcolor' => array('argb' => 'FFeeeeee')
				)
			)
		);
	   	 /*
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
					'fill'=>array(
					 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					 'rotation'   => 0,
					 'color' => array(
					  'rgb' => 'eeeeee'
					 ))
				 )
		 );			 
		 
		 */ 
	


		////$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
		////$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
		
		// Add a drawing to the worksheet
		//$objDrawing = new PHPExcel_Worksheet_Drawing();
		//$objDrawing->setName('Logo');
		//$objDrawing->setDescription('Logo');
		//$objDrawing->setPath('./img/dio_logo.png');
		//$objDrawing->setHeight(80);
		//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		////$objPHPExcel->getActiveSheet()->setCellValueExplicit('A1', $val,PHPExcel_Cell_DataType::TYPE_STRING);
		
		$rowID = 0;
		$line=2;
//http://drama.smartiscity.gr/readThis2.php
		for($i=0; $i<sizeof($data);$i++){
			$var = get_object_vars($data[$i]);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $var['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, strip_tags($var['lat']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$line, strip_tags($var['lng']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$line, $var['wifi']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$line, $var['datetime']);
			$recDate="".substr($var['datetime'], 0, 10)."";
			$recTime="".substr($var['datetime'], 11, 8)."";
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$line, $recDate);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$line, $recTime);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$line, $var['activity']);
			if(intval($var['id'])>0){
				$ids[$var['id']] = $var['id'];	
				//$dataid[$recDate][$var['id']]=intval($dataid[$recDate][$var['id']])+1;
				//$widget = str_replace(array('-'), '',substr($var['datetime'], 0, 10));
				$widget = $recDate;
				$widgetid=intval($var['id']);
				$lastval=intval($dataid[$widget][$widgetid]);
				$dataid[$widget][$widgetid]=$lastval+1;
				$myhour=intval(substr($var['datetime'],11,2));
				$dataid2[$widget][$widgetid][$myhour]=$dataid2[$widget][$widgetid][$myhour]+1;
			}

			//$dataid[$recDate][$var['id']]=($dataid[$recDate][$var['id']])+1;
			if(strlen($var['lastcall'])>0){
				$substring1 = substr($var['lastcall'], 8, (strpos($var['lastcall'], ",")-8));

 				$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, '"'.$substring1.'"');
				//$objPHPExcel->getActiveSheet()->setCellValue('I'.$line, $var['lastcall']);
				$substring2 = substr($var['lastcall'], (strpos($var['lastcall'], ",")+12),23);
				$substringDate = substr($substring2, 0,10);
				$substringTime = substr($substring2, 11,8);
				
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, '"'.$substringDate.'"');
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$line, '"'.$substringTime.'"');
				//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A1', $val,PHPExcel_Cell_DataType::TYPE_STRING);
			}	
			if(strlen($var['lastmessage'])>0){
				$lastMessageDate = substr($var['lastmessage'],10,11);
				$lastMessageTime = substr($var['lastmessage'],22,8);
				$lastMessageType = substr($var['lastmessage'],41,24);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$line, '"'.$lastMessageDate.'"');	
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$line, '"'.$lastMessageTime.'"');	
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$line, '"'.$lastMessageType.'"');	
				//$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $var['lastmessage']);	
			}	
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$line, '"'.md5($substring2).'"');	
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$line, '"'.$var['light'].'"');	
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$line, '"'.$var['steps'].'"');	
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$line, '"'.$var['screen'].'"');	
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$line, '"'.$var['accelerometer'].'"');	
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$line, '"'.$var['gyroscope'].'"');	
			
		
			/*

			
			
			*/

			//$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $var['lastcall']);
			//$objPHPExcel->getActiveSheet()->setCellValue('J'.$line, $var['lastmessage']);
			$line++;
			//echo sizeof($var["usage"]);
		}


		
  
// Rename worksheet

//second sheet
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle('Totals');
		$idscomma = implode(',',$ids);
		//$objPHPExcel->getActiveSheet()->setCellValue('B1', "$idscomma");
/**/


	$line=2;
	$alphabet = range('A', 'Z');
	$col=2;

	$counter=0;
	foreach(array_keys($ids) as $paramName) {
		$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, "$paramName");
		$col++;	
		$newid[$counter]=$paramName;
		$counter++;
	}
	
	$line++;
	
	foreach(array_keys($dataid) as $paramName) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, "$paramName");
		$col=2;
		for($i=0;$i<sizeof($newid);$i++){
			//echo intval($dataid[$paramName][$i]).' - ';
			$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, intval($dataid[$paramName][$newid[$i]]));
			$col++;
		}
		$line++;
	}
		
		
//third sheet
	$objPHPExcel->createSheet(2);
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setTitle('Details');
	$idscomma = implode(',',$ids);

	$line=2;
	$alphabet = range('A', 'Z');
	$col=2;

	$counter=0;
	foreach(array_keys($ids) as $paramName) { //Τύπωσε τους χρήστες
		$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, "user: $paramName");
		$col++;	
		$newid[$counter]=$paramName;
		$counter++;
	}
	$line++;

	foreach(array_keys($dataid) as $paramName) { //για κάθε μέρα επανέλαβε
		//$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, "$paramName");
		$col=2;
		//for($i=0;$i<sizeof($newid);$i++){ //Τύπωσε τα σύνολα
		//	$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, intval($dataid[$paramName][$newid[$i]]));
		//	$col++;	
		//}
		//$line++;
		for($u=0;$u<24;$u++){ //για όλες τις ώρες ενός 24ωρου επανέλαβε
			$col=2;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$line, "$paramName");
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$line, $u); //Τύπωσε την ώρα
			for($m=0;$m<sizeof($newid);$m++){
				//  $objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, intval($dataid[$paramName][$newid[$i]]));
				//$hourVal = intval($dataid[$widget][$widgetid][$u]);
				$hourVal = intval($dataid2["$paramName"][$newid[$m]][$u]);
				
				$col=intval($m+2);
				//echo $col.' = '.sizeof($newid).') - '.$newid[$m].'-'.$m.' - '.$u.' - '.$dataid2["$paramName"][$newid[$m]][$u].'<br>';
				//echo 'col: '.$col.' users: '.sizeof($newid).') - User:'.$newid[$m].'- user#:'.$m.' - Hour:'.$u.' - '.$dataid2["$paramName"][$newid[$m]][$u].'<br>';
				//echo $paramName.' = col: '.$col.' users: '.sizeof($newid).') - User:'.$newid[$m].'- user#:'.$m.' - Hour:'.$u.' - Value: '.$hourVal.'<br>';
				//echo $paramName.' - hour: '.$hourVal.'- col: '.$col.'- line: '.$line.'<br>';
				//$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, "$hourVal");

				$objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].$line, $hourVal);
				//$line++;
			}
		$line++;
		}
		//$line++;
	}
	//exit;

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('BD4QOL');

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="BD4QOL.xls"');
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
