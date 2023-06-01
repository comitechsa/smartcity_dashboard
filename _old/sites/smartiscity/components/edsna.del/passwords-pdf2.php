<?php
	$item=intval($_GET['id']);
	$recID=intval($_GET['recID']);
	$filter=(isset($_GET['recID']) && $recID>0 ? ' AND passwords.id='.$recID:'');
	$host="localhost";
	$user="wangrjz_hotbox";
	$database="wangrjz_hotbox";
	$password="qwe#123!@#";

	$connection=mysql_connect($host,$user,$password) or die ("could not connect");
	$db=mysql_select_db($database,$connection) or die ("could not connect to database");
	mysql_set_charset('utf8');

	$query = "SELECT passwords.*, users.password_message, users.company_name FROM passwords INNER JOIN users ON passwords.user_id = users.user_id WHERE passwords.user_id=".$item.$filter."  AND ISNULL(expire) ORDER BY period";
	$result= mysql_query($query) or die ("could not execute");
	//$row = mysql_fetch_array($result);
	//require('fpdf.php');
	require('tfpdf.php');

	$tableWidth=60;
	$pdf = new tFPDF('P','mm',array(80,80));
	$pdf->AddPage();
	// Add a Unicode font (uses UTF-8)
	//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	//$pdf->SetFont('DejaVu','',14);
	$pdf->SetFont('Freesans', 'B', 7);
	// Add a Unicode font (uses UTF-8)
	//$pdf->AddFont('Tahoma','','TAHOMA.TTF',true);
	//$pdf->SetFont('Tahoma','',12);
	
	//$pdf->SetFont('Arial','B',10);
	//$col=0;
	/*
	while ($row = mysql_fetch_array($result)) {
		$col++;
		$newLine=($col==5?1:0);
		$pdf->Cell($tableWidth*0.18, 18, $row['password'].'  ('.$row['period'].')','LTR',$newLine, 'C', 0);
		if($col==5) {
			$col=0;
		}
	}
	*/

	
	$data=array();
	//$data[1]['message'] = iconv('windows-1253','UTF-8',  $data[1]['message']);
	while ($row = mysql_fetch_array($result)) {
		$col++;
		$data[$col]['company_name']=$row['company_name'];
		$data[$col]['password']=$row['password'];
		$data[$col]['period']=$row['period'];
		$data[$col]['password_message']=$row['password_message'];
	}
	$col=0;
	//$data[1]['password_message'] = iconv('UTF-8','windows-1252',  $data[1]['password_message']);
	
	//$totalLines=ceil(sizeof($data)/3);

	
	//for($line=0;$line<$totalLines;$line++){
		$pdf->SetFont('Courier','B',12);
		$pdf->Cell($tableWidth, 8, $data[($line*3)+1]['company_name'],'LTR',1, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, $data[($line*3)+2]['company_name'],'LTR',0, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, $data[($line*3)+3]['company_name'],'LTR',1, 'C', 0);
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell($tableWidth, 8, 'Code: '.$data[1]['password'],'LR',1, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, 'Code: '.$data[($line*3)+2]['password'],'LR',0, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, 'Code: '.$data[($line*3)+3]['password'],'LR',1, 'C', 0);
		
		$pdf->Cell($tableWidth, 8, 'Days: '.$data[1]['period'],'LBR',1, 'C', 0);
		
		$pdf->Cell($tableWidth, 15, 'Message: '.$data[1]['password_message'],'LBR',1, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, 'Days: '.$data[($line*3)+2]['period'],'LBR',0, 'C', 0);
		//$pdf->Cell($tableWidth*0.30, 8, 'Days: '.$data[($line*3)+3]['period'],'LBR',1, 'C', 0);
		
	//}
	
	
//echo $data[1]['company_name']
//$pdf->Output();
$pdf->Output('codes.pdf','D');
?>