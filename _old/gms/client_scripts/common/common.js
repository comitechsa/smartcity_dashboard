function GetUrlParameter( paramName )
{
	var oRegex = new RegExp( '[\?&]' + paramName + '=([^&]+)', 'i' ) ;
	var oMatch = oRegex.exec( window.top.location.search ) ;
	
	if ( oMatch && oMatch.length > 1 )
		return oMatch[1] ;
	else
	{
		var oMatch = oRegex.exec( window.location.search ) ;
		if ( oMatch && oMatch.length > 1 )
			return oMatch[1] ;
		else
			return '' ;
	}
}

function leftTrim(sString) 
{
	while (sString.substring(0,1) == ' ')
	{
		sString = sString.substring(1, sString.length);
	}
	return sString;
}
function rightTrim(sString) 
{
	while (sString.substring(sString.length-1, sString.length) == ' ')
	{
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}

function trimAll(sString) 
{
	while (sString.substring(0,1) == ' ')
	{
		sString = sString.substring(1, sString.length);
	}
	while (sString.substring(sString.length-1, sString.length) == ' ')
	{
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}

function PopupWindowArguments(width,height,left,status)
{
	var argument = "channelmode=no,";
	argument += "directories=no,";
	argument += "fullscreen=no,";
	argument += "location=no,";
	argument += "menubar=no,";
	argument += "resizable=yes,";
	argument += "scrollbars=yes,";
	argument += "status=" + ( status ? status : "no") + ",";
	argument += "titlebar=no,";
	argument += "toolbar=no,";
	argument += "width=" + (width ? width : 500) + ",";
	argument += "height=" + (height ? height : 500) + ",";
	argument += "top=" + "0" + ",";
	argument += "left=" + (left ? left : (screen.width - (width ? (width+10) : 550))) + "";
	return argument;
}

function PrepareUrl(push)
{
	var ret = "";
	
	var urlA = window.top.location.href.split('?');

	if(urlA.length > 0)
	{
		ret += urlA[0];
		var h = {};
		
		if(urlA.length > 1)
		{
			var urlA2 = urlA[1].split('&');
			for(var i=0;i<urlA2.length;i++)
			{
				if(urlA2[i] != "")
				{
					var strA = urlA2[i].split('=');
					if(strA.length > 1)
					{
						h[strA[0]] = strA[1];
					}
				}
			}
		}
		
		if(push && push != "") for(var key in push) h[key] = push[key];

		var urlAdd = "";
		for(var key in h)
		{
			if( h[key] != "") urlAdd += "&" + key + "=" + h[key];
		}

		if(urlAdd != "") ret += "?" + urlAdd.substring(1);
	}
	return ret;
}

function OpenPrint(PrintObj,LeaveLinks)
{
	var PrintWindow = window.open(window.location.href,"PagePrint","status=yes,toolbar=no,menubar=yes,location=no,resizable=1,scrollbars=1"); 
	PrintWindow.document.write(GetPrintContent(PrintObj,LeaveLinks));
	PrintWindow.document.close();
	PrintWindow.focus();
	PrintWindow.print();
	PrintWindow.close();
}

function GetPrintContent(PrintObj,LeaveLinks)
{
	var PrintWindowHtml = "";
	var __Styles = GetPageStyleSheet();	
	PrintWindowHtml += "<html><head>";
	PrintWindowHtml += "<title>" + window.document.title + " :: Print</title>\n";
	for(var i = 0; i < __Styles.length ; i++)  PrintWindowHtml += "<link rel='stylesheet' type='text/css' href='" + __Styles[i].href + "'/>\n";
	PrintWindowHtml += "</head><body>";
	PrintWindowHtml += GetObject(PrintObj).innerHTML;
	PrintWindowHtml += "<body></html>";	
	if(!LeaveLinks) PrintWindowHtml = PrintWindowHtml.replace(/\s*href="[^"]*"/gi, "" ) ;
	return PrintWindowHtml;
}


function GetPageStyleSheet() {
	var i, a;
	var StyleArray = new Array();
	for(i = 0 ; (a = document.getElementsByTagName("link")[i]) ; i++)  if(a.getAttribute("rel").indexOf("style") != -1)  StyleArray.push(a);
	return StyleArray;
}
