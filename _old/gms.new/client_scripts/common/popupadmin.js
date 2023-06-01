function cmINLINE(key)
{
	if(__PageForm.__Record)
	{
		if(__PageForm.__Record.length)
		{
			for (i=0; i<__PageForm.__Record.length; i++) {
				if (__PageForm.__Record[i].value == key) {
					__PageForm.__Record[i].checked = true;
				}
			}
		}
		else if (__PageForm.__Record.value == key) 
		{
			__PageForm.__Record.checked = true;
		}
	}
	
	cm("ADDINLINE",0,1,"");
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

function PopupAdmin(__Instance, __JsCaller, __Ids, __Labels, __InlineAdmin) {

	this.Instance = "popup_" + __Instance;
	this.ID = __Instance;
	this.JsCaller = __JsCaller;
	
	this.IdsArray = new Array();
	this.LabelsArray = new Array();
	this.Ids = __Ids;
	this.Labels = __Labels
	this.ParseIds(this.Ids,this.Labels);
	this.InlineAdmin = __InlineAdmin;
	this.Draw();
}

PopupAdmin.prototype.ParseIds = function(___ids, ___labels) {
	if(___ids.length > 0)
	{
		var cAr = ___ids.split(",");
		var lAr = ___labels.split(",");
		for(var i = 0 ; i < cAr.length ; i++)
		{
			if(cAr[i] != "") 
				this.IdsArray.push(cAr[i]);
			if(lAr[i] != "") 
				this.LabelsArray.push(lAr[i]);
		}
	}
}

PopupAdmin.prototype.Draw = function() {
	var DivObject = GetObject(this.ID + "_div");
	if(DivObject)
	{
		DivObject.innerHTML = "";
		DivObject.innerHTML = this.toString();
		//if(this.InlineAdmin) alert(DivObject.innerHTML);
	}
};


PopupAdmin.prototype.toString = function() {

	var str = "<table border='0' width='100%' cellpadding='0' cellspacing='0' class='m_n'>";

	if(this.InlineAdmin)
	{
		for(var i = 0 ; i < this.IdsArray.length ; i++)
		{
			str += this.MakeRow(this.IdsArray[i],this.LabelsArray[i],i,1);
		}
				
		str += this.MakeRow("","",-1,2);
	}
	else
	{
		str += this.MakeRow(this.Ids,this.Labels,-1,-1);
	}
	return str + "</table>";

};

PopupAdmin.prototype.ChangeRow = function(obj, _index) {

	if(_index > -1 && obj.value != this.LabelsArray[_index])
	{
		//alert(this.LabelsArray.length);
		//alert(this.IdsArray.length);
		this.LabelsArray.splice(_index,1);
		this.IdsArray.splice(_index,1);
		this.ParseIds(GetObject(obj.id.replace("_label","")).value,obj.value);
		
	}
	else if(_index == -1 && obj.value != "")
	{
		this.ParseIds(GetObject(obj.id.replace("_label","")).value,obj.value);
	}
	
	this.Draw();
}

PopupAdmin.prototype.DeleteRow = function(_index) {
	this.LabelsArray.splice(_index,1);
	this.IdsArray.splice(_index,1);
	this.Draw();
}

PopupAdmin.prototype.ClearRow = function(_index) {
	GetObject(this.ID + "_" + _index + "_label").value = "";
	GetObject(this.ID + "_" + _index).value = "";
}

PopupAdmin.prototype.MakeRow = function(_value, _label, _index, _type) {
	
	var str = "";
	
	str += "<tr><td>";
	var changeCall = _type && _type != -1 ? "onfocus='" + this.Instance + ".ChangeRow(this," + _index + ");' onblur='" + this.Instance + ".ChangeRow(this," + _index + ");'" : "";
	str += "<input class='m_tb' " + changeCall + " readonly style='width:70%' type='text' name='" + this.ID + "_label' id='" + this.ID + "_" + _index + "_label' value='" + _label + "'>";
	str += "<img align='absmiddle' id='" + this.ID + "_" + _index + "_img' style='cursor:hand' src='/gms/images/upload.png' hspace='2' ";
	str += " onclick=\"" + this.JsCaller + "\">";
	str += "<input type='hidden' name=\"" + this.ID + (this.InlineAdmin ? "[]" : "") + "\" id='" + this.ID + "_" + _index + "' value='" + _value + "'/>";

	if(_type)
	{
		if(_type == -1)
		{
			str += "<img align='absmiddle' style='cursor:hand' src='/gms/images/delere_sm.png' hspace='2' ";
			str += " onclick=" + this.Instance + ".ClearRow(" + _index + ");>";
		}
		else if(_type == 1) 
		{
			str += "<img align='absmiddle' style='cursor:hand' src='/gms/images/delere_sm.png' hspace='2' ";
			str += " onclick=" + this.Instance + ".DeleteRow(" + _index + ");>";
		}
	}
	
	str += "</td></tr>";
	
	return str;
};

var LookupObjID;
function ShowPopup(obj,url)
{
	LookupObjID = obj.id.replace("_img","");
	var __selectedValues = GetObject(LookupObjID).value;
	var argument = PopupWindowArguments(550,500);
	var __url = url + "&sids=" + __selectedValues;		
	OpenWindow = window.open(__url, "popupAdmin", argument);
	OpenWindow.focus();
}


function SetSelect(ListBoxID)
{
	__values = "";
	__labels = "";

	if (GetObject(ListBoxID))
	{
		for (i=0; i<GetObject(ListBoxID).length; i++) {
			var op = GetObject(ListBoxID).options[i];
			__values += "," + op.value;
			__labels += " ," + op.text;
		}
	}
	
	if(__values == "")
	{
		__values = " ";
		__labels = "  ";
	}
	
	__id = window.opener.LookupObjID;
	
	window.opener.GetObject(__id).value = __values.substring(1);
	window.opener.GetObject(__id + "_label").value = __labels.substring(2);
	window.opener.GetObject(__id + "_label").focus();
	window.opener.focus();
	window.close();
}

function SetInlineValue(__values,__labels)
{
	__id = window.opener.LookupObjID;	
	window.opener.GetObject(__id).value = __values;
	window.opener.GetObject(__id + "_label").value = __labels;
	window.opener.GetObject(__id + "_label").focus();
	window.opener.focus();
	window.close();
}


function SetRadio()
{
	__values = "";
	__labels = "";

	if (document.forms[0].radio_sel)
	{
		if (document.forms[0].radio_sel.length)
		{
			for (i=0; i<document.forms[0].radio_sel.length; i++) {
				if (document.forms[0].radio_sel[i].checked) {
					__values += "," + document.forms[0].radio_sel[i].value;
					__labels += " ," + GetElementText(document.forms[0].radio_sel[i].parentNode);					
				}
			}
		}
		else if (document.forms[0].radio_sel.checked) 
		{
			__values = " " + document.forms[0].radio_sel.value;
			__labels = "  " + GetElementText(document.forms[0].radio_sel.parentNode);
		}
	}
		
	if(__values == "")
	{
		__values = " ";
		__labels = "  ";
	}
	
	__id = window.opener.LookupObjID;
	window.opener.GetObject(__id).value = __values.substring(1);
	window.opener.GetObject(__id + "_label").value = __labels.substring(2);
	window.opener.GetObject(__id + "_label").focus();
	
	
	//window.opener.focus();
	//window.close();
}
	
function GetElementText(obj)
{
	if(obj.innerText) {
		return obj.innerText;
	}else{
		var p = obj.innerHTML.replace(/<br>/gi,'\n');
		p = p.replace(/(<[^>]*?>)/g,'').replace("&nbsp;","");
		return p;
	}
}
