Effect.OpenUp = function(element) {
  HideObject();
  
  element = $(element);
  window.showIframeToHide = true;  
  element.style.display = '';
  OverlapInitHeight = cmGetHeight(element);
  OverlapInitWidth = cmGetWidth(element);
  element.style.display = 'none';
  new Effect.BlindDown(element, arguments[1] || {});
}

Effect.CloseDown = function(element) {
 window.showIframeToHide = null;
 element = $(element);
 new Effect.BlindUp(element, arguments[1] || {});
 HideObject();
}

Effect.Combo = function(element) {
  element = $(element);
  if(element.style.display == 'none') { new Effect.OpenUp(element, arguments[1] || {}); }
  else { new Effect.CloseDown(element, arguments[1] || {}); }
}

CheckAltaGeneralPopup = function(ev) {
	if (window.AltaGeneralPopup) {
		var el = Browser.isIE ? window.event.srcElement : ev.target;
		for (; el != null && el.id != (window.AltaGeneralPopup.id); el = el.parentNode);
		if (el == null) {
			CloseComboPopup();
		}
	}
};

function CloseComboPopup()
{
	if(window.AltaGeneralPopup)
	{
		Effect.Combo(window.AltaGeneralPopup.id,{duration: 0.3,scaleX: true,scaleContent: true});
		window.AltaGeneralPopup = null;
	}
}

function ShowPopupEffect(obj)
{
	window.AltaGeneralPopup = obj;
	Effect.Combo(obj.id,{duration: 0.3,scaleX: true,scaleContent: true});
}

addEvent(document, "mousedown", CheckAltaGeneralPopup);
window.AltaGeneralPopup = null;


window.OverlapFrame = null;
var OverlapInitHeight = 0;
var OverlapInitWidth = 0;
function ShowObject(obj)
{

	/*@cc_on	
	var _cmFrameMasking = true;
	@if (@_jscript_version >= 5.6)
		var v = navigator.appVersion;
		var i = v.indexOf ("MSIE ");
		if (i >= 0)
		{
			if (parseInt (navigator.appVersion.substring (i + 5)) >= 7)
				_cmFrameMasking = false;
		}
	@end
	
	if(_cmFrameMasking)
	{
		var Ifr=document.getElementById('OverlapFrame');
		if(Ifr)
		{
			Ifr.style.height = OverlapInitHeight;
			Ifr.style.width = OverlapInitWidth;
			Ifr.style.top = cmGetY(obj);
			Ifr.style.left = cmGetX(obj);
			Ifr.style.display="block";
			window.OverlapFrame = Ifr;
		}
	}
	@*/
/*@cc_on @*/


	
}

function HideObject()
{
//if(OverlapArray && OverlapArray[obj.id]) OverlapArray[obj.id].style.display = 'none';
/*@cc_on @*/
/*@if (@_jscript_version >= 6) @else @*/
	if(window.OverlapFrame)
	{
		window.OverlapFrame.style.display = 'none';
		window.OverlapFrame = null;
	}
/*@end @*/
}