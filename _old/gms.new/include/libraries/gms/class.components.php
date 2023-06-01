<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class GetPage
{
	var $dr = array();
	var $title = "";
	var $content = "";
	var $meta_keys = "";
	var $meta_desc = "";
	
	function GetPage($__ID = "")
	{
		if($__ID != "" && intval($__ID) > 0)
		{
			global $db,$config,$auth;
			
			$query = "select * from pages inner join pagestolanguages on pages.page_id = pagestolanguages.page_id WHERE language_id=" . $auth->LanguageID . " AND pages.page_id =" . intval($__ID) . " AND is_valid=1";
			if($result = $db->sql_query($query))
			{
				if($this->dr = $db->sql_fetchrow($result))
				{
					$this->title = $this->dr["page_title"];
					$this->content = $this->dr["content"];
					$this->meta_keys = $this->dr["meta_keys"];
					$this->meta_desc = $this->dr["meta_desc"];
				}
			}
			
			$db->sql_freeresult($result);
		}
	}
	
	function ParseContent($content)
	{
		
	}
	
	function LoadRequestPage()
	{
		$this->title = "";
		$this->content = "";
		$this->meta_keys = "";
		$this->meta_desc = "";
					
		global $config;
		if(isset($_GET["page"]) && $_GET["page"] != "")
		{
			$this->LoadPage($_GET["page"]);
		}
		
		if($this->content == "" && isset($config["startPageID"]))
		{
			$this->LoadPage($config["startPageID"]);
		}
	}
	
	function LoadPage($pageID)
	{
		$this->GetPage($pageID);
		if($this->content != "")
		{
			global 	$components;
			// Find the tags
			preg_match_all('/\<component\>(.*?)\<\/component\>/is', $this->content, $matches);
	
			// Loop through each tag
			for ($i=0; $i < count($matches['0']); $i++) {
				
				$tag = $matches['0'][$i];
				$text = $matches['1'][$i];

				$new = "";
				if(isset($components->Component[$text]))
				{
					$components->ComponentLoad($components->Component[$text],true);						
					$new = $components->Content;
					$components->Content = "";
				}
				
				// Replace with actual HTML
				$this->content = str_replace($tag, $new, $this->content );
			}
			
			$auth->CurrentPage = $pageID;
		}
	}
	
	function RenderRequestPage()
	{
		echo $this->content;
	}
}

$pages = new GetPage();

class Components
{
	var $Content = '';
	var $Component = array();
	
	function AddComponent($UrlParam, $Comp, $Option, $Path, $ShowOnlyComponentContent = false)
	{
		if($Path == "") $Path = 'gms/components/';
			
		$this->Component[$UrlParam] = array ($UrlParam,$Comp,$Path,$Option,$ShowOnlyComponentContent);
	}
	
	function ComponentLoad($Component, $PushInContent = false)
	{
		global $config, $auth, $db, $toolBar, $validator, $messages, $events;
		
		$adminPrefix = defined('_ADMIN_PROCCESS') ? "admin." : "";
		
		$file_include = $config["physicalPath"] . $Component[2] . $Component[1] . '/' . $adminPrefix . ($Component[3] != '' ? $Component[3] : $Component[1]) . ".php";

		if(file_exists($file_include))
		{
			$file_include_lang = $config["physicalPath"] . $Component[2] . $Component[1] . '/languages/' .  $auth->LanguageCode . ".php";
			//echo $file_include_lang;
			if(file_exists($file_include_lang)) require_once($file_include_lang);
			
			$file_include_gmsBase = $config["physicalPath"] . $Component[2] . $Component[1] . '/gmsCM.' . ($Component[3] != '' ? $Component[3] : $Component[1]) . ".php";
			
			if(file_exists($file_include_gmsBase)) require_once($file_include_gmsBase);
			
			$file_include_gmsExtend = $config["physicalPath"] . 'sites/' . $config["site"] . '/gmsCMExtend/' . '/gmsCM.' . ($Component[3] != '' ? $Component[3] : $Component[1]) . ".php";
			
			if(file_exists($file_include_gmsExtend)) require_once($file_include_gmsExtend);			
			
			if($PushInContent)
				@ob_start();
			
			include($file_include);
			
			if($PushInContent)
			{
				$this->Content = ob_get_contents();
				@ob_end_clean();
			}
		}
	}
	
	
	function LoadRequestComponent()
	{
		

		$this->Content = '';
	
		if(isset($_GET["com"]) && $_GET["com"] && isset($this->Component[$_GET["com"]]))
		{
			$temp_comp = $this->Component[$_GET["com"]];
			$this->ComponentLoad($temp_comp,true);
		}
		
		if($this->Content == '')
		{
			global $config;
			$adminPrefix = defined('_ADMIN_PROCCESS') ? "admin." : "";
			$file_include = $config["physicalPath"] . 'sites/' . $config["site"] . '/components/' . $adminPrefix . "index.php";
			if(file_exists($file_include))
			{
				ob_start();
				include($file_include);
				$this->Content = ob_get_contents();
				ob_end_clean();
			}
		}
		
		if(isset($temp_comp) && $temp_comp[4] === true)
		{
			global $htmlheader,$toolBar,$validator,$messages;
			global $config;
			LoadNoCacheHeader();
			LoadCharSetHeader();
			?>
			<html>
				<head>
				<? defined('_ADMIN_PROCCESS') ? $htmlheader->RenderAdminHeader() : $htmlheader->RenderPublicHeader(); ?>
                <?
                	if(isset($config["enabled_ext"]))
					{
						echo "<script type='text/javascript'>var enabled_ext=true;</script>";
					}
				?>
				</head>
				<body class="m_body">
					<form id="__PageForm" name="__PageForm" enctype="multipart/form-data" method="post" onSubmit="return PageIsValid();">
					<input type="hidden" name="Command" id="Command" value="-1">
						<?
						if(defined('_ADMIN_PROCCESS'))
						{
						?>
							<table width="100%" cellpadding="0" cellspacing="0" border="0" class="m_toolbar">
								<tr>
									<td align="right">
										<?
											$toolBar->RenderToolBar();
										?>
									</td>
								</tr>
							</table>
						<?
						}
						
						$this->RenderRequestComponent();					
						?>
					</form>
					<? $validator->RenderValidators();?>
					<? $messages->RenderMessages();?>
				</body>
			</html>
			<?
            global $tim, $db;
			$tim->stop();
			echo "<!--" . $tim->getTime() . "-->";
			//echo implode("<br>",$db->sql_queries);
			
			$db->sql_close();//Close The Database Connection
			WriteAuthenticateToSession();//Write Authenticate To Session
			
			exit;
		}
	}
	
	function RenderRequestComponent()
	{
		echo $this->Content;
	}
	
	function LoadComponents($array_of_comp)
	{
		if(count($array_of_comp) > 0 )
		{
			for($i =0 ; $i < count($array_of_comp) ; $i++)
			{
				if( isset($this->Component[$array_of_comp[$i]]))
				{
					$temp_comp = $this->Component[$array_of_comp[$i]];
					$this->ComponentLoad($temp_comp);
				}
			}
		}
	}
}

$components = new Components();