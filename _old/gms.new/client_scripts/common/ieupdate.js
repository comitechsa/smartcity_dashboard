theObjects = document.getElementsByTagName("object");  
for (var i = 0; i < theObjects.length; i++) {  
theObjects[i].outerHTML = theObjects[i].outerHTML;  
}

theObjects = document.getElementsByTagName("embed");  
for (var i = 0; i < theObjects.length; i++) {  
theObjects[i].outerHTML = theObjects[i].outerHTML;  
}