<?
//defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class XMLParser {
   var $filename;
   var $xml;
   var $data;
   
   function XMLParser($xml_file)
   {
       $this->filename = $xml_file;
       $this->xml = xml_parser_create();
	  // xml_parser_set_option( $this->xml, XML_OPTION_TARGET_ENCODING, 'UTF-8'); 
       xml_set_object($this->xml, $this);
       xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
       xml_set_character_data_handler($this->xml, 'dataHandler');
       $this->parse($xml_file);
   }
   
   
   function parse($xml_file)
   {
   	  set_time_limit(0);
      $bytes_to_parse = 4096;
	  
	  if(function_exists("curl_init"))
	  {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $xml_file);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			$txt = curl_exec($ch);
			
			curl_close($ch);
			if($txt=='') {
				 die('Cannot open XML data file: '.$xml_file);
				 return false;
			}
		$parse = xml_parse($this->xml, $txt);
	  }
	  else
	  {
	   	  if (($fp = fopen($xml_file, 'r'))) 
		  {
			while ($data = fread($fp, $bytes_to_parse)) 
			{
			   $parse = xml_parse($this->xml, $data, feof($fp));
		   
		    
				 if (!$parse) 
				 {
				 
				   die(sprintf("XML error: %s at line %d",
					   xml_error_string(xml_get_error_code($this->xml)),
						   xml_get_current_line_number($this->xml)));
						   xml_parser_free($this->xml);
				 }
			  }
		  }
	  }
	  return true;
   }
   
   function startHandler($parser, $name, $attributes)
   {
       $data['name'] = $name;
       if ($attributes) { $data['attributes'] = $attributes; }
       $this->data[] = $data;
   }

   function dataHandler($parser, $data)
   {
       if ($data = trim($data)) {
           $index = count($this->data) - 1;
           $this->data[$index]['content'] = $data;
       }
   }

   function endHandler($parser, $name)
   {
       if (count($this->data) > 1) {
           $data = array_pop($this->data);
           $index = count($this->data) - 1;
           $this->data[$index]['child'][] = $data;
       }
   }
}

class XMLStringParser {
   var $xml;
   var $data;
   
   function XMLStringParser($dataw)
   {
       $this->xml = xml_parser_create();
       xml_set_object($this->xml, $this);
       xml_set_element_handler($this->xml, 'startHandler', 'endHandler');
       xml_set_character_data_handler($this->xml, 'dataHandler');
       $parse = xml_parse($this->xml, $dataw);
   }
   
   function startHandler($parser, $name, $attributes)
   {
       $data['name'] = $name;
       if ($attributes) { $data['attributes'] = $attributes; }
       $this->data[] = $data;
   }

   function dataHandler($parser, $data)
   {
       //if ($data = trim($data)) 
	   //{
           $index = count($this->data) - 1;
           $this->data[$index]['content'] = (string)$data;
       //}
   }

   function endHandler($parser, $name)
   {
       if (count($this->data) > 1) {
           $data = array_pop($this->data);
           $index = count($this->data) - 1;
           $this->data[$index]['child'][] = $data;
       }
   }
}
?>