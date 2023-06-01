//$(document).ready(function(){
//
//});

$(document).ready(function(){
	//$("#uncheck").click(uncheckElements('check-variables'));   
	$("#uncheckID").click(function() {
		uncheckElements('check-variables');
		//closeIt(1, false);
	});
	
	$("#checkID").click(function() {
		checkElements('check-variables');
		//closeIt(1, false);
	});
});

	function ConfirmDelete(message,url)
	{
		if (confirm(message))
			 location.href=url;
	}

function showM(id)
{
	GetObject(id).style.top = cmGetY(GetObject(id + "_btn")) + cmGetHeight(GetObject(id + "_btn"));
	GetObject(id).style.left = cmGetX(GetObject(id + "_btn"));
	if(GetObject(id).style.display != '') ShowPopupEffect(GetObject(id));
}

function showM2(id, obs)
{
	GetObject(id).style.top = cmGetY(GetObject(id + "_btn")) + cmGetHeight(GetObject(id + "_btn"));
	GetObject(id).style.left = cmGetX(GetObject(id + "_btn")) + (cmGetWidth(GetObject(id + "_btn"))/2) - (obs?obs:300);
	if(GetObject(id).style.display != '') ShowPopupEffect(GetObject(id));
}


function Set_R(val)
{
	if(__PageForm.__Record)
	{
		if(__PageForm.__Record.length)
		{
			for (i=0; i<__PageForm.__Record.length; i++) 
			{
				if (__PageForm.__Record[i].value == val) {
					__PageForm.__Record[i].checked = true;
					break;
				}
			}
		}
		else if (__PageForm.__Record.value == val) 
		{
			__PageForm.__Record.checked = true;
		}
	}
}

function openpop(url, width, height, noscroll)
{
	var _width = width ? width : 500;
	var _height = height ? height : 500;
	var argument = "channelmode=no,";
	argument += "directories=no,";
	argument += "fullscreen=no,";
	argument += "location=no,";
	argument += "menubar=no,";
	argument += "resizable=" + (noscroll ?"no":"yes") + ",";
	argument += "scrollbars=" + (noscroll ?"no":"yes") + ",";
	argument += "status=no,";
	argument += "titlebar=no,";
	argument += "toolbar=no,";
	argument += "width=" + _width + ",";
	argument += "height=" + _height + ",";
	argument += "top=" + (screen.height/2-_height/2) + ",";
	argument += "left=" + (screen.width/2-_width/2) + "";
	
	var cas = window.open(url,'pop', argument ,true);
	cas.focus();
}

function uncheckElements(checkboxGroup)
{
	//uncheckElements(check-variables);
	//var uncheck=document.getElementsByTagName('input');
	var uncheck=document.getElementsByName(checkboxGroup);
	for(var i=0;i<uncheck.length;i++)
	{
		if(uncheck[i].type=='checkbox')
		{
			uncheck[i].checked=false;
		}
	}
}
function checkElements(checkboxGroup)
{
	//uncheckElements(check-variables);
	//var uncheck=document.getElementsByTagName('input');
	var uncheck=document.getElementsByName(checkboxGroup);
	for(var i=0;i<uncheck.length;i++)
	{
		if(uncheck[i].type=='checkbox')
		{
			uncheck[i].checked=true;
		}
	}
}
