if( document.implementation.hasFeature("XPath", "3.0") ){

	XMLDocument.prototype.selectNodes = function(cXPathString, xNode){
		if( !xNode ) { xNode = this;}
	    
		var defaultNS = this.defaultNS;

		var aItems = this.evaluate(cXPathString, xNode,
			{ 
				normalResolver:
					this.createNSResolver(this.documentElement),
						lookupNamespaceURI : function (prefix) {
							switch (prefix) {
								case "dflt":
									return defaultNS;
								default:
						return this.normalResolver.lookupNamespaceURI(prefix);
					}
			}
		},XPathResult.ORDERED_NODE_SNAPSHOT_TYPE,null);

		var aResult = [];
		for( var i = 0; i < aItems.snapshotLength; i++){
				aResult[i] =  aItems.snapshotItem(i);
		}
		return aResult;
	}
 
	XMLDocument.prototype.selectSingleNode = function(cXPathString, xNode)
	{
		if( !xNode ) { xNode = this; } 

		var xItems = this.selectNodes(cXPathString, xNode);
		if( xItems.length > 0 )
		{
			return xItems[0];
		}
		else
		{
			return null;
		}
	}
	
	Element.prototype.selectNodes = function(cXPathString){
		if(this.ownerDocument.selectNodes){
			return this.ownerDocument.selectNodes(cXPathString, this);
		}else{
			throw "For XML Elements Only";
		}
	}

	Element.prototype.selectSingleNode = function(cXPathString)
	{	
		if(this.ownerDocument.selectSingleNode)
		{
			return this.ownerDocument.selectSingleNode(cXPathString, this);
		}
		else{throw "For XML Elements Only";}
	}

	/* set the SelectionNamespaces property the same for NN or IE: */
	XMLDocument.prototype.setProperty = function(p,v){
		if(p=="SelectionNamespaces" && v.indexOf("xmlns:dflt")==0){
			this.defaultNS = v.replace(/^.*=\'(.+)\'/,"$1");
		}
	}
	XMLDocument.prototype.defaultNS;
}


function XmlToString(_xmlhttp)
{
	if(ie) return _xmlhttp.responseTEXT;
	else return (new XMLSerializer()).serializeToString(_xmlhttp.responseXML);
}

function GetNodeText(node)
{
	if (Browser.isFirefox) {
		return node.text;
    }
    else if (Browser.isOpera) {
		return node.text;
    }
    else if (Browser.isMozilla) {
       return node.textContent;
    }
    else {
       return node.text;
    }
}

function createXMLFromString(string) 
{
	/*@cc_on @*/
	/*@if (@_jscript_version >= 5)
		var xmlDocument;
		try
		{
			xmlDocument = new ActiveXObject('Msxml2.DOMDocument');
			xmlDocument.async = false;
			xmlDocument.loadXML(string);
			return xmlDocument;
		}
		catch (e) 
		{
			return null;
		}
	@end @*/
	
	var xmlParser, xmlDocument;
	try 
	{
		xmlParser = new DOMParser();
		xmlDocument = xmlParser.parseFromString(string, 'text/xml');
		return xmlDocument;
	}
	catch (e) 
	{
		return null;
	}
}

function GetXmlHttp	()
{
	var xmlhttp=false;
	/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
			// JScript gives us Conditional compilation, we can cope with old IE versions.
			// and security blocked creation of the objects.
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					xmlhttp = false;
				}
			}
	@end @*/
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	
	return xmlhttp;
}
function XmlHttpInit(url, func){
	var xmlhttp = GetXmlHttp();
	xmlhttp.open("GET", url , true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(func)
			{
				if(xmlhttp.getAllResponseHeaders() != "")
					eval(func + "(xmlhttp.responseXML)");
				else
					eval(func + "(createXMLFromString(xmlhttp.responseTEXT))");
			}
		}
	}
	xmlhttp.send(null);
}

function TextHttpInit(url, func){
	var xmlhttp = GetXmlHttp();
	xmlhttp.open("GET", url , true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(func)
			{
				eval(func + "(xmlhttp.responseText)");
			}
		}
	}
	xmlhttp.send(null);
}
