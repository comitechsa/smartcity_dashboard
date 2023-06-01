<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class Validator
{
	var $MultiligualValidators = array();	
	var $TagValidatorsID = array();
	var $TagValidatorsIsRequire = array();
	var $ValidatorsDataType = array();
	var $ValidatorsMessages = array();
	
	function AddMultiligualValidate($instance)
	{
		$this->MultiligualValidators[$instance] = $instance;
	}
	
	function AddTagValidator($TagID,$IsRequire,$DataType,$Message = "")
	{
		$this->TagValidatorsID[$TagID] = $TagID;
		$this->TagValidatorsIsRequire[$TagID] = $IsRequire;
		$this->ValidatorsDataType[$TagID] = $DataType;
		$this->ValidatorsMessages[$TagID] = $Message;
		
	}
	
	function AddFunctionValidator($functionName)
	{
	
	}
	
	function AddScriptValidator($functionName)
	{
	
	}
	
	function GetValidators()
	{
		$returnStr = "";
		
		$multiVar = "";
		foreach($this->MultiligualValidators as $key => $val){
			$multiVar .= ",'" . $val . "'";
		}
		
		if($multiVar != "")
			$multiVar = "\n  var MultilinqualValidation = [" . substr($multiVar,1) . "];";
			
		$TagVar = "";
		foreach($this->TagValidatorsID as $key => $val){
			$TagVar .= ",['" . $val . "'," . ($this->TagValidatorsIsRequire[$key] ? "1" : "0") . ",'" . $this->ValidatorsDataType[$key] . "','" . $this->ValidatorsMessages[$key] . "']";
		}
		
		if($TagVar != "")
			$TagVar = "\n var CustomValidation = [" . substr($TagVar,1) . "];";
		
			
		$returnStr .= $multiVar . $TagVar;
		return $returnStr;
				
	}
	
	function RenderValidators()
	{
		echo "\n\t\t<script type=\"text/javascript\">";//language=\"javascript\"
		echo "\n\t\t" . $this->GetValidators();
		echo "\n\t\t</script>\n";		
	}
}

$validator = new Validator();