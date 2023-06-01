var Browser = new Object();
Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument!='undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox")!=-1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera")!=-1);

function GetObject(id){
	if (document.getElementById)
		return document.getElementById(id);
	else if (document.all)
		return document.all[id];
	else
		return null;
}

function cm(command,validate,selector,confirmMsg)
{
	if(validate)
	{
		if(!PageIsValid())
			return;
	}
	
	if(selector)
	{
		if(!ValidateSelectorCheck())
			return;
	}
	
	if(confirmMsg != "" && !confirm(confirmMsg))
		return;
	
	OverrideValidation = true;
	GetObject("Command").value = command;
	GetObject("__PageForm").submit();
}

function ValidateSelectorCheck() {
	myOption = -1;
	if(__PageForm.__Record)
	{
		if(__PageForm.__Record.length)
		{
			for (i=0; i<__PageForm.__Record.length; i++) {
				if (__PageForm.__Record[i].checked) {
					myOption = i;
				}
			}
		}
		else if (__PageForm.__Record.checked) 
		{
			myOption = 1;
		}
	}
	if (myOption == -1) {
		alert(recordSelect);
		return false;
	}
	return true;
}

function __KeyDown(command,validate)
{
	if ( event.keyCode == 13 )
	{
		event.returnValue = false;
		event.cancel = true;
		cm(command,validate,0,'');
	}
}

function AddScript(path)
{
	document.write('<s'+'cript language="JavaScript" src="' + path + '"></s'+'cript>');
}

function AddStyleSheet(path)
{
	document.write('<l'+'ink rel="stylesheet" type="text/css" href="' + path + '"></l'+'ink>');
}

AddScript("/gms/client_scripts/common/cookies.js");
AddScript("/gms/client_scripts/common/possition.js");
AddScript("/gms/client_scripts/common/common.js");
AddScript("/gms/client_scripts/common/validation.js");

	
var CurrentFileObject;
function GetFile(obj,url)
{
	CurrentFileObject = GetObject(obj);
	window.open(url,'window',PopupWindowArguments(screen.width - 550),true);
}

function PreviewFile(obj)
{
	CurrentFileObject = GetObject(obj);
	if(CurrentFileObject && CurrentFileObject.value != "")
	{
		window.open(CurrentFileObject.value,'_preview',PopupWindowArguments(screen.width - 550),true);
	}
}

function SetUrl(filepath)
{
	CurrentFileObject.value = filepath;
	CurrentFileObject = null;
}

function SDbClick(pk, radN)
{
	if(GetObject("EDIT_ID") || GetObject("EDIT_P_ID"))
	{
		var _r = (radN ? radN : "__Record");
		if(__PageForm[_r])
		{
			if(__PageForm[_r].length)
			{
				for (i=0; i<__PageForm[_r].length; i++) {
					if (__PageForm[_r][i].value == pk) {
						__PageForm[_r][i].checked = true;
					}
				}
			}
			else if (__PageForm[_r].value == pk) 
			{
				__PageForm[_r].checked = true;
			}
		}
		
		if(GetObject("EDIT_ID")) cm('EDIT',0,1,'');
	   	else if(GetObject("EDIT_P_ID")) cm('EDIT_P',0,1,'');
	}
}
