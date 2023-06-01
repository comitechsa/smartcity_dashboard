function high(which2){
	theobject=which2;
	highlighting=setInterval("highlightit(theobject)",50);
}
function low(which2){
	clearInterval(highlighting);
	if (which2.style.MozOpacity)
		which2.style.MozOpacity=0.3;
	else if (which2.filters)
		which2.filters.alpha.opacity=60;
}

function highlightit(cur2){
	if (cur2.style.MozOpacity<1)
		cur2.style.MozOpacity=parseFloat(cur2.style.MozOpacity)+0.1;
	else if (cur2.filters&&cur2.filters.alpha.opacity<100)
		cur2.filters.alpha.opacity+=10;
	else if (window.highlighting)
		clearInterval(highlighting);
}

function LoadImage(img,b_ph_path)
{
	var Control = GetObject("Image_Load");
	if(Control)
		Control.src = img;
	
	Control = GetObject("big_photo");
	if(Control)
	{
		if(b_ph_path && b_ph_path != "")
		{
			Control.href = b_ph_path;
			Control.style.visibility = '';
		}
		else
		{
			Control.href = "";
			Control.style.visibility = 'hidden';
		}
	}	
}