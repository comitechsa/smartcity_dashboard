<?php
	$item=intval($_GET['id']);
	$recID=intval($_GET['recID']);
	$filter=(isset($_GET['recID']) && $recID>0 ? ' AND passwords.id='.$recID:'');
	$filter.=(isset($_GET["date_insert"]) && $_GET["date_insert"]!='' ? ' AND passwords.date_insert="'.$_GET['date_insert'].'"':'');
	$host="localhost";
	$user="wangrjz_hotbox";
	$database="wangrjz_hotbox";
	$password="qwe#123!@#";

	$connection=mysql_connect($host,$user,$password) or die ("could not connect");
	$db=mysql_select_db($database,$connection) or die ("could not connect to database");
	mysql_set_charset('utf8');

	$query = "SELECT passwords.*, users.company_name FROM passwords INNER JOIN users ON passwords.user_id = users.user_id WHERE passwords.user_id=".$item.$filter."  AND ISNULL(expire) ORDER BY period";
	
	$result= mysql_query($query) or die ("could not execute");
	//$row = mysql_fetch_array($result);

	require('fpdf.php');
	$tableWidth=210;
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);
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
	while ($row = mysql_fetch_array($result)) {
		$col++;
		$data[$col]['company_name']=$row['company_name'];
		$data[$col]['password']=$row['password'];
		$data[$col]['period']=$row['period'];
	}
	$col=0;

	$totalLines=ceil(sizeof($data)/3);	
	for($line=0;$line<$totalLines;$line++){
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell($tableWidth*0.30, 8, $data[($line*3)+1]['company_name'],'LTR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, $data[($line*3)+2]['company_name'],'LTR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, $data[($line*3)+3]['company_name'],'LTR',1, 'C', 0);
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell($tableWidth*0.30, 8, 'Code: '.$data[($line*3)+1]['password'],'LR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, 'Code: '.$data[($line*3)+2]['password'],'LR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, 'Code: '.$data[($line*3)+3]['password'],'LR',1, 'C', 0);
		
		$pdf->Cell($tableWidth*0.30, 8, 'Days: '.$data[($line*3)+1]['period'],'LBR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, 'Days: '.$data[($line*3)+2]['period'],'LBR',0, 'C', 0);
		$pdf->Cell($tableWidth*0.30, 8, 'Days: '.$data[($line*3)+3]['period'],'LBR',1, 'C', 0);
		
	}
	
	
//echo $data[1]['company_name']
//$pdf->Output();
$pdf->Output('codes.pdf','D');
?>