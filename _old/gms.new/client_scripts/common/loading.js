/* Loader */

function lon()
{
	GetObject("loaderContainerWH").style.height = document.getElementsByTagName('BODY')[0].scrollHeight;
	GetObject("loaderContainer").style.display = "";
}

function los()
{
	GetObject("loaderContainer").style.display = "none";
}

/* End Of Loader */