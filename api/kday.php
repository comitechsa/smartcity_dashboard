<?php
$json='
{
	"meta": {
		"code": 200,
		"date": "31122020"
	},
	"response": {
		"records": [
			{
				"municipalityId": "11",
				"plateNo": "EEM5202",
				"dateIn": "311220200520",
				"weight": "435"
			},  
			{
				"municipalityId": "11",
				"plateNo": "EEΡ1535",
				"dateIn": "311220200520",
				"weight": "2014"
			},
			{
				"municipalityId": "11",
				"plateNo": "EEΚ8080",
				"dateIn": "311220200732",
				"weight": "1024"
			}
		
		]
	}
}
';
echo $json;

echo '<br><br>';
$data = json_decode($json,true);
print_r($data);
echo '<br><br>';
echo '<br><br>';
echo '<br><br>';
echo $data->meta->code.'<br>';
echo $data->meta->date.'<br>';
echo $data->response->records[1]->municipalityId.'<br>';

echo '<br><br>';
$data2=array();
$data2['meta']['code']="200";
$data2['meta']['date']="31122019";
$data2['response']['records'][0]['municipalityId']="11";
$data2['response']['records'][0]['plateNo']="EEM5202";
$data2['response']['records'][0]['dateIn']="311220200520";
$data2['response']['records'][0]['weight']="435";
$data2['response']['records'][1]['municipalityId']="11";
$data2['response']['records'][1]['plateNo']="EEM5202";
$data2['response']['records'][1]['dateIn']="311220200520";
$data2['response']['records'][1]['weight']="435";
$json2=json_encode($data2);
echo $json2;


?>