// Node object
function Node(id, pid, name, url, title, isChecked, DoNRadio, target, icon, iconOpen, open,  RadioAttributes) {

	this.id = id;

	this.pid = pid;

	this.name = name;

	this.url = url;

	this.title = title;

	this.target = target;

	this.icon = icon;

	this.iconOpen = iconOpen;

	this._io = open || false;

	this._is = false;

	this._ls = false;

	this._hc = false;

	this._ai = 0;

	this._p;
	
	this._isChecked = isChecked;
	
	this._doNRadio = DoNRadio;
	
	this._radioAttributes = RadioAttributes;
		
};


// Tree object

function dTree(objName,Mode,RadioId,IsWithText,useIcons,IWwidth,IWheight,BWwidth) {
	this.config = {

		target					: null,

		folderLinks			: true,

		useSelection		: true,

		useCookies			: true,

		useLines				: true,

		useIcons				: (useIcons ? useIcons : false),

		useStatusText		: false,

		closeSameLevel	: false,

		inOrder					: false,
		
		radioId					: (RadioId ? RadioId : "radio_sel"),
		
		mode					: (Mode ? Mode : "Radio"),
		
		isWithText				: (IsWithText ? " onclick='SetTextValue(\"" + objName + "\",\"__VALUE__\",1);' " : ""),
		
		IWwidth					: (IWwidth ? IWwidth : 140),
		
		IWheight				: (IWheight ? IWheight : -1),
		
		BWwidth					: (BWwidth && BWwidth != "" ? BWwidth : -1)
		
		

	}
	
	this.icon = {

		root					: '/gms/client_scripts/dtree/images/base.gif',

		folder					: '/gms/client_scripts/dtree/images/folder.gif',

		folderOpen				: '/gms/client_scripts/dtree/images/folderopen.gif',

		node					: '/gms/client_scripts/dtree/images/page.gif',

		empty					: '/gms/client_scripts/dtree/images/empty.gif',

		line					: '/gms/client_scripts/dtree/images/line.gif',

		join					: '/gms/client_scripts/dtree/images/join.gif',

		joinBottom				: '/gms/client_scripts/dtree/images/joinbottom.gif',

		plus					: '/gms/client_scripts/dtree/images/plus.gif',

		plusBottom				: '/gms/client_scripts/dtree/images/plusbottom.gif',

		minus					: '/gms/client_scripts/dtree/images/minus.gif',

		minusBottom				: '/gms/client_scripts/dtree/images/minusbottom.gif',

		nlPlus					: '/gms/client_scripts/dtree/images/nolines_plus.gif',

		nlMinus					: '/gms/client_scripts/dtree/images/nolines_minus.gif'

	};

	this.SelectedValue = "";

	this.obj = objName;

	this.aNodes = [];

	this.aIndent = [];

	this.root = new Node(-1);

	this.selectedNode = null;

	this.selectedFound = false;

	this.completed = false;
	

};



// Adds a new node to the node array
dTree.prototype.add = function(id, pid, name, url, title, isChecked, DoNRadio, target, icon, iconOpen, open, RadioAttributes) {
	this.aNodes[this.aNodes.length] = new Node(id, pid, name, url, title, isChecked, DoNRadio, target, icon, iconOpen, open, RadioAttributes);
};



// Open/close all nodes

dTree.prototype.openAll = function() {

	this.oAll(true);

};

dTree.prototype.closeAll = function() {

	this.oAll(false);

};

// Outputs the tree to the page

dTree.prototype.toString = function() {

	var str = '';
	
	var tempStr = "";
	if (document.getElementById) {

		if (this.config.useCookies) this.selectedNode = this.getSelected();
		tempStr = this.addNode(this.root);

	} else tempStr = 'Browser not supported.';
	
	if(this.config.isWithText)
	{
		var bw = (this.config.BWwidth != -1) ? this.config.BWwidth : "70%";
		str += "<input style='width:" + bw + "' type='text' style='cursor:pointer' onclick='dTreeVis(\"" + this.obj + "\");' id='" + this.obj + "_txt' class='m_tb' readonly value='" + this.SelectedValue + "'>&nbsp;<img align='absmiddle' style='cursor:pointer' onclick='dTreeVis(\"" + this.obj + "\");' src='/gms/client_scripts/dtree/images/folder.gif' id='" + this.obj + "_img'>";

		str += "\n<div class='dtree' style='display:none;position:absolute;' id='" + this.obj + "_div'>";
		str += "\n<table border='0' width='" + this.config.IWwidth + "' class='m_bg m_bt' cellpadding='1' cellspacing='0'>";
		str += "\n\t<tr>";
		str += "\n\t\t<td>";
		str += "\n\t\t\t\t<table border='0' width='100%' class='m_ng' cellpadding='2' cellspacing='1'><tr><td>";
		if(this.config.IWheight != -1)
		{
			str += "\n\t\t\t\t<div style='height:" + this.config.IWheight + ";overflow:auto;'>";
		}
	}

	str += '<div class="dtree">\n';

	str += tempStr;

	str += '</div>';

	if (!this.selectedFound) this.selectedNode = null;

	this.completed = true;
	
	if(this.config.isWithText)
	{
		if(this.config.IWheight != -1)
		{
			str += "\n\t\t\t\t</div>";
		}
		str += "\n\t\t\t\t</td></tr></table>";
		str += "\n\t\t</td>";
		str += "\n\t</tr>";
		str += "</table>";
		str += "\n</div>";
		//</div>
	}
	
	//alert(str)
	return str;
	
};



// Creates the tree structure

dTree.prototype.addNode = function(pNode) {

	var str = '';

	var n=0;

	if (this.config.inOrder) n = pNode._ai;

	for (n; n<this.aNodes.length; n++) {

		if (this.aNodes[n].pid == pNode.id) {

			var cn = this.aNodes[n];

			cn._p = pNode;

			cn._ai = n;

			this.setCS(cn);

			if (!cn.target && this.config.target) cn.target = this.config.target;

			if (cn._hc && !cn._io && this.config.useCookies) cn._io = this.isOpen(cn.id);

			if (!this.config.folderLinks && cn._hc) cn.url = null;

			if (this.config.useSelection && cn.id == this.selectedNode && !this.selectedFound) {

					cn._is = true;

					this.selectedNode = n;

					this.selectedFound = true;

			}

			str += this.node(cn, n);

			if (cn._ls) break;

		}

	}

	return str;

};



// Creates the node icon, url and text

dTree.prototype.node = function(node, nodeId) {

	var str = '<div class="dTreeNode">' + this.indent(node, nodeId);

	if (this.config.useIcons) {

		if (!node.icon) node.icon = (this.root.id == node.pid) ? this.icon.root : ((node._hc) ? this.icon.folder : this.icon.node);

		if (!node.iconOpen) node.iconOpen = (node._hc) ? this.icon.folderOpen : this.icon.node;

		if (this.root.id == node.pid) {

			node.icon = this.icon.root;

			node.iconOpen = this.icon.root;

		}
		
		str += '<img id="i' + this.obj + nodeId + '" src="' + ((node._io) ? node.iconOpen : node.icon) + '" alt="" />';
	}

	if(!node._doNRadio)
	{
		//var CurrentWithText = this.config.isWithText.replace("__VALUE__",node.name);
		var CurrentWithText = this.config.isWithText;
		
		if(CurrentWithText != "" && this.config.mode == "Check")
		{
			CurrentWithText = CurrentWithText.replace("__VALUE__","$ALL");
		}
		else CurrentWithText = CurrentWithText.replace("__VALUE__",node.name);
		
		
		if (this.config.mode == "Radio")
		{
			str += '<input ' + (node._radioAttributes && node._radioAttributes != "" ? node._radioAttributes : "") + ' type="radio" ' + CurrentWithText + ' ' + (node._isChecked ? ' checked="checked" ' : '') + ' id="' + this.config.radioId + node.id + '" name="' + this.config.radioId + '" value="' + node.id + '" />&nbsp;'
		}
		else if (this.config.mode == "Check")
		{
			str += '<input ' + (node._radioAttributes && node._radioAttributes != "" ? node._radioAttributes : "") + ' type="checkbox" ' + CurrentWithText + ' ' + (node._isChecked ? ' checked="checked" ' : '') + ' id="' + this.config.radioId + node.id + '" name="' + this.config.radioId + '" value="' + node.id + '" />&nbsp;'
		}

		if(CurrentWithText != "" && node._isChecked && this.config.mode == "Radio")
		{
			this.SelectedValue = node.name;
			SetTextValue(this.obj,node.name);
		}
	}
	
	if (node.url) {

		str += '<a id="s' + this.obj + nodeId + '" href="' + node.url + '"';

		if (node.title) str += ' title="' + node.title + '"';

		if (node.target) str += ' target="' + node.target + '"';

		if (this.config.useStatusText) str += ' onmouseover="window.status=\'' + node.name + '\';return true;" onmouseout="window.status=\'\';return true;" ';

		str += '>';
		
	}
	
	str += node.name;

	if (node.url) {str += "</a>";}
	str += '</div>';

	if (node._hc) {

		str += '<div id="d' + this.obj + nodeId + '" class="clip" style="display:' + ((this.root.id == node.pid || node._io) ? 'block' : 'none') + ';">';

		str += this.addNode(node);

		str += '</div>';

	}

	this.aIndent.pop();
	return str;

};



// Adds the empty and line icons

dTree.prototype.indent = function(node, nodeId) {

	var str = '';

	if (this.root.id != node.pid) {

		for (var n=0; n<this.aIndent.length; n++)

			str += '<img src="' + ( (this.aIndent[n] == 1 && this.config.useLines) ? this.icon.line : this.icon.empty ) + '" alt="" />';

		(node._ls) ? this.aIndent.push(0) : this.aIndent.push(1);

		if (node._hc) {

			str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');"><img id="j' + this.obj + nodeId + '" src="';

			if (!this.config.useLines) str += (node._io) ? this.icon.nlMinus : this.icon.nlPlus;

			else str += ( (node._io) ? ((node._ls && this.config.useLines) ? this.icon.minusBottom : this.icon.minus) : ((node._ls && this.config.useLines) ? this.icon.plusBottom : this.icon.plus ) );

			str += '" alt="" /></a>';

		} else str += '<img src="' + ( (this.config.useLines) ? ((node._ls) ? this.icon.joinBottom : this.icon.join ) : this.icon.empty) + '" alt="" />';

	}

	return str;

};



// Checks if a node has any children and if it is the last sibling

dTree.prototype.setCS = function(node) {

	var lastId;

	for (var n=0; n<this.aNodes.length; n++) {

		if (this.aNodes[n].pid == node.id) node._hc = true;

		if (this.aNodes[n].pid == node.pid) lastId = this.aNodes[n].id;

	}

	if (lastId==node.id) node._ls = true;

};



// Returns the selected node

dTree.prototype.getSelected = function() {

	var sn = this.getCookie('cs' + this.obj);

	return (sn) ? sn : null;

};



// Highlights the selected node

dTree.prototype.s = function(id) {

	try
	{
		if (!this.config.useSelection) return;

		var cn = this.aNodes[id];

		if (cn._hc && !this.config.folderLinks) return;

		if (this.selectedNode != id) {

			if (this.selectedNode || this.selectedNode==0) {

				eOld = document.getElementById("s" + this.obj + this.selectedNode);

				eOld.className = "node";

			}

			eNew = document.getElementById("s" + this.obj + id);

			if(eNew)
				eNew.className = "nodeSel";

			this.selectedNode = id;

			if (this.config.useCookies) this.setCookie('cs' + this.obj, cn.id);

		}
	}catch(e){}

};



// Toggle Open or close

dTree.prototype.o = function(id) {

	var cn = this.aNodes[id];

	this.nodeStatus(!cn._io, id, cn._ls);

	cn._io = !cn._io;

	if (this.config.closeSameLevel) this.closeLevel(cn);

	if (this.config.useCookies) this.updateCookie();

};



// Open or close all nodes

dTree.prototype.oAll = function(status) {

	for (var n=0; n<this.aNodes.length; n++) {

		if (this.aNodes[n]._hc && this.aNodes[n].pid != this.root.id) {

			this.nodeStatus(status, n, this.aNodes[n]._ls)

			this.aNodes[n]._io = status;

		}

	}

	if (this.config.useCookies) this.updateCookie();

};



// Opens the tree to a specific node

dTree.prototype.openTo = function(nId, bSelect, bFirst) {

	try
	{
	if (!bFirst) {

		for (var n=0; n<this.aNodes.length; n++) {

			if (this.aNodes[n].id == nId) {

				nId=n;

				break;

			}

		}

	}

	var cn=this.aNodes[nId];

	if (cn.pid==this.root.id || !cn._p) return;

	cn._io = true;

	cn._is = bSelect;

	if (this.completed && cn._hc) this.nodeStatus(true, cn._ai, cn._ls);

	if (this.completed && bSelect) this.s(cn._ai);

	else if (bSelect) this._sn=cn._ai;

	this.openTo(cn._p._ai, false, true);
	
	}catch(e){}


};



// Closes all nodes on the same level as certain node

dTree.prototype.closeLevel = function(node) {

	for (var n=0; n<this.aNodes.length; n++) {

		if (this.aNodes[n].pid == node.pid && this.aNodes[n].id != node.id && this.aNodes[n]._hc) {

			this.nodeStatus(false, n, this.aNodes[n]._ls);

			this.aNodes[n]._io = false;

			this.closeAllChildren(this.aNodes[n]);

		}

	}

}



// Closes all children of a node

dTree.prototype.closeAllChildren = function(node) {

	for (var n=0; n<this.aNodes.length; n++) {

		if (this.aNodes[n].pid == node.id && this.aNodes[n]._hc) {

			if (this.aNodes[n]._io) this.nodeStatus(false, n, this.aNodes[n]._ls);

			this.aNodes[n]._io = false;

			this.closeAllChildren(this.aNodes[n]);		

		}

	}

}



// Change the status of a node(open or closed)

dTree.prototype.nodeStatus = function(status, id, bottom) {

	eDiv	= document.getElementById('d' + this.obj + id);

	eJoin	= document.getElementById('j' + this.obj + id);

	if (this.config.useIcons) {

		eIcon	= document.getElementById('i' + this.obj + id);

		eIcon.src = (status) ? this.aNodes[id].iconOpen : this.aNodes[id].icon;

	}

	eJoin.src = (this.config.useLines)?

	((status)?((bottom)?this.icon.minusBottom:this.icon.minus):((bottom)?this.icon.plusBottom:this.icon.plus)):

	((status)?this.icon.nlMinus:this.icon.nlPlus);

	eDiv.style.display = (status) ? 'block': 'none';

};





// [Cookie] Clears a cookie

dTree.prototype.clearCookie = function() {

	var now = new Date();

	var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);

	this.setCookie('co'+this.obj, 'cookieValue', yesterday);

	this.setCookie('cs'+this.obj, 'cookieValue', yesterday);

};



// [Cookie] Sets value in a cookie

dTree.prototype.setCookie = function(cookieName, cookieValue, expires, path, domain, secure) {

	document.cookie =

		escape(cookieName) + '=' + escape(cookieValue)

		+ (expires ? '; expires=' + expires.toGMTString() : '')

		+ (path ? '; path=' + path : '')

		+ (domain ? '; domain=' + domain : '')

		+ (secure ? '; secure' : '');

};



// [Cookie] Gets a value from a cookie

dTree.prototype.getCookie = function(cookieName) {

	var cookieValue = '';

	var posName = document.cookie.indexOf(escape(cookieName) + '=');

	if (posName != -1) {

		var posValue = posName + (escape(cookieName) + '=').length;

		var endPos = document.cookie.indexOf(';', posValue);

		if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));

		else cookieValue = unescape(document.cookie.substring(posValue));

	}

	return (cookieValue);

};



// [Cookie] Returns ids of open nodes as a string

dTree.prototype.updateCookie = function() {

	var str = '';

	for (var n=0; n<this.aNodes.length; n++) {

		if (this.aNodes[n]._io && this.aNodes[n].pid != this.root.id) {

			if (str) str += '.';

			str += this.aNodes[n].id;

		}

	}

	this.setCookie('co' + this.obj, str);

};



// [Cookie] Checks if a node id is in a cookie

dTree.prototype.isOpen = function(id) {

	var aOpen = this.getCookie('co' + this.obj).split('.');

	for (var n=0; n<aOpen.length; n++)

		if (aOpen[n] == id) return true;
	
	return false;

};



// If Push and pop is not implemented by the browser

if (!Array.prototype.push) {

	Array.prototype.push = function array_push() {

		for(var i=0;i<arguments.length;i++)

			this[this.length]=arguments[i];

		return this.length;

	}

};

if (!Array.prototype.pop) {

	Array.prototype.pop = function array_pop() {

		lastElement = this[this.length-1];

		this.length = Math.max(this.length-1,0);

		return lastElement;

	}

};


function dTreeVis(obj)
{
	var Cobj = GetObject(obj + "_div");
	if(Cobj)
	{
		if(Cobj.style.display == "")
		{
			CloseComboPopup();
			//Cobj.style.display = "none";
		}
		else
		{
			//Cobj.style.display = "";
			CTxtobj = GetObject(obj + "_img");
			Ctxtiobj = GetObject(obj + "_txt");
			
			if(CTxtobj)
			{
				//Cobj.style.position = "absolute";
				//Cobj.style.top = (cmGetY(CTxtobj)+cmGetHeight(CTxtobj))+"px";
				//Cobj.style.left = cmGetX(CTxtobj)+"px";
			}			
			ShowPopupEffect(Cobj);
			//window.showIframeToHide = null;
		}
	}
}


/*
function dTreeVis(obj)
{
	
}

function SetTextValue(obj,_value,_perform)
{
	var Cobj = GetObject(obj + "_txt");
	if(Cobj)
	{
		Cobj.value = _value;
	}
	if(_perform) 
	{
		 dTreeVis(Cobj);
	}
}
*/


function SetTextValue(obj,_value,_perform)
{
	var Cobj = GetObject(obj + "_txt");
	
	var IsAll = ("$ALL" == _value);
	if(Cobj)
	{
		if(IsAll)
		{			
			_value = "";
			cForm = document.forms[0];
			obj = obj + "[]";
			if (cForm[obj])
			{
				if (cForm[obj].length)
				{
					for (var i=0; i<cForm[obj].length; i++) {
						if (cForm[obj][i].checked) 
						{
							if(document.all)
							{
								_value += cForm[obj][i].parentNode.innerText + ",";
							}
							else
							{
								_value += cForm[obj][i].parentNode.textContent + ",";
							}
						}
					}
				}
				else if (cForm[obj].checked) 
				{
					if(document.all)
					{
						_value += cForm[obj].parentNode.innerText;
					}
					else
					{
						_value += cForm[obj].parentNode.textContent;
					}
				}
			}
		}
		
		Cobj.value = _value;
		
		
		if(!IsAll)
		{
			//GetObject(obj + "_div").style.display = "none";
		}
	}
	
	if(!IsAll && _perform) dTreeVis(obj);
}