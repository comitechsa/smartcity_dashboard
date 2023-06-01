var my12_hour = 0;
var dn = ""; var old = "";
function show_clock() 
{
	//show clock in NS 4
	if (document.layers)
               document.ClockPosNS.visibility="show"
	if (old == "die") { return; }

	seconds++;
	
	if (seconds >= 60) {
		seconds = "0";
		minutes++;
		if (minutes <= 9) { minutes = "0"+minutes; }
	}
	if (minutes >= 60) {
		minutes = "0";
		hours++;
		if (hours <= 9) { hours = "0"+hours; }
	}
	if (hours >= 24) {
		hours = "00";
	}
	
	if (my12_hour) {
		dn = "AM";
		if (hours > 12) { dn = "PM"; hours = hours - 12; }
		if (hours == 0) { hours = 12; }
	} else {
		dn = "";
	}
	
	if (seconds <= 9) { seconds = "0"+seconds; }
	if (minutes == "0") { minutes = "00"; }

	myclock = '';
	myclock += '<b>'+hours+':'+minutes+':'+seconds+' '+dn + '</b> ' + gmtDisplay;

	if (old == "true") {
		document.write(myclock);
		old = "die"; return;
	}

	if (document.layers) {
		clockpos = document.ClockPosNS;
		liveclock = clockpos.document.LiveClockNS;
		liveclock.document.write(myclock);
		liveclock.document.close();
	} else if (document.getElementById) {
		if (document.getElementById("LiveClockIE") != null)
			document.getElementById("LiveClockIE").innerHTML = myclock;
	} else if (document.all) {
		LiveClockIE.innerHTML = myclock;
	}
	setTimeout("show_clock()",1000);
}