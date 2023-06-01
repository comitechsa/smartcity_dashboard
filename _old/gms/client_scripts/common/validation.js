var OverrideValidation = false;

var ValidationOneByOne = false;

function PageIsValid()
{
	MakeHtmlContent();
	if(OverrideValidation)
		return true;
	
	var IsValidPage = true;
	try
	{
		if(typeof(CustomValidation) == "object" && !CustomValidationValidate(CustomValidation))
			IsValidPage = false;

		if(typeof(MultilinqualValidation) == "object")
		{
			var mIsValidPage = true;
			if(IsValidPage || (!IsValidPage && !ValidationOneByOne))
				mIsValidPage = MultilinqualValidationValidate(MultilinqualValidation);
			
			if(IsValidPage)
				IsValidPage = mIsValidPage;
		}
		
	}catch(e) {
		alert("Error in Validation. Cause:" + e);
		IsValidPage = false;
	}
	
	return IsValidPage;
}


function ValidateOnlyThis(ValidateObjects)
{
	if(typeof(CustomValidation) == "object" && ValidateObjects && ValidateObjects != "")
	{
		MakeHtmlContent();
		
		OverrideValidation = true;
		var ValidateObjectsArray = ValidateObjects.split(";");
		var NewCustomValidation = new Array();
		for(var i=0;i<CustomValidation.length;i++)
		{
			for(var j=0;j<ValidateObjectsArray.length;j++)
			{
				if(ValidateObjectsArray[j] == CustomValidation[i][0])
				{
					NewCustomValidation.push(CustomValidation[i]);
				}
			}
		}
		
		if(!CustomValidationValidate(NewCustomValidation))
		{
			return false;
		}
	}
	
	return true;
}


var ValidationErrorMessagesInAlert = true;
var ControlIsFocus = false;
var AlertValidationMessages = "1";

function CustomValidationValidate(ObjectValidation)
{
	ControlIsFocus = false;
	AlertValidationMessages = "";
	var ValidatioPass = true;
	for(var i=0;i<ObjectValidation.length;i++)
	{
		var control = GetObject(ObjectValidation[i][0]);

		var errorMessage = ObjectValidation[i].length > 3 && ObjectValidation[i][3] != "" && ObjectValidation[i][3] != null ? ObjectValidation[i][3] : "";

		if(control)
		{
			if (trimAll(control.value) == "" && ObjectValidation[i][1] == 1)
			{
				ValidatioPass = false;
				
				MakeStar(control,errorMessage,1);
				if(ValidationOneByOne)
				{
					ShowAlertValidationMessages();
					return false;
				}
			}
			else if (control.value != "" && Validator(control.value,ObjectValidation[i][2]) == null)
			{
				ValidatioPass = false;
				MakeStar(control,errorMessage,2);
				if(ValidationOneByOne)
				{
					ShowAlertValidationMessages();
					return false;
				}
			}else{
				RemoveStar(control);
			}
		}
	}

	if(ValidatioPass)
	{
		return true;
	}
	else
	{
		ShowAlertValidationMessages();
	}
}

var MulitilingualTitle = "";

function ValidateMultilinqual(_instanceName) {
	
	MulitilingualTitle = "";
	if(typeof(_instanceName) == "string")
	{
		val_ar = eval("validation" + _instanceName)
		_instanceNameIn = eval(_instanceName);
	}
	else{
		val_ar = eval("validation" + _instanceName.InstanceName)
		_instanceNameIn = _instanceName;
	}

	MakeHtmlContent();

	if(val_ar)
	{
		for(var i = 0 ; i < _instanceNameIn.Tabs.length ; i++)
		{
			var lang_Code = GetObject(_instanceNameIn.Tabs[i][0] + "_code");
			var lang_Name = GetObject(_instanceNameIn.Tabs[i][0] + "_codeDesc");
			var obj_ck = GetObject(_instanceNameIn.Tabs[i][0] + "_ck");
					
			if (obj_ck && obj_ck.value == "1")
			{
				var prefix = _instanceNameIn.InstanceName + lang_Code.value;
				MulitilingualTitle = (lang_Name ? " (" + lang_Name.value + ")" : "");
				var NewCustomValidation = new Array();
				for(var j=0;j<val_ar.length;j++)
				{
					NewCustomValidation.push(new Array(prefix + val_ar[j][0],val_ar[j][1],val_ar[j][2],val_ar[j][3]));
				}
				
				_instanceNameIn.Active = parseInt(i);
				_instanceNameIn.Render();					
					
				if(!CustomValidationValidate(NewCustomValidation))
				{
					MulitilingualTitle = "";
					return false;
				}
			}
		}
	}
	MulitilingualTitle = "";
	return true;
}

MakeHtmlContent = function(){
	for(ii=0;ii<ArrayCheck.length;ii++)
	{
		obj = GetObject(ArrayCheck[ii].Name);
		obj.value = ArrayCheck[ii].EditorDocument.body.innerHTML;
	}
}

var ArrayCheck = new Array(); 
function FCKeditor_OnComplete( editorInstance )
{
	ArrayCheck.push(editorInstance);
}

function ShowAlertValidationMessages()
{
	if(AlertValidationMessages != "")
		alert(AlertValidationMessages);
}

function MultilinqualValidationValidate(ObjectMultilinqualValidation)
{
	for(var i=0;i<ObjectMultilinqualValidation.length;i++){
		var strMV = ObjectMultilinqualValidation[i] + "";
		if(!ValidateMultilinqual(strMV))
			return false;
	}	
	return true;
}
	
function MakeStar(control, msg, wht){
	var msgUnder = "";
	if(msg && msg != "")
	{
		if(ValidationErrorMessagesInAlert)
		{
			AlertValidationMessages += "- " + msg + (MulitilingualTitle != "" ? MulitilingualTitle + " " : "") + "\n";
		}
		else
		{
			msgUnder = msg;
		}
	}
	var _er = "";
	_er = "&nbsp;<span class='error' id='" + control.id + "sp' name='" + control.id + "sp'>*";
	
	if(msgUnder != "") {
		_er += "<br>" + msgUnder;
	}
	_er += "</span>";
	
	try
	{
		if(enabled_ext)
		{
			var _title = "";
			if(CurrentLanguage == "gr") _title = (wht ? (wht==1?"Απαραίτητο πεδίο":"Λάθος συμπλήρωση") : "");
			else _title = (wht ? (wht==1?"Require field":"Wrong input") : "");
			_er = "<img src='/gms/images/exclamation.png' align='absmiddle' hspace='3' title='"+_title+"' id='" + control.id + "sp' name='" + control.id + "sp'/>";
		}
	} catch(exp){}

	if (GetObject(control.id + 'sp')){
		GetObject(control.id + 'sp').style.visibility = 'visible';
		if (GetObject(control.id + 'msg')){
			GetObject(control.id + 'msg').style.visibility = 'visible';
		}
	}
	else
	{
		if (document.all) {
			control.insertAdjacentHTML("AfterEnd",_er);
		}
		else if (document.getElementById) {
			var r = control.ownerDocument.createRange();
			r.setStartAfter(control);
			var df = r.createContextualFragment( _er );
			control.parentNode.insertBefore(df, control.nextSibling);
		}
	}
	
	if(!ControlIsFocus)
	{
		try	{
			control.focus();
			ControlIsFocus = true;
		}
		catch(e){}
	}
}

	
function RemoveStar(control){
	var Controlsp = GetObject(control.id + 'sp');
	if (Controlsp){
		Controlsp.style.visibility = 'hidden';
	}	
	Controlsp = GetObject(control.id + 'msg');
	if (Controlsp){
		Controlsp.style.visibility = 'hidden';
	}
}

function Properties(){
	this.decimalchar = "."; // For Greece
	this.groupchar = ","; // For Greece
	this.digits = "2";
	this.dateorder = "ymd"; //dmy
	this.century = "2000";
	this.cutoffyear = "2029";
}

function Validator(op, dataType) {

	var val = new Properties();

	function GetFullYear(year) {
		return (year + parseInt(val.century)) - ((year < val.cutoffyear) ? 0 : 100);
	}
	var num, cleanInput, m, exp;
	if (dataType == "Integer") {
		exp = /^\s*[-\+]?\d+\s*$/;
		if (op.match(exp) == null) 
			return null;
		num = parseInt(op, 10);
		return (isNaN(num) ? null : num);
	}
	else if (dataType == "Double") {
		
		exp = new RegExp("^\\s*([-\\+])?(\\d+)?(\\" + val.decimalchar + "(\\d+))?\\s*$");
		m = op.match(exp);
		if (m == null) return null;
		cleanInput = (m[1]?m[1]:"") + (m[2].length>0 ? m[2] : "0") + "." + (m[4]?m[4]:"");
		num = parseFloat(cleanInput);
		return (isNaN(num) ? null : num);            
	} 
	else if (dataType == "Currency") {
		exp = new RegExp("^\\s*([-\\+])?(((\\d+)\\" + val.groupchar + ")*)(\\d+)"
						+ ((val.digits > 0) ? "(\\" + val.decimalchar + "(\\d{1," + val.digits + "}))?" : "")
						+ "\\s*$");
		m = op.match(exp);
		if (m == null)
			return null;
		var intermed = m[2] + m[5] ;
		cleanInput = m[1] + intermed.replace(new RegExp("(\\" + val.groupchar + ")", "g"), "") + ((val.digits > 0) ? "." + m[7] : 0);
		num = parseFloat(cleanInput);
		return (isNaN(num) ? null : num);            
	}
	else if (dataType == "Date") {
		var yearFirstExp = new RegExp("^\\s*((\\d{4})|(\\d{2}))([-/]|\\. ?)(\\d{1,2})\\4(\\d{1,2})\\s*$");
		m = op.match(yearFirstExp);
		var day, month, year;
		if (m != null && (m[2].length == 4 || val.dateorder == "ymd")) {
			day = m[6];
			month = m[5];
			year = (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10))
		}
		else {
			if (val.dateorder == "ymd"){
				return null;		
			}						
			var yearLastExp = new RegExp("^\\s*(\\d{1,2})([-/]|\\. ?)(\\d{1,2})\\2((\\d{4})|(\\d{2}))\\s*$");
			m = op.match(yearLastExp);
			if (m == null) {
				return null;
			}
			if (val.dateorder == "mdy") {
				day = m[3];
				month = m[1];
			}
			else {
				day = m[1];
				month = m[3];
			}
			year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10))
		}
		month -= 1;
		var date = new Date(year, month, day);
	    
		return (typeof(date) == "object" && year == date.getFullYear() && month == date.getMonth() && day == date.getDate()) ? date.valueOf() : null;
	}
	else {
		return op.toString();
	}
}
