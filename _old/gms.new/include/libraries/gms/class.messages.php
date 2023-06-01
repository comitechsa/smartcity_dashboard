<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class Messages
{
	var $MsgCollector = array();
	
	function addMessage($msg)
	{
		array_push($this->MsgCollector,$msg);
	}
	
	function GetValidators($split = "\\n")
	{
		$ret = "";
		
		for($i = 0 ; $i < count($this->MsgCollector) ; $i++){
			$ret .= "- " . str_replace("'","",$this->MsgCollector[$i]) . $split;
		}
		
		return $ret;
	}
	
	function RenderMessages()
	{
		global $config;
		
		if(count($this->MsgCollector) > 0)
		{			
			if(isset($config["enabled_jquery_msg"]) && $config["enabled_jquery_msg"] == "true")
			{
				?>
				<div id="msgD" title="Message"><?=$this->GetValidators("<br>")?></div>
                <script language="javascript">$(document).ready(function() {$("#msgD").dialog({modal:true});});</script>
				<?
			}
			else if(isset($config["enabled_ext"]) && $config["enabled_ext"] == "true")
			{
				?>
                <script language="javascript">
					window.parent.Ext.onReady(function(){window.parent.Ext.InfoWindow.msg('Message', "<?=str_replace("\"","",$this->GetValidators("<br>"))?>",4);}, this);</script>
				<?
			}
			else
			{
				echo "\n\t\t<script language=\"javascript\">";
				echo "\n\t\t alert('" . $this->GetValidators() . "');";
				echo "\n\t\t</script>\n";
			}
			
		}
		
		$this->MsgCollector = array();
	 	WriteAuthenticateToSession();
	}
}


?>