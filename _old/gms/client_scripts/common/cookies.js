/*  Cookies Area */

function getCookie(CookieName) {
	
	var cookieValue = '';
	if(CookieName != "")
	{
		var posName = document.cookie.indexOf(escape(CookieName) + '=');
		if (posName != -1) {
			var posValue = posName + (escape(CookieName) + '=').length;
			var endPos = document.cookie.indexOf(';', posValue);
			if (endPos != -1) 
				cookieValue = unescape(document.cookie.substring(posValue, endPos));
			else 
				cookieValue = unescape(document.cookie.substring(posValue));
		}
	}

	return (cookieValue);

};

function updateCookie(CookieName,Value) {
	if(CookieName != "")
		setCookie(CookieName, Value);
};

function setCookie(cookieName, cookieValue, expires, path, domain, secure) {
	document.cookie =
		escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
};

/*  End Of Cookies Area */