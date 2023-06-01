<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class ToolBar
{
	var $commands = array();
	var $images = array();
	var $texts = array();
	var $validatePage = array();
	var $selectRowCheck = array();
	var $confirmMsg = array();
	var $jsCommand = array();
	
	var $basePath = "/gms/images/toolbar/";
	
	function ResetToolBar()
	{
		$this->commands = array();
		$this->images = array();
		$this->texts = array();
		$this->confirmMsg = array();
		$this->jsCommand = array();
	}
	
	function AddCommand($command,$image,$text,$validatePage = 0,$selectRowCheck = 0,$confirmMsg = "")
	{
		$this->commands[$command] = $command;
		$this->images[$command] = $image;
		$this->texts[$command] = $text;
		$this->validatePage[$command] = $validatePage;
		$this->selectRowCheck[$command] = $selectRowCheck;
		$this->confirmMsg[$command] = $confirmMsg;
	}
	
	function AddSimpleCommand($command,$image,$text,$validatePage = 0,$confirmMsg = "")
	{
		$this->commands[$command] = $command;
		$this->images[$command] = $image;
		$this->texts[$command] = $text;
		$this->validatePage[$command] = $validatePage;
		$this->selectRowCheck[$command] = "0";
		$this->confirmMsg[$command] = $confirmMsg;
	}
	
	function AddJsCommand($command, $image, $text, $js = "")
	{
		$this->jsCommand[$command] = $js;
		$this->images[$command] = $image;
		$this->texts[$command] = $text;
	}
	
	function GetToolBar()
	{
		$ret = "";
		
		foreach($this->jsCommand as $key => $val){
			$ret .= "&nbsp;&nbsp;<a id='" . $key . "_ID' href=\"javascript:$val;\"><img border='0' src='" . $this->basePath . $this->images[$key] . "' align='middle' hspace='0'>&nbsp;" . $this->texts[$key] . "</a>";
		}
		
		foreach($this->commands as $key => $val){
			$ret .= "&nbsp;&nbsp;<a id='" . $val . "_ID' href=\"javascript:cm('" . $val . "'," . ($this->validatePage[$key] ? "1" : "0") . "," . ($this->selectRowCheck[$key] ? "1" : "0") . ",'" . (isset($this->confirmMsg[$key]) ? $this->confirmMsg[$key] : "") . "')\"><img border='0' src='" . $this->basePath . $this->images[$key] . "' align='middle' hspace='0'>&nbsp;" . $this->texts[$key] . "</a>";
		}
				
		return $ret . "&nbsp;&nbsp;" ;
	}
	
	function RenderToolBar()
	{
		echo $this->GetToolBar() . "&nbsp;&nbsp;" ;
	}
	
	function CurrentCommand()
	{
		if(isset($_POST["Command"]) && !empty($_POST["Command"]) && $_POST["Command"] != "-1")
		{
			return $_POST["Command"];
		}
		return "";
	}	
	
	function CurrentRecord()
	{
		if(isset($_POST["__Record"]) && !empty($_POST["__Record"]) && $_POST["__Record"] != "-1")
		{
			return $_POST["__Record"];
		}
		return "";
	}
	
	function GetSelector($value)
	{
		return "<input class='n' type='radio' name='__Record' id='__Record' value='$value'>";
	}	
}

$toolBar = new ToolBar();
?>