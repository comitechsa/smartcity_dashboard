//Add Event
addEvent = function(el, evname, func) {
	if (Browser.isIE) {
		el.attachEvent("on" + evname, func);
	} else {
		el.addEventListener(evname, func, true);
	}
};

//Remove Event
removeEvent = function(el, evname, func) {
	if (Browser.isIE) {
		el.detachEvent("on" + evname, func);
	} else {
		el.removeEventListener(evname, func, true);
	}
};