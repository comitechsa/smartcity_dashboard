config = {
	img : "/gms/client_scripts/gtab/images/tab.gif",
	img_l : "/gms/client_scripts/gtab/images/tab_left.gif",
	img_r : "/gms/client_scripts/gtab/images/tab_right.gif",
	active_img : "/gms/client_scripts/gtab/images/tab_active.gif",
	active_img_l : "/gms/client_scripts/gtab/images/tab_active_left.gif",
	active_img_r : "/gms/client_scripts/gtab/images/tab_active_right.gif",
	hover : "/gms/client_scripts/gtab/images/tab_hover.gif",
	none_img : "/gms/images/none.gif"
}


function gtab(active,tabs,instanceName,mode) {
	
	this.Tabs = tabs;
	this.Config = config;
	this.InstanceName = instanceName;
	this.TabPosition = 0;
	this.Mode = mode ? mode : 0;
	this.Active = this.getSelected() != null ? this.getSelected() : active;
	
	_tabControl = GetObject(instanceName);
	var tempTabs = _tabControl.innerHTML;
	var t_ret = "";
	t_ret += "<div id='htabs" + instanceName + "'></div>";
	t_ret += "<table border='0' width='100%' class='tab_content' cellpadding='0' cellspacing='0'>";
	t_ret += "<tr>";
	t_ret += "<td class='tab_border' width='1'>";
	t_ret += "<img src='" + this.Config["none_img"] + "' width='1'>";
	t_ret += "</td>";
	t_ret += "<td width='100%'>";
	t_ret += tempTabs;
	t_ret += "</td>";
	t_ret += "<td class='tab_border' width='1'><img src='" + this.Config["none_img"] + "' width='1'></td>";
	t_ret += "</tr>";
	t_ret += "<tr><td colspan='3' class='tab_border' width='1'><img src='" + this.Config["none_img"] + "' height='1' width='100%'></td></tr>";
	t_ret += "</table>";
	tempTabs = null;
	
	_tabControl.innerHTML = t_ret;
	//document.write ("<xmp>" + t_ret + "</xmp>");
	this.Change = function(active,_instanceName) 
	{		
		if(this.Mode == 1){
			if(!ValidateMultilinqual(eval(_instanceName))){
				return;
			}
		}
		
		this.Active = parseInt(active);
		ch_name = "press_tab_" + _instanceName;	
		
		if(this.Mode == 4){
			if(this.Tabs[this.Active][2] != "") window.location.href = this.Tabs[this.Active][2];
		}
				
		if (GetObject(ch_name)){
			GetObject(ch_name).value = active;
		}
		else{
			tabControl = GetObject("htabs" + _instanceName);
			tabControl.innerHTML += "<input type='hidden' name='" + ch_name + "' id='" + ch_name + "' value='" + active + "'>";
		}
		
		updateCookie('gt_tab');
		if(this.Mode != 4) this.Render();
		
	}
	
	this.Render = function() {
		for(i=0;i<this.Tabs.length;i++){
			obj = GetObject(this.Tabs[i][0]);
			if (i == this.Active){
				obj.style.visibility = "visible";
				obj.style.margin = "5 5 5 5";
				obj.style.display = "";
			}else{
				obj.style.visibility = "hidden";
				obj.style.display = "none";
			}
		}
		
		
		if(this.Tabs.length <= 1 && this.Mode == 1) 
		{
			var ret = "<table cellspacing='0' cellpadding='0' border='0' align='center' width='100%' class='tab_header'>";
			ret += "<tr><td class='tab_border'><img src='" + this.Config.none_img + "' height='1' width='100%'></td></tr>";
			ret += "</table>";
			//ret += "<td class='tab_border' width='1'><img src='" + this.Config.none_img + "' height='100%' width='1'></td>";
			obj_btn = GetObject("htabs" + this.InstanceName);
			
			obj_btn.innerHTML = ret;
			return;
		}
		
		var ret = "<table cellspacing='0' cellpadding='0' border='0' align='center' width='100%' class='tabheader'><tr>";
		for(i=0;i<this.Tabs.length;i++){
			if (i == 0)
				ret += "<td><img src='" + this.Config.none_img + "' width='10'></td>";
			else
				ret += "<td><img src='" + this.Config.none_img + "' width='1'></td>";

			var CheckBoxVal = "";
			if(this.Mode == 1){
				obj_ck = GetObject(this.Tabs[i][0] + "_ck");
				if(obj_ck){
					Enable = obj_ck.value == "1";
					CheckBoxVal = "<input type='checkbox' onclick='ChangeMode(this," + i + ",\"" + this.InstanceName + "\");' class='normal' " + (Enable ? " checked " : " ") +  ">&nbsp;";
					if(!Enable){
						MakeControls(this.Tabs[i][0],false);
					}
				}
			}
			
			if (i == this.Active){
				ret += "<td><img src='" + this.Config.active_img_l + "'></td>";
				ret += "<td class='tab_header' style=\"background-image: url('" + this.Config.active_img + "');\"><nobr>";
				ret += CheckBoxVal;
				ret += "" + this.Tabs[i][1] + "</nobr></td>";
				ret += "<td><img src='" + this.Config.active_img_r + "'></td>";
			}else{
				ret += "<td><img src='" + this.Config.img_l + "'></td>";
				
				ret += "<td class='tab_header'";
				ret += " onclick='javascript:" + this.InstanceName + ".Change(\"" + i + "\",\"" + this.InstanceName + "\");' ";
				ret += " style=\"cursor:hand;background-image: url('" + this.Config.img + "');\" onmouseover='over(this);' onmouseout='out(this);' >";
			
				ret += "<nobr>";
				ret += CheckBoxVal;
				ret += this.Tabs[i][1];
				ret += "</nobr></td>";
				ret += "<td><img src='" + this.Config.img_r + "'></td>"
			}
		}
		
		ret += "<td width='100%'>&nbsp;</td>";

		Max_columns = (4 * this.Tabs.length) - 3;
		
		ret += "</tr><tr>";
		ret += "<td class='tab_border' width='1'><img src='" + this.Config.none_img + "' height='100%' width='1'></td>";

		if ( this.Active != 0 )
		{	
			ret += "<td class='tab_border' height='1' colspan='" + (this.Active * 4) + "'><img src='" + this.Config.none_img + "' height='1' width='100%'></td>";
		}	
		ret += "<td colspan='3' class='tab_content'><img src='" + this.Config.none_img + "' height='1' ></td>";
		ret += "<td class='tab_border' height='1' colspan='" + (Max_columns - (this.Active * 4)) + "'><img src='" + this.Config.none_img + "' height='1' width='100%'></td>";
		ret += "</tr></table>";
		
		obj_btn = GetObject("htabs" + this.InstanceName);		
		obj_btn.innerHTML = ret;
		//document.write ("<textarea>" + ret + "</textarea>");
	}
	
	//try{
	
		this.Render();
	//}
	//catch(e){alert(e);}
};

gtab.prototype.getSelected = function() {

	//var sn = getCookie('gt_tab','gt' + this.InstanceName);
	//return (sn) ? sn : null;
	return null;
};

function over(obj){
	obj.style.background = "url(" + config.hover + ")";
}

function out(obj){
	obj.style.background = "url(" + config.img + ")";
}


ChangeMode = function(sender,row,_instanceName) {
	_ins = eval(_instanceName);
	MakeControls(_ins.Tabs[row][0],sender.checked);
	obj_ck = GetObject(_ins.Tabs[row][0] + "_ck");
	obj_ck.value = sender.checked ? "1" : "0";
}



MakeControls = function(sender, action) {
	obj = GetObject(sender);
	if(obj.all && obj.all.tags){
		for (j = 0; j < obj.all.tags("INPUT").length; j++){
			control = obj.all.tags("INPUT")[j];
			if(control)
				control.disabled = !action;
		}
	}
}
